@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務月曆 | ')

@section('content')
    <?php
    use Carbon\Carbon;
    //$this_month =(empty($month))?date('Y-m'):$month;
    $this_month = request('month', date('Y-m'));
    $format_this_month = Carbon::parse($this_month)->format('Y 年 n 月');

    $items = \App\Models\MonthlyCalendar::where('item_date','like',$this_month.'%')->get();
    $item_array = [];
    foreach($items as $item){
        $item_array[$item->id]['user_id'] = $item->user_id;
        $item_array[$item->id]['item_date'] = $item->item_date;
        $item_array[$item->id]['item'] = $item->item;
    }

    $d = explode('-',$this_month);
    $dt = Carbon::create($d[0], $d[1],1);
    $next_month = $dt->addMonthsNoOverflow(1)->format('Y-m');

    $dt = Carbon::create($d[0], $d[1],1);
    $last_month = $dt->subMonthsNoOverflow(1)->format('Y-m');

    $this_month_date = get_month_date($this_month);
    $first_w = get_date_w($this_month_date[1]);
    ?>
    <style nonce="{{ $csp_nonce }}">
        /* ── Table layout ── */
        .cal-table {
            table-layout: fixed;
            width: 100%;
        }
        .cal-table col {
            width: 14.2857%;
        }

        /* ── Cell height ── */
        .cal-table tbody tr {
            height: 110px;
        }

        /* ── Event pill ── */
        .cal-event {
            display: block;
            font-size: 16px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            border-radius: 50rem;
            padding: 3px 8px;
            margin-bottom: 3px;
            cursor: pointer; /* 🎯 加上這行，滑鼠移上去就會自動變成手型 */
        }
        .cal-event-primary   { background-color: rgba(13,110,253,.1);  color: #084298; }
        .cal-event-success   { background-color: rgba(25,135, 84,.1);  color: #0a3622; }
        .cal-event-danger    { background-color: rgba(220, 53, 69,.1);  color: #842029; }
        .cal-event-warning   { background-color: rgba(255,193,  7,.15); color: #664d03; }
        .cal-event-info      { background-color: rgba( 13,202,240,.1);  color: #055160; }
        .cal-event-today     { background-color: #0d6efd;               color: #fff;    }

        /* ── Today cell ── */
        .cal-today {
            background-color: rgba(13,110,253,.07) !important;
        }

        /* ── Today date badge ── */
        .cal-today-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 50%;
            background-color: #0d6efd;
            color: #fff;
            margin-bottom: 4px;
        }

        /* ── Legend pills ── */
        .legend-pill {
            font-size: 11px;
            border-radius: 50rem;
            padding: 3px 10px;
        }
        #monthly_calendar {
            scroll-margin-top: 120px; /* 數字越大，定位點就越往上（視覺上下載點會往下移） */
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校務月曆
            </h1>
                        
            <div class="container">
                <div id="monthly_calendar"></div>
                @can('create', \App\Models\Post::class)        
                    <form action="{{ route('monthly_calendars.block_store') }}" method="POST" class="mb-4" id="add_calendar_item">
                        @csrf
                        
                        <div class="row g-2 align-items-center" style="max-width: 600px;">
                            
                            <div class="col-sm-4">
                                <input type="date" id="item_date" name="item_date" class="form-control form-control-sm bg-white" required maxlength="10" value="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-sm-5">
                                <input type="text" id="item" name="item" class="form-control form-control-sm" required placeholder="請輸入行程事項...">
                            </div>
                            
                            <div class="col-sm-3 d-grid">
                                <button type="button" class="btn btn-success btn-sm fw-bold save-btn" data-form="add_calendar_item">
                                    <i class="fas fa-plus me-1"></i> 新增事項
                                </button>
                            </div>
                            
                        </div>
                        
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    </form>
                @endcan
                <!-- Navigation -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <a href="?month={{ $last_month }}" class="btn btn-outline-secondary btn-sm px-3">← 上個月</a>
                    <h4 class="mb-0 fw-semibold">{{ $format_this_month }}</h4>
                    <a href="?month={{ $next_month }}" class="btn btn-outline-secondary btn-sm px-3">下個月 →</a>
                </div>

                <!-- Calendar -->
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <table class="table table-bordered mb-0 cal-table">
                    <colgroup>
                        <col><col><col><col><col><col><col>
                    </colgroup>

                    <thead class="table-light">
                        <tr>
                        <th class="text-center small fw-semibold text-danger py-2">日</th>
                        <th class="text-center small fw-semibold text-secondary py-2">一</th>
                        <th class="text-center small fw-semibold text-secondary py-2">二</th>
                        <th class="text-center small fw-semibold text-secondary py-2">三</th>
                        <th class="text-center small fw-semibold text-secondary py-2">四</th>
                        <th class="text-center small fw-semibold text-secondary py-2">五</th>
                        <th class="text-center small fw-semibold text-success py-2">六</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>            
                            @foreach($this_month_date as $k => $v)            
                                <?php
                                $this_date_w = get_date_w($v);
                                $num = substr($v,8,2);        
                                $bg_array = ['cal-event-primary','cal-event-success','cal-event-danger','cal-event-warning','cal-event-info'];                    
                                $today_bg = ($v == date('Y-m-d'))?"bg-primary bg-opacity-10":"";                
                                ?>
                                @if($k == 1)
                                    @for($i=1;$i<=$first_w;$i++)
                                        <td class="table-light p-2 align-top"></td>
                                    @endfor
                                @endif
                                <td class="{{ $today_bg }} p-2 align-top">
                                    <div class="small fw-semibold mb-1">{{ $num }}</div>
                                    <?php $n=0; ?>
                                    @foreach($item_array as $k1=>$v1)                        
                                        <?php 
                                            $n = $n%5;
                                            $pill_bg = ($v == date('Y-m-d'))?"cal-event-today":$bg_array[$n];
                                            $n++;                            
                                        ?>
                                        @if($v1['item_date'] == $v)                            
                                            <span class="cal-event {{ $pill_bg }}">
                                            @auth
                                                @if($v1['user_id'] == auth()->user()->id or auth()->user()->admin ==1)
                                                    <a href="#!" class="delete-btn1" data-url="{{ route('monthly_calendars.block_destroy',$k1) }}">
                                                        <i class="fas fa-times text-danger"></i>                 
                                                    </a>                   
                                                @endif
                                            @endauth                            
                                            {{ $v1['item'] }}
                                            </span>                            
                                        @endif
                                    @endforeach
                                </td>        
                                @if($this_date_w == 6)
                                    </tr>
                                @endif                  
                            @endforeach    
                            @for($i=1;$i<=6-$this_date_w;$i++)
                                <td class="table-light p-2 align-top"></td>
                            @endfor
                        </tr>

                    </tbody>
                    </table>
                </div>  
            </div>
            <div class="card border-0 shadow-sm rounded-3 my-4 overflow-hidden">
                <div class="card-header bg-light border-bottom-0 pt-3 pb-2 px-4">
                    <h5 class="card-title fw-bold text-dark d-flex align-items-center mb-0">
                        <i class="fab fa-google text-primary me-2"></i> 從 Google 日曆匯入
                    </h5>
                </div>
                
                <div class="card-body px-4 pb-4 pt-3">
                    <form action="{{ route('monthly_calendars.file') }}" method="POST" enctype="multipart/form-data" id="this_form" class="mb-4">
                        @csrf
                        
                        <div class="input-group style-group mb-2" style="max-width: 600px;">
                            <input type="file" name="filename" class="form-control form-control-sm" required>
                            <button type="submit" class="btn btn-primary btn-sm fw-bold px-3" onclick="return confirm('確定儲存嗎？')">
                                <i class="fas fa-save me-1"></i> 從 ics 檔匯入
                            </button>
                        </div>
                    </form>

                    <div class="alert alert-info border-0 bg-light text-dark p-3 rounded-3 mb-3 small" style="max-width: 750px;">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                                <img src="{{ asset('images/google_calendar1.png') }}" class="img-fluid rounded border shadow-sm" alt="Google日曆操作示意圖">
                            </div>
                            
                            <div class="col-md-8">
                                <div class="fw-bold text-primary mb-2" style="font-size: 14px;">
                                    <i class="fas fa-info-circle me-1"></i> 操作步驟指南：
                                </div>
                                <ul class="list-unstyled mb-0 lh-lg" style="font-size: 13px;">
                                    <li class="d-flex align-items-start mb-1">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle me-2 mt-1 d-inline-flex align-items-center justify-content-center" style="width:18px; height:18px; font-size:10px;">1</span>
                                        <span>至個人 Google 日曆，點擊<strong>「日曆設定」</strong> ➔ ➔ 點選<strong>「匯出日曆」</strong>，系統會自動下載一個 ZIP 壓縮檔。</span>
                                    </li>
                                    <li class="d-flex align-items-start mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle me-2 mt-1 d-inline-flex align-items-center justify-content-center" style="width:18px; height:18px; font-size:10px;">2</span>
                                        <span>將下載的 ZIP 檔<strong>解壓縮</strong>，會得到一個 <code>.ics</code> 檔案，再透過上方表單將它上傳即可。</span>
                                    </li>
                                    <li class="pt-2 border-top border-secondary border-opacity-10 d-flex align-items-center">
                                        <i class="fas fa-gift text-success me-2"></i>
                                        <span>實用資源推薦：可下載 <a href="https://calendar.google.com/calendar/ical/zh.taiwan%23holiday%40group.v.calendar.google.com/public/basic.ics" target="_blank" class="fw-bold text-success text-decoration-none border-bottom border-success border-opacity-50 pb-0.5">台灣節日.ics</a> 搭配利用。</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>            
            <script nonce="{{ $csp_nonce }}">
                document.addEventListener('DOMContentLoaded', function () {
                
                // 1. 當點擊行程事項時，執行 sw_alert
                document.body.addEventListener('click', function (e) {
                    const eventPill = e.target.closest('.cal-event');
                    
                    // 如果點擊的是事項，且點擊的「不是」刪除按鈕本身，才執行 sw_alert
                    if (eventPill && !e.target.closest('.delete-btn1')) {
                        // 你可以把需要傳給 sw_alert 的參數塞在這裡，例如行程文字或日期
                        const itemText = eventPill.textContent.trim(); 
                        
                        // 執行你的 function
                        sw_alert(itemText); 
                    }
                });

                // 2. 為了保險起見，當點擊刪除按鈕時，阻止事件向外傳遞（阻止冒泡）
                document.body.addEventListener('click', function (e) {
                    const deleteBtn = e.target.closest('.delete-btn1');
                    if (deleteBtn) {
                        e.stopPropagation(); // 💡 關鍵：停止事件向上擴散，這樣就不會觸發到 cal-event 的點擊
                    }
                });

            });
            </script>
@endsection
