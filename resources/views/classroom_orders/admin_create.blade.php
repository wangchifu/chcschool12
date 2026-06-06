@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '教室預約 | ')

@section('content')
    <div class="row justify-content-center g-4 my-2">
        <div class="col-md-11">
            
            {{-- 標題美化 --}}
            <h1 class="fw-bold text-dark mb-4">
                <i class="fas fa-plus-circle text-success me-2"></i>教室預約 - 新增教室
            </h1>

            {{-- 預備原有的後端資料結構邏輯 --}}
            <?php
            $name = null;
            $disable = null;
            $sections = config("chcschool.class_sections");
            for($i = 0; $i < 9; $i++) {
                foreach($sections as $k => $v) {
                    $close[$i][$k] = null;
                    if($k == "45" || $k == "0" || $i == "0" || $i == "6") {
                        $close[$i][$k] = 1;
                    }
                }
            }
            ?>

            {{-- 🎯 外觀美化：包覆一層帶有些許陰影與圓角的白色卡片，與列表風格同步 --}}
            <div class="card shadow-sm border border-secondary border-opacity-10 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- 🎯 修正：替換掉舊版套件，改用最純粹、不帶行內屬性、100% 符合 CSP 的 HTML5 標準表單 --}}
                    <form action="{{ route('classroom_orders.admin_store') }}" method="POST" id="this_form1">
                        @csrf
                        
                        {{-- 引入你原有的表單欄位內容樣板 --}}
                        @include('classroom_orders.admin_form')
                        
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection