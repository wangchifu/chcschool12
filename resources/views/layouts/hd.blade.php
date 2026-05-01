<div class="mb-3">
    <div class="d-flex justify-content-between align-items-end mb-2">
        <span class="fw-bold text-dark">
            <i class="fas fa-hdd me-1"></i> 儲存空間使用率
        </span>
        <span class="badge bg-secondary">
            {{ $size }} MB / 5,120 MB (5GB)
        </span>
    </div>

    <div class="progress" style="height: 20px; border-radius: 10px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
             role="progressbar" 
             aria-valuenow="{{ $per }}" 
             aria-valuemin="0" 
             aria-valuemax="100" 
             style="width: {{ $per }}%">
             @if($per > 5) {{ $per }}% @endif
        </div>
    </div>
    
    @if($per <= 5)
        <div class="text-end mt-1">
            <small class="text-muted">{{ $per }}%</small>
        </div>
    @endif
</div>
<hr>
