@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '職稱搜尋 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            {{-- 🎯 修正：優化職稱標題下邊距 --}}
            <h1 class="mb-3">{{ $job_title }} 公告</h1>
            
            {{-- 🎯 修正：升級為 Bootstrap 5 麵包屑結構，移除預設超連結底線 --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-decoration-none">公告列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">職稱搜尋</li>
                </ol>
            </nav>
            
            {{-- 引入公告清單 --}}
            @include('posts.list')
            
            {{-- 分頁導覽 --}}
            <div class="mt-4">
                {{ $posts->links('layouts.pagination') }}
            </div>
        </div>
    </div>
@endsection