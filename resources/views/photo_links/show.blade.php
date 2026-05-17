@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '圖片連結 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3">圖片連結</h1>
            
            {{-- Breadcrumb 面包屑導航 --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">圖片連結</li>
                </ol>
            </nav>
            
            {{-- Bootstrap 5 Tab 標籤頁導航 --}}
            <?php $active0 = ($photo_type_id == null) ? "active" : null; ?>
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ $active0 }}" href="{{ route('photo_links.show') }}">全部</a>
                </li>
                @foreach($photo_types as $photo_type)
                    <?php $active[$photo_type->id] = ($photo_type->id == $photo_type_id) ? "active" : null; ?>
                    <li class="nav-item">
                        <a class="nav-link {{ $active[$photo_type->id] }}" href="{{ route('photo_links.show', $photo_type->id) }}">{{ $photo_type->name }}</a>
                    </li>
                @endforeach
            </ul>
            
            {{-- Bootstrap 5 表格 (加入 table-light 與 align-middle 垂直置中) --}}
            <table class="table table-striped align-middle" style="word-break:break-all;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">排序</th>
                        <th style="width: 150px;">代表圖片</th>
                        <th>名稱</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($photo_links as $photo_link)
                    <tr>
                        <td>
                            {{ $photo_link->order_by }}
                        </td>
                        <td>
                            <?php
                                $school_code = school_code();
                                $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                            ?>
                            {{-- 套用下方全域 CSS 的 class 類別，並加入 img-thumbnail 樣式 --}}
                            <a href="{{ $photo_link->url }}" target="_blank" class="photo-link-thumbnail">
                                <img src="{{ asset($img) }}" height="50" alt="{{ $photo_link->name }} 連結縮圖" class="img-thumbnail">
                            </a>
                        </td>
                        <td>
                            <a href="{{ $photo_link->url }}" target="_blank" class="text-decoration-none">{{ $photo_link->name }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
            {{-- 分頁導航 (Bootstrap 5 會自動渲染符合的樣式) --}}
            <div class="mt-3">
                {{ $photo_links->links() }}
            </div>
        </div>
    </div>

    {{-- 符合 CSP 規範的安全 CSS 區塊 --}}
    <style nonce="{{ $csp_nonce }}">
        /* 圖片懸停平滑淡出效果 */
        .photo-link-thumbnail img {
            transition: opacity 0.2s ease-in-out;
        }
        .photo-link-thumbnail:hover img {
            opacity: 0.6;
        }
    </style>
@endsection