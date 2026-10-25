<!DOCTYPE html>
<html lang="zh-Hant-TW">

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
        $bg_color = (empty($setup->bg_color))?"#f8fafc":$setup->bg_color;
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
    style-src 'self' 'nonce-{{ $csp_nonce }}' https://cdn.jsdelivr.net https://fonts.googleapis.com;
    font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com;
    img-src 'self' data:;
    connect-src 'self';">
    @yield('my_meta')
    <title>@yield('title') @if(View::hasSection('title')) - @endif{{ $setup->site_name }}</title>
    @include('layouts.css')    
    @yield('in_head')
    <link href="{{ asset('css/my.css') }}" rel="stylesheet">

    <style nonce="{{ $csp_nonce }}">
        body#page-top {
            background-color: {{ str_starts_with($bg_color, '#') ? $bg_color : '#' . $bg_color }};
        }

        /* 若資料庫有自訂色彩，自動生成漸層與高質感邊框 */
        @if($nav_color == "navbar-custom" && count($navbar_custom) >= 4)
        .navbar-custom {
            background: linear-gradient(135deg, {{ $navbar_custom[0] }} 0%, #1e293b 100%) !important;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: {{ $navbar_custom[1] }} !important;
        }
        .navbar-custom .nav-link:hover, .navbar-custom .nav-link:focus {
            color: {{ $navbar_custom[3] }} !important;
        }
        @endif

        .text-decoration-none {
            text-decoration: none !important;
        }
    </style>
</head>

<body id="page-top">
    <!-- 無障礙快速跳轉連結 (Tab 第一個出現) -->
    <a href="#main-content" class="skip-link">跳至主要內容區塊</a>

    <!-- 頁首導覽 (Header Landmark) -->
    <header style="position: relative; z-index: 1050;">
        <!-- 無障礙定位點 U (Accesskey U) -->
        <a href="#mainNav" id="a-u" accesskey="U" title="上方導覽頁首區塊" class="visually-hidden-focusable">:::</a>
        @include('layouts.nav', ['csp_nonce' => $csp_nonce])
    </header>
    
    @yield('top_image')
        
    <!-- 主要內容區 (Main Landmark) -->
    <main id="main-content" class="container-fluid pt-4 pb-5" tabindex="-1">
        <!-- 無障礙定位點 C (Accesskey C) -->
        <a href="#main-content" id="a-c" accesskey="C" title="中央主要內容區塊" class="visually-hidden-focusable">:::</a>
        @yield('content')
    </main>

    <!-- 頁尾區 (Footer Landmark) -->
    <footer class="footer">
        <!-- 無障礙定位點 Z (Accesskey Z) -->
        <a href="#footer-content" id="a-z" accesskey="Z" title="下方頁尾資訊區塊" class="visually-hidden-focusable">:::</a>
        <div id="footer-content" class="container text-center">
            @yield('footer')
        </div>
    </footer>

    @include('layouts.js')
    <script src="{{ asset('js/tinymce.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/sweet_alert.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/venobox.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/my.js') }}" nonce="{{ $csp_nonce }}"></script>
    @auth
        <script src="{{ asset('js/logout.js') }}" nonce="{{ $csp_nonce }}"></script>
    @endauth
    @yield('down_body')
</body>
</html>