<?php
$schools = config('chcschool.schools');
$school_name = str_replace('彰化縣','',$schools[$school_code]);

$check = \App\Models\UserGroup::where('user_id',$user->id)
    ->where('group_id',1)
    ->first();
?>

{{-- 🛠️ 導覽列：升級 Bootstrap 5 標準輕量排版，加入 rounded 圓角、陰影與內襯間距 --}}
<nav class="navbar navbar-expand bg-light rounded-3 shadow-sm p-3 mb-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <span class="fw-bold text-dark">{{ $schools[$school_code] }}</span>
        <span class="text-secondary ms-2"><i class="fas fa-user"></i> {{ $user->name }}</span>
        <span class="text-muted ms-1"><i class="fas fa-info-circle"></i></span>
    </div>
    <a href="{{ route('tasks.logout') }}" onclick="if(confirm('登出嗎?')) return true;else return false">
        <i class="fas fa-sign-out-alt text-danger fs-5"></i>
    </a>
</nav>

@if($check)
    <form action="{{ route('tasks.store') }}" method="POST" id="tasks_store" enctype="multipart/form-data" class="bg-white p-3 rounded-3 border shadow-sm mb-4">
    @csrf
    
    {{-- 🛠️ 區塊美化：使用 Bootstrap 5 的 row 與 col 網格，取代舊式 table 佈局 --}}
    <div class="row g-2 align-items-center">
        {{-- 輸入框欄位 (寬度佔 7/12) --}}
        <div class="col-md-7">
            <input type="text" name="title" id="title" class="form-control" required="required" placeholder="給大家的事項">
        </div>
        
        {{-- 檔案選擇欄位 (寬度佔 3/12) --}}
        <div class="col-md-3">
            <input type="file" name="files[]" class="form-control" multiple="multiple">
        </div>
        
        {{-- 按鈕欄位 (寬度佔 2/12，並在手機版自動撐滿) --}}
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-success fw-bold py-2 btn-sm" onclick="if(confirm('您確定送出嗎?')) return true;else return false">
                <i class="fas fa-plus"></i> 新增
            </button>
        </div>
    </div>
    
    {{-- 提示文字小備註 --}}
    <div class="mt-2 ps-1">
        <small class="text-muted"><i class="fas fa-exclamation-circle me-1"></i>請簡短扼要；一次新增一事項。</small>
    </div>
    
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    </form>
@endif