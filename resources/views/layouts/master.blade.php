<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <?php        
        $school_code = school_code();
        $setup = \App\Models\Setup::first();

        $setup_key = "setup".$school_code;
        if(!session($setup_key)){
            $att['views'] = $setup->views+1;
            $setup->update($att);
        }
        session([$setup_key => '1']);

        $nav_color = (empty($setup->nav_color))?"navbar-dark bg-dark":"navbar-custom";
        $bg_color = (empty($setup->bg_color))?"#f0f1f6":$setup->bg_color;
        $navbar_custom = (empty($setup->nav_color))?['0'=>'','1'=>'','2'=>'','3'=>'']:explode(",",$setup->nav_color);
    ?>
    @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" />
    @else
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('images/site_logo.png') }}" />
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="此網站包含一個專屬的網站標誌（Favicon）。">
    <meta name="author" content="">
    <meta http-equiv="Content-Security-Policy" content="script-src * 'unsafe-inline' 'unsafe-eval';">
    @yield('my_meta')
    <title>@yield('title'){{ $setup->site_name }}</title>
    <script src=" https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js "></script>             
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>        
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/localization/messages_zh_TW.min.js"></script>   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>   
    <script src=" https://cdn.jsdelivr.net/npm/venobox@2.1.8/dist/venobox.min.js "></script>     
    <!-- icons -->    
    <link href=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css " rel="stylesheet">   
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">    
    <link href=" https://cdn.jsdelivr.net/npm/venobox@2.1.8/dist/venobox.min.css " rel="stylesheet">
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">
    @yield('in_head')
</head>

<body id="page-top" style="background-color:{{ $bg_color }};font-family:'Arial','Microsoft YaHei','黑體','宋體',sans-serif;">
<style>
    /** 客製化 navbar */
    .navbar-custom {
        background-color: {{ $navbar_custom[0] }};
    }
    
    /* 修改品牌(Brand)與文字顏色 */
    .navbar-custom .navbar-brand,
    .navbar-custom .navbar-text {
        color: {{ $navbar_custom[1] }};
    }
    
    /* 修改連結顏色 */
    .navbar-custom .navbar-nav .nav-link {
        color: {{ $navbar_custom[2] }};
    }
    
    /* 修改「啟用中(active)」或「滑過(hover)」的連結顏色 */
    /* BS5 修正：.active 類別現在多數直接在 .nav-link 上 */
    .navbar-custom .navbar-nav .nav-link.active, 
    .navbar-custom .navbar-nav .nav-link:hover {
        color: {{ $navbar_custom[3] }};
    }
</style>
@include('layouts.nav')
@yield('top_image')
<br>
<div class="container-fluid">
    @yield('content')
</div>
<br>
<br>
<div class="table-responsive">
    @yield('footer')
</div>
@include('layouts.sweet_alert')
@include('layouts.venobox')
<script src="{{ asset('js/my_js.js') }}"></script>
</body>
</html>