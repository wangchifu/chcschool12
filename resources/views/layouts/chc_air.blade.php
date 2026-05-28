<?php
// 1. 宣告所有初始變數，防止 Undefined 錯誤
$options = "";
$air_data = [];
$aqi_val = null;
$badge_class = "bg-secondary text-white"; // 預設灰色
$img = "000.jpg";

// 2. 改用 Laravel 絕對路徑 base_path() 定位專案目錄
$download_dir = base_path('service/chc_air/download');

// 如果資料夾不存在，自動建立它
if (!is_dir($download_dir)) {
    @mkdir($download_dir, 0755, true);
}

if(date('i') > 10){
    $chk_file = date('YmdH0000');
}else{
    if(date('H') <> '00'){
        $last = sprintf('%02s', date('H')-1);
        $chk_file = date('Ymd').$last.'0000';
    }else{
        $chk_file = "nothing";
    }
}

$chk_file_path = $download_dir.'/'.$chk_file.'.txt';

if(file_exists($chk_file_path)){
    $air_data = unserialize(file_get_contents($chk_file_path));
}elseif($chk_file == "nothing"){
    $air_data = [];
}else{
    $url = env('AIR_API_URL');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $html = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($html);

    $today_count_file = $download_dir.'/'.date('Ymd').'.txt';
    if(file_exists($today_count_file)){
        $count = file_get_contents($today_count_file);
    }else{
        $count = 0;
    }
    
    // 安全寫入計數器
    $file_count = @fopen($today_count_file, 'w');    
    if ($file_count) {
        $count++;
        fwrite($file_count, $count);
        fclose($file_count);
    }

    if(!isset($data->records)){
        $data = [];
        $air_data = [];
    }else{
        foreach($data->records as $k=>$v){
            $select_data[$v->county][] = $v->sitename;
            $air_data[$v->sitename]['AQI'] = $v->aqi;
            $air_data[$v->sitename]['Status'] = $v->status;
            $air_data[$v->sitename]['PublishTime'] = $v->publishtime;
        }
        if(!isset($v->publishtime)){
            $fname = "no_publishtime";
            $air_data = [];
        }else{
            $fname = str_replace('/','',$v->publishtime);
        }        
        $fname = str_replace(' ','',$fname);
        $fname = str_replace(':','',$fname);
        
        $api_cache_file = $download_dir.'/'.$fname.'.txt';
        $file = @fopen($api_cache_file, 'w');
        if ($file) {
            fwrite($file, serialize($air_data));
            fclose($file);
        }
    }
}

// 取得前端傳來的測站名稱
$SiteName = isset($request) ? $request->input('SiteName') : null;

// 安全防禦：確保 $air_data 是陣列才進行後續查詢
if(is_array($air_data) && !empty($air_data)){
    if(!isset($air_data[$SiteName]) and $SiteName != null){
        $SiteName = "彰化";
    }
    if(empty($_COOKIE['chc_air'])){
        $select_site = "彰化";
    }else{
        $select_site = $_COOKIE['chc_air'];
        if($SiteName) $select_site = $SiteName;
    }

    if(!isset($air_data[$select_site])) {
        $select_site = key($air_data) ?: "彰化";
    }

    setcookie("chc_air", $select_site, time()+31556926, "/");

    // 產生下拉選單選項
    foreach($air_data as $k=>$v){
        $selected = ($k == $select_site) ? "selected" : "";
        $options .= "<option value='$k' $selected>$k</option>";
    }

    // 根據 AQI 計算數值
    $aqi_val = isset($air_data[$select_site]['AQI']) ? $air_data[$select_site]['AQI'] : null;
} else {
    $select_site = "彰化";
}

if($aqi_val !== null && $aqi_val !== '') {
    if($aqi_val <= 50){
        $img = "50.jpg";
        $badge_class = "bg-success text-white";
    } elseif($aqi_val >= 51 && $aqi_val <= 100){
        $img = "100.jpg";
        $badge_class = "bg-warning text-dark";
    } elseif($aqi_val >= 101 && $aqi_val <= 150){
        $img = "150.jpg";
        $badge_class = "bg-orange text-white";
    } elseif($aqi_val >= 151 && $aqi_val <= 200){
        $img = "200.jpg";
        $badge_class = "bg-danger text-white";
    } elseif($aqi_val >= 201){
        $img = "300.jpg";
        $badge_class = "bg-purple text-white";
    }
}
?>

{{-- 🎯 區塊 A：Bootstrap 5 現代化外層卡片容器 --}}
<div class="card shadow-sm border-0 bg-white rounded-3 overflow-hidden">
    
    {{-- 卡片頭部：測站選擇與 AQI 數值顯示 --}}
    <div class="card-body p-3 bg-light border-bottom">
        <div class="row align-items-center g-2">
            
            {{-- 下拉選單（符合 CSP 規範） --}}
            <div class="col-6 col-sm-7">
                <select name="SiteName" id="SiteName" class="form-select form-select-sm fw-bold border-secondary-subtle">
                    <?php if(!empty($options)): ?>
                        <?php echo $options; ?>
                    <?php else: ?>
                        <option value="彰化">彰化 (無資料)</option>
                    <?php endif; ?>
                </select>
            </div>
            
            {{-- AQI 智慧標籤 --}}
            <div class="col-6 col-sm-5 text-end">
                <span class="fs-7 text-secondary me-1 fw-medium">AQI:</span>
                <?php if($aqi_val !== null): ?>
                    <span class="badge <?php echo $badge_class; ?> px-2.5 py-1.5 fs-6 fw-bold shadow-sm rounded-pill">
                        <?php echo $aqi_val; ?>
                    </span>
                <?php else: ?>
                    <span class="badge bg-secondary px-2.5 py-1.5 fs-6 rounded-pill">--</span>
                <?php endif; ?>
            </div>
            
        </div>
    </div>

    {{-- 卡片主體：空品插圖展示 --}}
    <div class="card-body p-0 text-center bg-dark-subtle position-relative">
        <img src="{{ asset('images/chc_air/'.$img) }}" class="img-fluid w-100 d-block" alt="空氣品質對應圖">
    </div>

    {{-- 卡片底部：發布時間 --}}
    <?php if(is_array($air_data) && isset($air_data[$select_site]['PublishTime'])): ?>
        <div class="card-footer bg-white py-2 px-3 text-end">
            <small class="text-muted fs-7">
                <i class="far fa-clock me-1 text-secondary"></i>
                <?php echo $air_data[$select_site]['PublishTime']; ?>
            </small>
        </div>
    <?php endif; ?>

</div>

{{-- 🎯 核心修正：在 style 標籤上也帶入 nonce，使其完全符合 CSP 政策，不被瀏覽器阻擋 --}}
<style nonce="{{ $csp_nonce }}">
    .bg-orange { background-color: #ff8c00 !important; color: #fff !important; }
    .bg-purple { background-color: #8a2be2 !important; color: #fff !important; }
    .fs-7 { font-size: 0.825rem; }
</style>

{{-- 🎯 區塊 B：完全符合 CSP 的安全的事件監聽器 --}}
<script nonce="{{ $csp_nonce }}">
    $(document).ready(function() {
        $('#SiteName').on('change', function() {
            var selectedSite = $(this).val();
            if (selectedSite) {
                window.location.href = "?SiteName=" + encodeURIComponent(selectedSite);
            }
        });
    });
</script>