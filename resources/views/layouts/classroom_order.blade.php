<?php
    use Carbon\Carbon;
    $classrooms = \App\Models\Classroom::where('disable','=',null)->get();
    $i=1;

    $select_sunday = date('Y-m-d');
    $s_cht_week = config("chcschool.s_cht_week");
    $s_class_sections = config("chcschool.s_class_sections");

    $n = date('w',strtotime($select_sunday));
    $sunday = new Carbon($select_sunday);
    $sunday->subDays($n);

    $last_sunday = $sunday->subDays(7)->toDateString();
    $next_sunday = $sunday->addDays(14)->toDateString();

    $sunday->subDays(7);

    $week = [
        '0'=>$sunday->toDateString(),
        '1'=>$sunday->addDay()->toDateString(),
        '2'=>$sunday->addDay()->toDateString(),
        '3'=>$sunday->addDay()->toDateString(),
        '4'=>$sunday->addDay()->toDateString(),
        '5'=>$sunday->addDay()->toDateString(),
        '6'=>$sunday->addDay()->toDateString(),
    ];
?>

{{-- 頁籤選單區 --}}
<?php $i = 1; ?>
<ul class="nav nav-tabs fw-bold mb-3" id="myTab" role="tablist">
    @foreach($classrooms as $classroom)
        <?php $active = ($i == 1) ? "active" : ""; ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $active }} text-secondary px-3 py-2" 
               id="classroom-tab-{{ $i }}" 
               data-bs-toggle="tab" 
               href="#classroom_profile{{ $i }}" 
               role="tab" 
               aria-controls="classroom_profile{{ $i }}" 
               aria-selected="{{ $i == 1 ? 'true' : 'false' }}">
                <i class="fas fa-door-open me-1 text-opacity-75"></i>{{ $classroom->name }}
            </a>
        </li>
        <?php $i++; ?>
    @endforeach
</ul>

