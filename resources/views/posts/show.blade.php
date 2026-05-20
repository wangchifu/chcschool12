@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', $post->title.' | ')

@section('in_head')
    <link rel="stylesheet" href="{{ asset('venobox/venobox.min.css') }}" type="text/css" media="screen">
    <script src="{{ asset('venobox/venobox.min.js') }}"></script>
@endsection

@section('content')
    <div class="row justify-content-center">

        <div class="col-lg-8">

            <?php
            if($post->insite==1){
                if(auth()->check() or check_ip()){
                    $can_see = 1;
                }else{
                    $can_see = 0;
                }
            }else{
                $can_see = 1;
            };
            //下架日比今天早(小)，不能看
            if($post->die_date != null and $post->die_date < date('Y-m-d')){
                $can_see = 0;
            }
            //上架日比今天晚(大)，不能看
            if(substr($post->created_at,0,10) > date('Y-m-d')){
                $can_see = 0;
            }
            //作者可以看
            if(auth()->check()){
                if($post->user_id == auth()->user()->id){
                $can_see = 1;
                }
            }            
            ?>
            
            {{-- 導覽列 --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-decoration-none">公告列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">公告內容</li>
                </ol>
            </nav>

            {{-- 🎯 標題區塊優化：加大字級，增加下方留白 --}}
            <div class="mb-4">
                @if($can_see)
                    <h1 class="fw-bold text-dark mb-3">{{ $post->title }}</h1>                             
                @else
                    @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                        <h1 class="text-danger fw-bold mb-3"><i class="fas fa-ban me-2"></i>[ 內部公告 ] {{ $post->title }}</h1>                                           
                    @endif
                    @if($post->die_date < date('Y-m-d') and $post->die_date != null)
                        <h1 class="text-muted fw-bold mb-3">本公告已下架</h1>                
                    @elseif(substr($post->created_at,0,10) > date('Y-m-d'))
                        <h1 class="text-warning fw-bold mb-3">本公告尚未上架</h1>
                    @endif
                @endif            
            </div>

            {{-- 🎯 上下頁導覽按鈕：左側新增返回按鈕 --}}
            <div class="mb-4 d-flex gap-2">
                <button type="button" id="back_btn" class="btn btn-secondary btn-sm rounded-pill me-2">
                    <i class="fas fa-arrow-left me-1"></i> 返回
                </button>

                @if($last_id)
                    <a href="{{ route('posts.show',$last_id) }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fas fa-chevron-left me-1"></i> 上一則</a>
                @else
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill disabled"><i class="fas fa-chevron-left me-1"></i> 上一則</a>
                @endif
                
                @if($next_id)
                    <a href="{{ route('posts.show',$next_id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">下一則 <i class="fas fa-chevron-right ms-1"></i></a>
                @else
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill disabled">下一則 <i class="fas fa-chevron-right ms-1"></i></a>
                @endif
            </div>

            {{-- 🎯 資訊與管理列：移除生硬的線條，改用柔和區塊底色區隔 --}}
            <div class="p-3 bg-light rounded-3 mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="fs-6 text-secondary"> {{-- 🎯 改用標準 fs-6，顏色改用略深的 text-secondary --}}
                    <?php $insite = ($post->insite != null)?$post->insite:0; ?>
                    <span class="me-2">
                        <i class="fas fa-tag text-primary me-1"></i> {{-- 圖標上色 --}}
                        <a href="{{ route('posts.type',$insite) }}" class="text-decoration-none text-dark fw-bold">{{ $post_type_array[$insite] }}</a>
                    </span>
                    <span class="me-2 text-muted">|</span>
                    <span class="me-2">
                        張貼者：<a href="{{ route('posts.job_title',$post->job_title) }}" class="text-decoration-none text-dark fw-bold">{{ $post->job_title }}</a>
                    </span>
                    @if($post->die_date)
                        <span class="text-muted">|</span>
                        <span class="ms-1 text-danger fw-semibold"> {{-- 讓期限稍微顯眼一點 --}}
                            <i class="far fa-calendar-times me-1"></i>期限：{{ $post->die_date }} 止
                        </span>
                    @endif
                </div>                

                {{-- 後台管理按鈕組 --}}
                @auth
                    <div class="d-flex flex-wrap gap-1 align-items-center">
                        @if(auth()->user()->admin)
                            @if($post->top)
                                @if(!empty($post->top_date))
                                    <span class="badge bg-secondary me-1">置頂至 {{ $post->top_date }}</span>
                                @endif
                                <a href="#!" class="btn btn-warning btn-sm delete-btn1" data-msg="確定要取消置頂？" data-url="{{ route('posts.top_down',$post->id) }}"><i class="fas fa-sort-amount-down me-1"></i>取消置頂</a>
                            @else
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fas fa-sort-amount-up me-1"></i>置頂
                                </button>
                            @endif
                            @if($post->inbox)
                                <a href="#!" class="btn btn-secondary btn-sm delete-btn1" data-msg="確定取消常駐公告？" data-url="{{ route('posts.inbox',$post->id) }}">
                                    <i class="fas fa-inbox me-1"></i>取消常駐
                                </a>
                            @else
                                <a href="#!" class="btn btn-outline-warning btn-sm delete-btn1" data-msg="確定放進常駐公告區塊？" data-url="{{ route('posts.inbox',$post->id) }}">
                                    <i class="fas fa-inbox me-1"></i>常駐
                                </a>
                            @endif
                        @endif

                        @if(auth()->user()->id == $post->user_id or auth()->user()->admin == 1)
                            <a href="{{ route('posts.edit',$post->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit me-1"></i>修改</a>
                            <a href="#!" class="btn btn-outline-danger btn-sm delete-btn2" data-form="delete_form"><i class="fas fa-trash me-1"></i>刪除</a>
                            
                            <form action="{{ route('posts.destroy',$post->id) }}" method="POST" id="delete_form">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>
                @endauth
            </div>

            {{-- 🎯 第二層資訊列：整合日期與點閱數，視覺上更乾淨 --}}
            <div class="d-flex justify-content-between text-secondary px-1 mb-4">
                <span><i class="far fa-clock me-1"></i>張貼日期：{{ $post->created_at }}</span>
                <span><i class="far fa-eye me-1"></i>點閱：<a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/'.$post->id.'.txt') }}" target="_blank" class="text-decoration-none text-secondary fw-bold">{{ $post->views }}</a></span>
            </div>

            {{-- 標題圖片展示 --}}
            @if($can_see && !empty($post->title_image))
                <div class="mb-4 text-center">
                    <img class="img-fluid rounded-3 shadow-sm" src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" alt="標題圖片">
                </div>
            @endif            

            {{-- 🎯 內文區塊重新設計：改用現代質感的左側裝飾邊框與輕微陰影，取消密密麻麻的虛線 --}}
            <div class="mb-4">
                @if($can_see)
                    <div class="card border-0 border-start border-4 border-primary shadow-sm bg-white">
                        <div class="card-body p-4 fs-5 text-dark lh-base">
                            {!! $post->content !!}
                        </div>
                    </div>
                @else
                    @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                        <div class="card border-0 border-start border-4 border-danger shadow-sm bg-white">
                            <div class="card-body p-4 fs-5 text-center py-5">
                                <span class="text-danger fw-bold"><i class="fas fa-lock me-2"></i>[ 內部公告 ] 本功能限登入後瀏覽！</span>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            {{-- 🎯 相關照片區塊優化 --}}
            @if(!empty($photos) and $can_see)
                <div class="card border-0 shadow-sm mb-4">
                    <h5 class="card-header bg-light fw-bold text-secondary py-3"><i class="far fa-images me-2 text-primary"></i>相關照片</h5>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($photos as $k=>$v)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" class="venobox" data-gall="gall1">
                                    <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" alt="相關照片{{ $k }}" class="img-fluid rounded border hover-shadow">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- 🎯 附件下載區塊優化 --}}
            @if(!empty($files) and $can_see)                    
                <div class="card border-0 shadow-sm mb-4">
                    <h5 class="card-header bg-light fw-bold text-secondary py-3"><i class="fas fa-paperclip me-2 text-primary"></i>附件下載</h5>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($files as $k=>$v)
                                <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/files/'.$v) }}" class="btn btn-light border btn-sm text-secondary px-3 py-2 rounded-2" target="_blank">
                                    <i class="fas fa-download me-2 text-primary"></i>{{ $v }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>                    
            @endif            
        </div>        

        <div class="col-lg-3">

                <div class="card my-4 shadow-sm">
                    <h5 class="card-header bg-light fw-bold text-secondary py-3">
                        <i class="fas fa-fire text-danger me-2"></i>近月內熱門公告
                    </h5>
                    {{-- 🎯 修正：改用 list-group 結構，移除 card-body 以達到無縫貼邊的現代感設計 --}}
                    <div class="list-group list-group-flush">
                        @foreach($hot_posts as $hot_post)
                            <a href="{{ route('posts.show',$hot_post->id) }}" class="list-group-item list-group-item-action py-3">
                                {{-- 上排：日期與點閱數 --}}
                                <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>{{ substr($hot_post->created_at, 0, 10) }}
                                    </small>
                                    <span class="badge bg-primary rounded-pill">
                                        <i class="far fa-eye me-1"></i>{{ $hot_post->views }}
                                    </span>
                                </div>
                                {{-- 下排：公告標題 (加上 d-block 與 text-truncate 確保長標題不破版) --}}
                                <div class="text-dark fw-semibold text-truncate d-block">
                                    {{ $hot_post->title }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <span style="font-size: 16px;" class="modal-title" id="exampleModalLabel">置頂至哪一天？</span>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('posts.top_up2',$post->id) }}" method="post" id="this_form1">
                @csrf
                <div class="modal-body">
                  <input type="date" name="top_date" id="top_date" class="form-control" title="請填入置頂至哪一天" required="required">
                </div>
                <div class="modal-footer">
                  <span class="btn btn-secondary" data-bs-dismiss="modal">取消</span>
                  <button type="button" class="btn btn-primary save-btn" data-form="this_form1">送出</button>
                </div>
            </form>
          </div>
        </div>
    </div>

    {{-- 🎯 修正：正確補上 nonce="{{ $csp_nonce }}" 確保通過 CSP 驗證 --}}
    <script nonce="{{ $csp_nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            var backBtn = document.getElementById('back_btn');
            if (backBtn) {
                backBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // 防止按鈕觸發其他預設行為
                    window.history.back();
                });
            }
        });

        function send_form(){
            if($('#top_date').val().length === 0){
                alert('請選日期！')
            }else{
                $('#top_up_form').submit();
            }
            
        }        
    </script>
@endsection