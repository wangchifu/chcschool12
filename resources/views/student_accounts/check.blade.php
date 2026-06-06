@extends('layouts.master')

@section('title', '學生帳號查詢 | ')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-search me-2"></i>學生 彰化縣帳號(OpenID)查詢</h5>                    
                </div>
                <div class="card-body p-4">
                    @if($has_file)
                        @include('layouts.errors')
                        @if(session('success_account'))
                            <div class="alert alert-success border-2 border-success rounded-3 p-4 mb-4 shadow-sm">
                                <div class="text-center mb-2 text-success fw-bold">
                                    <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                                    <span style="font-size: 1.2rem;">🎉 恭喜！已成功找到您的帳號</span>
                                </div>
                                
                                <div class="bg-white border rounded-3 p-3 my-3 text-center">
                                    <div class="text-muted small fw-bold mb-1">彰化縣 OpenID 帳號</div>
                                    <span class="d-block text-danger fw-black font-monospace text-break" 
                                        id="account_text" 
                                        style="font-size: 2.5rem; letter-spacing: 2px;">
                                        {{ session('success_account') }}
                                    </span>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-success fw-bold" id="copy_btn">
                                        <i class="fas fa-copy me-1"></i> 點我複製帳號
                                    </button>
                                </div>
                            </div>
                        @endif
                        {{-- 嘗試次數提示 --}}
                        @if(session('student_check_error') > 0)
                            <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>目前已經嘗試 {{ session('student_check_error') }} 次。</div>
                            </div>
                        @endif

                        @if(session('student_check_error') > 5)
                            <div class="alert alert-danger text-center py-4" role="alert">
                                <i class="fas fa-lock fa-2x mb-2 d-block"></i>
                                <span class="fw-bold">嘗試次數超過 5 次，請稍後再試。</span>
                            </div>
                        @else
                            <form action="{{ route('student_account.do_check') }}" method="POST">
                                @csrf
                                
                                {{-- 班級座號 --}}
                                <div class="mb-3">
                                    <label for="classnum" class="form-label fw-bold text-secondary">1. 班級座號 5 碼</label>
                                    <input type="text" 
                                        name="classnum" 
                                        id="classnum" 
                                        class="form-control form-control-lg @error('classnum') is-invalid @enderror"                                         
                                        maxlength="5"
                                        pattern="\d{5}"
                                        title="請輸入5位數字的班級座號"
                                        required autofocus
                                        value="{{ old('classnum') }}">
                                    <div class="form-text text-muted mt-1"><i class="fas fa-info-circle me-1"></i>例如 1年1班1號 請輸入 10101</div>
                                </div>

                                {{-- 西元生日 --}}
                                <div class="mb-4">
                                    <label for="birthday" class="form-label fw-bold text-secondary">2. 西元 8 碼生日</label>
                                    <input type="text" 
                                        name="birthday" 
                                        id="birthday" 
                                        class="form-control form-control-lg @error('birthday') is-invalid @enderror"                                         
                                        maxlength="8" 
                                        pattern="\d{8}"
                                        title="請輸入8位數字的西元生日"
                                        required>
                                    <div class="form-text text-muted mt-1"><i class="fas fa-info-circle me-1"></i>例如 2012 年 5 月 20 日請輸入 20120520</div>
                                </div>

                                {{-- 驗證碼區塊 --}}
                                <div class="mb-4 p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <label for="captcha" class="form-label fw-bold text-secondary mb-2">3. 驗證碼</label>
                                    <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                                        <img src="{{ route('pic') }}" class="img-fluid rounded border border-secondary border-opacity-25 shadow-sm" alt="驗證碼圖片">
                                        <span class="text-success small fw-semibold"><i class="fas fa-arrow-right me-1"></i>請將圖片中的國字改為阿拉伯數字</span>
                                    </div>
                                    
                                    {{-- 🎯 校正回歸：維持你原本後端能對接的 name="chaptcha" (包含多出來的 h) --}}
                                    <input type="text" 
                                        name="chaptcha" 
                                        id="captcha" 
                                        class="form-control form-control-lg @error('captcha') is-invalid @enderror"                                         
                                        pattern="\d{5}"
                                        maxlength="5"
                                        required>
                                </div>

                                {{-- 送出按鈕與警告文字 --}}
                                <div class="d-grid gap-2 mt-4">
                                    <div class="text-danger small fw-semibold text-center mb-1">
                                        <i class="fas fa-gavel me-1"></i>盜用他人帳號是違法行為，請勿以身試法。
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm py-2.5">
                                        <i class="fas fa-search me-1"></i> 開始查詢
                                    </button>
                                </div>
                            </form>
                        @endif
                                                
                    @else
                        <div class="alert alert-warning text-center py-4" role="alert">   
                            <i class="fas fa-exclamation-circle fa-2x mb-2 d-block opacity-75"></i>
                            <span class="fw-bold">尚未上傳學生帳號清單，請聯絡學校管理員。</span>
                        </div>
                    @endif                    
                </div>
            </div>
        </div>
    </div>
</div>
{{-- 🎯 升級版：通吃 HTTP/HTTPS 的複製帳號腳本，完美帶上 CSP Nonce 安全憑證 --}}
<script nonce="{{ $csp_nonce }}">
    document.addEventListener('DOMContentLoaded', function() {
        const copyBtn = document.getElementById('copy_btn');
        const accountText = document.getElementById('account_text');

        if (copyBtn && accountText) {
            copyBtn.addEventListener('click', function() {
                const textToCopy = accountText.innerText.trim();
                
                // 執行安全複製的函式
                secureCopyText(textToCopy, copyBtn);
            });
        }

        // 核心複製機制（解決非 HTTPS 環境下 navigator.clipboard 癱瘓的問題）
        function secureCopyText(text, buttonElement) {
            if (navigator.clipboard && window.isSecureContext) {
                // 1. 如果是標準 HTTPS 環境，優先使用現代化 API
                navigator.clipboard.writeText(text).then(function() {
                    showSuccessStatus(buttonElement);
                }).catch(function(err) {
                    fallbackCopyMethod(text, buttonElement); // 失敗時走退路做法
                });
            } else {
                // 2. 如果是傳統 HTTP 或學校內網 IP 環境，直接走相容性最高的退路寫法
                fallbackCopyMethod(text, buttonElement);
            }
        }

        // 傳統退路寫法：透過動態隱藏輸入框來騙過瀏覽器
        function fallbackCopyMethod(text, buttonElement) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            
            // 讓這個輸入框完全隱形，不影響畫面
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            
            // 選取文字並執行複製
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showSuccessStatus(buttonElement);
                } else {
                    alert('複製失敗，請手動選取文字複製。');
                }
            } catch (err) {
                console.error('退路複製方法失敗: ', err);
                alert('您的瀏覽器不支援自動複製，請手動選取文字複製。');
            }
            
            // 任務完成後，把臨時建立的輸入框銷毀
            document.body.removeChild(textArea);
        }

        // 動態改變按鈕狀態的 UI 提示
        function showSuccessStatus(btn) {
            btn.className = "btn btn-success fw-bold";
            btn.innerHTML = '<i class="fas fa-check me-1"></i> 複製成功！';
            
            setTimeout(function() {
                btn.className = "btn btn-outline-success fw-bold";
                btn.innerHTML = '<i class="fas fa-copy me-1"></i> 點我複製帳號';
            }, 1500);
        }
    });
</script>
@endsection