@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校園部落格 | ')

@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom pb-3 mb-4">
                <div>
                    <h1 class="fw-bold text-secondary mb-1">
                        <i class="fas fa-newspaper me-2 text-primary opacity-75"></i>校園部落格
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                            <li class="breadcrumb-item active" aria-current="page">文章列表</li>
                        </ol>
                    </nav>
                </div>
                @can('create',\App\Models\Post::class)
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('blogs.create') }}" class="btn btn-success fw-bold px-3 py-2 shadow-sm rounded-pill venobox" data-vbtype="iframe">
                        <i class="fas fa-plus-circle me-1"></i> 新增文章
                    </a>
                </div>
                @endcan
            </div>

            <div class="row row-cols-1 g-4 mb-4">
                @foreach($blogs as $blog)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden position-relative btn-hover-transition" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            <div class="row g-0 align-items-center">
                                
                                <div class="col-md-3 bg-light text-center" style="min-height: 160px; max-height: 220px; overflow: hidden;">
                                    <a href="{{ route('blogs.show',$blog->id) }}" class="d-block h-100 w-100 venobox" data-vbtype="iframe">
                                        @if($blog->title_image)
                                            <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" 
                                                 class="img-fluid w-100 h-100" 
                                                 style="object-fit: cover;" 
                                                 alt="{{ $blog->title }}">
                                        @else
                                            <img src="{{ asset('images/image.jpg') }}" 
                                                 class="img-fluid w-100 h-100" 
                                                 style="object-fit: cover;" 
                                                 alt="隨機圖片">
                                        @endif
                                    </a>
                                </div>
                                
                                <div class="col-md-9">
                                    <div class="card-body p-4">
                                        <h3 class="card-title h4 fw-bold mb-2">
                                            <a href="{{ route('blogs.show',$blog->id) }}" class="text-dark text-decoration-none link-primary venobox" data-vbtype="iframe">
                                                {{ $blog->title }}
                                            </a>
                                        </h3>
                                        
                                        <div class="card-text text-secondary mb-3 small" style="word-break: break-all;">
                                            <?php
                                            $content = $blog->content;
                                            $content = str_replace('&nbsp;','',$content);
                                            $content = strip_tags($content);
                                            $content = mb_strimwidth($content, 0, 200, '...', 'utf-8');
                                            ?>
                                            {{  $content  }}
                                        </div>
                                        
                                        <div class="d-flex flex-wrap justify-content-between align-items-center border-top pt-3 mt-2 small text-muted">
                                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                                <span>
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
                                                <span>
                                                    <i class="fas fa-calendar-alt me-1 opacity-75"></i>
                                                    {{ $blog->created_at }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-eye me-1 opacity-75"></i>
                                                    點閱：{{ $blog->views }}
                                                </span>
                                            </div>
                                            
                                            @auth
                                                @if(auth()->user()->id == $blog->user_id or auth()->user()->admin == 1)
                                                <div class="mt-2 mt-sm-0">
                                                    @if(auth()->user()->id == $blog->user_id)
                                                        <a href="{{ route('blogs.edit',$blog->id) }}" class="btn btn-outline-primary btn-xs py-1 px-2 fw-semibold rounded venobox" data-vbtype="iframe"><i class="fas fa-edit"></i> 修改</a>
                                                    @endif
                                                    <a href="#!" class="btn btn-outline-danger btn-xs py-1 px-2 fw-semibold rounded delete-btn2" data-form="delete{{ $blog->id }}"><i class="fas fa-trash"></i> 刪除</a>
                                                </div>
                                                @endif
                                            @endauth
                                            
                                            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" id="delete{{ $blog->id }}" style="display: inline;"> @csrf @method('DELETE') </form>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4 shadow-xs">
                {{ $blogs->links('layouts.pagination') }}
            </div>
        </div>
    </div>

    <style nonce="{{ $csp_nonce ?? '' }}">
        .btn-hover-transition:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .btn-xs {
            padding: .25rem .4rem;
            font-size: .75rem;
            border-radius: .2rem;
        }
    </style>
@endsection