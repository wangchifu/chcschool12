<?php $module_setup = get_module_setup(); ?>
<style nonce="<?php echo $csp_nonce; ?>">
    @if($nav_color == "navbar-custom" && count($navbar_custom) >= 4)
    .navbar-custom {
        background: linear-gradient(135deg, {{ $navbar_custom[0] }} 0%, #1e293b 100%) !important;
    }
    .navbar-custom .navbar-brand {
        color: {{ $navbar_custom[1] }} !important;
    }
    @endif

    .custom-toggler.navbar-toggler {
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        padding: 0.4rem 0.65rem;
        border-radius: 0.5rem;
    }

    .custom-toggler .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,0.9)' stroke-width='2.5' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
    }
</style>

<nav class="navbar navbar-expand-lg {{ $nav_color }}" id="mainNav" aria-label="簡易導覽區">
    <div class="container-fluid px-3 px-lg-4">
        <!-- 首頁連結：合併圖文 -->
        <a href="{{ route('index') }}" class="d-flex align-items-center me-2 text-decoration-none navbar-brand js-scroll-trigger fs-5" aria-label="{{ $setup->site_name }} 首頁">
            @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="32" height="32" class="d-inline-block align-top me-2" alt="">
            @else
                <img src="{{ asset('images/site_logo.png') }}" width="32" height="32" class="d-inline-block align-top me-2" alt="">
            @endif
            <span style="white-space:pre-wrap;">{{ $setup->site_name }}</span>
        </a>
        
        <!-- 手機版選單切換按鈕 -->
        <button class="navbar-toggler custom-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="切換導覽選單">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto">
            </ul>
        </div>
    </div>
</nav>