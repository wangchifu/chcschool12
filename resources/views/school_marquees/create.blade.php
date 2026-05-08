@extends('layouts.master_clean')

@section('title', '新增跑馬燈 | ')

@section('content')        
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3">
                <i class="fas fa-running me-2"></i>校園跑馬燈
            </h1>                        

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3 text-center">
                    <h3 class="card-title mb-0 fw-bold">新增跑馬燈內容</h3>
                </div>
                <div class="card-body p-4">
                    @include('layouts.errors')

                    <form action="{{ route('school_marquee.store') }}" method="POST" id="marquee_form1">
                        @csrf
                        
                        {{-- 標題 --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">標題 <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" required placeholder="請輸入跑馬燈文字內容">
                        </div>

                        <div class="row">
                            {{-- 開始日期 --}}
                            <div class="col-md-6 mb-4">
                                <label for="start_date" class="form-label fw-bold">開始日期 <span class="text-danger">*</span></label>
                                <input id="start_date" name="start_date" type="date" required maxlength="10" class="form-control bg-white" placeholder="YYYY-MM-DD">
                                <script>
                                    $('#start_date').datepicker({
                                        uiLibrary: 'bootstrap4', // Gijgo 目前對 BS4 支援較佳，建議保留此設定或視情況更新
                                        format: 'yyyy-mm-dd',
                                        locale: 'zh-TW',
                                    });
                                </script>
                            </div>

                            {{-- 結束日期 --}}
                            <div class="col-md-6 mb-4">
                                <label for="stop_date" class="form-label fw-bold">結束日期 <span class="text-danger">*</span></label>
                                <input id="stop_date" name="stop_date" type="date" required maxlength="10" class="form-control bg-white" placeholder="YYYY-MM-DD">
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

                        <div class="d-flex justify-content-center border-top pt-4">                           
                            {{-- 使用 save-btn 類別對接全域 SweetAlert 邏輯 --}}
                            <span class="btn btn-primary px-5 save-btn" data-form="marquee_form1">
                                <i class="fas fa-save me-1"></i> 儲存設定
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection