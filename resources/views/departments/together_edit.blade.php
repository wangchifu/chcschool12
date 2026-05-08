@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '修改介紹 | ')

@section('content')
    {{-- 使用 pt-4 讓標題與頂部保持美觀距離 --}}
    <div class="row justify-content-center pt-4">
        <div class="col-md-11">
            <h2 class="fw-bold mb-4">
                <i class="fas fa-users-edit me-2 text-primary"></i>共同編輯：{{ $department->title }}
            </h2>

            <form action="{{ route('departments.together_update', $department->id) }}" method="POST" id="this_form1">
                @csrf
                @method('PATCH')

                <div class="card shadow-sm border-0 mb-4">
                    <h3 class="card-header bg-light py-3 fw-bold">
                        <i class="fas fa-file-alt me-2"></i>介紹資料
                    </h3>
                    <div class="card-body p-4">
                        @include('layouts.errors')

                        <div class="row">
                            {{-- 排序 (唯讀) --}}
                            <div class="col-md-4 mb-3">
                                <label for="order_by" class="form-label fw-bold text-muted">排序 (不可修改)</label>
                                <input type="text" name="order_by" id="order_by" value="{{ $department->order_by }}" class="form-control bg-light" readonly>
                            </div>

                            {{-- 共編群組 (禁用) --}}
                            <div class="col-md-8 mb-3">
                                <label for="group_id" class="form-label fw-bold text-muted">共編群組 (不可修改)</label>
                                <select name="group_id_display" id="group_id_display" class="form-select bg-light" disabled>
                                    @foreach($group_array as $id => $name)
                                        <option value="{{ $id }}" {{ $department->group_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                {{-- 因為 select disabled 後資料送不出去，若後端需要此值請加 hidden input --}}
                                <input type="hidden" name="group_id" value="{{ $department->group_id }}">
                            </div>
                        </div>

                        {{-- 標題 --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">標題 <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" value="{{ $department->title }}" class="form-control" required placeholder="請輸入標題">
                        </div>        

                        {{-- 內文 (CKEditor) --}}
                        <div class="mb-4">
                            <label for="my_editor" class="form-label fw-bold">內文 <span class="text-danger">*</span></label>
                            <textarea name="content" id="my_editor" class="form-control" required>{{ $department->content }}</textarea>
                        </div>

                        <hr class="my-4">

                        {{-- 操作按鈕 --}}
                        <div class="text-center">
                            {{-- 使用您定義的 save-btn 類別 --}}
                            <span class="btn btn-primary px-5 save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存變更
                            </span>                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>    
@endsection