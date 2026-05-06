@extends('layouts.master_clean')

@section('title', '新增欄位 | ')

@section('content')
    @include('layouts.errors')
    <form action="{{ route('setups.add_col') }}" method="POST" id="this_form1">
        @csrf
        <table class="table">
            <tr>
                <td>
                    <div class="mb-3">
                        <label for="order_by" class="form-label">1.排序</label>
                        <input type="number" name="order_by" id="order_by" class="form-control" placeholder="數字">
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="title" class="form-label">2.名稱</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="num" class="form-label">3.欄位寬度比例 ( 1-12 整數 )</label>
                        <input type="text" name="num" id="num" class="form-control" required maxlength="2">
                    </div>
                </td>
            </tr>
        </table>
        <div class="mb-3">
            <span class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                <i class="fas fa-save"></i> 儲存設定
            </span>                                                        
        </div>        
    </form>        
@endsection