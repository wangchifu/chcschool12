@extends('layouts.master')

{{-- 標題建議改為更具讀讀性的文字 --}}
@section('title', "查看歷史 Log (ID: ".$id.") | ")

@section('content')
    {{-- 統一使用 py-4 增加間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-4">
                <i class="fas fa-history me-2 text-secondary"></i>編輯歷史紀錄 (ID: {{ $id }})
            </h1>

            @foreach($logs as $log)
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                        {{-- 左側：紀錄資訊 --}}
                        <div class="text-danger fw-bold">
                            <i class="fas fa-clock me-1"></i> {{ $log->created_at }} 
                            <span class="text-dark ms-2">
                                <i class="fas fa-user me-1"></i> 由 <span class="text-primary">{{ $log->user->name }}</span> 送出
                            </span>
                        </div>

                        {{-- 右側：刪除按鈕，套用 delete-btn1 邏輯 --}}
                        <div>
                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn2"                                     
                                    data-form="delete_form{{ $log->id }}">
                                <i class="fas fa-trash-alt me-1"></i>刪除此 Log
                            </button>

                            {{-- 純 HTML 刪除表單 --}}
                            <form action="{{ route('departments.delete_log', $log->id) }}" method="POST" id="delete_form{{ $log->id }}" class="d-none">
                                @csrf
                                @method('DELETE') {{-- 如果您的路由是用 DELETE 方法請保留，若是 GET 則移除此行並將 method 改為 GET --}}
                            </form>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive article-content border p-3 rounded bg-white">
                            {{-- 歷史內容呈現 --}}
                            {!! $log->content !!}
                        </div>
                    </div>
                </div>
                {{-- BS5 下建議用 mb-5 取代 <hr>，視覺上更乾淨 --}}
            @endforeach

            {{-- 如果完全沒資料時的提示 --}}
            @if($logs->isEmpty())
                <div class="alert alert-info text-center">
                    目前尚無編輯紀錄。
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('departments.show', $id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>返回上一頁
                </a>
            </div>
        </div>
    </div>
@endsection
