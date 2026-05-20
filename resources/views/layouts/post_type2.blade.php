{{-- 🎯 修正：保持 CSP 的 nonce 機制 --}}
<style nonce="{{ $csp_nonce }}">
    .image-container {
        max-width: 90%;
        margin: 0 5%;
    }
    .pp1 {
        margin-bottom: 0;
    }
</style>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <?php $setup = \App\Models\Setup::first(); ?>
    @if($setup->all_post)
    <li class="nav-item" role="presentation">
        {{-- 🎯 關鍵修正：將 data-toggle 改為 data-bs-toggle --}}
        <a class="nav-link active" id="post_type2_all_post-tab" data-bs-toggle="tab" href="#post_type2_all_post" role="tab" aria-controls="post_type2_all_post" aria-selected="true">全部公告</a>
    </li>
    @endif
    <?php $p = 1; ?>
    @foreach($post_types as $post_type)
        <?php
        $active = ($p == 1 and $setup->all_post == null) ? "active" : null;    
        $aria_selected = ($p == 1 and $setup->all_post == null) ? "true" : "false";      
        ?>
        <li class="nav-item" role="presentation">
            {{-- 🎯 關鍵修正：將 data-toggle 改為 data-bs-toggle --}}
            <a class="nav-link {{ $active }}" id="post_type2_profile{{ $p }}-tab" data-bs-toggle="tab" href="#post_type2_profile{{ $p }}" role="tab" aria-controls="post_type2_profile{{ $p }}" aria-selected="{{ $aria_selected }}">{{ $post_type->name }}</a>
        </li>
        <?php $p++; ?>
    @endforeach
</ul>

