<ul class="nav nav-tabs border-bottom-0 mb-4" id="setupTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $active[1] }} fw-bold px-3 py-2" href="{{ route('setups.index') }}">
            <i class="fas fa-id-card me-1"></i> 基本資料
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $active[2] }} fw-bold px-3 py-2" href="{{ route('setups.photo') }}">
            <i class="fas fa-image me-1"></i> 首頁圖片
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $active[3] }} fw-bold px-3 py-2" href="{{ route('setups.col') }}">
            <i class="fas fa-columns me-1"></i> 首頁欄位
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $active[4] }} fw-bold px-3 py-2" href="{{ route('setups.block') }}">
            <i class="fas fa-th-large me-1"></i> 區塊內容
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $active[5] }} fw-bold px-3 py-2" href="{{ route('setups.module') }}">
            <i class="fas fa-cubes me-1"></i> 模組功能
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $active[6] }} fw-bold px-3 py-2" href="{{ route('setups.quota') }}">
            <i class="fas fa-hdd me-1"></i> 空間管理
        </a>
    </li>
</ul>