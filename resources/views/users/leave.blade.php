@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '帳號管理 | ')

@section('content')
    {{-- 1. 統一加上 py-4，讓頁面頂部有呼吸空間 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            {{-- 2. 標題統一加上 fw-bold (加粗) 更顯眼 --}}
            <h1 class="mb-4 fw-bold">
                帳號管理
            </h1>

            <?php
            // 在職頁面：$active[1]="active"; $active[2]="";
            $active[1]=""; $active[2]="active";
            ?>

            {{-- 3. 導覽分頁區塊 --}}
            <div class="mb-3">
                @include('users.nav', $active)
            </div>

            {{-- 4. 直接引入內容。 
                 注意：因為你的 users.form 內部已經有 <div class="card"> 了，
                 所以這裡「不應該」再包一層 card，否則邊框會重疊。 --}}
            @include('users.form')
            
        </div>
    </div>
@endsection