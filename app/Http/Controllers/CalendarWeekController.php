<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\CalendarWeek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarWeekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $semesters = [];
        //取學期選單
        $ss = DB::select('select semester from calendar_weeks group by semester');
        foreach($ss as $s){
            $semesters[$s->semester] = $s->semester;
        }
        rsort($semesters);

        $data = [
            'semesters'=>$semesters,
        ];
        return view('calendar_weeks.index',$data);
    }

    public function edit($semester)
    {
        $calendar_weeks = CalendarWeek::where('semester',$semester)
            ->orderBy('week')
            ->get();
        $data = [
            'semester'=>$semester,
            'calendar_weeks'=>$calendar_weeks,
        ];
        return view('calendar_weeks.edit',$data);
    }

    public function update(Request $request)
    {
        foreach($request->input('week_id') as $k=>$v){
            $calendar_week = CalendarWeek::find($k);
            $att['start_end'] = $v;
            $calendar_week->update($att);
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

    public function create(Request $request)
    {
        $semester = get_date_semester($request->input('open_date'));
        $set_week = $request->input('set_week');
        $open_date = explode('-',$request->input('open_date'));
        $dt = Carbon::create($open_date[0], $open_date[1], $open_date[2], 00);

        //Carbon::setTestNow($knownDate);

        //$dt = new Carbon('last sunday');

        $w = 1;
        $d = 0;
        do{
            $start_end[$w][$d] = $dt->toDateString();
            $d = 6;
            $start_end[$w][$d] = $dt->addDay(6)->toDateString();
            $dt->addDay();
            $w++;
            $d = 0;
        }while($w < $set_week+1);

        $data = [
            'start_end'=>$start_end,
            'semester'=>$semester,
        ];


        return view('calendar_weeks.create',$data);
    }

    public function store(Request $request)
    {
        $semester = $request->input('semester');
        $all = [];
        foreach($request->input('week') as $k => $v){
            if(!empty($v)){
                $start_end = $request->input('start_end');
                $att['week'] = $v;
                $att['semester'] = $semester;
                $att['start_end'] = $start_end[$k];
                $one = [
                    'semester'=>$att['semester'],
                    'week'=>$att['week'],
                    'start_end'=>$att['start_end'],
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];

                array_push($all,$one);
            }

        }

        CalendarWeek::insert($all);

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

    public function destroy($semester)
    {
        CalendarWeek::where('semester',$semester)->delete();
        Calendar::where('semester',$semester)->delete();
        return redirect()->route('calendar_weeks.index');
    }

}
