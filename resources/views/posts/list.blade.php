<?php
    if(!isset($type_name)) $type_name = null;
    $key = rand(100, 999);
    session(['search' => $key]);    
    $post_types = \App\Models\PostType::orderBy('order_by')->get(); 

    $post_type_array = [];
    foreach($post_types as $post_type){
        $post_type_array[$post_type->id] = $post_type->name;
    }
?>

{{-- 1. 工具列排版：改用 Bootstrap 5 彈性盒模型 (Flexbox) 布局 --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    
    {{-- 左側：類別篩選與管理功能 --}}
    <div class="d-flex flex-wrap align-items-center gap-2">
        <form id="select_type_form" action="{{ route('posts.select_type') }}" method="post" class="m-0">
            @csrf                    
            {{-- 🎯 修正：移除 style，改用 w-auto (自適應內容寬度) --}}
            <select id="select_type" name="select_type" class="form-select form-select-sm w-auto" title="選擇公告類別">
                <option value="a">請選類別</option>
                @foreach($post_types as $post_type)
                    @if($post_type->disable != 1)
                        <?php $selected = ($post_type->name == $type_name) ? "selected" : null; ?>
                        <option value="{{ $post_type->id }}" {{ $selected }}>{{ $post_type->name }}</option>
                    @endif
                @endforeach
            </select>
        </form>

        @can('create', \App\Models\Post::class)
            <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> 新增公告
            </a>
        @endcan
        
        @auth
            @if(auth()->user()->admin == 1)
                <a href="{{ route('posts.show_type') }}" id="btn-manage-type" class="btn btn-outline-success btn-sm venobox" data-vbtype="iframe">
                    <i class="fas fa-cog me-1"></i> 類別管理
                </a>
            @endif
        @endauth
    </div>

    {{-- 右側：關鍵字搜尋與驗證碼 --}}
    <div>
        <form action="{{ route('posts.search') }}" method="post" class="search-form m-0" id="this_form">
            @csrf
            {{-- 🎯 修正：移除輸入框的 max-width 內聯樣式。
                 改用 row、g-1 (Grid) 緊密布局，由外層控制整體寬度，在寬螢幕下收合在右側，寬度自動完美分配 --}}
            <div class="row g-1 align-items-center">
                <div class="col-auto">
                    <input type="text" class="form-control form-control-sm" name="search" id="search" title="請輸入要搜尋公告的關鍵字" placeholder="關鍵字" required>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control form-control-sm text-center bg-light fw-bold" name="check" title="請輸入左方圖示呈現的驗證碼" placeholder="請輸入 {{ session('search') }}" required maxlength="3">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-secondary btn-sm" aria-label="提交搜尋公告的表單">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            @include('layouts.errors')
        </form>
    </div>
</div>

{{-- 2. 公告列表主體表格 --}}
<div class="table-responsive">
    {{-- 🎯 修正：移除 style="word-break:break-all;"，改用 Bootstrap 5 內建的 text-break 類別 --}}
    <table class="table table-striped align-middle text-break">
        <thead class="table-light">
            <tr>
                {{-- 🎯 修正：將「發佈者」寬度微調增加至 15%（原 12%），「標題」寬度微調為 47%（原 50%），讓文字容納空間更合理 --}}
                <th class="text-nowrap" style="width: 15%;">日期</th>
                <th class="text-nowrap" style="width: 15%;">類別</th>
                <th class="text-nowrap" style="width: 47%;">標題</th>
                <th class="text-nowrap" style="width: 15%;">發佈者</th>
                <th class="text-nowrap" style="width: 8%;">點閱</th>
            </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td class="text-secondary text-nowrap">                    
                    {{ substr($post->created_at, 0, 10) }}
                </td>
                <td>
                    @if($post->insite == null)
                        <a href="{{ route('posts.type', 0) }}" class="badge bg-light text-dark text-decoration-none border">一般公告</a>
                    @else
                        <a href="{{ route('posts.type', $post->insite) }}" class="badge bg-light text-primary text-decoration-none border">{{ $post_type_array[$post->insite] }}</a>
                    @endif
                </td>
                <td>
                    @if($post->top)
                        <span class="badge bg-danger me-1">置頂</span>
                    @endif
                    @if($post->inbox)
                        <span class="badge bg-warning text-dark me-1">常駐</span>
                    @endif
                    
                    <?php
                    if($post->insite == 1){
                        $can_see = (auth()->check() || check_ip()) ? 1 : 0;
                    } else {
                        $can_see = 1;
                    };
                    $school_code = school_code();
                    $title = $post->title;
                    
                    $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                    $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                    ?>
                    
                    @if($can_see)
                        @if($post->insite == 1)
                            <span class="text-danger fw-bold">[ 內部公告 ]</span>
                        @endif
                        <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark fw-md">{{ $title }}</a>
                    @else
                        <span class='text-danger fw-bold'>[ 內部公告 ]</span>
                        <span class="text-muted text-decoration-line-through">{{ $title }}</span>
                    @endif
                    
                    @if(!empty($photos))
                        <span class="text-success ms-1" title="附有圖片"><i class="fas fa-image"></i></span>
                    @endif
                    @if(!empty($files))
                        <span class="text-info ms-1" title="附有檔案"><i class="fas fa-download"></i></span>
                    @endif
                </td>
                {{-- 🎯 修正：在 <td> 加上 text-nowrap 類別，強制發佈者欄位不論中英文均不換行 --}}
                <td class="text-nowrap">
                    <a href="{{ route('posts.job_title', $post->job_title) }}" class="text-decoration-none text-secondary"><small>{{ $post->job_title }}</small></a>
                </td>
                <td class="text-muted">
                    {{ $post->views }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- 3. 安全且完全抽離的 JavaScript 事件處理監聽器區塊 --}}
<script nonce="{{ $csp_nonce }}">
    document.addEventListener("DOMContentLoaded", function() {        
        // 下拉選單變更時自動遞交表單
        const selectType = document.getElementById('select_type');
        if (selectType) {
            selectType.addEventListener('change', function() {
                if (this.value !== 'a') {
                    document.getElementById('select_type_form').submit();
                }
            });
        }
    });
</script>