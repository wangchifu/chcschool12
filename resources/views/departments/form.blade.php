<div class="card my-4 shadow-sm border-0">
    <h3 class="card-header bg-light py-3 fw-bold">
        <i class="fas fa-edit me-2 text-primary"></i>介紹資料
    </h3>
    <div class="card-body p-4">
        {{-- 顯示錯誤訊息 --}}
        @include('layouts.errors')

        {{-- 排序 --}}
        <div class="mb-3">
            <label for="order_by" class="form-label fw-bold">排序</label>
            <input type="number" name="order_by" id="order_by" 
                   value="{{ $department->order_by ?? $new_order_by ?? '' }}" 
                   class="form-control" maxlength="3">
        </div>

        {{-- 共編群組 --}}
        <div class="mb-3">
            <label for="group_id" class="form-label fw-bold">共編群組 <span class="text-danger">*</span></label>
            <select name="group_id" id="group_id" class="form-select" required>
                @foreach($group_array as $id => $name)
                    <option value="{{ $id }}" {{ (isset($department) && $department->group_id == $id) ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 標題 --}}
        <div class="mb-3">
            <label for="title" class="form-label fw-bold">標題 <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" 
                   value="{{ $department->title ?? '' }}" 
                   class="form-control" required placeholder="請輸入標題">
        </div>        

        {{-- 內文 (CKEditor) --}}
        <div class="mb-4">
            <label for="my_editor" class="form-label fw-bold">內文 <span class="text-danger">*</span></label>
            <textarea name="content" id="my_editor" class="form-control" required>{{ $department->content ?? '' }}</textarea>
        </div>        

        <hr class="my-4">

        {{-- 操作按鈕 --}}
        <div class="text-center">
            {{-- 套用 save-btn 邏輯，自動執行 SweetAlert 與隱藏按鈕 --}}
            <span class="btn btn-primary px-5 save-btn" data-form="this_form1">
                <i class="fas fa-save me-1"></i> 儲存設定
            </span>
            
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary px-4 ms-2">
                取消返回
            </a>
        </div>
    </div>
</div>