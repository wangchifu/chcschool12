@extends('layouts.master_close')

@section('title','管理登入 | ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>管理員登入</h4></div>

            <div class="card-body">
                @if(session('login_error') < 3)
                <form method="POST" action="{{ route('auth') }}" id="this_form">
                    @csrf

                    <div class="row mb-3">
                        <label for="username" class="col-sm-4 col-form-label text-md-end">帳號</label>

                        <div class="col-md-6">
                            <input tabindex="1" id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">密碼</label>

                        <div class="col-md-6">
                            <input id="password" type="password" tabindex="2" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-start">
                        </div>
                        <div class="col-md-6 text-md-start">
                            <a href="{{ route('admin_login') }}"><img src="{{ route('pic') }}" class="img-fluid"></a>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="chaptcha" class="col-md-4 col-form-label text-md-end">驗證碼</label>

                        <div class="col-md-6">
                            <input type="text" tabindex="3" class="form-control" name="chaptcha" required placeholder="上圖國字轉阿拉伯數字" maxlength="5">
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary btn-sm" tabindex="4">
                                <i class="fas fa-sign-in-alt"></i> 登入
                            </button>
                        </div>
                    </div>
                </form>
                @else
                    <?php
                        $k = rand(100,999);
                        session(['check_bot'=>$k]);
                    ?>
                    <div class="alert alert-danger" role="alert">
                        登入錯誤超過三次，請輸入三碼數字後送出：
                    </div>
                    <form action="{{ route('not_bot') }}" method="post" class="d-flex align-items-center">
                        @csrf
                        <input type="text" name="check_bot" class="form-control me-2" style="width: 150px;" placeholder="請輸入：{{ session('check_bot') }}">
                        <button class="btn btn-primary btn-sm">我不是機器人</button>
                    </form>
                @endif
                @include('layouts.errors')
            </div>
        </div>
    </div>
</div>
<script>
    var validator = $("#this_form").validate();
</script>
@endsection
