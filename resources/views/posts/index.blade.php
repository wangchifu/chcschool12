@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', (empty($setup->post_name) ? '公告系統' : $setup->post_name) . ' | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            {{-- 加上 mb-4 讓標題與分頁保持舒適間距 --}}
            <h1 class="mb-4">
                @if(empty($setup->post_name))
                    公告系統
                @else
                    {{ $setup->post_name }}
                @endif
            </h1>
            
            {{-- 當有權限時才顯示 Bootstrap 5 分頁導覽 --}}
            @can('create', \App\Models\Post::class)
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" aria-current="page" href="{{ route('posts.index') }}">架上公告</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="{{ route('posts.index_my') }}">我的公告</a>
                    </li>
                </ul>
            @endcan
            
            {{-- 公告列表內頁組件 --}}
            @include('posts.list')
            
            {{-- Laravel 分頁連結 (自動支援 BS5) --}}
            <div class="mt-4">
                {{ $posts->links('layouts.pagination') }}
            </div>
        </div>
    </div>
@endsection