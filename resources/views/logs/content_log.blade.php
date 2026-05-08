@extends('layouts.master')

{{-- 標題建議改為更具讀讀性的文字 --}}
@section('title', "查看歷史 Log (ID: ".$id.") | ")

@section('content')
    {{-- 統一使用 py-4 增加間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-4">
                <i class="fas fa-history me-2 text-secondary"></i>內容編輯紀錄 (ID: {{ $id }})
            </h1>

            @foreach($logs as $log)
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                        {{-- 左側：紀錄資訊，採用您偏好的 text-danger 風格 --}}
                        <div class="text-danger fw-bold">
                            <i class="fas fa-clock me-1"></i> {{ $log->created_at }} 
                            <span class="text-dark ms-2">
                                <i class="fas fa-user me-1"></i> 由 <span class="text-primary">{{ $log->user->name }}</span> 送出
                            </span>
                        </div>

                        {{-- 右側：刪除按鈕，套用您貼出的 delete-btn2 與隱藏表單邏輯 --}}
                        <div>
                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn2"                                     
                                    data-form="delete_log_form{{ $log->id }}">
                                <i class="fas fa-trash-alt me-1"></i>刪除此 Log
                            </button>

                            {{-- 純 HTML 刪除表單 --}}
                            <form action="{{ route('contents.delete_log', $log->id) }}" method="POST" id="delete_log_form{{ $log->id }}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- 顯示 Log 標題 --}}
                        <h4 class="fw-bold mb-3">{{ $log->title }}</h4>
                        
                        <div class="table-responsive article-content border p-3 rounded bg-white">
                            {{-- 歷史內容呈現 --}}
                            {!! $log->content !!}
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 py-3">
                        <span class="text-muted small fw-bold me-2">當時權限設定：</span>
                        @if($log->power == null)
                            <span class="badge bg-success">公開</span>
                        @elseif($log->power == 2)
                            <span class="badge bg-warning text-dark">校內網域或登入</span>
                        @elseif($log->power == 3)
                            <span class="badge bg-info text-dark">僅限登入者</span> 
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- 如果完全沒資料時的提示 --}}
            @if($logs->isEmpty())
                <div class="alert alert-light text-center shadow-sm py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">目前尚無任何編輯紀錄。</p>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('contents.show', $id) }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i>返回內容頁面
                </a>
            </div>
        </div>
    </div>
@endsection