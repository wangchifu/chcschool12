@extends('layouts.master')

@section('nav_departments_active', 'active')

@section('title', $department->title.' | ')

@section('content')
    {{-- 統一使用 py-4 增加上下間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-4 text-dark">{{ $department->title }}</h1>
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                    <div class="btn-group">
                        @auth
                            <?php 
                            // 檢查是否在共同編輯群組中
                            $can_edit = 0;
                            if($department->group_id != null){
                                $check_edit = \App\Models\UserGroup::where('user_id', auth()->user()->id)->where('group_id', $department->group_id)->first();
                                if(!empty($check_edit)){
                                    $can_edit = 1;
                                }
                            } else {
                                // 行政人員預設可以編 (group_id = 1)
                                $check_edit = \App\Models\UserGroup::where('user_id', auth()->user()->id)->where('group_id', 1)->first();
                                if(!empty($check_edit)){
                                    $can_edit = 1;
                                }
                            }
                            ?>
                            <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-sm me-1">
                                    <i class="fas fa-reply me-1"></i>返回
                            </a>
                            @if($can_edit)
                                <a href="{{ route('departments.together_edit', $department->id) }}" class="btn btn-primary btn-sm me-1 venobox" data-vbtype="iframe">
                                    <i class="fas fa-edit me-1"></i>共同編輯
                                </a>
                            @endif

                            @if(auth()->user()->admin)                        
                                {{-- 使用自定義的 delete-btn1 邏輯搭配隱藏表單 --}}
                                <button type="button" class="btn btn-danger btn-sm me-1 delete-btn2"                                         
                                        data-form="delete_form{{ $department->id }}">
                                    <i class="fas fa-trash me-1"></i>刪除
                                </button>

                                {{-- 純 HTML 刪除表單 --}}
                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" id="delete_form{{ $department->id }}" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <a href="{{ route('departments.show_log', $department->id) }}" class="btn btn-info btn-sm text-white me-1">
                                    <i class="fas fa-history me-1"></i>查看 Log ({{ $logs_count }})
                                </a>
                            @endif                        
                        @endauth
                    </div>

                    {{-- 點閱數呈現 --}}
                    <div>
                        <span class="badge rounded-pill bg-dark py-2 px-3">
                            <i class="far fa-eye me-1"></i> 點閱 <span class="ms-1 text-info fw-bold">{{ $department->views }}</span>
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive article-content">
                        {!! $department->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection