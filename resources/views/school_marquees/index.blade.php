@extends('layouts.master')

@section('title', '校園跑馬燈 | ')

@section('content')        
    {{-- 統一 py-4 增加間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3"><i class="fas fa-running me-2"></i>校園跑馬燈</h1>
            
            {{-- 分頁導覽 --}}
            <ul class="nav nav-tabs mb-4">
                @if(auth()->user()->admin)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_marquee.setup') }}"><i class="fas fa-cog"></i> 管理設定</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_marquee.index') }}"><i class="fas fa-list"></i> 跑馬燈列表</a>
                </li>
            </ul>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title mb-0 text-center fw-bold">
                        跑馬燈預覽與列表
                    </h3>
                </div>
                <div class="card-body">                    
                    {{-- 跑馬燈預覽區域 --}}
                    @if($school_marquees->count() > 0)                    
                        @php
                            $m_width = $setup->school_marquee_width ?? "12";
                            $m_color = $setup->school_marquee_color ?? "warning";
                            $m_behavior = $setup->school_marquee_behavior ?? "scroll";
                            $m_direction = $setup->school_marquee_direction ?? "up";
                            $m_speed = $setup->school_marquee_scrollamount ?? "2";
                        @endphp

                        @if($school_marquee2s->count() > 0)
                            <div class="row justify-content-center mb-5">
                                <div class="col-lg-{{ $m_width }}">
                                    <div class="alert alert-{{ $m_color }} shadow-sm">
                                        <h6 class="alert-heading small mb-2 text-muted fw-bold">效果預覽：</h6>
                                        <marquee behavior="{{ $m_behavior }}" direction="{{ $m_direction }}" scrollamount="{{ $m_speed }}" style="height: 24px;">
                                            @foreach($school_marquees as $marquee)
                                                @if($m_direction == "up" || $m_direction == "down")
                                                    <p class="mb-0">{{ $marquee->title }}</p>                                                
                                                @else
                                                    <span class="me-5"><i class="fas fa-bullhorn me-1 text-danger"></i> {{ $marquee->title }}</span>
                                                @endif
                                            @endforeach
                                        </marquee>
                                    </div>
                                </div>
                            </div>                                                       
                        @endif
                    @endif

                    {{-- 新增按鈕 --}}
                    <div class="mb-3">
                        <a href="{{ route('school_marquee.create') }}" class="btn btn-success btn-sm px-3 shadow-sm venobox" data-vbtype="iframe">
                            <i class="fas fa-plus me-1"></i> 新增跑馬燈
                        </a>
                    </div>

                    {{-- 列表表格 --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle" style="word-break:break-all;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>標題</th>
                                    <th style="width: 150px;">開始日期</th>
                                    <th style="width: 150px;">結束日期</th>
                                    <th style="width: 120px;">上架者</th>
                                    <th class="text-center" style="width: 180px;">動作</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($school_marquees as $school_marquee)
                                @php
                                    $today = date('Y-m-d');
                                    $is_expired = ($school_marquee->stop_date < $today || $school_marquee->start_date > $today);
                                    $row_class = $is_expired ? "text-muted opacity-75" : "";
                                    $title_style = $is_expired ? "text-decoration: line-through;" : "";
                                @endphp
                                <tr class="{{ $row_class }}">
                                    <td class="text-secondary ps-3">{{ $school_marquee->id }}</td>
                                    <td>
                                        <span class="fw-bold" style="{{ $title_style }}">
                                            {{ $school_marquee->title }}
                                        </span>
                                        @if($is_expired)
                                            <span class="badge bg-secondary ms-1">未排程中</span>
                                        @endif
                                    </td>
                                    <td>{{ $school_marquee->start_date }}</td>
                                    <td>{{ $school_marquee->stop_date }}</td>
                                    <td>{{ $school_marquee->user->name }}</td>
                                    <td class="text-center">
                                        @if($school_marquee->user_id == auth()->user()->id || auth()->user()->admin)
                                            <div class="btn-group">
                                                <a href="{{ route('school_marquee.edit',$school_marquee->id) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                                    <i class="fas fa-edit"></i> 修改
                                                </a>
                                                
                                                {{-- 套用您全域的 delete-btn1 邏輯 --}}
                                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn2"                                                         
                                                        data-form="delete_form{{ $school_marquee->id }}">
                                                    <i class="fas fa-trash"></i> 刪除
                                                </button>
                                            </div>

                                            <form action="{{ route('school_marquee.destroy', $school_marquee->id) }}" method="POST" id="delete_form{{ $school_marquee->id }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection