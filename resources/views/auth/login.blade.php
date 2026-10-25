@extends('layouts.master')

@section('title','管理登入 | ')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-7 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-shield me-2" aria-hidden="true"></i>登入</h4>
            </div>

            <div class="card-body p-4">
                @if(session('login_error') < 3)
                <form method="POST" action="{{ route('auth') }}" id="this_form">
                    @csrf

                    <div class="row mb-3 align-items-center">
                        <label for="username" class="col-md-4 col-form-label text-md-end fw-bold">本機帳號</label>

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

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-7 offset-md-4 d-flex align-items-center gap-2 flex-nowrap">
                            <!-- 無障礙無刷新按鈕 -->
                            <button type="button" id="refreshCaptcha" class="btn p-0 border-0 bg-transparent d-inline-flex flex-shrink-1 overflow-hidden" title="點擊換一張驗證碼" aria-label="點擊換一張驗證碼">
                                <img id="captchaImage" src="{{ route('pic') }}" class="rounded border img-fluid" style="max-height: 38px; max-width: 150px; object-fit: contain;" alt="驗證碼圖片">
                            </button>
                            
                            <button type="button" id="loadAudio" class="audio-captcha-btn border-0 flex-shrink-0" aria-label="播放驗證碼語音">
                                <i class="fas fa-volume-up" aria-hidden="true"></i> [語音播放]
                            </button>
                            <audio id="myAudio">
                                <source src="" type="audio/mp3">                                
                            </audio>                            
                        </div>
                    </div>
                    
                    <div class="row mb-4 align-items-center">
                        <label for="chaptcha" class="col-md-4 col-form-label text-md-end fw-bold">驗證碼</label>

                        <div class="col-md-7">
                            <input type="text" id="chaptcha" tabindex="3" class="form-control" name="chaptcha" required placeholder="上圖國字轉阿拉伯數字" maxlength="5" title="請輸入驗證碼">
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
                    <div class="alert alert-warning border-0 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i> 登入錯誤超過三次，請輸入三碼數字後送出：
                    </div>
                    <form action="{{ route('not_bot') }}" method="post" class="mt-3 d-flex align-items-center gap-2">
                        @csrf
                        <input type="text" name="check_bot" aria-label="防機器人驗證碼，請輸入：{{ session('check_bot') }}" placeholder="請輸入：{{ session('check_bot') }}" class="form-control w-auto" required>
                        <button class="btn btn-primary btn-sm">我不是機器人</button>
                    </form>
                @endif

                @include('layouts.errors')
                
                <hr class="my-4">

                <div class="text-center text-md-end">
                    <a href="{{ route('sso') }}" class="image-button text-decoration-none">
                        <img src="{{ asset('images/chc.jpg') }}" alt="彰化縣教育雲端 OpenID 登入圖示" width="80" class="mb-1"><br>
                        <span class="small text-muted fw-bold">OpenID 登入</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('down_body')
    <script nonce="<?php echo $csp_nonce; ?>">
        var validator = $("#this_form").validate();

        $(document).ready(function () {
            // 點擊驗證碼圖片：無刷新更新 Session 驗證碼與圖片
            $('#refreshCaptcha').click(function () {
                $('#captchaImage').attr('src', '{{ route('pic') }}?' + new Date().getTime());
            });

            // 點擊播放語音驗證碼
            $('#loadAudio').click(function () {
                $.ajax({
                    url: '{{ route('voice') }}',
                    type: 'GET',
                    success: function (response) {
                        if (response.startsWith('Error')) {
                            alert(response);
                        } else {
                            const base64Src = `data:audio/mpeg;base64,${response}`;
                            $('#myAudio source').attr('src', base64Src);
                            $('#myAudio')[0].load();
                            $('#myAudio')[0].play();
                        }
                    },
                    error: function () {
                        alert('無法載入音頻數據！');
                    }
                });
            });
        });
    </script>
@endsection