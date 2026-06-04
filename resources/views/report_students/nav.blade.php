<ul class="nav nav-tabs border-bottom-0 mb-4 gap-1">
    <li class="nav-item">
        <a class="nav-link rounded-top-3 fw-bold px-4 py-2.5 transition-all {{ $active['index'] }}" 
           href="{{ route('report_students.index') }}">
            <i class="fas fa-user-edit me-2"></i>導師填報
        </a>
    </li>
    
    @if($admin)    
    <li class="nav-item">
        <a class="nav-link rounded-top-3 fw-bold px-4 py-2.5 transition-all {{ $active['admin'] }}" 
           href="{{ route('report_students.admin') }}">
            <i class="fas fa-tasks me-2"></i>填報管理
        </a>
    </li>
    @endif
</ul>

{{-- 🎯 精緻化小樣式：滑鼠懸停時的細緻平滑過渡效果 --}}
<style nonce="{{ $csp_nonce }}">
    .nav-tabs .nav-link {
        color: #6c757d;
        border: 1px solid transparent;
    }
    .nav-tabs .nav-link:hover {
        color: #0d6efd;
        background-color: #f8f9fa;
        border-color: #dee2e6 #dee2e6 transparent;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>