@extends('layouts.master_clean')

@section('title', '編輯內部文件 | ')

@section('content')
    <div class="container-fluid pt-3">
        <h1>修改檔案</h1>
        @include('layouts.errors')
        
        <form action="{{ route('inside_files.update', $inside_file->id) }}" method="POST" id="this_form1">
            @csrf
            @method('PATCH')

            <div class="row align-items-start g-2">
                <div class="col">
                    <div class="mb-2">
                        <label for="name" class="form-label fw-bold small text-secondary">名稱</label>
                        <input type="text" name="name" id="name" value="{{ $inside_file->name }}" class="form-control form-control-sm" placeholder="名稱" required>
                    </div>
                    
                    @if($inside_file->type == 3)
                        <div class="mb-2">
                            <label for="url" class="form-label fw-bold small text-secondary">連結</label>
                            <input type="url" name="url" id="url" value="{{ $inside_file->url }}" class="form-control form-control-sm" placeholder="連結" required>
                        </div>
                    @endif
                </div>
                
                <div class="col-auto pt-4 mt-1">
                    <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm save-btn" data-form="this_form1">
                        <i class="fas fa-save me-1"></i> 儲存
                    </button>
                </div>
            </div>

            <input type="hidden" name="path" value="{{ $path }}">
        </form>
    </div>
@endsection