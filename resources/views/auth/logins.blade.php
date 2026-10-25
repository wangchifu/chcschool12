@extends('layouts.master')

@section('title','教職員登入 | ')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-6 col-lg-4">
        <div class="card">            
            <div class="card-header d-flex align-items-center justify-content-center gap-2">
                <a href="https://eip.chc.edu.tw" target="_blank" rel="noopener noreferrer" title="開啟彰化縣 EIP 平台">
                    <img src="{{ asset('images/chc2.png') }}" alt="彰化縣 EIP Logo" width="36" height="36" class="img-brand-icon">
                </a>
                <span class="fs-6 fw-bold">彰化縣教育雲端帳號登入</span>
            </div>

            <div class="card-body p-4 text-center">                
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">                        
                        <div class="py-3">
                            <a href="{{ route('sso') }}" class="image-button text-decoration-none">
                                <img src="{{ asset('images/chc.jpg') }}" alt="彰化縣 OpenID 登入圖示" width="120" class="mb-2"><br>
                                <span class="fw-bold text-dark fs-6">OpenID 登入</span>
                            </a>
                        </div>

                        @include('layouts.errors')

                        <div class="my-3">
                            <a href="https://eip.chc.edu.tw/recovery-password" target="_blank" rel="noopener noreferrer" class="btn btn-outline-warning-custom btn-sm">
                                <i class="fas fa-key me-1" aria-hidden="true"></i> 忘記密碼？
                            </a>              
                        </div>

                        <hr class="my-3">

                        <div class="text-end">
                            <a href="{{ route('admin_login') }}" class="text-muted small text-decoration-none">
                                <i class="fas fa-cog me-1" aria-hidden="true"></i> 使用本機帳號
                            </a>
                        </div>                                                                      
                    </div>                               
                </div>                                  
            </div>
        </div>
    </div>
</div>
@endsection