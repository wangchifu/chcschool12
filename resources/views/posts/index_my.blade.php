@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '我的公告 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            {{-- 標題 --}}
            <h1 class="mb-4">
                @if(empty($setup->post_name))
                  公告系統
                @else
                  {{ $setup->post_name }}
                @endif
            </h1>
            
            {{-- 1. 頂部分頁導覽 --}}
            @can('create', \App\Models\Post::class)
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="{{ route('posts.index') }}">架上公告</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" aria-current="page" href="{{ route('posts.index_my') }}">我的公告</a>
                    </li>
                </ul>            
                
                {{-- 🎯 修正：按鈕移回分頁下方，與上一頁的排版完全一致 --}}
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i> 新增公告
                    </a>
                </div>
            @endcan
            
            {{-- 公告列表主體表格 --}}
            <div class="table-responsive">
                <table class="table table-striped align-middle" style="word-break:break-all;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 140px; white-space: nowrap;">日期</th>
                            <th style="width: 110px; white-space: nowrap;">類別</th>
                            <th style="min-width: 250px; white-space: nowrap;">標題</th>
                            <th style="width: 120px; white-space: nowrap;">發佈者</th>
                            <th style="width: 70px; white-space: nowrap;">點閱</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>                                
                                <span class="text-secondary">{{ substr($post->created_at, 0, 10) }}</span>
                                
                                @if($post->created_at > date('Y-m-d H:i:s'))
                                    <span class="badge bg-danger d-block text-start fw-normal mt-1 p-2" style="font-size: 0.75rem; line-height: 1.3;">
                                        尚未上架<br><small>{{ $post->created_at }}</small>
                                    </span>
                                @endif
                                @if($post->die_date < date('Y-m-d') && $post->die_date != null)
                                    <span class="badge bg-dark d-block text-start fw-normal mt-1 p-2" style="font-size: 0.75rem; line-height: 1.3;">
                                        已經下架<br><small>{{ $post->die_date }}</small>
                                    </span>
                                @endif
                                @if($post->die_date > date('Y-m-d') && $post->die_date != null && $post->created_at <= date('Y-m-d H:i:s'))
                                    <span class="badge bg-warning text-dark d-block text-start fw-normal mt-1 p-2" style="font-size: 0.75rem; line-height: 1.3;">
                                        下架日期<br><small>{{ $post->die_date }}</small>
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($post->insite == null)
                                    <a href="{{ route('posts.type', 0) }}" class="badge bg-light text-dark text-decoration-none border">一般公告</a>
                                @else
                                    <a href="{{ route('posts.type', $post->insite) }}" class="badge bg-light text-primary text-decoration-none border">{{ $post_types[$post->insite] }}</a>
                                @endif
                            </td>
                            <td>
                                {{-- 置頂與常駐徽章 --}}
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
                                
                                {{-- 附件小圖示 --}}
                                @if(!empty($photos))
                                    <span class="text-success ms-1" title="附有圖片"><i class="fas fa-image"></i></span>
                                @endif
                                @if(!empty($files))
                                    <span class="text-info ms-1" title="附有檔案"><i class="fas fa-download"></i></span>
                                @endif
                            </td>
                            <td>
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
            
            {{-- Laravel 分頁連結 --}}
            <div class="mt-4">
                {{ $posts->links('layouts.pagination') }}
            </div>
        </div>
    </div>
@endsection