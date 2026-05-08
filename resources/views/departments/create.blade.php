@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '新增介紹 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    {{-- 統一使用 py-4 增加上下間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3">新增介紹</h1>                        

            <form action="{{ route('departments.store') }}" method="POST" id="this_form1">
                @csrf
                <div class="card shadow-sm border-0 mb-4">
                    <h3 class="card-header bg-light py-3 fw-bold">
                        <i class="fas fa-edit me-2 text-primary"></i>介紹資料
                    </h3>
                    <div class="card-body p-4">
                        @include('layouts.errors')

                        <div class="row">
                            {{-- 排序 --}}
                            <div class="col-md-4 mb-3">
                                <label for="order_by" class="form-label fw-bold">排序</label>
                                <input type="number" name="order_by" id="order_by" value="{{ $new_order_by }}" class="form-control" maxlength="3">
                            </div>

                            {{-- 共編群組 --}}
                            <div class="col-md-8 mb-3">
                                <label for="group_id" class="form-label fw-bold">共編群組 <span class="text-danger">*</span></label>
                                <select name="group_id" id="group_id" class="form-select" required>
                                    @foreach($group_array as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 標題 --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">標題 <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="請輸入標題" required>
                        </div>        

                        {{-- 內文 (CKEditor) --}}
                        <div class="mb-4">
                            <label for="my_editor" class="form-label fw-bold">內文 <span class="text-danger">*</span></label>
                            <textarea name="content" id="my_editor" class="form-control" required></textarea>
                        </div>                        

                        <hr class="my-4">

                        {{-- 操作按鈕 --}}
                        <div class="text-center">
                            {{-- 套用 save-btn 邏輯：自動隱藏、SweetAlert、檢查 --}}
                            <span class="btn btn-primary px-5 save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存設定
                            </span>                                                    
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>    
@endsection