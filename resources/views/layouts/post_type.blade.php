<ul class="nav nav-tabs" id="myTab" role="tablist">
    @if($setup->all_post)
    <li class="nav-item" role="presentation">
        {{-- 🎯 關鍵修正：將 data-toggle 改為 data-bs-toggle --}}
        <a class="nav-link active" id="post_type_all_post-tab" data-bs-toggle="tab" href="#post_type_all_post" role="tab" aria-controls="post_type_all_post" aria-selected="true">全部公告</a>
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
            <a class="nav-link {{ $active }}" id="post_type_profile{{ $p }}-tab" data-bs-toggle="tab" href="#post_type_profile{{ $p }}" role="tab" aria-controls="post_type_profile{{ $p }}" aria-selected="{{ $aria_selected }}">{{ $post_type->name }}</a>
        </li>
        <?php $p++; ?>
    @endforeach
</ul>

<div class="tab-content" id="myTabContent">
    @if ($setup->all_post == 1)
        <div class="tab-pane fade show active" id="post_type_all_post" role="tabpanel" aria-labelledby="post_type_all_post-tab" style="margin: 10px;">
            @auth
                @can('create',\App\Models\Post::class)
                    <div class="mb-3">
                        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> 新增公告</a>
                    </div>
                @endcan
            @endauth
            
            <div class="table-responsive">
                <table class="table table-striped align-middle text-break" style="table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
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
                                    {{ substr($post->created_at,0,10) }}
                                </td>
                                <td>                                
                                    <?php $insite = ($post->insite != null) ? $post->insite : 0; ?>
                                    <span class="badge bg-light text-dark border">{{ $post_type_array[$insite] ?? '一般公告' }}</span>
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
                                    }else{
                                        $can_see = 1;
                                    };
                                    $school_code = school_code();
                                    $title = \Illuminate\Support\Str::limit($post->title, 80);
                                    
                                    $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                                    $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                                    ?>
                                    @if($post->insite == 1)
                                        <span class="text-danger fw-bold">[ 內部公告 ]</span>
                                    @endif
                                    @if($can_see)
                                        <a href="{{ route('posts.show',$post->id) }}" class="text-decoration-none text-dark fw-md">{{ $title }}</a>
                                    @else                    
                                        <span class="text-muted text-decoration-line-through">{{ $title }}</span>
                                    @endif
                                    @if(!empty($photos))
                                        <span class="text-success ms-1"><i class="fas fa-image"></i></span>
                                    @endif
                                    @if(!empty($files))
                                        <span class="text-info ms-1"><i class="fas fa-download"></i></span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('posts.job_title',$post->job_title) }}" class="text-decoration-none text-secondary"><small>{{ $post->job_title }}</small></a>
                                </td>
                                <td class="text-muted">
                                    {{ $post->views }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('posts.index') }}" class="text-decoration-none"><small><i class="far fa-hand-point-up"></i> 更多 公告...</small></a>
        </div>
    @endif

    <?php $p = 1; ?>
    @foreach($post_types as $post_type)
        <?php $active = ($p == 1 and $setup->all_post == null) ? "show active" : null; ?>
        <div class="tab-pane fade {{ $active }}" id="post_type_profile{{ $p }}" role="tabpanel" aria-labelledby="post_type_profile{{ $p }}-tab" style="margin: 10px;">
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
            
            <div class="table-responsive">
                <table class="table table-striped align-middle text-break" style="table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
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
                                    {{ substr($post->created_at,0,10) }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border">{{ $post_type->name }}</span>
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
                                    }else{
                                        $can_see = 1;
                                    };
                                    $school_code = school_code();
                                    //$title = \Illuminate\Support\Str::limit($post->title, 80);
                                    $title = $post->title;
                                    
                                    $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                                    $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                                    ?>
                                    @if($post->insite == 1)
                                        <span class="text-danger fw-bold">[ 內部公告 ]</span>
                                    @endif
                                    @if($can_see)
                                        <a href="{{ route('posts.show',$post->id) }}" class="text-decoration-none text-dark fw-md">{{ $title }}</a>
                                    @else
                                        <span class="text-muted text-decoration-line-through">{{ $title }}</span>
                                    @endif
                                    @if(!empty($photos))
                                        <span class="text-success ms-1"><i class="fas fa-image"></i></span>
                                    @endif
                                    @if(!empty($files))
                                        <span class="text-info ms-1"><i class="fas fa-download"></i></span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('posts.job_title',$post->job_title) }}" class="text-decoration-none text-secondary"><small>{{ $post->job_title }}</small></a>
                                </td>
                                <td class="text-muted">
                                    {{ $post->views }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('posts.type',$post_type->id) }}" class="text-decoration-none"><small><i class="far fa-hand-point-up"></i> 更多 {{ $post_type->name }}...</small></a>
        </div>
    @endforeach
</div>