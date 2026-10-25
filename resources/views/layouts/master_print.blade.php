<!DOCTYPE html>
<html lang="zh-Hant-TW">

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
            background-color: #ffffff !important; /* 列印頁強制乾淨白底 */
        }

        /* 列印專屬優化設定 */
        @media print {
            .no-print { display: none !important; }
            @page { margin: 1cm; }
            body { background: white !important; }
        }
    </style>
</head>

<body id="page-top">    
    <!-- 主要內容區 (Main Landmark) -->
    <main id="main-content" class="container-fluid pt-3 pb-5" tabindex="-1">
        <!-- 無障礙定位點 C (Accesskey C) -->
        <a href="#main-content" id="a-c" accesskey="C" title="中央主要內容區塊" class="visually-hidden-focusable">:::</a>
        
        <!-- 手動列印控制備用按鈕 (非列印狀態下顯示) -->
        <div class="text-end mb-3 no-print">
            <button type="button" onclick="window.print();" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-print me-1" aria-hidden="true"></i> 列印本頁
            </button>
        </div>

        @yield('content')
    </main> 

    @include('layouts.js')
    <script nonce="{{ $csp_nonce }}">
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
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