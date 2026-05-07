<div class="card my-4 shadow-sm">
    <h3 class="card-header bg-light py-3 fw-bold">
        <i class="fas fa-edit me-2 text-primary"></i>群組資料
    </h3>
    <div class="card-body p-4">
        {{-- 名稱欄位 --}}
        <div class="mb-4">
            <label for="name" class="form-label fw-bold">群組名稱 <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" value="{{ $group->name ?? '' }}" class="form-control" placeholder="請輸入群組名稱" required>
        </div>

        {{-- 停用開關 --}}
        <div class="mb-4">
            <div class="form-check form-switch">
                {{-- 將傳統 Checkbox 改為 BS5 漂亮的 Switch 開關樣式 --}}
                <input class="form-check-input" type="checkbox" name="disable" value="1" id="disable" {{ (isset($group) && $group->disable) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="disable">停用此群組</label>
            </div>
            <div class="form-text text-muted">停用後，該群組的使用者權限將暫時失效。</div>
        </div>

        <hr class="my-4">
    </div>
</div>
