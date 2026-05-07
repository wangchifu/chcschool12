@extends('layouts.master_clean')

@section('title', '編輯欄位 | ')

@section('in_head')    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">    
    <script src="{{ asset('js/sweet_alert.js') }}"></script>
@endsection

@section('content')
    <h2 class="mb-4 fw-bold text-dark">
        <i class="fas fa-edit me-2"></i> 修改欄位
    </h2>
    @include('layouts.errors')
    <form action="{{ route('setups.update_col', $setup_col->id) }}" method="POST" id="this_form1">
        @csrf
        @method('PATCH')
        
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 150px;">
                            排序
                        </th>
                        <th>
                            名稱
                        </th>
                        <th style="width: 300px;">
                            所佔比例 <small class="text-muted">(Bootstrap 網頁一行佔 12)</small>
                        </th>
                        <th style="width: 150px;">
                            動作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="order_by" id="order_by" value="{{ $setup_col->order_by }}" class="form-control" placeholder="數字">
                        </td>
                        <td>
                            <input type="text" name="title" value="{{ $setup_col->title }}" class="form-control" required>
                        </td>
                        <td>
                            <input type="text" name="num" value="{{ $setup_col->num }}" class="form-control" required maxlength="2">
                        </td>
                        <td>
                            <span class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                                <i class="fas fa-save"></i> 儲存變更
                            </span>                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
    <form action="{{ route('setups.delete_col',$setup_col->id) }}" method="post" id='delete_form'>
        @csrf
        @method('delete')
        <span class="btn btn-danger btn-sm delete-btn2" data-form="delete_form">
            <i class="fas fa-save"></i> 刪除
        </span>        
    </form>
@endsection