<div class="tab-content" id="myTabContent2">
    @if($setup->all_post == 1)
    <div class="tab-pane fade show active" id="post_type2_all_post" role="tabpanel" aria-labelledby="post_type2_all_post-tab" style="margin: 10px;">
        @auth
            @can('create',\App\Models\Post::class)
                <div class="mb-3">
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> 新增公告</a>
                </div>
            @endcan
        @endauth
        
        <table class="table table-striped align-middle text-break">
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td class="p-3">
                        {{-- 標題列 --}}
                        <div class="mb-2">
                            @if($post->top)
                                <span class="badge bg-danger me-1">置頂</span>
                            @endif
                            @if($post->inbox)
                                <span class="badge bg-warning text-dark me-1">常駐</span>
                            @endif
                            <?php
                            if($post->insite == 1){
                                $can_see = (auth()->check() || check_ip()) ? 1 : 0;
                            }else{
                                $can_see = 1;
                            };
                            $school_code = school_code();
                            $title = $post->title;
                            
                            $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                            $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                            ?>
                            
                            <span class="fs-5 fw-bold">
                                @if($can_see)
                                    @if($post->insite == 1)
                                        <span class="text-danger">[ 內部公告 ]</span>
                                    @endif
                                    <a href="{{ route('posts.show',$post->id) }}" class="text-decoration-none text-dark">{{ $title }}</a>
                                @else
                                    <span class='text-danger'>[ 內部公告 ]</span>
                                    <span class="text-muted text-decoration-line-through">{{ $title }}</span>
                                @endif
                            </span>
                        </div>

                        {{-- 圖文區塊 --}}
                        <div class="d-flex align-items-start gap-3">
                            @if($can_see && $post->title_image)
                                <div class="flex-shrink-0">
                                    <a href="{{ route('posts.show',$post->id) }}">
                                        <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" class="img-fluid rounded border shadow-sm" style="width: 100px; object-fit: cover;" alt="{{ $post->id }}公告的示意圖片">
                                    </a>
                                </div>
                            @endif
                            
                            <div class="flex-grow-1">
                                <?php
                                $content = \Illuminate\Support\Str::of(strip_tags($post->content))->limit(150);
                                $content = str_replace('&nbsp;','',$content);
                                ?>
                                <p class="pp1 text-secondary">
                                    @if($can_see)
                                        {{ $content }}
                                    @else
                                        <span class="text-muted small"><i class="fas fa-lock me-1"></i>請登入後再查看完整內容</span>
                                    @endif
                                </p>
                                
                                {{-- 底部資訊列 --}}
                                <div class="mt-2 small text-muted">
                                    <?php $insite = ($post->insite != null) ? $post->insite : 0; ?>
                                    <span class="badge bg-light text-secondary border me-1">{{ $post_type_array[$insite] ?? '一般公告' }}</span>
                                    <span class="me-2">/ 發佈：<a href="{{ route('posts.job_title',$post->job_title) }}" class="text-decoration-none text-secondary fw-semibold">{{ $post->job_title }}</a></span>
                                    <span class="me-2">/ 日期：{{ substr($post->created_at, 0, 10) }}</span>
                                    <span class="me-2">/ 點閱：{{ $post->views }}</span>
                                    
                                    @if(!empty($photos))
                                        <span class="text-success ms-1" title="附有圖片"><i class="fas fa-image"></i></span>
                                    @endif
                                    @if(!empty($files))
                                        <span class="text-info ms-1" title="附有檔案"><i class="fas fa-download"></i></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a href="{{ route('posts.index') }}" class="text-decoration-none"><small><i class="far fa-hand-point-up"></i> 更多 公告...</small></a>
    </div>
    @endif

    <?php $p = 1; ?>
    @foreach($post_types as $post_type)
        <?php $active = ($p == 1 and $setup->all_post == null) ? "show active" : null; ?>
        <div class="tab-pane fade {{ $active }}" id="post_type2_profile{{ $p }}" role="tabpanel" aria-labelledby="post_type2_profile{{ $p }}-tab" style="margin: 10px;">
            <?php
            $p++;
            $insite = ($post_type->id == 0) ? null : $post_type->id;
            $posts = \App\Models\Post::where('insite',$insite)
                ->where(function ($query) {
                    $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
                })->where('created_at','<',date('Y-m-d H:i:s'))->orderBy('top','DESC')
                ->orderBy('created_at','DESC')
                ->paginate($post_show_number);

            foreach($posts as $post){
                if($post->top == 1){
                    if($post->top_date < date('Y-m-d')){
                        $att['top'] = null;
                        $att['top_date'] = null;
                        $post->update($att);
                    }    
                }
            }
            ?>
            @auth
                @can('create',\App\Models\Post::class)
                    <div class="mb-3">
                        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> 新增公告</a>
                    </div>
                @endcan
            @endauth
            
            <table class="table table-striped align-middle text-break">
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td class="p-3">
                            {{-- 標題列 --}}
                            <div class="mb-2">
                                @if($post->top)
                                    <span class="badge bg-danger me-1">置頂</span>
                                @endif
                                @if($post->inbox)
                                    <span class="badge bg-warning text-dark me-1">常駐</span>
                                @endif
                                <?php
                                if($post->insite == 1){
                                    $can_see = (auth()->check() || check_ip()) ? 1 : 0;
                                }else{
                                    $can_see = 1;
                                };
                                $school_code = school_code();
                                $title = $post->title;
                                
                                $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                                $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                                ?>
                                
                                <span class="fs-5 fw-bold">
                                    @if($can_see)
                                        @if($post->insite == 1)
                                            <span class="text-danger">[ 內部公告 ]</span>
                                        @endif
                                        <a href="{{ route('posts.show',$post->id) }}" class="text-decoration-none text-dark">{{ $title }}</a>
                                    @else
                                        <span class='text-danger'>[ 內部公告 ]</span>
                                        <span class="text-muted text-decoration-line-through">{{ $title }}</span>
                                    @endif
                                </span>
                            </div>

                            {{-- 圖文區塊 --}}
                            <div class="d-flex align-items-start gap-3">
                                @if($can_see && $post->title_image)
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('posts.show',$post->id) }}">
                                            <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" class="img-fluid rounded border shadow-sm" style="width: 100px; object-fit: cover;" alt="{{ $post->id }}公告的示意圖片">
                                        </a>
                                    </div>
                                @endif
                                
                                <div class="flex-grow-1">
                                    <?php
                                    $content = \Illuminate\Support\Str::of(strip_tags($post->content))->limit(150);
                                    $content = str_replace('&nbsp;','',$content);
                                    ?>
                                    <p class="pp1 text-secondary">
                                        @if($can_see)
                                            {{ $content }}
                                        @else
                                            <span class="text-muted small"><i class="fas fa-lock me-1"></i>請登入後再查看完整內容</span>
                                        @endif
                                    </p>
                                    
                                    <div class="mt-2 small text-muted">
                                        <span class="badge bg-light text-primary border me-1">{{ $post_type->name }}</span>
                                        <span class="me-2">/ 發佈：<a href="{{ route('posts.job_title',$post->job_title) }}" class="text-decoration-none text-secondary fw-semibold">{{ $post->job_title }}</a></span>
                                        <span class="me-2">/ 日期：{{ substr($post->created_at, 0, 10) }}</span>
                                        <span class="me-2">/ 點閱：{{ $post->views }}</span>
                                        
                                        @if(!empty($photos))
                                            <span class="text-success ms-1" title="附有圖片"><i class="fas fa-image"></i></span>
                                        @endif
                                        @if(!empty($files))
                                            <span class="text-info ms-1" title="附有檔案"><i class="fas fa-download"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="{{ route('posts.type',$post_type->id) }}" class="text-decoration-none"><small><i class="far fa-hand-point-up"></i> 更多 {{ $post_type->name }}...</small></a>
        </div>
    @endforeach
</div>