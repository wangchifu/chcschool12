<div class="card mb-4 border border-secondary border-opacity-10 shadow-sm rounded-3">
    <h3 class="card-header bg-light text-dark fw-bold py-3">
        <i class="fas fa-school me-2 text-primary"></i>教室設定資料
    </h3>
    <div class="card-body p-4">
        
        <div class="mb-4">
            <label for="name" class="form-label fw-bold text-secondary">名稱 <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control form-control-lg" placeholder="請輸入教室名稱，例如：視聽教室" required value="{{ $name }}">
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold text-secondary d-block">啟用狀態</label>
            <div class="form-check form-switch fs-5">
                <input type="checkbox" name="disable" value="1" id="disable" class="form-check-input" {{ $disable ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-danger small" for="disable">勾選此處將立即「停用」此教室預約</label>
            </div>
        </div>

        <div class="card my-4 border border-secondary border-opacity-10 shadow-sm rounded-3">
            {{-- 標題改為紅色系，呼應不開放的設定 --}}
            <div class="card-header bg-danger bg-opacity-10 py-3">
                <label class="form-label fw-bold text-danger mb-0 fs-5">
                    <i class="fas fa-calendar-times me-2"></i>設定不開放節次（請將「不開放」的節次打勾）
                </label>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-bordered align-middle mb-0 text-center table-hover">
                        <thead class="table-light text-secondary fw-bold">
                            <tr>
                                @foreach(config("chcschool.cht_week") as $v)
                                    <th scope="col" class="py-2">{{ $v }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(config("chcschool.class_sections") as $w => $v)
                                <tr>
                                    @for($i = 0; $i < 7; $i++)
                                        <td>
                                            {{-- 🎯 核心修正：統一換上 btn-check 配合 btn-outline-danger (紅色外框) --}}
                                            {{-- 當打勾時，瀏覽器會自動把它填滿成紅色實心，完全免用 JavaScript --}}
                                            <input type="checkbox" 
                                                   name="close_section[{{ $i }}][{{ $w }}]" 
                                                   value="1" 
                                                   class="btn-check" 
                                                   id="s{{ $i }}{{ $w }}" 
                                                   {{ $close[$i][$w] ? 'checked' : '' }}>
                                            
                                            <label class="btn btn-outline-danger btn-sm w-100 fw-bold py-2 text-nowrap d-block" for="s{{ $i }}{{ $w }}">
                                                {{ $v }}
                                            </label>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-2">
            <button type="submit" class="btn btn-primary btn-lg fw-bold px-4 shadow-sm save-btn" data-form="this_form1">
                <i class="fas fa-save me-1"></i> 儲存教室設定
            </button>
        </div>

    </div>
</div>

{{-- 🎯 微調紅色按鈕沒勾選時的淡化邊框，視覺上更柔和不刺眼 --}}
<style nonce="{{ $csp_nonce }}">
    .btn-outline-danger { 
        --bs-btn-border-color: rgba(220, 53, 69, 0.2); 
    }
</style>