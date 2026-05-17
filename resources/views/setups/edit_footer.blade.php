@extends('layouts.master_clean')

@section('title', '編輯置底 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @include('layouts.errors')
    <h1>編輯置底</h1>
    {{-- 移除表單標籤內的 onsubmit="return false" --}}
    <form action="{{ route('setups.update_footer') }}" method="POST" id="this_form1">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="footer" class="form-label fw-bold">置底內容</label>
            <textarea name="footer" id="my_editor" class="form-control" rows="5">{{ $setup->footer }}</textarea>
        </div>

        <div class="mb-3">
            {{-- 移除按鈕上的 onclick，並確保 type="submit" 以便觸發表單的監聽器 --}}
            <button type="button" id="btn-submit-footer" class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                <i class="fas fa-save me-1"></i> 儲存置底
            </button>
        </div>
    </form>

    {{-- 安全的 JavaScript 區塊，帶有 nonce 滿足 CSP 規範 --}}
    <script nonce="{{ $csp_nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('this_form');

            if (form) {
                // 監聽表單的送出事件 (取代原先的 onsubmit="return false" 與 onclick 組合)
                form.addEventListener('submit', function(e) {
                    // 1. 先阻止表單的預設直接送出行為
                    e.preventDefault(); 
                    
                    // 2. 呼叫你專案系統原本寫好的確認視窗函式 (如 SweetAlert 封裝)
                    // 它確認後會自行操作 document.getElementById(form_id).submit(); 送出
                    sw_confirm2('確定儲存？', 'this_form');
                });
            }
        });
    </script>
@endsection