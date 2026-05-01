@extends('layouts.master_clean')

@section('title', '新增欄位 | ')

@section('content')
    @include('layouts.errors')
    <form action="{{ route('setups.add_col') }}" method="POST" id="this_form" onsubmit="return false">
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
            <button type="submit" class="btn btn-success btn-sm" onclick="sw_confirm2('確定新增？','this_form')">
                <i class="fas fa-plus me-1"></i> 新增欄位
            </button>
        </div>
    </form>    
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
