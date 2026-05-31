@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '修改報告 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-4">修改報告</h1>
            
            <form action="{{ route('meetings_reports.update', $report->id) }}" method="POST" enctype="multipart/form-data" id="this_form1">
                @csrf
                @method('PATCH')
                
                <div class="card border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden my-4">
                    <h3 class="card-header bg-light fs-5 fw-bold py-3 px-4 text-dark border-bottom">
                        {{ $report->meeting->open_date }} {{ $report->meeting->name }} 報告資料
                    </h3>
                    <div class="card-body p-4">
                        @include('layouts.errors')
                        
                        <div class="mb-3">
                            <label for="job_title" class="form-label fw-bold text-secondary">職稱*</label>
                            <input type="text" name="job_title" id="job_title" value="{{ empty(auth()->user()->title) ? '無職稱' : auth()->user()->title }}" class="form-control" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label fw-bold text-secondary">內容*</label>
                            <textarea name="content" id="content" class="form-control" rows="10" placeholder="請輸入內容" required>{{ $report->content }}</textarea>
                        </div>
                        
                        @include('layouts.hd')
                        
                        <div class="mb-4">
                            <label for="files" class="form-label fw-bold text-secondary">附件管理 <span class="text-muted fw-normal small">(不大於 5MB)</span></label>
                            
                            @if(!empty($files))
                                <div class="d-flex flex-wrap gap-1.5 mb-3">
                                    @foreach($files as $k=>$v)
                                        <?php
                                        $file = "reports/".$report->id."/".$v;
                                        $file = str_replace('/','&',$file);
                                        ?>
                                        <a href="#!" class="btn btn-danger btn-sm fw-semibold btn-file-delete delete-btn1" data-url="{{ url('meetings_reports/'.$file.'/fileDel') }}">
                                            <i class="fas fa-times-circle me-1"></i> 刪除 {{ $v }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($per < 100)
                                <input type="file" name="files[]" id="files" class="form-control" multiple>
                            @else
                                <div class="mt-1">
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1.5 fw-bold small">
                                        <i class="fas fa-exclamation-triangle me-1"></i> 容量已滿！無法加附件！
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-2 pt-2">
                            <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存設定
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection