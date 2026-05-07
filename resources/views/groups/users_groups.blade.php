@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '使用者-群組列表 | ')

@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h2 class="fw-bold mb-4">
                <i class="fas fa-users-cog me-2 text-primary"></i>使用者 - [ <span class="text-danger">{{ $group->name }}</span> ] 列表管理
            </h2>            

            <div class="row">
                {{-- 左側列表 --}}
                <div class="col-md-9">
                    <div class="card shadow-sm border-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" style="width: 70px;">序號</th>
                                        <th>帳號</th>
                                        <th>姓名</th>
                                        <th>職稱</th>
                                        <th>狀態</th>
                                        <th class="text-center">動作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($user_data as $index => $v)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                                        <td>{{ $v['username'] }}</td>
                                        <td class="fw-bold">{{ $v['name'] }}</td>
                                        <td>{{ $v['title'] }}</td>
                                        <td>
                                            @if($v['disable'])
                                                <span class="badge bg-danger">已離職</span>
                                            @else
                                                <span class="badge bg-success">在職</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{-- 套用 delete-btn1 邏輯 --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn2" data-form="delete_form{{ $v['id'] }}">
                                                <i class="fas fa-walking"></i> 離開群組
                                            </button>

                                            {{-- 純 HTML 刪除表單 --}}
                                            <form action="{{ route('users_groups.destroy') }}" method="POST" id="delete_form{{ $v['id'] }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="group_id" value="{{ $group->id }}">
                                                <input type="hidden" name="user_id" value="{{ $v['id'] }}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 右側新增 --}}
                <div class="col-md-3">
                    <div class="card shadow-sm border-primary">
                        <div class="card-header bg-primary text-white py-3">
                            <i class="fas fa-user-plus me-1"></i> 加入使用者
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users_groups.store') }}" method="POST" id="add_form1">
                                @csrf
                                <div class="mb-3">
                                    <label for="user_id" class="form-label small fw-bold">選擇使用者 (可多選)</label>
                                    <select name="user_id[]" id="user_id" class="form-select" multiple size="18" required>
                                        @foreach($user_menu as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text small mt-2">按住 Ctrl 或拖曳滑鼠多選</div>
                                </div>

                                <input type="hidden" name="group_id" value="{{ $group->id }}">

                                {{-- 套用 save-btn 邏輯 --}}
                                <div class="d-grid">
                                    <span class="btn btn-success save-btn" data-form="add_form1">
                                        <i class="fas fa-plus me-1"></i> 加入群組
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection