@extends('layouts.master_clean')

@section('title', '編輯帳號 | ')

@section('content')
    <div class="pt-4">
        <h2 class="mb-4 fw-bold text-dark">
            <i class="fas fa-edit me-2"></i>修改本機帳號
        </h2>
        <form action="{{ route('users.update', $user->id) }}" method="POST" id="this_form1">
            @csrf
            @method('PATCH')

            <div class="card shadow-sm mb-3">                
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th colspan="5" class="py-3 ps-3">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-edit me-2 text-primary"></i>
                                        姓名：<span class="text-dark">{{ $user->name }}</span> 
                                        <span class="ms-3 text-muted">帳號：{{ $user->username }}</span>
                                    </h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label for="title" class="form-label small fw-bold">職稱 (目前: {{ $user->title }})</label>
                                    <select class="form-select" id="title" name="title" tabindex="1" required>
                                        @foreach($title_array as $k => $v)
                                            <option value="{{ $v }}" {{ ($user->title == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach                            
                                    </select>                
                                </td>
                                <td>
                                    <label class="form-label small fw-bold">姓名</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required readonly placeholder="姓名">
                                </td>
                                <td style="width: 120px;">
                                    <label for="order_by" class="form-label small fw-bold">排序</label>
                                    <input type="number" name="order_by" id="order_by" value="{{ $user->order_by }}" class="form-control" maxlength="3">
                                </td>
                                <td style="width: 250px;">
                                    <label for="group_id" class="form-label small fw-bold">群組 (可多選)</label>
                                    <select name="group_id[]" id="group_id" class="form-select" multiple size="4">
                                            <option value="">無</option>
                                        @foreach($groups as $id => $name)
                                            <option value="{{ $id }}" {{ in_array($id, $default_group) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <?php
                                        $check1 = ($user->disable)?"":"checked";
                                        $check2 = ($user->disable)?"checked":"";
                                        $admin = ($user->admin)?"checked":"";
                                        $disabled = ($user->username=="admin")?"disabled":null;
                                    ?>
                                    <div class="mb-2">
                                        @if(auth()->user()->id != $user->id)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="disable" value="" id="enable" {{ $check1 }}>
                                                <label class="form-check-label" for="enable">在職</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="disable" value="1" id="disable" {{ $check2 }}>
                                                <label class="form-check-label text-danger" for="disable">離職</label>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="admin" value="1" id="admin" {{ $admin }} {{ $disabled }}>
                                        <label class="form-check-label fw-bold" for="admin">網站管理者</label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{-- 這裡套用你之前的 save-btn 類別，會自動觸發 SweetAlert 邏輯 --}}
                <span class="btn btn-primary px-4 save-btn" data-form="this_form1">
                    <i class="fas fa-save me-1"></i> 儲存變更
                </span>                
            </div>
        </form>

        <div class="mt-3">
            @include('layouts.errors')
        </div>
    </div>
@endsection
