@extends('layouts.master_clean')

@section('title', '新增使用者權限 | ')

@section('content')
<!-- Chosen v1.8.2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.min.css" rel="stylesheet" />
<link href="{{ asset('css/component-chosen.min.css') }}" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.min.js"></script>

<div class="p-2">
    <form action="{{ route('user_powers.store') }}" method="POST" id="this_form" onsubmit="return false">
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
            <button type="submit" class="btn btn-success px-4 shadow-sm" onclick="sw_confirm2('確定指定此權限嗎？','this_form')">
                <i class="fas fa-user-plus me-1"></i> 新增權限指定
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $(".search_select").chosen({
            search_contains: true,
            width: '100%', // 確保在 Bootstrap 欄位中寬度正確
            no_results_text: "找不到匹配的使用者:"
        });
    });
</script>
@endsection
