@extends('layouts.master_clean')

@section('title', '新增使用者權限 | ')

@section('in_head')    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chosen-js@1.8.7/chosen.min.css">
    <link href="{{ asset('css/component-chosen.min.css') }}" rel="stylesheet" />    
@endsection

@section('content')
<div class="p-2">
    <form action="{{ route('user_powers.store') }}" method="POST" id="this_form1">
        @csrf
        
        <table class="table table-striped align-middle border">
            <thead class="table-light">
                <tr>
                    <th style="width: 30%;">模組</th>
                    <th style="width: 40%;">選擇使用者</th>
                    <th style="width: 30%;">指定權限</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-bold text-primary">
                        <i class="fas fa-cube me-1"></i>{{ $module }}
                    </td>
                    <td>
                        <select name="user_id" class="form-control search_select" required>
                            <option value="">請輸入關鍵字搜尋...</option>
                            @foreach($users as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark">{{ $type }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="name" value="{{ $module }}">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="mt-3 text-center">
            <span class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                <i class="fas fa-save"></i> 新增權限指定
            </span>            
        </div>
    </form>
</div>
@endsection

@section('down_body')
    <script src="https://cdn.jsdelivr.net/npm/chosen-js@1.8.7/chosen.jquery.min.js"></script>
    <script nonce="<?php echo $csp_nonce; ?>">
        $(document).ready(function() {
            $(".search_select").chosen({
                search_contains: true,
                width: '100%', // 確保在 Bootstrap 欄位中寬度正確
                no_results_text: "找不到匹配的使用者:"
            });
        });
    </script>
@endsection