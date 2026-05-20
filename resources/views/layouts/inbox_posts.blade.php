<?php
$inbox_posts = \App\Models\Post::where('inbox',1)
                ->where(function ($query) {
                    $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
                })->where('created_at','<',date('Y-m-d H:i:s'))
                ->orderBy('created_at','DESC')
                ->get();
?>
<div class="table-responsive">
    {{-- 🎯 關鍵修正：同步加入 fixed 佈局，強制死守百分比寬度 --}}
    <table class="table table-striped align-middle text-break" style="table-layout: fixed;">
        <thead class="table-light">
        <tr>
            {{-- 🎯 寬度比例、類別完全與其他清單對齊 --}}
            <th class="text-nowrap" style="width: 15%;">日期</th>
            <th class="text-nowrap" style="width: 15%;">類別</th>
            <th class="text-nowrap" style="width: 47%;">標題</th>
            <th class="text-nowrap" style="width: 15%;">發佈者</th>
            <th class="text-nowrap" style="width: 8%;">點閱</th>
        </tr>
        </thead>
        <tbody>
        @foreach($inbox_posts as $post)
        <tr>
            {{-- 日期欄位 (純日期) --}}
            <td class="text-secondary text-nowrap">                
                {{ substr($post->created_at,0,10) }}
            </td>
            
            {{-- 類別欄位 --}}
            <td>
                <?php $insite = ($post->insite != null) ? $post->insite : 0; ?>
                <span class="badge bg-light text-dark border">{{ $post_type_array[$insite] ?? '一般公告' }}</span>
            </td>
            
            {{-- 標題欄位 --}}
            <td>
                {{-- 🎯 修正：將 p 改為 span 徽章，移除殘留 margin --}}
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
            
            {{-- 發佈者欄位 (加上 text-nowrap 與 small 外觀) --}}
            <td class="text-nowrap">
                <a href="{{ route('posts.job_title',$post->job_title) }}" class="text-decoration-none text-secondary"><small>{{ $post->job_title }}</small></a>
            </td>
            
            {{-- 點閱欄位 --}}
            <td class="text-muted">
                {{ $post->views }}
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>