<script nonce="{{ $csp_nonce }}">
    // AJAX 切換週別或教室
    function change_classroom_order(select_sunday, classroom_id){
        $.ajax({
            url: '{{ route('classroom_orders.block_show') }}',
            type : 'post',
            dataType : 'json',
            data : {
                _token: '{{ csrf_token() }}',
                select_sunday: select_sunday,
                select_classroom: classroom_id
            },
            success : function(result) {
                if(result != 'failed') {
                    document.getElementById('classroom_order_content').innerHTML = get_classroom_order(result);
                    
                    // 🎯 關鍵修復：當網頁用 JS 重建按鈕後，必須重新初始化 VenoBox 燈箱
                    // 這裡同時寫入 jQuery 版與原生 JS 版的 VenoBox 初始化，確保相容任何版本的 VenoBox 
                    if (typeof $.fn.venobox === 'function') {
                        $('.venobox').venobox(); 
                    } else if (typeof VenoBox === 'function') {
                        new VenoBox({ selector: '.venobox' });
                    }
                }
            },
            error: function(result) {
                sw_alert('切換上下週失敗，請檢查網路連線或 CSP connect-src 權限。');
            }
        })
    }

    // JS 動態生成區
    function get_classroom_order(result){
        var i = 1;
        data = '';
        for(var k in result['classroom_data']){
            if(k == result['select_classroom']){
                data = data + '<div class="tab-pane fade show active" id="classroom_profile'+i+'" role="tabpanel" aria-labelledby="classroom-tab-'+i+'">';
            }else{
                data = data + '<div class="tab-pane fade" id="classroom_profile'+i+'" role="tabpanel" aria-labelledby="classroom-tab-'+i+'">';
            }
            
            data = data + '<div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-3">';
            data = data + '<table class="table table-bordered table-hover align-middle mb-0 text-center">';
            data = data + '<thead class="table-primary text-dark fw-bold text-nowrap">';
            
            // 第一層表頭：星期
            data = data + '<tr>';
            data = data + '<th rowspan="2" style="width: 50px;" class="bg-light align-middle">';
            data = data + '<span class="btn btn-link text-primary p-0 fs-4 btn-change-week" data-sunday="'+result['last_sunday']+'" data-classroom="'+k+'"><i class="fas fa-arrow-alt-circle-left"></i></span>';
            data = data + '</th>';
            for(var k1 in result['week']){
                data = data + '<th class="py-2">';
                if(k1==0){
                    data = data + '<span class="text-danger fw-bold">'+result['s_cht_week'][k1]+'</span>';
                }else if(k1==6){
                    data = data + '<span class="text-success fw-bold">'+result['s_cht_week'][k1]+'</span>';
                }else{
                    data = data + '<span class="text-secondary fw-bold">'+result['s_cht_week'][k1]+'</span>';
                }
                data = data + '</th>';
            }
            data = data + '<th rowspan="2" style="width: 50px;" class="bg-light align-middle">';
            data = data + '<span class="btn btn-link text-primary p-0 fs-4 btn-change-week" data-sunday="'+result['next_sunday']+'" data-classroom="'+k+'"><i class="fas fa-arrow-alt-circle-right"></i></span>';
            data = data + '</th>';
            data = data + '</tr>';
            
            // 第二層表頭：日期
            data = data + '<tr>';
            for(var k1 in result['week']){
                data = data + '<th class="py-2 bg-white">';
                var date_show = result['week'][k1].substring(5,10);
                
                if(result['today'] == date_show){
                    data = data + '<span class="badge bg-info text-white px-2 py-1 fw-bold rounded-2 shadow-sm">' + date_show + '</span>';
                } else {
                    if(k1==0){
                        data = data + '<span class="text-danger small font-monospace fw-semibold">' + date_show + '</span>';
                    }else if(k1==6){
                        data = data + '<span class="text-success small font-monospace fw-semibold">' + date_show + '</span>';
                    }else{
                        data = data + '<span class="text-secondary small font-monospace fw-semibold">' + date_show + '</span>';
                    }
                }
                data = data + '</th>';
            }
            data = data + '</tr>';
            data = data + '</thead>';
            data = data + '<tbody>';

            // 內容節次
            for(var k1 in result['s_class_sections']){
                data = data + '<tr>';
                data = data + '<td class="table-light fw-bold text-secondary text-nowrap px-2">' + result['s_class_sections'][k1] + '</td>';

                for(var k2 in result['week']){
                    data = data + '<td class="p-2">';
                    if(result['has_order'][result['week'][k2]][k1][k] != ""){
                        var uName = result['has_order'][result['week'][k2]][k1][k];
                        data = data + '<button class="btn btn-sm btn-link text-danger p-0 btn-alert-order text-decoration-none" data-user="' + uName + '">';
                        data = data + '<i class="fas fa-user-lock fs-5"></i><br>';
                        data = data + '<span class="small text-muted d-block mt-1 fw-normal">' + uName + '</span>';
                        data = data + '</button>';
                    }
                    if(result['can_not_order'][result['week'][k2]][k1][k] == "1"){
                        data = data + '<span class="text-danger opacity-50 fw-bold btn-alert-disabled"><i class="fas fa-ban"></i></span>';
                    }
                    data = data + '</td>';
                }
                data = data + '<td class="bg-light"></td>';
                data = data + '</tr>';
            }

            data = data + '</tbody>';
            data = data + '</table>';
            data = data + '</div>';
            
            data = data + '<div class="mb-3 text-start"><a href="./classroom_orders/'+k+'/show/'+result['today2']+'" class="btn btn-success fw-bold px-3 shadow-sm venobox" data-vbtype="iframe"><i class="fas fa-calendar-check me-1"></i> 前往預約 ' + result['classroom_data'][k] + '</a></div>';
            data = data + '</div>';
            i++;
        }
        return data;
    }

    // 全域事件監聽委派 (符合 CSP)
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            // 1. 監聽切換上下週按鈕
            var changeWeekBtn = e.target.closest('.btn-change-week');
            if (changeWeekBtn) {
                var sunday = changeWeekBtn.getAttribute('data-sunday');
                var classroomId = changeWeekBtn.getAttribute('data-classroom');
                change_classroom_order(sunday, classroomId);
                return;
            }

            // 2. 監聽已被預約的提示
            var alertOrderBtn = e.target.closest('.btn-alert-order');
            if (alertOrderBtn) {
                var userName = alertOrderBtn.getAttribute('data-user');
                sw_alert('被 ' + userName + ' 預約了');
                return;
            }

            // 3. 監聽無法預約的提示
            var alertDisabledBtn = e.target.closest('.btn-alert-disabled');
            if (alertDisabledBtn) {
                sw_alert('無法預約');
                return;
            }
        });
    });
