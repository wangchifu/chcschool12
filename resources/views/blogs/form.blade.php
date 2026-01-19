<div class="card my-4">
    <h3 class="card-header">文章資料</h3>
    <div class="card-body">
        @include('layouts.errors')
        <div class="form-group">
            <label for="content">標題圖片( 不大於5MB )
                <small class="text-secondary">jpeg, png 檔</small>
            </label>
            <input type="file" name="title_image" class="form-control">
        </div>
        <div class="form-group">
            <label for="title">標題*</label>
            <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}" class="form-control" placeholder="標題" required>
        </div>
        <div class="form-group">
            <label for="content">內文*</label>
            <textarea name="content" id="my-editor" class="form-control" required>{{ old('content', $blog->content) }}</textarea>
        </div>
        <script src="{{ asset('mycke/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('my-editor'
                ,{
                    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                });
        </script>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                <i class="fas fa-save"></i> 儲存設定
            </button>
        </div>
    </div>
</div>
