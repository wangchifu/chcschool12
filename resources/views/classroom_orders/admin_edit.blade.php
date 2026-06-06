@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '教室預約 | ')

@section('content')
    <div class="row justify-content-center g-4 my-2">
        <div class="col-md-11">
            
            {{-- 標題美化 --}}
            <h1 class="fw-bold text-dark mb-3">教室預約</h1>

            {{-- 預備原有的後端資料結構與字串比對邏輯 --}}
            <?php
            $name = $classroom->name;
            $disable = $classroom->disable;
            $sections = config("chcschool.class_sections");
            for($i = 0; $i < 7; $i++) {
                foreach($sections as $k => $v) {
                    $close[$i][$k] = null;
                    if(strpos($classroom->close_sections, "'".$i."-".$k."'") !== false){
                        $close[$i][$k] = 1;
                    }
                }
            }
            ?>

            {{-- 🎯 外觀美化：包覆一層帶有些許陰影與圓角的白色卡片，與新增教室、管理列表風格完全同步 --}}
            <div class="card shadow-sm border border-secondary border-opacity-10 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- 🎯 修正：替換掉舊版套件，改用標準 HTML5 表單，並透過隱藏欄位正確帶入 Laravel 的 PATCH 方法 --}}
                    <form action="{{ route('classroom_orders.admin_update', $classroom->id) }}" method="POST" id="this_form1">
                        @csrf
                        @method('PATCH')
                        
                        {{-- 引入已優化為紅色不開放節次的表單欄位內容樣板 --}}
                        @include('classroom_orders.admin_form')
                        
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection