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
    <meta http-equiv="Content-Security-Policy" content="
    default-src 'self';
    script-src 'self' 'nonce-{{ $csp_nonce }}' https://cdn.jsdelivr.net;
    style-src 'self' 'nonce-{{ $csp_nonce }}' https://cdn.jsdelivr.net;
    font-src 'self' https://cdn.jsdelivr.net;
    img-src 'self' data:;
    connect-src 'self';">
    <title>@yield('title'){{ $setup->site_name }}</title>    
    @include('layouts.js_css')    
    @yield('in_head')        
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">

    {{-- 將 style 移入 head 並優化 CSS 解析穩定性 --}}
    <style nonce="{{ $csp_nonce }}">
        body#page-top {        
            /* 確保背景顏色有 # 號且不加引號，避免 CSS 失效 */
            background-color: {{ str_starts_with($bg_color, '#') ? $bg_color : '#' . $bg_color }};
            font-family: 'Arial', 'Microsoft YaHei', sans-serif;
        }

        /* 處理 navbar-custom 自定義顏色 */
        @if($nav_color == "navbar-custom" && count($navbar_custom) >= 4)
        .navbar-custom {
            background-color: {{ $navbar_custom[0] }} !important;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: {{ $navbar_custom[1] }} !important;
        }
        .navbar-custom .nav-link:hover {
            color: {{ $navbar_custom[3] }} !important;
        }
        @endif

        /* 通用類別 */
        .text-decoration-none { text-decoration: none !important; }
    </style>
</head>

<body id="page-top">
    @include('layouts.nav_close',['csp_nonce'=>$csp_nonce])    
    
    {{-- 加入 pt-4 增加與導覽列的間距，代替 <br> --}}
    <main class="container-fluid pt-4 mb-5">
        @yield('content')
    </main>

    <div class="table-responsive">
        @yield('footer')
    </div>
@yield('down_body')

{{-- 加上 nonce 確保符合 script-src 政策 --}}
    <script src="{{ asset('js/tinymce.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/sweet_alert.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/venobox.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/my_js.js') }}" nonce="{{ $csp_nonce }}"></script>
</body>
</html>