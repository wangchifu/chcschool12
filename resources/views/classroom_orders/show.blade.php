@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '教室預約 | ')

@section('content')
    {{-- 🎯 終極防禦安全機制：如果因為 Laravel 模型綁定跳轉導致 $classroom 物件在 View 中短暫判定遺失 --}}
    {{-- 我們直接在最前面用原生 PHP 安全撈回，確保下方所有美編欄位絕不崩潰 --}}
    <?php
    if (!isset($classroom) || empty($classroom)) {
        // 從目前的路由中直接抓取 {classroom} 的 ID 參數，並從資料庫重新取出
        $current_id = request()->route('classroom');
        if ($current_id) {
            $classroom = \App\Models\Classroom::find($current_id);
        }
    }
    ?>

    <div class="row justify-content-center g-4 my-2">
        <div class="col-md-11">
            
            {{-- 標題美化 --}}
            <h1 class="fw-bold text-dark mb-2">教室預約</h1>
            <h2 class="text-secondary fw-bold mb-4">
                <i class="fas fa-door-open text-primary me-2"></i>{{ $classroom ? $classroom->name : '教室資料載入中...' }}
            </h2>

            <?php
            $cht_week = config("chcschool.cht_week");
            $class_sections = config("chcschool.class_sections");
            ?>

            {{-- 表格質感美化：加入圓角、陰影外框與垂直置中 (與前面完全相同樣式) --}}
            <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-4">
                <table class="table table-bordered table-hover align-middle mb-0 text-center">
                    <thead class="table-primary text-dark fw-bold text-nowrap">
                        {{-- 第一層表頭：星期 --}}
                        <tr>
                            <th rowspan="2" style="width: 60px;" class="bg-light align-middle">
                                @if($classroom)
                                    {{-- 🎯 換週按鈕優化：傳入正確的 $classroom->id --}}
                                    <a href="{{ route('classroom_orders.show', ['classroom' => $classroom->id, 'select_sunday' => $last_sunday]) }}" class="btn btn-link text-primary p-0 fs-3">
                                        <i class="fas fa-arrow-alt-circle-left"></i>
                                    </a>
                                @endif
                            </th>
                            @foreach($week as $k => $v)
                                <?php
                                $font = "";
                                if($k == "0"){ $font = "text-danger"; }
                                if($k == "6"){ $font = "text-success"; }
                                ?>
                                <th class="py-2">
                                    <span class="{{ $font }} fs-6 fw-bold">{{ $cht_week[$k] }}</span>
                                </th>
                            @endforeach
                            <th rowspan="2" style="width: 60px;" class="bg-light align-middle">
                                @if($classroom)
                                    {{-- 🎯 換週按鈕優化：傳入正確的 $classroom->id --}}
                                    <a href="{{ route('classroom_orders.show', ['classroom' => $classroom->id, 'select_sunday' => $next_sunday]) }}" class="btn btn-link text-primary p-0 fs-3">
                                        <i class="fas fa-arrow-alt-circle-right"></i>
                                    </a>
                                @endif
                            </th>
                        </tr>
                        {{-- 第二層表頭：日期 --}}
                        <tr>
                            @foreach($week as $k => $v)
                                <?php
                                $font = "";
                                if($k == "0"){ $font = "text-danger"; }
                                if($k == "6"){ $font = "text-success"; }
                                
                                // 當天日期的 Badge 樣式優化
                                $is_today = ($v == date('Y-m-d'));
                                ?>
                                <th class="py-2 bg-white">
                                    @if($is_today)
                                        <span class="badge bg-info text-white px-2 py-1.5 fw-bold rounded-2 shadow-sm">{{ $v }} (今天)</span>
                                    @else
                                        <span class="{{ $font }} small font-monospace fw-semibold text-secondary">{{ $v }}</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class_sections as $k1 => $v1)
                            <tr>
                                {{-- 節次欄位美化 --}}
                                <td class="table-light fw-bold text-secondary text-nowrap px-3">{{ $v1 }}</td>
                                
                                @foreach($week as $k2 => $v2)
                                    <td>
                                        @if(empty($has_order[$v2][$k1]['id']))
                                            {{-- 確保 $classroom 存在才進行欄位比對 --}}
                                            @if($classroom && strpos($classroom->close_sections, "'".$k2."-".$k1."'") !== false)
                                                <span class="text-danger opacity-50 fw-bold"><i class="fas fa-ban"></i></span>
                                            @else
                                                @if(str_replace('-','',$v2) < date('Ymd'))
                                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-2 small fw-normal">逾期</span>
                                                @else
                                                    @if($classroom)
                                                        {{-- 🎯 選我預約按鈕 --}}
                                                        <a href="#!"
                                                           class="btn btn-outline-success btn-sm fw-bold w-100 py-1.5 delete-btn1"
                                                           id="s{{ $k1 }}{{ $k2 }}" data-msg="確定預約{{ $classroom->name }} {{ $v2 }} {{ $v1 }} 嗎？" data-url="{{ route('classroom_orders.select', ['classroom_id' => $classroom->id, 'section' => $k1, 'order_date' => $v2]) }}">
                                                            <i class="fas fa-check-circle me-1"></i>選我
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            {{-- 顯示已預約者姓名 --}}
                                            <div class="fw-bold text-dark mb-1">{{ $has_order[$v2][$k1]['user_name'] }}</div>
                                            
                                            @if(auth()->user()->id == $has_order[$v2][$k1]['id'] and str_replace('-','',$v2) >= date('Ymd'))
                                                {{-- 刪除按鈕與圖示 --}}
                                                <a href="#!" class="btn btn-link p-0 text-danger delete-btn2" data-msg="確定刪除 {{ $classroom ? $classroom->name : '' }} {{ $v2 }} {{ $v1 }} 的預約？" data-form="delete{{ $k1 }}{{ $k2 }}">
                                                    <i class="fas fa-times-circle fs-5"></i>
                                                </a>
                                                
                                                {{-- 帶入標準 HTML5 的 DELETE 表單 --}}
                                                <form action="{{ route('classroom_orders.destroy') }}" method="POST" id="delete{{ $k1 }}{{ $k2 }}" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="classroom_id" value="{{ $classroom ? $classroom->id : '' }}">
                                                    <input type="hidden" name="order_date" value="{{ $v2 }}">
                                                    <input type="hidden" name="section" value="{{ $k1 }}">
                                                </form>

                                                {{-- 依要求保留原本的迴圈內 script 結構，方便您後續處理 --}}
                                                <script>
                                                    function go_delete{{ $k1 }}{{ $k2 }}(){
                                                        document.getElementById('delete{{ $k1 }}{{ $k2 }}').submit();
                                                    }
                                                </script>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                                <td class="bg-light"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection