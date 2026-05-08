@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '新增內容 | ')

@section('content')
    {{-- 統一 py-4 增加間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3"><i class="fas fa-file-medical me-2"></i>新增內容</h1>        

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    {{-- 顯示錯誤訊息 --}}
                    @include('layouts.errors')

                    <form action="{{ route('contents.store') }}" method="POST" id="this_form1">
                        @csrf
                        
                        {{-- 引入共用的表單欄位 --}}
                        @include('contents.form')                        
                        <div class="d-flex justify-content-center mt-3">                                                        
                            {{-- 移除了 border-top (上框線) 和 pt-4 (上內距)，改用 mt-3 (上外距) 稍微隔開內容 --}}
                            <span class="btn btn-primary px-5 save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存內容
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection