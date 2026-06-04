<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setup;
use App\Models\StudentClass;
use App\Models\ClubStudent;
use App\Models\ReportStudent;
use App\Models\ReportStudentItem;
use App\Models\ReportStudentAnswer;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportStudentController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['填報學生'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }
    public function index()
    {
        $admin = check_power('填報學生', 'A', auth()->user()->id);

        $student_classes = StudentClass::where('user_names','like', "%".auth()->user()->name."%")->get();  
        $teacher_class = [];
        foreach($student_classes as $student_class){
            $teacher_class[$student_class->semester]['year'] = $student_class->student_year;
            $teacher_class[$student_class->semester]['class'] = $student_class->student_class;
        }    
        $report_students = ReportStudent::where('stopped_at','>=',date('Y-m-d'))->orderBy('semester','DESC')->orderBy('id','DESC')->get();

        $data = [
            'admin'=>$admin,
            'teacher_class'=>$teacher_class,
            'report_students'=>$report_students,
        ];
        return view('report_students.index',$data);
    }

    public function teacher_fill(ReportStudent $report_student)
    {        
        $student_class = StudentClass::where('semester',$report_student->semester)->where('user_names','like', "%".auth()->user()->name."%")->first();          
        
        $class_num = $student_class['student_year'].sprintf("%02s",$student_class['student_class']);
        
        $students = ClubStudent::where('semester',$report_student->semester)
            ->where('class_num','like',$class_num.'%')            
            ->orderBy('class_num')
            ->where('disable',null)
            ->get();                          
        
        $girls = [];
        $boys = [];
        $all_students = [];
        foreach($students as $student){            
            if($student->sex == "男") $boys[$student->id] = substr($student->class_num,-2).'-'.$student->name;
            if($student->sex == "女") $girls[$student->id] = substr($student->class_num,-2).'-'.$student->name;
            $all_students[$student->id] = substr($student->class_num,-2).'-'.$student->name;
        }  

        $answers = [];
        $answers = ReportStudentAnswer::where('report_student_id',$report_student->id)
            ->where('user_id',auth()->user()->id)
            ->get();
        foreach($answers as $answer){
            $answers[$answer->report_student_item_id] = $answer->student_id;
        }
        
        $data = [            
            'report_student' => $report_student,
            'student_year'=>$student_class->student_year,
            'student_class'=>$student_class->student_class,
            'boys'=>$boys,
            'girls'=>$girls,
            'all_students'=>$all_students,
            'answers'=>$answers,
        ];
        return view('report_students.teacher_fill',$data);
    }

    public function save_teacher_fill(Request $request, ReportStudent $report_student)
    {        
        ReportStudentAnswer::where('report_student_id',$report_student->id)
            ->where('user_id',auth()->user()->id)
            ->delete();
        $answers = $request->all();
        $data = [];
        foreach ($answers['answers'] as $item_id => $student_id) {
            // 只有當 student_id 有值時才加入（預防前端傳回空值）
            if ($student_id) {
                $data[] = [
                    'report_student_id'      => $report_student->id,
                    'report_student_item_id' => $item_id,
                    'student_id'             => $student_id,
                    'user_id'                => auth()->user()->id,
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ];
            }
        }

        // 2. 執行資料庫動作
        if (!empty($data)) {
            ReportStudentAnswer::insert($data);
        }
        
        echo "
            <script>
            // 確保頁面加載完成後執行
            window.onload = function() {
                // 檢查父頁面是否存在且可以訪問 jQuery
                if (window.parent && window.parent.$) {
                    // 關閉 venobox 視窗
                    if (typeof window.parent.$.venobox !== 'undefined') {
                        window.parent.$.venobox.close();  // 關閉 venobox 視窗
                    }

                    // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                    window.parent.location.reload();                
                }
            };
            </script>";
    }

    public function admin()
    {
        $admin = check_power('填報學生', 'A', auth()->user()->id);
        $semester = get_date_semester(date('Y-m-d'));
        $class_num = [];
        $club_student_num = [];
        $student_classes = StudentClass::orderBy('semester','DESC')->get()->groupBy('semester');
        foreach($student_classes as $k=>$student_class){
            $class_num[$k] = count($student_class);
        }
        $club_students = ClubStudent::where('disable',null)->get()->groupBy('semester');
        foreach($club_students as $k=>$club_student){
            $club_student_num[$k] = count($club_student);
        }        
        
        $report_students = ReportStudent::orderBy('semester','DESC')->orderBy('id','DESC')->get();
        $now_report = [];
        $not_report = [];
        foreach ($report_students as $report_student) {
            if($report_student->stopped_at > date('Y-m-d')){
                $now_report[$report_student->id]['semester'] = $report_student->semester;
                $now_report[$report_student->id]['name'] = $report_student->name;
                $now_report[$report_student->id]['user'] = $report_student->user->name;
                $now_report[$report_student->id]['started_at'] = $report_student->started_at;
                $now_report[$report_student->id]['stopped_at'] = $report_student->stopped_at;
            }else{
                $not_report[$report_student->id]['semester'] = $report_student->semester;
                $not_report[$report_student->id]['name'] = $report_student->name;
                $not_report[$report_student->id]['user'] = $report_student->user->name;
                $not_report[$report_student->id]['started_at'] = $report_student->started_at;
                $not_report[$report_student->id]['stopped_at'] = $report_student->stopped_at;
            }
        }

        $data = [
            'admin'=>$admin,
            'semester' => $semester,
            'class_num' => $class_num,
            'club_student_num' => $club_student_num,
            'now_report' => $now_report,            
            'not_report' => $not_report,            
        ];
        return view('report_students.admin',$data);
    }

    public function admin_result(ReportStudent $report_student)
    {
        $admin = check_power('填報學生', 'A', auth()->user()->id);
        
        $items = ReportStudentItem::where('report_student_id',$report_student->id)->get();

        $answers = ReportStudentAnswer::where('report_student_id',$report_student->id)->get();

        $answer_data = [];
        foreach($answers as $answer){
            $student = ClubStudent::find($answer->student_id);
            $answer_data[substr($student->class_num,0,-2)][$answer->report_student_item_id]['name'] = $student->name;
        }    
        ksort($answer_data);
        foreach($answer_data as $class_num => $data){
            $formatted_class_name = $this->formatClassName($class_num);
            $answer_data[$formatted_class_name] = $data;
            if($formatted_class_name != $class_num){
                unset($answer_data[$class_num]);
            }
        }
        
        $data = [
            'admin'=>$admin,
            'report_student' => $report_student,
            'items' => $items,
            'answer_data' => $answer_data,
        ];
        return view('report_students.admin_result',$data);
    }

    public function admin_result_download(ReportStudent $report_student)
    {
        $admin = check_power('填報學生', 'A', auth()->user()->id);
        
        $items = ReportStudentItem::where('report_student_id',$report_student->id)->get();

        $answers = ReportStudentAnswer::where('report_student_id',$report_student->id)->get();

        $answer_data = [];
        foreach($answers as $answer){
            $student = ClubStudent::find($answer->student_id);
            $answer_data[substr($student->class_num,0,-2)][$answer->report_student_item_id]['name'] = $student->name;
        }    
        ksort($answer_data);
        foreach($answer_data as $class_num => $data){
            $formatted_class_name = $this->formatClassName($class_num);
            $answer_data[$formatted_class_name] = $data;
            if($formatted_class_name != $class_num){
                unset($answer_data[$class_num]);
            }
        }
        $i=1;
        $excel_data = [];
        foreach ($answer_data as $k=>$v) { 
            $excel_data[$i]['班級'] = $k; 
            foreach($items as $item){
                $excel_data[$i][$item->name] = isset($v[$item->id]) ? $v[$item->id]['name'] : '';
            }            
            $i++;
        }        
        $list = collect($excel_data);
        $fileName = $report_student->name . '填報結果.xlsx';
        // 使用 rawurlencode 處理中文
        return (new FastExcel($list))->download(rawurlencode($fileName));        
    }

    public function admin_item(ReportStudent $report_student)
    {
        $admin = check_power('填報學生', 'A', auth()->user()->id);
        $items = ReportStudentItem::where('report_student_id', $report_student->id)->get();
        $data = [
            'admin'=>$admin,
            'report_student' => $report_student,
            'items'=>$items,
        ];
        return view('report_students.admin_item',$data);
    }

    public function admin_item_store(Request $request)
    {
        $att = $request->all();
        $report_student_id = $att['report_student_id'];
        
        if (isset($att['name'])) {
            $data = []; // 準備存放所有資料的陣列
            foreach ($att['name'] as $k => $v) {
                if($v != ''){
                    $data[] = [
                    'report_student_id' => $report_student_id,
                    'name'              => $v,
                    'created_at'        => now(), // 注意：insert() 不會自動填入時間，需手動補上
                    'updated_at'        => now(),
                    ];
                }                
            }
            // 使用 Model 的 insert 方法一次存入
            ReportStudentItem::insert($data);
        }
        echo "
            <script>
            // 確保頁面加載完成後執行
            window.onload = function() {
                // 檢查父頁面是否存在且可以訪問 jQuery
                if (window.parent && window.parent.$) {
                    // 關閉 venobox 視窗
                    if (typeof window.parent.$.venobox !== 'undefined') {
                        window.parent.$.venobox.close();  // 關閉 venobox 視窗
                    }

                    // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                    window.parent.location.reload();                
                }
            };
            </script>";
    }

    public function admin_item_delete(ReportStudentItem $report_student_item)
    {
        $report_student_item->delete();
        return redirect()->back();
    }
    

    public function store_report_student(Request $request)
    {
          $att = $request->all();
          $att['user_id'] = auth()->user()->id;
          ReportStudent::create($att);
          return redirect()->back();
    }

    public function update_report_student(Request $request,ReportStudent $report_student)
    {
          $att = $request->all();      
          if($att['action']=="更新"){
              //更新
              $report_student->update($att);
          }elseif($att['action']=="複製"){
            /**
              //複製
              unset($att['report_student_id']);
              $att['semester'] = $report_student->semester;
              $att['name'] = $report_student->name;
              $att['started_at'] = $report_student->started_at;
              $att['stopped_at'] = $report_student->stopped_at;
              $att['user_id'] = auth()->user()->id;
              $new_report_student = ReportStudent::create($att);
              // 1. 取得舊有的 items
                $items = ReportStudentItem::where('report_student_id', $report_student->id)->get();

                // 2. 將資料轉換為陣列格式
                $data = $items->map(function ($item) use ($new_report_student) {
                    return [
                        'report_student_id' => $new_report_student->id,
                        'name'              => $item->name,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];
                })->toArray();

                // 3. 一次性寫入資料庫
                if (!empty($data)) {
                    ReportStudentItem::insert($data);
                }     
            */    
            }                    
          return redirect()->back();
    }

    public function copy_report_student(ReportStudent $report_student)
    {
        $items = ReportStudentItem::where('report_student_id', $report_student->id)->get();
        $data = [
            'report_student'=>$report_student,
            'items'=>$items,
        ];
        return view('report_students.copy',$data);
    }

    public function do_copy_report_student(Request $request,ReportStudent $report_student)
    {
        $att = $request->all(); 
        //複製
        unset($att['report_student_id']);        
        $att['user_id'] = auth()->user()->id;
        $new_report_student = ReportStudent::create($att);
        // 1. 取得舊有的 items
        $items = ReportStudentItem::where('report_student_id', $report_student->id)->get();

        // 2. 將資料轉換為陣列格式
        $data = $items->map(function ($item) use ($new_report_student) {
            return [
                'report_student_id' => $new_report_student->id,
                'name'              => $item->name,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        })->toArray();

        // 3. 一次性寫入資料庫
        if (!empty($data)) {
            ReportStudentItem::insert($data);
        }    

        echo "
            <script>
            // 確保頁面加載完成後執行
            window.onload = function() {
                // 檢查父頁面是否存在且可以訪問 jQuery
                if (window.parent && window.parent.$) {
                    // 關閉 venobox 視窗
                    if (typeof window.parent.$.venobox !== 'undefined') {
                        window.parent.$.venobox.close();  // 關閉 venobox 視窗
                    }

                    // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                    window.parent.location.reload();                
                }
            };
            </script>";

    }

    public function delete_report_student(ReportStudent $report_student)
    {          
          ReportStudentItem::where('report_student_id',$report_student->id)->delete();
          ReportStudentAnswer::where('report_student_id',$report_student->id)->delete();
          $report_student->delete();
          return redirect()->back();
    }

    public function stu_adm_more($semester, $student_class_id = null)
    {
        $admin = check_power('運動會報名', 'A', auth()->user()->id);
        if(!$admin) return back();

        $student_classes = StudentClass::where('semester', $semester)
            ->orderBy('student_year')
            ->orderBy('student_class')
            ->get();

        $student_class_id = ($student_class_id == null) ? $student_classes->first()->id : $student_class_id;

        $this_class = StudentClass::find($student_class_id);
        $sc = $this_class->student_year . sprintf("%02s", $this_class->student_class);

        $club_students = ClubStudent::where('semester', $semester)
            //->where('disable', null)
            ->where('class_num', 'like', $sc . '%')
            ->orderBy('class_num')
            ->get();

        $data = [
            'admin'=>$admin,
            'club_students' => $club_students,
            'semester' => $semester,
            'student_classes' => $student_classes,
            'this_class' => $this_class,
        ];
        return view('report_students.stu_adm_more', $data); 
    }

    public function stu_disable(ClubStudent $club_student, $student_class_id)
    {
        $att['class_num'] = substr($club_student->class_num, 0, -2) . '99';
        $att['disable'] = 1;
        $club_student->update($att);
        return redirect()->back();
    }

    public function formatClassName($input) {
        // 1. 使用正則表達式提取數字（假設格式永遠是 3 位數，如 107, 712, 920）
        // 第一位是年級，後兩位是班級
        if (preg_match('/(\d{1})(\d{2})/', $input, $matches)) {
            $gradeNum = (int)$matches[1];
            $classNum = (int)$matches[2];

            // 2. 定義數字轉中文的陣列
            $chineseNums = [
                1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 
                6 => '六', 7 => '七', 8 => '八', 9 => '九', 10 => '十',
                11 => '十一', 12 => '十二', 13 => '十三', 14 => '十四', 15 => '十五',
                16 => '十六', 17 => '十七', 18 => '十八', 19 => '十九', 20 => '二十'
            ];

            // 3. 組合結果
            $gradeChinese = $chineseNums[$gradeNum] ?? $gradeNum;
            $classChinese = $chineseNums[$classNum] ?? $classNum;

            return "{$gradeChinese}年{$classChinese}班";
        }

        return $input; // 如果格式不符，回傳原字串
    }

    public function stu_import(Request $request)
    {
        $admin = check_power('填報學生', 'A', auth()->user()->id);        
        if(!$admin) return back();
        
        $semester = $request->input('semester');        
        
        // 處理檔案上傳
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $collection = (new FastExcel)->import($file);

            // 🎯 預備容器：用來收集待會要「批次寫入/更新」的整包資料
            $studentsToUpsert = [];
            $classTeacherMap = [];

            foreach ($collection as $line) {
                // 欄位防呆檢查
                if (!isset($line['姓名']) || !isset($line['性別']) || !isset($line['年級(數字)']) || !isset($line['班序(數字)']) || !isset($line['生日(西元)']) || !isset($line['學號']) || !isset($line['座號']) || !isset($line['導師姓名'])) {
                    return back()->withErrors(['欄位有錯，請檢查 excel 檔']);
                }

                // 遇空行中斷
                if (empty($line['姓名']) && empty($line['年級(數字)'])) {
                    break;
                }

                // 收集班級導師對應表（暫存於記憶體，重複的班級會自動覆蓋，只保留最後一筆）
                $classTeacherMap[$line['年級(數字)']][$line['班序(數字)']] = $line['導師姓名'];

                // 處理生日與密碼
                $b = explode('/', $line['生日(西元)']->format('Y/m/d'));
                $pwd = $b[0] . sprintf("%02s", $b[1]) . sprintf("%02s", $b[2]);
                $class_num = $line['年級(數字)'] . sprintf("%02s", $line['班序(數字)']) . sprintf("%02s", $line['座號']);

                // 🎯 將這一筆學生包成陣列，丟進大池子，不再單獨敲資料庫
                $studentsToUpsert[] = [
                    'semester'    => $semester,
                    'no'          => $line['學號'],
                    'name'        => $line['姓名'],
                    'pwd'         => $pwd,
                    'class_num'   => $class_num,
                    'birthday'    => $pwd,
                    'sex'         => $line['性別'],
                    'created_at'  => now(), // 批次寫入時原生時間戳需要手動帶入
                    'updated_at'  => now(),
                ];
            }

            // ==========================================
            // 🎯 核心優化 A：學生資料「一條 SQL 處理全部」
            // ==========================================
            if (!empty($studentsToUpsert)) {
                // Laravel Upsert 機制：
                // 參數 1：所有學生資料陣列
                // 參數 2：唯一識別不重複的欄位 (必須是資料庫的 PRIMARY 或 UNIQUE 鍵，這裡以學期+學號為例)
                // 參數 3：如果重複了，要更新哪些欄位
                ClubStudent::upsert(
                    $studentsToUpsert, 
                    ['semester', 'no'], 
                    ['name', 'pwd', 'class_num', 'birthday', 'sex', 'updated_at']
                );
            }

            // ==========================================
            // 🎯 核心優化 B：班級資料「一條 SQL 處理全部」
            // ==========================================
            $classesToUpsert = [];
            foreach ($classTeacherMap as $year => $classes) {
                foreach ($classes as $classNum => $teacherName) {
                    $classesToUpsert[] = [
                        'semester'      => $semester,
                        'student_year'  => $year,
                        'student_class' => $classNum,
                        'user_names'    => $teacherName,
                        'user_ids'      => null, // 避免先前拉過 API 已經有導師了，依你原作邏輯清空
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
            }

            if (!empty($classesToUpsert)) {
                StudentClass::upsert(
                    $classesToUpsert,
                    ['semester', 'student_year', 'student_class'],
                    ['user_names', 'user_ids', 'updated_at']
                );
            }
        }

        return back();
    }
}
