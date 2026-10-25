@extends('layouts.master_close')

@section('title','管理登入 | ')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-7 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-lock me-2" aria-hidden="true"></i>管理員登入</h4>
            </div>

            <div class="card-body p-4">
                @if(session('login_error') < 3)
                <form method="POST" action="{{ route('auth') }}" id="this_form">
                    @csrf

                    <div class="row mb-3 align-items-center">
                        <label for="username" class="col-sm-4 col-form-label text-md-end fw-bold">帳號</label>

                        <div class="col-md-7">
                            <input tabindex="1" id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus placeholder="請輸入帳號">

                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="password" class="col-md-4 col-form-label text-md-end fw-bold">密碼</label>

                        <div class="col-md-7">
                            <input id="password" type="password" tabindex="2" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="請輸入密碼">

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-7 offset-md-4">
                            <a href="{{ route('admin_login') }}" title="點擊換一張驗證碼">
                                <img src="{{ route('pic') }}" class="img-fluid rounded border" alt="驗證碼圖片">
                            </a>
                        </div>
                    </div>
                    
                    <div class="row mb-4 align-items-center">
                        <label for="chaptcha" class="col-md-4 col-form-label text-md-end fw-bold">驗證碼</label>

                        <div class="col-md-7">
                            <input type="text" id="chaptcha" tabindex="3" class="form-control" name="chaptcha" required placeholder="上圖國字轉阿拉伯數字" maxlength="5">
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-7 offset-md-4">
                            <button type="submit" class="btn btn-primary btn-sm px-4" tabindex="4">
                                <i class="fas fa-sign-in-alt me-1" aria-hidden="true"></i> 登入
                            </button>
                        </div>
                    </div>
                </form>
                @else
                    <?php
                        $k = rand(100,999);
                        session(['check_bot'=>$k]);
                    ?>
                    <div class="alert alert-danger border-0 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i> 登入錯誤超過三次，請輸入三碼數字後送出：
                    </div>
                    <form action="{{ route('not_bot') }}" method="post" class="d-flex align-items-center gap-2">
                        @csrf
                        <input type="text" name="check_bot" aria-label="防機器人驗證碼，請輸入：{{ session('check_bot') }}" class="form-control" style="width: 150px;" placeholder="請輸入：{{ session('check_bot') }}" required>
                        <button class="btn btn-primary btn-sm">我不是機器人</button>
                    </form>
                @endif

                @include('layouts.errors')
            </div>
        </div>
    </div>
</div>
<script nonce="<?php echo $csp_nonce; ?>">
    var validator = $("#this_form").validate();
</script>
@endsection