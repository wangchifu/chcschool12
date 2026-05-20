@auth
    @can('create',\App\Models\Post::class)
        <div class="mb-3">
            <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> 新增公告</a>
        </div>
    @endcan
@endauth

{{-- 🎯 關鍵修正：加入 align-middle 與 text-break，確保圖文橫向排列時垂直居中且不破版 --}}
<table class="table table-striped align-middle text-break">
    <tbody>
    <?php $i = 1; ?>
    @foreach($posts as $post)
        <?php
        if($post->insite == 1){
            $can_see = (auth()->check() || check_ip()) ? 1 : 0;
        }else{
            $can_see = 1;
        };
        $school_code = school_code();
        $title = \Illuminate\Support\Str::limit($post->title, 80);
        
        $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
        $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
        $n = 2;
        ?>
        <tr>
            {{-- 🎯 流水號欄位：寬度固定，不因文字過長而變形 --}}
            <td class="text-secondary fw-bold text-center" style="width: 5%;">
                {{ $i }}
            </td>
            
            @if($can_see)
                @if($post->title_image)
                    <td style="width: 20%;">
                        <a href="{{ route('posts.show',$post->id) }}">
                            <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" class="img-fluid rounded border shadow-sm" alt="{{ $post->id }}公告的示意圖片">
                        </a>
                    </td>
                    <?php $n = 1; ?>
                @endif
            @endif
            
            <td colspan="{{ $n }}" class="p-3">
                @if($can_see)
                    {{-- 標題與徽章列 --}}
                    <div class="mb-2">
                        {{-- 🎯 修正：將 p 改為 span 徽章，移除殘留的 margin --}}
                        @if($post->top)
                            <span class="badge bg-danger me-1">置頂</span>
                        @endif
                        @if($post->inbox)
                            <span class="badge bg-warning text-dark me-1">常駐</span>
                        @endif
                        @if($post->insite == 1)
                            <span class="badge bg-danger me-1">內部公告</span>
                        @endif
                        <span class="fs-5 fw-bold">
                            <a href="{{ route('posts.show',$post->id) }}" class="text-decoration-none text-dark">{{ $post->title }}</a>
                        </span>
                    </div>
                    
                    {{-- 內文摘要 --}}
                    <p class="text-secondary mb-2">
                        <?php
                            $content = \Illuminate\Support\Str::limit(strip_tags($post->content), 320);
                            $content = str_replace('&nbsp;','',$content);
                        ?>
                        {{ $content }}
                        
                        {{-- 附件小圖示 --}}
                        @if(!empty($photos))
                            <span class="text-success ms-1" title="附有圖片"><i class="fas fa-images"></i></span>
                        @endif
                        @if(!empty($files))
                            <span class="text-info ms-1" title="附有檔案"><i class="fas fa-download"></i></span>
                        @endif
                    </p>
                    
                    {{-- 🎯 輔助資訊列優化 --}}
                    <div class="small text-muted">
                        <span class="badge bg-light text-secondary border me-1">
                            {{ ($post->insite == null) ? '一般公告' : ($post_type_array[$post->insite] ?? '內部公告') }}
                        </span>
                        <span class="me-2">/ 發佈者：<a href="{{ route('posts.job_title',$post->job_title) }}" class="text-decoration-none text-secondary fw-semibold">{{ $post->job_title }}</a></span>
                        <span class="me-2">/ 日期：{{ substr($post->created_at, 0, 10) }}</span>
                        <span>/ 點閱：{{ $post->views }}</span>
                    </div>
                @else
                    {{-- 無權限查看時的排版 --}}
                    <div class="mb-2">
                        <span class='badge bg-danger me-1'>內部公告</span>
                        <span class="fs-5 text-muted text-decoration-line-through">
                            {{ $title }}
                        </span>
                    </div>
                    
                    <div class="small text-muted">
                        <span class="badge bg-light text-danger border me-1">
                            {{ ($post->insite == null) ? '一般公告' : ($post_type_array[$post->insite] ?? '內部公告') }}
                        </span>
                        <span class="me-2">/ 發佈者：<a href="{{ route('posts.job_title',$post->job_title) }}" class="text-decoration-none text-secondary fw-semibold">{{ $post->job_title }}</a></span>
                        <span class="me-2">/ 日期：{{ substr($post->created_at, 0, 10) }}</span>
                        <span>/ 點閱：{{ $post->views }}</span>
                    </div>
                @endif
            </td>
        </tr>
        <?php $i++; ?>
    @endforeach
    </tbody>
</table>

<div class="mt-2">
    <a href="{{ route('posts.index') }}" class="text-decoration-none"><small><i class="far fa-hand-point-up"></i> 更多 公告...</small></a>
</div>