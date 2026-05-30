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
    <a href="?month={{ $last_month }}#monthly_calendar" class="btn btn-outline-secondary btn-sm px-3">← 上個月</a>
    <h4 class="mb-0 fw-semibold">{{ $format_this_month }}</h4>
    <a href="?month={{ $next_month }}#monthly_calendar" class="btn btn-outline-secondary btn-sm px-3">下個月 →</a>
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