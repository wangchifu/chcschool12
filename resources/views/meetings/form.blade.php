<div class="card my-4 border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden">
    <h3 class="card-header bg-light fs-5 fw-bold py-3 px-4 text-dark border-bottom">會議資料</h3>
    <div class="card-body p-4">
        
        <div class="mb-3">
            <label for="datepicker" class="form-label fw-bold text-secondary">會議日期 (西元/月/日)*</label>
            <input type="date" name="open_date" id="datepicker" value="{{ $default_date }}" class="form-control" required>            
        </div>
        
        <div class="mb-3">
            <label for="title" class="form-label fw-bold text-secondary">會議名稱*</label>
            <input type="text" name="name" id="title" value="{{ $default_name }}" class="form-control" placeholder="會議名稱" required>
        </div>
        
        <div class="mt-4">
            <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm save-btn" data-form="this_form1">
                <i class="fas fa-save me-1"></i> 儲存設定
            </button>
        </div>

    </div>
</div>