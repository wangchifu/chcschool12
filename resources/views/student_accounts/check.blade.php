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
@endsection