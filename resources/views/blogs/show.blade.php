@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', $blog->title.' | ')

@section('content')
    <style nonce="{{ $csp_nonce ?? '' }}">
        .blog-post-title {
            font-size: 2.25rem;
            color: #212529;
            letter-spacing: -0.03em;
        }
        .blog-meta-box {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
        }
        .blog-content-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333333;
        }
        /* 🎯 改善原本 img 的文繞圖浮動效果 */
        .blog-main-image {
            float: left;
            margin-right: 1.5rem;
            margin-bottom: 1rem;
            max-width: 40%;
            box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.08);
            transition: transform 0.2s ease;
        }
        .blog-main-image:hover {
            transform: scale(1.02);
        }
        /* 手機版 RWD 自動還原，不文繞圖避免字擠在一起 */
        @media (max-width: 768px) {
            .blog-main-image {
                float: none;
                max-width: 100%;
                margin-right: 0;
                margin-bottom: 1.5rem;
                display: block;
            }
        }
    </style>

    <div class="row justify-content-center py-4">
        <div class="col-md-10 col-lg-9">
            
            <h1 class="blog-post-title fw-bold mb-3">
                {{ $blog->title }}
            </h1>            
            
            <div class="blog-meta-box p-3 mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center shadow-sm border border-light">
                <div class="text-secondary small mb-2 mb-sm-0">
                    <span class="fw-bold text-dark me-2">
                        <i class="fas fa-user-circle me-1 text-primary opacity-75"></i>
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
                    <span class="me-2"><i class="fas fa-calendar-alt me-1 opacity-75"></i>{{ $blog->created_at }}</span>
                    <span><i class="fas fa-eye me-1 opacity-75"></i>點閱：{{ $blog->views }}</span>
                </div>
                
                @auth
                    @if(auth()->user()->id == $blog->user_id || auth()->user()->admin == 1)
                    <div class="d-flex gap-1">
                        @if(auth()->user()->id == $blog->user_id)
                            <a href="{{ route('blogs.edit',$blog->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3"><i class="fas fa-edit me-1"></i>修改</a>
                        @endif
                        <a href="#!" class="btn btn-outline-danger btn-sm rounded-pill px-3 delete-btn2" data-form="delete{{ $blog->id }}"><i class="fas fa-trash me-1"></i>刪除</a>
                    </div>
                    @endif
                @endauth
                <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" id="delete{{ $blog->id }}" style="display:inline;"> @csrf @method('DELETE') <input type="hidden" name="close_window" value="1"></form>
            </div>

            <div class="blog-content-body clearfix mb-5 px-1">
                @if($blog->title_image)
                    <a href="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="venobox" data-gall="gall1">
                        <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="blog-main-image img-fluid rounded" alt="文章封面">
                    </a>
                @endif
                
                <div class="table-responsive">
                    {!! $blog->content !!}
                </div>
            </div>
            
            <hr class="text-muted opacity-25 my-4">

            <div class="d-flex justify-content-between align-items-center mb-5">
                @if($last_id)
                    <a href="{{ route('blogs.show',$last_id) }}" class="btn btn-light border btn-sm px-3 py-2 rounded-pill shadow-sm text-secondary">
                        <i class="fas fa-arrow-left me-1"></i> 上一篇文章
                    </a>
                @else
                    <a href="#" class="btn btn-light border btn-sm px-3 py-2 rounded-pill disabled opacity-50">
                        <i class="fas fa-arrow-left me-1"></i> 上一篇文章
                    </a>
                @endif

                @if($next_id)
                    <a href="{{ route('blogs.show',$next_id) }}" class="btn btn-light border btn-sm px-3 py-2 rounded-pill shadow-sm text-secondary">
                        下一篇文章 <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @else
                    <a href="#" class="btn btn-light border btn-sm px-3 py-2 rounded-pill disabled opacity-50">
                        下一篇文章 <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>

        </div>
    </div>

<script nonce="{{ $csp_nonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        var vb = new VenoBox({
            selector: '.venobox',
            numeration: true,
            infinigall: true,
            spinner: 'rotating-plane'
        });
    
        $(document).on('click', '.vbox-close', function() {
            vb.close();
        });
    });
</script>
@endsection