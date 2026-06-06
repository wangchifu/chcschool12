@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '修改文章 | ')

@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            
            <div class="d-flex align-items-center border-bottom pb-3 mb-4">
                <h1 class="fw-bold text-secondary mb-0">
                    <i class="fas fa-edit me-2 text-warning opacity-75"></i>修改文章
                </h1>
            </div>          
            
            <form action="{{ route('blogs.update', $blog->id) }}" method="POST" id="this_form1" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="fas fa-file-alt me-2 text-muted"></i>文章資料編修
                        </h5>
                    </div>
                    
                    <div class="card-body p-4">
                        @include('layouts.errors')
                        
                        <div class="mb-4">
                            <label for="title_image" class="form-label fw-bold text-secondary d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <span>標題圖片</span>
                                    <span class="badge bg-light text-dark border ms-2 font-monospace small">不大於 5MB</span>
                                </div>
                                {{-- 🎯 精緻化刪除圖片按鈕 --}}
                                @if($title_image)
                                    <?php
                                    $file = "blogs/".$blog->id."/title_image.png";
                                    $file = str_replace('/','&',$file);
                                    ?>
                                    <a href="#!" class="badge bg-danger text-white text-decoration-none px-2 py-1.5 shadow-sm delete-btn1" data-url="{{ route('blogs.delete_title_image',$blog->id) }}">
                                        <i class="fas fa-times-circle me-1"></i> 刪除現有圖片
                                    </a>
                                @endif
                            </label>                            
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="fas fa-image"></i></span>
                                <input type="file" name="title_image" id="title_image" class="form-control" accept="image/jpeg, image/png">
                            </div>
                            <div class="form-text text-muted small mt-1">僅支援 jpeg, png 格式圖片。若不修改請留空。</div>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold text-secondary mb-2">標題 <span class="text-danger">*</span></label>                            
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="fas fa-heading"></i></span>
                                <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}" class="form-control py-2" placeholder="請輸入文章標題" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="my_editor" class="form-label fw-bold text-secondary mb-2">內文 <span class="text-danger">*</span></label>
                            <div class="border rounded-3 p-1 bg-light">
                                {{-- 🎯 這裡完全移除了 required 屬性，徹底避開 TinyMCE 內容同步的阻擋衝突，完全交由 Laravel 後端把關 --}}
                                <textarea name="content" id="my_editor" class="form-control border-0" rows="10" placeholder="撰寫你的精彩內容...">{{ old('content', $blog->content) }}</textarea>
                            </div>
                        </div>                        
                        
                        <hr class="text-muted opacity-25 my-4">

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