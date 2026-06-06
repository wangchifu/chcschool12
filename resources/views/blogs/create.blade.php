@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '新增文章 | ')

@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            
            <!-- 標頭區塊 -->
            <div class="d-flex align-items-center border-bottom pb-3 mb-4">
                <h1 class="fw-bold text-secondary mb-0">
                    <i class="fas fa-pen-nib me-2 text-success opacity-75"></i>新增文章
                </h1>
            </div>          
            
            <form action="{{ route('blogs.store') }}" method="POST" id="this_form1" enctype="multipart/form-data">
                @csrf
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <!-- 輕量化卡片標頭 -->
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="fas fa-file-alt me-2 text-muted"></i>文章資料設定
                        </h5>
                    </div>
                    
                    <div class="card-body p-4">
                        @include('layouts.errors')
                        
                        <!-- 1. 標題圖片區塊 -->
                        <div class="mb-4">
                            <label for="title_image" class="form-label fw-bold text-secondary d-flex align-items-center mb-2">
                                <span>標題圖片</span>
                                <span class="badge bg-light text-dark border ms-2 font-monospace small">不大於 5MB</span>
                            </label>                            
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="fas fa-image"></i></span>
                                <input type="file" name="title_image" id="title_image" class="form-control" accept="image/jpeg, image/png">
                            </div>
                            <div class="form-text text-muted small mt-1">僅支援 jpeg, png 格式圖片</div>
                        </div>

                        <!-- 2. 文章標題區塊 -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold text-secondary mb-2">標題 <span class="text-danger">*</span></label>                            
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="fas fa-heading"></i></span>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control py-2" placeholder="請輸入文章標題" required>
                            </div>
                        </div>

                        <!-- 3. 文章內文區塊 -->
                        <div class="mb-4">
                            <label for="my_editor" class="form-label fw-bold text-secondary mb-2">內文 <span class="text-danger">*</span></label>
                            <div class="border rounded-3 p-1 bg-light">
                                <textarea name="content" id="my_editor" class="form-control border-0" rows="10" placeholder="撰寫你的精彩內容...">{{ old('content') }}</textarea>
                            </div>
                        </div>                        
                        
                        <hr class="text-muted opacity-25 my-4">

                        <!-- 4. 動作按鈕區（完全保留原本的 save-btn 類別與屬性） -->
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary px-4 py-2 shadow-sm rounded-pill fw-bold save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存設定
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection