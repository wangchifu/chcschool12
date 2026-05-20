@extends('layouts.master_clean')

@section('nav_post_active', 'active')

@section('title', '顯示公告 | ')

@section('in_head')
    <link rel="stylesheet" href="{{ asset('venobox/venobox.min.css') }}" type="text/css" media="screen">
    <script src="{{ asset('venobox/venobox.min.js') }}"></script>
@endsection

@section('content')
    <div class="row justify-content-center">

        <div class="col-lg-11">

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

            {{-- 🎯 標題區塊優化 --}}
            <div class="mb-4">
                @if($can_see)
                    <h1 class="fw-bold text-dark mb-3">{{ $post->title }}</h1>                             
                @else
                    @if($post->insite==1 and ($post->die_date >= date('Y-m-d') or $post->die_date==null) and $post->created_at < date('Y-m-d H:i:s'))
                        <h1 class="text-danger fw-bold mb-3"><i class="fas fa-ban me-2"></i>[ 內部公告 ] {{ $post->title  }}</h1>                                           
                    @endif
                    @if($post->die_date < date('Y-m-d') and $post->die_date != null)
                        <h1 class="text-muted fw-bold mb-3">本公告已下架</h1>                
                    @elseif(substr($post->created_at,0,10) > date('Y-m-d'))
                        <h1 class="text-warning fw-bold mb-3">本公告尚未上架</h1>
                    @endif
                @endif            
            </div>

            {{-- 🎯 資訊整合列：移除生硬線條，改用柔和輕色調區塊 --}}
            <div class="p-3 bg-light rounded-3 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill me-3" onclick="window.history.back();">
                            <i class="fas fa-arrow-left me-1"></i>返回
                        </button>
                        <?php $insite = ($post->insite != null)?$post->insite:0; ?>
                        <span class="badge bg-secondary me-2">{{ $post_type_array[$insite] }}</span>
                        <span class="me-2">| 張貼者：<strong>{{ $post->job_title }}</strong></span>
                        @if($post->die_date)
                            <span>| <i class="far fa-calendar-times me-1"></i>期限：{{ $post->die_date }} 止</span>
                        @endif
                    </div>
                    <div class="text-secondary small">
                        <span class="me-3"><i class="far fa-clock me-1"></i>{{ $post->created_at }}</span>
                        <span><i class="far fa-eye me-1"></i>點閱：<strong>{{ $post->views }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- 標題圖片展示 --}}
            @if($can_see && !empty($post->title_image))
                <div class="mb-4 text-center">
                    <img class="img-fluid rounded-3 shadow-sm" src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" alt="標題圖片">
                </div>
            @endif            

            {{-- 🎯 內文區塊重新設計：改用現代質感的左側裝飾邊框與輕微陰影，取消虛線 --}}
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
                                <span class="text-danger fw-bold"><i class="fas fa-lock me-2"></i>[ 內部公告 ] 請登入後瀏覽！</span>
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
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                <a href="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" class="venobox" data-gall="gall1">
                                    <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" alt="..." class="img-fluid rounded border hover-shadow">
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

    </div>
    
    {{-- 🎯 修正：正確補上 nonce="{{ $csp_nonce }}" 確保通過 CSP 驗證 --}}
    <script nonce="{{ $csp_nonce }}">
        function send_form(){
            if($('#top_date').val().length === 0){
                alert('請選日期！')
            }else{
                $('#top_up_form').submit();
            }
            
        }
        var vb = new VenoBox({
            selector: '.venobox',
            numeration: true,
            infinigall: true,
            spinner: 'rotating-plane'
        });
    
        $(document).on('click', '.vbox-close', function() {
            vb.close();
        });
    </script>
@endsection