@extends('layouts.master')

@section('title', '填報學生')

@section('content')
    <?php
    $active['index'] ="active";
    $active['admin'] ="";    
    ?>
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">填報學生-導師填報</h1>
            @include('report_students.nav')
            <hr class="text-muted opacity-25">
            
            <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-4">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-dark fw-bold">
                        <tr>
                            <th scope="col" class="py-3 px-4" style="width: 180px;">填報期限</th>
                            <th scope="col" class="py-3 px-3">填報名稱</th>
                            <th scope="col" class="py-3 px-3" style="width: 120px;">建立者</th>
                            <th scope="col" class="py-3 px-4 text-end" style="width: 280px;">動作與狀態</th>
                        </tr>
                    </thead>
                    <tbody>                
                        @foreach($report_students as $report_student)
                            <tr>
                                <td class="px-4 small">
                                    <div class="d-flex flex-column">
                                        <span class="text-secondary"><i class="far fa-calendar-alt me-1"></i>起：{{ $report_student->started_at }}</span>
                                        <span class="text-danger fw-semibold"><i class="far fa-calendar-times me-1"></i>迄：{{ $report_student->stopped_at }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-3 fw-semibold text-dark">
                                    {{ $report_student->name }}
                                    @if($report_student->disable == 1)
                                        <span class="badge bg-danger ms-1">已停止填報</span>
                                    @endif
                                </td>
                                
                                <td class="px-3 text-secondary small">
                                    {{ $report_student->user->name ?? '系統管理員' }}
                                </td>
                                
                                <td class="px-4 text-end"> 
                                    <div class="d-inline-flex align-items-center gap-2">
                                        
                                        {{-- 🎯 檢查是否有填報資料 (修正後的乾淨寫法) --}}
                                        @php
                                            $check_answers = \App\Models\ReportStudentAnswer::where('report_student_id', $report_student->id)
                                                ->where('user_id', auth()->id())
                                                ->exists();
                                        @endphp

                                        @if($check_answers)
                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1.5 small fw-medium">
                                                <i class="fas fa-check-circle me-1"></i>已有填報資料
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-2 py-1.5 small fw-medium">
                                                <i class="fas fa-exclamation-circle me-1"></i>尚未填報資料
                                            </span>
                                        @endif

                                        {{-- 填報按鈕功能 --}}
                                        @if(!isset($teacher_class[$report_student->semester]))       
                                            <small class="text-danger fw-medium">本學期非導師，無法填報</small>
                                        @else                    
                                            <a href="{{ route('report_students.teacher_fill', ['report_student' => $report_student->id]) }}" class="btn btn-primary btn-sm fw-bold venobox px-3 shadow-sm" data-vbtype="iframe">
                                                <i class="fas fa-pen me-1"></i>填報學生
                                            </a>                            
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>                
                </table>            
            </div>
             
        </div>
    </div> 
@endsection