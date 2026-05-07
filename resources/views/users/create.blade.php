@extends('layouts.master_clean')

@section('title', '新增本機帳號 | ')

@section('content')
    {{-- 用 pt-4 代替 <br> --}}
    <div class="pt-4">
        <h2 class="mb-4 fw-bold text-dark">
            <i class="fas fa-user-plus me-2"></i>新增本機帳號
        </h2>

        <form action="{{ route('users.store') }}" method="POST" id="this_form1">
            @csrf
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 120px;">帳號</th>
                                <th>預設密碼</th>
                                <th style="min-width: 120px;">職稱</th>
                                <th style="min-width: 120px;">姓名</th>
                                <th style="width: 100px;">排序</th>
                                <th style="min-width: 200px;">群組 (可多選)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="username" id="username" class="form-control" required placeholder="帳號">
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">demo1234</span>
                                </td>
                                <td>
                                    <input type="text" name="title" id="title" class="form-control" required placeholder="職稱">
                                </td>
                                <td>
                                    <input type="text" name="name" id="name" class="form-control" required placeholder="姓名">
                                </td>
                                <td>
                                    <input type="number" name="order_by" id="order_by" class="form-control" placeholder="10">
                                </td>
                                <td>
                                    {{-- 加上 form-select 與多選提示 --}}
                                    <select name="group_id[]" id="group_id" class="form-select" multiple size="4" required>
                                        <option value="">無</option>
                                        @foreach($groups as $id => $group_name)
                                            <option value="{{ $id }}">{{ $group_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text mt-1 text-primary">
                                        <i class="fas fa-info-circle"></i> 按住 Ctrl 可多選
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @include('layouts.errors')

            <div class="mt-4">
                {{-- 使用你之前定義的 save-btn 類別，搭配 data-form 觸發 SweetAlert --}}
                <span class="btn btn-primary px-4 save-btn" data-form="this_form1">
                    <i class="fas fa-save me-1"></i> 儲存變更
                </span>            
            </div>
        </form>
    </div>
@endsection