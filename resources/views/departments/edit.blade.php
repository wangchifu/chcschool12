@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '修改介紹 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    {{-- 統一使用 py-4 增加上下間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3">修改介紹</h1>                        

            {{-- 將 Form::model 改為純 HTML 標籤 --}}
            <form action="{{ route('departments.update', $department->id) }}" method="POST" id="this_form1">
                @csrf
                @method('PATCH')

                {{-- 引入剛才修改好的 departments.form --}}
                @include('departments.form')                
            </form>
        </div>
    </div>    
@endsection