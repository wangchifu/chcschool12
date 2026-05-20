@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '新增公告 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">公告列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增公告</li>
                </ol>
            </nav>
            <h1>
                @if(empty($setup->post_name))
                  公告系統
                @else
                  {{ $setup->post_name }}
                @endif
            </h1>
            
            {{-- 原生 HTML 表單 --}}
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="this_form1">
                @csrf
                
                <div class="card my-4">
                    <h3 class="card-header">公告資料</h3>
                    <div class="card-body">
                        
                        {{-- 1. 職稱 --}}
                        <div class="mb-3">
                            <?php $job_title = (auth()->user()->username=="admin")?"系統管理":auth()->user()->title; ?>
                            <label for="job_title" class="form-label"><strong class="text-danger">1.職稱*</strong> <a href="{{ route('edit_title') }}">更改</a></label>
                            <input type="text" name="job_title" id="job_title" class="form-control" value="{{ $job_title }}" readonly>
                        </div>
                        
                        {{-- 2. 公告類別 --}}
                        <div class="mb-3">
                            <label for="insite" class="form-label"><strong class="text-danger">2.公告類別*</strong></label>
                            <select name="insite" id="insite" class="form-select">
                                @foreach($types as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- 3. 標題圖片 --}}
                        <div class="mb-3">
                            <label for="title_image" class="form-label">
                                {{-- 🎯 修正：將 data-toggle 改為 data-bs-toggle --}}
                                <a data-bs-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3" class="text-dark text-decoration-none">
                                    3.標題圖片( 不大於5MB )                                                            
                                    <small class="text-secondary">jpeg, png 檔</small>
                                </a>
                            </label>
                            <div class="collapse" id="collapse3">
                                <input type="file" name="title_image" id="title_image" class="form-control" accept="image/jpeg,image/png">
                            </div>
                        </div>
                        
                        {{-- 4. 標題 --}}
                        <div class="mb-3">
                            <label for="title" class="form-label"><strong class="text-danger">4.標題*</strong></label>
                            <input type="text" name="title" id="title" class="form-control" required placeholder="請輸入標題">
                        </div>
                        
                        {{-- 5. 上架起迄日期 --}}
                        <p class="mb-2">
                            {{-- 🎯 修正：將 data-toggle 改為 data-bs-toggle --}}
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
                                            <input type="date" name="live_date" id="live_date" class="form-control mb-1" onchange="check_date(); check_today();">
                                            <input type="time" name="live_time" id="live_time" class="form-control">
                                            <small class="text-muted d-block">(不填代表即刻貼出)</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-3">
                                            <label for="die_date" class="form-label mb-1">迄(含)</label>
                                            <input type="date" name="die_date" id="die_date" class="form-control mb-1" onchange="check_date()">
                                            <small class="text-muted d-block">(不填代表不下架)</small>
                                        </div>
                                    </td>
                                </tr>                                
                            </table> 
                        </div>
                                                         
                        {{-- 6. 內文 --}}
                        <div class="mb-3">
                            <label for="content" class="form-label"><strong class="text-danger">6.內文*</strong></label>
                            <textarea name="content" id="my_editor2" class="form-control" rows="10" required placeholder="請輸入內容"></textarea>
                        </div>                                                               
                        
                        @include('layouts.hd')                    
                        
                        {{-- 7. 相關照片 --}}
                        <div class="mb-3">
                            <label for="photos" class="form-label">7.相關照片( 單檔不大於5MB的圖檔 )</label>
                            <small class="text-danger d-block mb-1">(注意！請勿將公告當成圖庫相簿使用，單次也不要超過十張以上的照片，若造成伺服器負擔，經查證將取消貴校此功能。)</small>
                            @if($per < 100)
                                <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/*">
                            @else
                                <span class="text-danger fw-bold">容量已滿！無法上傳照片了！</span>
                            @endif
                        </div>
                        
                        {{-- 8. 附件 --}}
                        <div class="mb-3">
                            <label for="files" class="form-label">8.附件( 不大於10MB，若為文字檔，請改為[ <a href="https://moda.gov.tw/digital-affairs/digital-service/app-services/248" target="_blank">ODF格式</a> ] [ <a href="{{ asset('ODF.pdf') }}" target="_blank">詳細公文</a> ] [ <a href="{{ asset('office2016_odt_pdf.png') }}" target="_blank">轉檔教學</a> ] )
                                <small class="text-secondary d-block mt-1">csv, txt, zip, jpeg, png, pdf, odt, ods, mp3 檔</small>
                            </label>
                            @if($per < 100)
                                <input type="file" name="files[]" id="files" class="form-control" multiple>
                            @else
                                <span class="text-danger fw-bold">容量已滿！無法加附件！</span>
                            @endif
                        </div>                  
                        
                        {{-- Line 連動功能區 --}}
                        @if($setup->post_line_token)
                        @endif  
                        
                        @if($setup->post_line_bot_token)
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="send_line_bot_token" name="send_line_bot_token" value="yes">
                            <label class="form-check-label text-danger" for="send_line_bot_token">
                                <i class="fab fa-line text-success fs-3 mb-0 align-middle me-1"></i> 同步發至 line bot (1.未來公告則不會發出，2.僅有200則免費的推播，群組一則以總人數計)
                            </label>
                        </div>
                        @endif
                        
                        {{-- 按鈕區 --}}
                        <div class="mb-2">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                            <button type="button" id="submit_button" class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                                <i class="fas fa-save"></i> 儲存設定
                            </button>
                        </div>
                        
                        @include('layouts.errors')
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