<?php
$blogs = \App\Models\Blog::orderBy('created_at','DESC')
    ->paginate(5);
?>

<style nonce="{{ $csp_nonce ?? '' }}">
    /* 核心修正：為外層容器加上 overflow-x: hidden，徹底封鎖右側隱形捲軸 */
    .widget-blog-container {
        overflow-x: hidden;
        padding: 2px; /* 留一點微小空間給懸浮陰影伸展 */
    }

    /* 讓側邊欄清單在懸浮時有優雅的微淡入與平移效果 */
    .widget-blog-item {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    
    .widget-blog-item:hover {
        background-color: #f8f9fa !important;
        transform: translateX(3px);
    }
    
    /* 配合字體變大，微調圖片尺寸讓視覺比例更完美 */
    .widget-blog-img {
        width: 105px;
        height: 80px;
        object-fit: cover;
    }
</style>

<div class="widget-blog-container">

    @can('create',\App\Models\Post::class)
        <div class="mb-3">
            <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm fw-bold rounded-pill shadow-sm venobox" data-vbtype="iframe">
                <i class="fas fa-plus shadow-xs"></i> 新增文章
            </a>
        </div>
    @endcan

    <div class="list-group list-group-flush border-top border-bottom mb-3 shadow-xs">
        @foreach($blogs as $blog)
            <?php
            $content = $blog->content;
            $content = str_replace('&nbsp;','',$content);
            $content = strip_tags($content);
            $content = mb_strimwidth($content, 0, 140, '...', 'utf-8');
            ?>
            
            <div class="list-group-item widget-blog-item py-3 px-1 border-0 border-bottom-dashed">
                <div class="d-flex align-items-start gap-3">
                    
                    <div class="flex-shrink-0">
                        <a href="{{ route('blogs.show',$blog->id) }}" class="d-block">
                            @if($blog->title_image)
                                <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="widget-blog-img rounded shadow-sm border" alt="文章圖片">
                            @else
                                <img src="https://picsum.photos/160/120?random={{ $blog->id }}" class="widget-blog-img rounded shadow-sm border" alt="隨機圖片">
                            @endif
                        </a>
                    </div>
                    
                    <div class="flex-grow-1 min-w-0" style="word-break: break-all;">
                        <h5 class="mb-1 fw-bold fs-5">
                            <a href="{{ route('blogs.show',$blog->id) }}" class="text-dark text-decoration-none link-primary text-truncate d-block venobox" data-vbtype="iframe">
                                {{ $blog->title }}
                            </a>
                        </h5>
                        
                        <p class="text-secondary mb-1 lh-sm fs-6">
                            {{ $content }}
                        </p>
                        
                        <div class="text-muted d-flex flex-wrap gap-2 align-items-center" style="font-size: 0.85rem;">
                            <span class="text-dark fw-semibold">
                                <i class="fas fa-user-circle me-1 opacity-75"></i>
                                @if(!empty($blog->job_title))
                                    {{ $blog->job_title }}
                                @else
                                    @if($blog->user->name == "系統管理員")
                                        系統管理員
                                    @else
                                        {{ $blog->user->title }}
                                    @endif
                                @endif
                            </span>
                            <span>•</span>
                            <span>{{ $blog->created_at->format('m/d') }}</span>
                            <span>•</span>
                            <span><i class="fas fa-eye me-0.5"></i> {{ $blog->views }}</span>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <div class="text-start">
        <a href="{{ route('blogs.index') }}" class="btn btn-light border rounded-pill px-3 py-1.5 fw-semibold text-secondary shadow-xs" style="font-size: 0.9rem;">
            <i class="far fa-hand-point-up me-1 text-primary"></i> 更多文章...
        </a>
    </div>

</div>