</script>

{{-- 靜態初始課表顯示區 --}}
<div class="tab-content mt-2" id="classroom_order_content">
    <?php $i=1; ?>
    @foreach($classrooms as $classroom)
        <?php
        $active = ($i==1)?"show active":"";
        $check_orders = \App\Models\ClassroomOrder::where('classroom_id',$classroom->id)->get();
        $has_order = [];
        foreach($check_orders as $check_order){
            $has_order[$check_order->order_date][$check_order->section]['id'] = $check_order->user_id;
            $has_order[$check_order->order_date][$check_order->section]['user_name'] = $check_order->user->name;
        }
        ?>
        <div class="tab-pane fade {{ $active }}" id="classroom_profile{{ $i }}" role="tabpanel" aria-labelledby="classroom-tab-{{ $i }}">
            
            <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-3">
                <table class="table table-bordered table-hover align-middle mb-0 text-center">
                    <thead class="table-primary text-dark fw-bold text-nowrap">
                        {{-- 第一層表頭：星期 --}}
                        <tr>
                            <th rowspan="2" style="width: 50px;" class="bg-light align-middle">
                                <span class="btn btn-link text-primary p-0 fs-4 btn-change-week" data-sunday="{{ $last_sunday }}" data-classroom="{{ $classroom->id }}"><i class="fas fa-arrow-alt-circle-left"></i></span>
                            </th>
                            @foreach($week as $k => $v)
                                <?php
                                $font="";
                                if($k=="0"){ $font="text-danger"; }
                                if($k=="6"){ $font="text-success"; }
                                ?>
                                <th class="py-2">
                                    <span class="{{ $font }} fw-bold">{{ $s_cht_week[$k] }}</span>
                                </th>
                            @endforeach
                            <th rowspan="2" style="width: 50px;" class="bg-light align-middle">
                                <span class="btn btn-link text-primary p-0 fs-4 btn-change-week" data-sunday="{{ $next_sunday }}" data-classroom="{{ $classroom->id }}"><i class="fas fa-arrow-alt-circle-right"></i></span>
                            </th>
                        </tr>
                        {{-- 第二層表頭：日期 --}}
                        <tr>
                            @foreach($week as $k => $v)
                                <?php
                                $font="";
                                if($k=="0"){ $font="text-danger"; }
                                if($k=="6"){ $font="text-success"; }
                                
                                $is_today = ($v==date('Y-m-d'));
                                ?>
                                <th class="py-2 bg-white">
                                    @if($is_today)
                                        <span class="badge bg-info text-white px-2 py-1 fw-bold rounded-2 shadow-sm">{{ substr($v,5,5) }}</span>
                                    @else
                                        <span class="{{ $font }} small font-monospace fw-semibold text-secondary">{{ substr($v,5,5) }}</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($s_class_sections as $k1=>$v1)
                            <tr>
                                <td class="table-light fw-bold text-secondary text-nowrap px-2">{{ $v1 }}</td>
                                @foreach($week as $k2 => $v2)
                                    <td class="p-2">
                                        @if(empty($has_order[$v2][$k1]['id']))
                                            @if(strpos($classroom->close_sections, "'".$k2."-".$k1."'") !== false)
                                                <span class="text-danger opacity-50 fw-bold btn-alert-disabled"><i class="fas fa-ban"></i></span>
                                            @endif
                                        @else
                                            <button class="btn btn-sm btn-link text-danger p-0 btn-alert-order text-decoration-none" data-user="{{ $has_order[$v2][$k1]['user_name'] }}">
                                                <i class="fas fa-user-lock fs-5"></i><br>
                                                <span class="small text-muted d-block mt-1 fw-normal">{{ $has_order[$v2][$k1]['user_name'] }}</span>
                                            </button>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="bg-light"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mb-3 text-start">
                <a href="{{ route('classroom_orders.show',[$classroom->id,date('Y-m-d')]) }}" class="btn btn-success fw-bold px-3 shadow-sm venobox" data-vbtype="iframe">
                    <i class="fas fa-calendar-check me-1"></i> 前往預約 {{ $classroom->name }}
                </a>
            </div>
        </div>
        <?php $i++; ?>
    @endforeach
</div>