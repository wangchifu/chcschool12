@extends('layouts.master_clean')

@section('title', '新增本機帳號 | ')

@section('content')
    <br>
    <h2 class="mb-4">新增本機帳號</h2>

    <form action="{{ route('users.store') }}" method="POST" id="this_form" onsubmit="return false">
        @csrf
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 120px;">帳號</th>
                        <th>預設密碼</th>
                        <th style="min-width: 120px;">職稱</th>
                        <th style="min-width: 120px;">姓名</th>
                        <th style="width: 80px;">排序</th>
                        <th style="min-width: 200px;">群組 (可多選)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="username" id="username" class="form-control" required placeholder="帳號">
                        </td>
                        <td class="text-muted">
                            <code>demo1234</code>
                        </td>
                        <td>
                            <input type="text" name="title" id="title" class="form-control" required placeholder="職稱">
                        </td>
                        <td>
                            <input type="text" name="name" id="name" class="form-control" required placeholder="姓名">
                        </td>
                        <td>
                            <input type="text" name="order_by" class="form-control" maxlength="3">
                        </td>
                        <td>
                            <select name="group_id[]" id="group_id" class="form-select" multiple size="3">
                                @foreach($groups as $id => $group_name)
                                    <option value="{{ $id }}">{{ $group_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-1 d-block">按住 Ctrl 可多選</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @include('layouts.errors')

        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm" onclick="return confirm('確定新增帳號嗎？')">
                <i class="fas fa-save me-1"></i> 儲存變更
            </button>
        </div>
    </form>
@endsection
