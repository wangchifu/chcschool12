@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '修改公告 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1><i class="fas fa-bullhorn"></i> 修改公告</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">公告列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改公告</li>
                </ol>
            </nav>
            
            {{-- 原生 HTML 表單 (替換 Form::model) --}}
            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="this_form1">
                @csrf
                @method('PATCH')
                
                <div class="card my-4">
                    <h3 class="card-header">公告資料</h3>
                    <div class="card-body">
                        @include('layouts.errors')
                        
                        {{-- 1. 職稱 --}}
                        <div class="mb-3">
                            <label for="job_title" class="form-label"><strong class="text-danger">1.職稱*</strong></label>
                            <input type="text" name="job_title" id="job_title" class="form-control" value="{{ $post->job_title }}">
                        </div>
                        
                        {{-- 2. 公告類別 --}}
                        <div class="mb-3">
                            <label for="insite" class="form-label"><strong class="text-danger">2.公告類別*</strong></label>
                            <select name="insite" id="insite" class="form-select">
                                @foreach($types as $key => $value)
                                    <option value="{{ $key }}" {{ $post->insite == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- 3. 標題圖片 --}}
                        <div class="mb-3">
                            <label for="title_image" class="form-label">
                                <a data-bs-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3" class="text-dark text-decoration-none">
                                    3.標題圖片( 不大於5MB )                                                            
                                    <small class="text-secondary">jpeg, png 檔</small>
                                </a>
                            </label>
                            <div class="collapse" id="collapse3">
                                @if($title_image)
                                    <?php
                                    $file = "posts/".$post->id."/title_image.png";
                                    $file = str_replace('/','&',$file);
                                    ?>
                                    <div class="mb-2">
                                        <span class="text-muted small me-2">目前已上傳圖片：</span>
                                        <a href="{{ route('posts.delete_title_image',$post->id) }}" class="badge bg-danger text-decoration-none" id="fileDel" onclick="return confirm('確定刪標題圖片')">
                                            <i class="fas fa-times-circle"></i> 刪除現有圖片
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="title_image" id="title_image" class="form-control" accept="image/jpeg,image/png">
                            </div>
                        </div>
                        
                        {{-- 4. 標題 --}}
                        <div class="mb-3">
                            <label for="title" class="form-label"><strong class="text-danger">4.標題*</strong></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}" required placeholder="請輸入標題">
                        </div>
                        
                        {{-- 5. 上架起迄日期 --}}
                        <p class="mb-2">
                            <a data-bs-toggle="collapse" href="#collapse5" role="button" aria-expanded="false" aria-controls="collapse5" class="text-dark text-decoration-none">
                                5.上架起迄日期 ( 可不填 )
                            </a>
                            [<a href="{{ asset('live_date.png') }}" target="_blank">教學</a>]
                        </p>
                        
                        <div class="collapse mb-3" id="collapse5">
                            <table class="w-auto">
                                <tr>
                                    <td>
                                        <div class="mb-3 me-2">
                                            <label for="live_date" class="form-label mb-1">起</label>
                                            <input type="date" name="live_date" id="live_date" class="form-control mb-1" value="{{ substr($post->created_at,0,10) }}" onchange="check_date(); check_today();">
                                            <input type="time" name="live_time" id="live_time" class="form-control" value="{{ substr($post->created_at,11,8) }}">
                                            <small class="text-muted d-block">(不填代表即刻貼出)</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-3">
                                            <label for="die_date" class="form-label mb-1">迄(含)</label>
                                            <input type="date" name="die_date" id="die_date" class="form-control mb-1" value="{{ $post->die_date }}" onchange="check_date()">
                                            <small class="text-muted d-block">(不填代表不下架)</small>
                                        </div>
                                    </td>
                                </tr>                                
                            </table> 
                        </div>
                                                         
                        {{-- 6. 內文 --}}
                        <div class="mb-3">
                            <label for="content" class="form-label"><strong class="text-danger">6.內文*</strong></label>
                            <textarea name="content" id="my_editor2" class="form-control" rows="10" placeholder="請輸入內容">{{ $post->content }}</textarea>
                        </div>                                                                                                               
                        
                        @include('layouts.hd')                    
                        
                        {{-- 7. 相關照片 --}}
                        <div class="mb-3">
                            <label for="photos" class="form-label">7.相關照片( 單檔不大於5MB的圖檔 )</label>
                            <small class="text-danger d-block mb-2">(注意！請勿將公告當成圖庫相簿使用，單次也不要超過十張以上的照片，若造成伺服器負擔，經查證將取消貴校此功能。)</small>
                            
                            @if(!empty($photos))
                                <div class="row g-2 mb-2">
                                    @foreach($photos as $k=>$v)
                                        <div class="col-md-2 col-4">
                                            <figure class="figure border p-1 rounded bg-light text-center w-100">
                                                <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/photos/'.$v) }}" class="figure-img img-fluid rounded mb-1" alt="相關照片">
                                                <figcaption class="figure-caption">
                                                    <a href="{{ route('posts.delete_photo',['post'=>$post->id,'filename'=>$v]) }}" class="badge bg-danger text-decoration-none d-block text-truncate" onclick="return confirm('確定刪除？')" title="{{ $v }}">
                                                        <i class="fas fa-times-circle"></i> 刪除
                                                    </a>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($per < 100)
                                <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/*">
                            @else
                                <span class="text-danger fw-bold d-block mt-1">容量已滿！無法上傳照片了！</span>
                            @endif
                        </div>
                        
                        {{-- 8. 附件 --}}
                        <div class="mb-3">
                            <label for="files" class="form-label">8.附件( 不大於10MB，若為文字檔，請改為[ <a href="https://moda.gov.tw/digital-affairs/digital-service/app-services/248" target="_blank">ODF格式</a> ] [ <a href="{{ asset('ODF.pdf') }}" target="_blank">詳細公文</a> ] [ <a href="{{ asset('office2016_odt_pdf.png') }}" target="_blank">轉檔教學</a> ] )
                                <small class="text-secondary d-block mt-1">csv, txt, zip, jpeg, png, pdf, odt, ods, mp3 檔</small>
                            </label>
                            
                            @if(!empty($files))
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    @foreach($files as $k=>$v)
                                        <a href="{{ route('posts.delete_file',['post'=>$post->id,'filename'=>$v]) }}" class="badge bg-danger text-decoration-none py-2 px-3" onclick="return confirm('確定刪除？')">
                                            <i class="fas fa-times-circle me-1"></i>{{ $v }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($per < 100)
                                <input type="file" name="files[]" id="files" class="form-control" multiple>
                            @else
                                <span class="text-danger fw-bold d-block mt-1">容量已滿！無法加附件！</span>
                            @endif
                        </div>                  
                        
                        {{-- 按鈕區 --}}
                        <div class="mb-2">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                            <button type="button" id="submit_button" class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                                <i class="fas fa-save"></i> 儲存設定
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script nonce="{{ $csp_nonce }}">                
        function check_date(){                                        
            if(document.getElementById("die_date").value < document.getElementById("live_date").value && document.getElementById("die_date").value !== ""){
                document.getElementById("die_date").value = "";
                alert('迄日，不得小於起日！');
            }
        }
        function check_today(){                               
            check_date();         
            if('{{ date('Y-m-d') }}' >= document.getElementById("live_date").value && document.getElementById("live_date").value !== ""){
                document.getElementById("live_date").value = "";
                alert('不能選今天以前的日子！');
            }
        }
    </script>
@endsection