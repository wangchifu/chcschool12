@extends('layouts.master_clean')

@section('title', '修改跑馬燈 | ')

@section('content')        
    <div class="row justify-content-center pt-4">
        <div class="col-md-11">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3 text-center">
                    <h3 class="card-title mb-0 fw-bold text-primary">
                        <i class="fas fa-edit me-1"></i>修改跑馬燈
                    </h3>
                </div>
                <div class="card-body p-4">
                    @include('layouts.errors')

                    <form action="{{ route('school_marquee.update', $school_marquee->id) }}" method="POST" id="edit_marquee_form">
                        @csrf
                        {{-- 如果您的路由是用 PATCH 或 PUT，請取消下面這行的註解 --}}
                        {{-- @method('PATCH') --}}

                        {{-- 標題 --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">標題 <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" required value="{{ $school_marquee->title }}" placeholder="請輸入標題">
                        </div>

                        <div class="row">
                            {{-- 開始日期 --}}
                            <div class="col-md-6 mb-4">
                                <label for="start_date" class="form-label fw-bold">開始日期 <span class="text-danger">*</span></label>
                                <input id="start_date" name="start_date" type="date" required maxlength="10" class="form-control bg-white" value="{{ $school_marquee->start_date }}">                        
                                <script>
                                    $('#start_date').datepicker({
                                        uiLibrary: 'bootstrap4',
                                        format: 'yyyy-mm-dd',
                                        locale: 'zh-TW',
                                    });
                                </script>
                            </div>

                            {{-- 結束日期 --}}
                            <div class="col-md-6 mb-4">
                                <label for="stop_date" class="form-label fw-bold">結束日期 <span class="text-danger">*</span></label>
                                <input id="stop_date" name="stop_date" type="date" required maxlength="10" class="form-control bg-white" value="{{ $school_marquee->stop_date }}">
                                <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                                <script>
                                    $('#stop_date').datepicker({
                                        uiLibrary: 'bootstrap4',
                                        format: 'yyyy-mm-dd',
                                        locale: 'zh-TW',
                                    });
                                </script>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="text-center">
                            {{-- 使用 save-btn 類別對接全域 SweetAlert 邏輯 --}}
                            <span class="btn btn-primary px-5 save-btn" data-form="edit_marquee_form">
                                <i class="fas fa-save me-1"></i> 儲存設定
                            </span>                                                        
                        </div>                    
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection