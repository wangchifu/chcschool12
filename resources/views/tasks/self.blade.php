@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '行政待辦 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            @include('tasks.form')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index') }}">待辦</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index2') }}">完成</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index3') }}">無關</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('tasks.self') }}"><i class="fas fa-plus"></i> 自己</a>
                </li>
            </ul>
            <hr>
            
            {{-- 🛠️ 1. 改為標準 HTML <form> 標籤，並加上 enctype 確保檔案能正常上傳 --}}
            <form action="{{ route('tasks.self_store') }}" method="POST" id="tasks_self_store" enctype="multipart/form-data">
                {{-- 🛠️ 2. 補上 Laravel 必要的 CSRF 權杖 --}}
                @csrf
                
                <table width="100%">
                    <tr>
                        <td width="60%">
                            {{-- 🛠️ 3. 改為標準 <input type="text"> --}}
                            <input type="text" name="title" id="title" class="form-control" required="required" placeholder="自己的事項">
                        </td>
                        <td>
                            {{-- 🛠️ 4. 改為標準 <input type="file"> --}}
                            <input type="file" name="files[]" class="form-control" multiple="multiple">
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm save-btn" data-form="tasks_self_store">
                                <i class="fas fa-plus"></i> 新增
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <small>請簡短扼要；一次新增一事項。</small>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="user_id" value="{{ $user->id }}">
            {{-- 🛠️ 5. 改為標準 </form> --}}
            </form>

        </div>
    </div>
@endsection