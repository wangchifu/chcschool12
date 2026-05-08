@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '學校介紹管理 | ')

@section('content')
    {{-- 統一加上 py-4 增加上下間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3">
                學校介紹管理
            </h1>

            {{-- 導覽列 --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">簡介列表</li>
                </ol>
            </nav>

            <div class="mb-3">
                <a href="{{ route('departments.create') }}" class="btn btn-success btn-sm px-3 shadow-sm venobox" data-vbtype="iframe">
                    <i class="fas fa-plus me-1"></i> 新增介紹
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" style="word-break:break-all;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 80px;">ID</th>
                                <th style="width: 100px;">排序</th>
                                <th style="width: 200px;">共編群組</th>
                                <th>標題</th>
                                <th class="text-center" style="width: 180px;">動作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($departments as $department)
                            <tr>
                                <td class="ps-3 text-muted">{{ $department->id }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $department->order_by }}</span>
                                </td>
                                <td>
                                    <?php $group_id = (empty($department->group_id)) ? "1" : $department->group_id; ?>
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-users me-1"></i>{{ $group_array[$group_id] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('departments.show', $department->id) }}" class="text-decoration-none fw-bold">
                                        {{ $department->title }} <i class="fas fa-external-link-alt ms-1 small text-muted"></i>
                                    </a>
                                </td>                        
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                            <i class="fas fa-edit"></i> 修改
                                        </a>

                                        {{-- 使用自定義的 delete-btn1 邏輯搭配隱藏表單 --}}
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-btn2"                                                 
                                                data-form="delete_form{{ $department->id }}">
                                            <i class="fas fa-trash"></i> 刪除
                                        </button>
                                    </div>

                                    {{-- 純 HTML 刪除表單 --}}
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" id="delete_form{{ $department->id }}" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection