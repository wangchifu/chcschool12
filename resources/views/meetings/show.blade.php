@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', $meeting->open_date.$meeting->name.' | ')

@section('content')
    <div class="row justify-content-center g-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h1 class="fw-bold text-dark mb-0 fs-2">
                    {{ $meeting->open_date }} {{ get_chinese_weekday($meeting->open_date) }} {{ $meeting->name }}
                </h1>
                <a href="{{ route('meetings.txtDown',$meeting->id) }}" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm">
                    <i class="fas fa-download me-1"></i> 報告內容下載
                </a>
            </div>

            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}" class="text-decoration-none">會議列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">會議內容</li>
                </ol>
            </nav>

            @can('create',\App\Models\Meeting::class)
                @if($has_report=="0" and $die_line =="0")
                    <div class="mb-3">
                        <a href="{{ route('meetings_reports.create',$meeting->id) }}" class="btn btn-success btn-sm fw-bold px-3 shadow-sm venobox" data-vbtype="iframe">
                            <i class="fas fa-plus me-1"></i> 新增報告
                        </a>
                    </div>
                @endif
            @endcan

            <hr class="text-muted opacity-25">

            <?php $i=1; ?>
            @foreach($reports as $report)
                <?php
                //有無附件
                $school_code = school_code();
                $files = get_files(storage_path('app/privacy/'.$school_code.'/reports/'.$report->id));
                ?>
                <div id="report{{ $i }}" class="pt-5 mt-n5"></div>
                
                <div class="card border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden my-4">
                    <h3 class="card-header bg-light fs-5 fw-bold py-3 px-4 text-dark border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span>{{ $i }}. {{ $report->job_title }}</span>
                        
                        @if($has_report == "1" and $report->user_id == auth()->user()->id and $die_line =="0")
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('meetings_reports.edit',$report->id) }}" class="btn btn-outline-primary btn-sm fw-semibold venobox" data-vbtype="iframe">
                                    <i class="fas fa-edit me-1"></i> 修改
                                </a>
                                
                                <form action="{{ route('meetings_reports.destroy',$report->id) }}" method="POST" id="delete{{ $report->id }}" class="form-report-delete m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm fw-semibold btn-report-delete-submit delete-btn2" data-form="delete{{ $report->id }}">
                                        <i class="fas fa-trash me-1"></i> 刪除
                                    </button>
                                </form>
                            </div>
                        @endif
                    </h3>
                    
                    <div class="card-body p-4 text-dark fs-5">
                        <p class="mb-0">
                            <?php $content = str_replace(chr(13) . chr(10), '<br>', $report->content);?>
                            {!! $content !!}
                        </p>
                    </div>
                    
                    @if(!empty($files))
                        <div class="card-footer bg-light border-top py-3 px-4">
                            <span class="fw-bold small text-secondary me-2">附件：</span>
                            <div class="d-inline-flex flex-wrap gap-1.5 align-items-center">
                                @foreach($files as $k=>$v)
                                    <?php
                                    $file = $school_code."/reports/".$report->id."/".$v;
                                    $file = str_replace('/','&',$file);
                                    ?>
                                    <a href="{{ url('file/'.$file) }}" class="btn btn-primary btn-sm fw-semibold"><i class="fas fa-download me-1"></i> {{ $v }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <?php $i++; ?>
            @endforeach
            
            <hr class="text-muted opacity-25">
        </div>

        <div class="col-lg-3">
            <div class="card border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden mb-4">
                <h5 class="card-header bg-light fs-6 fw-bold py-2.5 px-3 border-bottom text-dark">相關資訊</h5>
                <div class="card-body p-3">
                    @if($die_line == 1)
                        <div class="mb-2">
                            <span class="btn btn-danger btn-sm fw-bold px-3 py-1 w-100 shadow-none mb-2" disabled>
                                <i class="fas fa-lock me-1"></i> 已鎖定
                            </span>
                        </div>
                    @endif
                    <p class="lead fs-6 mb-0 text-secondary fw-semibold">
                        報告人次：<span class="text-dark fw-bold fs-5">{{ $meeting->reports->count() }}</span>
                    </p>
                </div>
            </div>
            
            <div class="sticky-top pt-5 mt-4 z-0">
                <div class="pt-2">
                    <div class="card border border-secondary border-opacity-25 shadow-lg rounded-3 overflow-hidden">
                        <h5 class="card-header bg-light fs-6 fw-bold py-2.5 px-3 border-bottom text-dark d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-list-ol me-2 text-secondary"></i>快速連結</span>
                        </h5>
                        
                        <div class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
                            <?php $i=1; ?>
                            @foreach($reports as $report)
                                <a href="#report{{ $i }}" class="list-group-item list-group-item-action py-2.5 px-3 fs-5 text-dark fw-medium text-truncate">
                                    <span class="text-secondary me-1 fs-6">{{ $i }}.</span> {{ $report->job_title }}
                                </a>
                                <?php $i++; ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div>
@endsection