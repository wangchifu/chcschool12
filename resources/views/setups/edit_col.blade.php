@extends('layouts.master_clean')

@section('title', '編輯欄位 | ')

@section('content')
    @include('layouts.errors')
    <form action="{{ route('setups.update_col', $setup_col->id) }}" method="POST" id="this_form" onsubmit="return false">
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
                            <button type="submit" class="btn btn-primary btn-sm" onclick="sw_confirm2('確定修改？','this_form')">
                                <i class="fas fa-save me-1"></i> 儲存變更
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
    <form action="{{ route('setups.delete_col',$setup_col->id) }}" method="post" onsubmit="return false" id='delete_form'>
        @csrf
        @method('delete')
        <button class="btn btn-danger btn-sm" onclick="sw_confirm2('確定刪除？若有區塊放置在此欄位，記得去變更！','delete_form')"><i class="fas fa-trash"></i> 刪除</button>
    </form>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
