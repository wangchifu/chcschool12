<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <?php
        $school_code = school_code();
        $setup = \App\Models\Setup::find(1);                        
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
    
    {{-- CSP 政策 --}}
    <meta http-equiv="Content-Security-Policy" content="
    default-src 'self';
    script-src 'self' 'nonce-{{ $csp_nonce }}' https://cdn.jsdelivr.net;
    style-src 'self' 'nonce-{{ $csp_nonce }}' https://cdn.jsdelivr.net;
    font-src 'self' https://cdn.jsdelivr.net;
    img-src 'self' data:;
    connect-src 'self';">

    @yield('my_meta')
    <title>@yield('title') | {{ $setup->site_name }}</title>
    @include('layouts.js_css')    
    @yield('in_head')    
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">

    <style nonce="{{ $csp_nonce }}">
        body#page-top {        
            font-family: 'Arial', 'Microsoft YaHei', sans-serif;
            background-color: white !important; {{-- 列印頁通常強制白色背景 --}}
        }

        /* 列印專屬設定 */
        @media print {
            .no-print { display: none !important; } {{-- 標記為 no-print 的元素不會被印出來 --}}
            @page { margin: 1cm; } {{-- 設定邊界 --}}
        }
    </style>
</head>

<body id="page-top">    

    {{-- pt-3 增加一點頂部距離，避免列印時太貼邊 --}}
    <main class="container-fluid pt-3 mb-5">
        @yield('content')
    </main> 
       
    {{-- 自動觸發列印 --}}
    <script nonce="{{ $csp_nonce }}">
        window.addEventListener('load', function() {
            window.print();
            // 如果列印後想自動關閉視窗，可以取消下面註解
            // window.onafterprint = function() { window.close(); };
        });
    </script>

    {{-- 加上 nonce 確保符合 script-src 規範 --}}
    <script src="{{ asset('js/tinymce.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/sweet_alert.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/venobox.js') }}" nonce="{{ $csp_nonce }}"></script>
    <script src="{{ asset('js/my_js.js') }}" nonce="{{ $csp_nonce }}"></script>
</body>
</html>