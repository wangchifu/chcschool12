@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教室預約 | ')

@section('content')
    <div class="row justify-content-center g-4 my-2">
        <div class="col-md-11">
            
            {{-- 標題美化 --}}
            <h1 class="fw-bold text-dark mb-3">教室預約</h1>
            
            {{-- 頁籤選單區間距優化 --}}
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('classroom_orders.index') }}">教室預約</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('classroom_orders.admin') }}">教室管理</a>
                </li>
            </ul>

            {{-- 新增按鈕與下方間距 --}}
            <div class="mb-3">
                <a href="{{ route('classroom_orders.admin_create') }}" class="btn btn-success btn-sm fw-bold px-3 venobox" data-vbtype="iframe">
                    <i class="fas fa-plus me-1"></i> 新增教室
                </a>
            </div>

            {{-- 表格質感美化：加入圓角、陰影外框與垂直置中 --}}
            <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm">
                <table class="table table-striped table-hover align-middle mb-0 text-center">
                    <thead class="table-primary text-dark fw-bold text-nowrap">
                        <tr>
                            <th scope="col" style="width: 80px;" class="py-3">序號</th>
                            <th scope="col" class="py-3">名稱</th>
                            <th scope="col" style="width: 150px;" class="py-3">狀態</th>
                            <th scope="col" style="width: 200px;" class="py-3">管理動作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach($classrooms as $classroom)
                            <tr>
                                {{-- 序號欄灰底微調 --}}
                                <td class="bg-light text-secondary fw-semibold">{{ $i }}</td>
                                
                                {{-- 名稱靠左留白，閱讀更順眼 --}}
                                <td class="fw-medium text-dark text-start px-4">{{ $classroom->name }}</td>
                                
                                {{-- 狀態改用粉嫩質感的 Badge 標籤代替純文字 --}}
                                <td>
                                    @if($classroom->disable)
                                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 fw-bold rounded-2">停用</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success px-2.5 py-1.5 fw-bold rounded-2">啟用</span>
                                    @endif
                                </td>
                                
                                {{-- 按鈕加上粗體與適當間距 --}}
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('classroom_orders.admin_edit', $classroom->id) }}" class="btn btn-info btn-sm text-white fw-bold venobox" data-vbtype="iframe">
                                            <i class="fas fa-edit me-1"></i>修改
                                        </a>
                                        
                                        {{-- 🎯 純 GET 刪除連結，無任何 onclick 與 javascript 阻擋 --}}
                                        <a href="#!" class="btn btn-danger btn-sm fw-bold delete-btn1" data-url="{{ route('classroom_orders.admin_destroy', $classroom->id) }}">
                                            <i class="fas fa-trash me-1"></i>刪除
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection