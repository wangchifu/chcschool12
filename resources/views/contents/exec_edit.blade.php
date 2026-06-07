@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '修改內容 | ')

@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-4"><i class="fas fa-edit me-2 text-primary"></i>修改內容</h1>
            
            <form action="{{ route('contents.exec_update', $content->id) }}" method="POST" id="this_form">
                @csrf
                @method('PATCH')

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 fw-bold">內容資料 (共同編輯)</h5>
                    </div>
                    <div class="card-body p-4">
                        @include('layouts.errors')

                        {{-- 標題 --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">標題 <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" required placeholder="請輸入標題" value="{{ old('title', $content->title) }}">
                        </div>

                        {{-- 內文 --}}
                        <div class="mb-3">
                            <label for="my_editor" class="form-label fw-bold">內文 <span class="text-danger">*</span></label>
                            <textarea name="content" id="my_editor" class="form-control" rows="10">{{ old('content', $content->content) }}</textarea>
                        </div>                    
                        
                        <hr class="my-4">

                        {{-- 瀏覽權限 --}}
                        <label class="form-label fw-bold mb-2">瀏覽權限</label>
                        <div class="ps-2">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="power" id="power1" value="" {{ old('power', $content->power) == null ? 'checked' : '' }}>
                                <label class="form-check-label" for="power1">公開</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="power" id="power2" value="2" {{ old('power', $content->power) == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="power2">在校內網域或登入者都可看</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="power" id="power3" value="3" {{ old('power', $content->power) == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="power3">只有登入者可看</label>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- 儲存按鈕：對接全域 save-btn 邏輯 --}}
                        <div class="d-flex justify-content-center mt-3">
                            <span class="btn btn-primary px-5 save-btn" data-form="this_form">
                                <i class="fas fa-save me-1"></i> 儲存設定
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>    
@endsection