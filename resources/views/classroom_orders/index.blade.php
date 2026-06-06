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
                    <a class="nav-link active" href="{{ route('classroom_orders.index') }}">教室預約</a>
                </li>
                @if($classroom_admin)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('classroom_orders.admin') }}">教室管理</a>
                    </li>
                @endif
            </ul>

            {{-- 表格質感美化：加入圓角、陰影外框與垂直置中 (與管理端一致) --}}
            <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-4">
                <table class="table table-striped table-hover align-middle mb-0 text-center">
                    <thead class="table-primary text-dark fw-bold text-nowrap">
                        <tr>
                            <th scope="col" style="width: 80px;" class="py-3">序號</th>
                            <th scope="col" class="py-3">名稱</th>
                            <th scope="col" style="width: 150px;" class="py-3">動作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach($classrooms as $classroom)
                            <tr>
                                {{-- 序號欄灰底與加粗 --}}
                                <td class="bg-light text-secondary fw-semibold">{{ $i }}</td>
                                
                                {{-- 名稱靠左留白，閱讀體驗更佳 --}}
                                <td class="fw-medium text-dark text-start px-4">{{ $classroom->name }}</td>
                                
                                {{-- 動作按鈕 --}}
                                <td>
                                    <a href="{{ route('classroom_orders.show', ['classroom' => $classroom->id, 'select_sunday' => date('Y-m-d')]) }}" class="btn btn-info btn-sm text-white fw-bold venobox" data-vbtype="iframe">
                                        <i class="fas fa-check-circle me-1"></i> 預約
                                    </a>
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