@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '關鍵字搜尋 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            {{-- 調整標題外距，保持與前後頁面相同的現代質感 --}}
            <h1 class="mb-3">搜尋「{{ $search }}」公告</h1>
            
            {{-- Bootstrap 5 麵包屑導覽優化 --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}" class="text-decoration-none">首頁</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('posts.index') }}" class="text-decoration-none">公告列表</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">關鍵字搜尋</li>
                </ol>
            </nav>
            
            {{-- 公告列表內頁共同組件 (已自動套用上一節重構的 BS5 與 Flexbox 樣式) --}}
            @include('posts.list')
            
            {{-- 帶有搜尋條件的 Laravel 分頁連結 (自動支援 BS5) --}}
            <div class="mt-4">
                {{ $posts->appends(['search' => $search])->links('layouts.pagination') }}
            </div>
        </div>
    </div>
@endsection