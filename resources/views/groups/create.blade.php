@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '新增群組 | ')

@section('content')
    {{-- 統一使用 py-4 增加上下間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3">
                <i class="fas fa-users me-2"></i>群組管理：新增群組
            </h1>

            <div class="row justify-content-center mt-5">
                <div class="col-md-6"> {{-- 寬度稍微放寬至 6，在 BS5 下視覺較平衡 --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title mb-0"><i class="fas fa-plus-circle me-1"></i> 請填寫群組資訊</h5>
                        </div>
                        <div class="card-body p-4">
                            {{-- 改為純 HTML 表單 --}}
                            <form action="{{ route('groups.store') }}" method="POST" id="this_form1">
                                @csrf
                                
                                {{-- 引入表單欄位 --}}
                                @include('groups.form')

                                <div class="mt-4 text-center">
                                    {{-- 使用您自定義的 save-btn 類別，觸發自動檢查與 SweetAlert --}}
                                    <span class="btn btn-primary px-5 save-btn" data-form="this_form1">
                                        <i class="fas fa-save me-1"></i> 儲存群組
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