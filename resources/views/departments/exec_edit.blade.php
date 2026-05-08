@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '修改介紹 | ')

@section('content')
    {{-- 使用 pt-4 讓標題與頂部保持距離 --}}
    <div class="row justify-content-center pt-4">
        <div class="col-md-11">
            <h2 class="fw-bold mb-4">
                <i class="fas fa-edit me-2 text-primary"></i>修改介紹
            </h2>

            {{-- 將 Form::model 改為標準 HTML 標籤 --}}
            <form action="{{ route('departments.exec_update', $department->id) }}" method="POST" id="this_form1">
                @csrf
                @method('PATCH')

                {{-- 引入您之前優化過的 departments.form --}}
                @include('departments.form')
                
            </form>
        </div>
    </div>
@endsection