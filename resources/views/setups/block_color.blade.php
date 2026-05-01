@extends('layouts.master_clean')

@section('title', '區塊顏色樣式 | ')

@section('content')
    <link href="{{ asset('css/block_style.css') }}" rel="stylesheet">
    <a href="#" onclick="history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
    <div class="row justify-content-center g-4 mb-4">
        <div class="col-md-3">
            <div class="block1 shadow rounded h-100">
                <div class="title1 p-2">
                    <h4 class="mb-0">單色綠</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block2 shadow rounded h-100">
                <div class="title2 p-2">
                    <h4 class="mb-0">單色黃</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="block3 shadow rounded h-100">
                <div class="title3 p-2">
                    <h4 class="mb-0">單色藍</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="block4 shadow rounded h-100">
                <div class="title4 p-2">
                    <h4 class="mb-0">單色紅</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-4 mb-4">
        <div class="col-md-3">
            <div class="soft-block1 shadow rounded h-100">
                <div class="soft-title1 p-2">
                    <h4 class="mb-0">單色淺綠</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="soft-block2 shadow rounded h-100">
                <div class="soft-title2 p-2">
                    <h4 class="mb-0">單色淺黃</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="soft-block3 shadow rounded h-100">
                <div class="soft-title3 p-2">
                    <h4 class="mb-0">單色淺藍</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="soft-block4 shadow rounded h-100">
                <div class="soft-title4 p-2">
                    <h4 class="mb-0">單色淺紅</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-4 mb-4">
        <div class="col-md-3">
            <div class="gradient-block1 shadow rounded h-100">
                <div class="gradient-title1 p-2">
                    <h4 class="mb-0">漸層紅</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
        </div>

    <div class="row justify-content-center g-4 mb-4">
        <div class="col-md-8">
            <div class="default-block1 shadow rounded">
                <div class="default-title1 p-2">
                    <h4 class="mb-0">預設深灰</h4>
                </div>
                <div class="content2 p-3">
                    <p class="mb-0">彰化縣網中心首頁代管方案三，歡迎使用！</p>
                </div>
            </div>
        </div>
    </div>
@endsection
