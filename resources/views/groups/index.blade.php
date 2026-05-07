@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '群組管理 | ')

@section('content')
    {{-- 統一使用 py-4 增加上下間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3"><i class="fas fa-users me-2"></i>群組管理</h1>
            
            {{-- 導覽列 --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">群組管理</li>
                </ol>
            </nav>

            <div class="mb-3">
                <a href="{{ route('groups.create') }}" class="btn btn-success btn-sm px-3 shadow-sm venobox" data-vbtype="iframe">
                    <i class="fas fa-plus me-1"></i> 新增群組
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <h3 class="card-header bg-light py-3">
                    <i class="fas fa-list-ul me-1"></i> 群組列表
                </h3>
                <div class="card-body p-0"> {{-- 移除內距讓表格撐滿 --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 80px;">序號</th>
                                    <th>名稱</th>
                                    <th>所屬人員</th>
                                    <th>狀態</th>
                                    <th class="text-center" style="width: 200px;">動作</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($groups as $index => $group)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $group->name }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-info text-dark me-2">
                                            {{ !empty($user_group_data[$group->id]) ? count($user_group_data[$group->id]) : 0 }} 人
                                        </span>
                                        <a href="{{ route('users_groups',$group->id) }}" class="btn btn-outline-info btn-sm venobox" data-vbtype="iframe">
                                            <i class="fas fa-users-cog"></i> 管理人員
                                        </a>
                                    </td>
                                    <td>
                                        @if($group->disable)
                                            <span class="badge bg-danger">已停用</span>
                                        @else
                                            <span class="badge bg-success">使用中</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($group->id > 4)
                                            <div class="btn-group">
                                                <a href="{{ route('groups.edit',$group->id) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                                    <i class="fas fa-edit"></i> 修改
                                                </a>
                                                
                                                {{-- 使用您自定義的 delete-btn1 邏輯 --}}
                                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn2" data-form="delete_form{{ $group->id }}">
                                                    <i class="fas fa-trash"></i> 刪除
                                                </button>
                                            </div>

                                            {{-- 純 HTML 刪除表單 --}}
                                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST" id="delete_form{{ $group->id }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @else
                                            <span class="text-muted small"><i class="fas fa-lock"></i> 內定群組</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection