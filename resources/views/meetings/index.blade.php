@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '會議文稿 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="mb-4">
                <h1 class="fw-bold text-dark mb-2">會議文稿</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                        <li class="breadcrumb-item active" aria-current="page">會議列表</li>
                    </ol>
                </nav>
            </div>
            
            @can('create',\App\Models\Meeting::class)
                <div class="mb-3 text-start">
                    <a href="{{ route('meetings.create') }}" class="btn btn-success btn-sm fw-bold px-3 shadow-sm venobox" data-vbtype="iframe">
                        <i class="fas fa-plus me-1"></i> 新增會議
                    </a>
                </div>
            @endcan
            
            <div class="table-responsive bg-white rounded-3 shadow-sm border overflow-hidden mb-4">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">會議日期</th>
                            <th>會議名稱</th>
                            <th>報告人次</th>
                            <th class="text-end pe-3">動作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($meetings as $meeting)
                            <?php
                            $open_date = str_replace('-','',$meeting->open_date);
                            $die_line = (date('Ymd') > $open_date)?"1":"0";
                            ?>
                            <tr>
                                <td class="ps-3 fw-semibold text-secondary">
                                    {{ $meeting->open_date }} {{ get_chinese_weekday($meeting->open_date) }}
                                </td>
                                <td>
                                    @if($die_line)
                                        <span class="btn btn-danger btn-sm py-0 px-1.5 me-1" disabled>
                                            <i class="fas fa-lock" title="會議已截止"></i>
                                        </span>
                                    @endif
                                    <a href="{{ route('meetings.show',$meeting->id) }}" class="text-decoration-none fw-bold text-dark">
                                        {{ $meeting->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2.5 py-1.5">
                                        {{ $meeting->reports->count() }} 人次
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex gap-1">
                                        @can('update',$meeting)
                                            <a href="{{ route('meetings.edit',$meeting->id) }}" class="btn btn-outline-primary btn-sm fw-semibold venobox" data-vbtype="iframe">
                                                <i class="fas fa-edit me-1"></i> 修改
                                            </a>
                                        @endcan
                                        
                                        @can('update',$meeting)
                                            <form action="{{ route('meetings.destroy',$meeting->id) }}" method="POST" id="delete{{ $meeting->id }}" class="form-meeting-delete m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm fw-semibold btn-meeting-delete-submit delete-btn2" data-form="delete{{ $meeting->id }}">
                                                    <i class="fas fa-trash me-1"></i> 刪除
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center my-4">
                {{ $meetings->links('layouts.pagination') }}
            </div>
        </div>
    </div>
@endsection