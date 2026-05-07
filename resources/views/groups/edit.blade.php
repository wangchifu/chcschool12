@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '編輯群組 | ')

@section('content')
    {{-- 統一使用 py-4 增加上下間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3">
                <i class="fas fa-users me-2"></i>群組管理：編輯群組
            </h1>                    

            <div class="row justify-content-center mt-5">
                <div class="col-md-6"> {{-- 寬度維持 6，視覺較平衡 --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-edit me-1"></i> 修改群組資訊：{{ $group->name }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            {{-- 改為純 HTML 表單，手動加入 @method('PATCH') --}}
                            <form action="{{ route('groups.update', $group->id) }}" method="POST" id="this_form1">
                                @csrf
                                @method('PATCH')
                                
                                {{-- 引入表單欄位 (內部已改為純 HTML 並會自動帶入 $group 值) --}}
                                @include('groups.form')

                                <div class="mt-4 text-center">
                                    {{-- 使用您自定義的 save-btn 類別，觸發自動檢查與 SweetAlert --}}
                                    <span class="btn btn-primary px-5 save-btn" data-form="this_form1">
                                        <i class="fas fa-save me-1"></i> 儲存變更
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