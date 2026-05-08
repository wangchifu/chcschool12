<div class="card shadow-sm border-0 my-4">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>內容編輯</h5>
    </div>
    <div class="card-body p-4">
        @include('layouts.errors')

        {{-- 標題 --}}
        <div class="mb-3">
            <label for="title" class="form-label fw-bold">標題 <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" class="form-control" required placeholder="請輸入標題" value="{{ $content->title ?? old('title') }}">
        </div>

        {{-- 共編群組 --}}
        <div class="mb-3">
            <label for="group_id" class="form-label fw-bold">共編群組 <span class="text-danger">*</span></label>
            <select name="group_id" id="group_id" class="form-select">
                @foreach($group_array as $id => $name)
                    <option value="{{ $id }}" {{ (isset($content) && $content->group_id == $id) ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 標籤 --}}
        <div class="mb-3">
            <label for="tags" class="form-label fw-bold">標籤</label>
            <small class="text-muted ms-2">(請用英文字母逗號 `,` 分隔多個標籤)</small>
            <input type="text" name="tags" id="tags" class="form-control" placeholder="例如：公告,校務,活動" value="{{ $content->tags ?? old('tags') }}">
        </div>

        {{-- 內文 (CKEditor) --}}
        <div class="mb-3">
            <label for="my_editor" class="form-label fw-bold">內文 <span class="text-danger">*</span></label>
            <textarea name="content" id="my_editor" class="form-control" required>{{ $content->content ?? old('content') }}</textarea>
        </div>        

        <hr class="my-4">

        {{-- 權限設定 --}}
        <label class="form-label fw-bold mb-2">瀏覽權限</label>
        <div class="ps-2">
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="power" id="power1" value="" {{ (empty($content->power)) ? 'checked' : '' }}>
                <label class="form-check-label" for="power1">
                    <span class="badge bg-success">公開</span> 全世界都可瀏覽
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="power" id="power2" value="2" {{ (isset($content) && $content->power == 2) ? 'checked' : '' }}>
                <label class="form-check-label" for="power2">
                    <span class="badge bg-warning text-dark">限制</span> 在校內網域或登入者都可看
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="power" id="power3" value="3" {{ (isset($content) && $content->power == 3) ? 'checked' : '' }}>
                <label class="form-check-label" for="power3">
                    <span class="badge bg-info text-dark">私有</span> 只有登入者可看
                </label>
            </div>
        </div>
    </div>
</div>