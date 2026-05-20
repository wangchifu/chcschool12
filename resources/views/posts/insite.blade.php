@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '公告系統 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            {{-- 🎯 修正：優化標題下邊距 --}}
            <h1 class="mb-4">公告系統：內部公告</h1>
            
            {{-- 🎯 修正：升級為 Bootstrap 5 的導覽頁籤結構 --}}
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link text-decoration-none" href="{{ route('posts.index') }}">一般公告</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-decoration-none" href="{{ route('posts.honor') }}">
                        <img src="{{ asset('images/gold-medal.svg') }}" width="16" class="me-1" alt="金牌">榮譽榜
                    </a>
                </li>
                <li class="nav-item">
                    {{-- 🎯 修正：將 active 移至此處，並補上符合 BS5 規範的 aria-current="page" --}}
                    <a class="nav-link active" aria-current="page" href="{{ route('posts.insite') }}">內部公告</a>
                </li>
            </ul>
            
            {{-- 引入公告清單 --}}
            @include('posts.list')
            
            {{-- 分頁導覽 --}}
            <div class="mt-4">
                {{ $posts->links('layouts.pagination') }}
            </div>
        </div>
    </div>
@endsection