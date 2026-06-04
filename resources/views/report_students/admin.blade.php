@extends('layouts.master')

@section('title', '填報學生')

@section('content')
    <?php
    $active['index'] ="";
    $active['admin'] ="active";    
    ?>
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">填報學生-填報管理</h1>
            @include('report_students.nav')
            <hr class="text-muted opacity-25">
            
            <div class="d-none">
                <form action="{{ route('report_students.store_report_student') }}" method="POST" id="this_form1">
                    @csrf
                </form>
                
                @foreach($now_report as $k=>$v)
                    <form action="{{ route('report_students.update_report_student', ['report_student' => $k]) }}" method="POST" id="form_update_now_{{ $k }}">
                        @csrf
                        <input type="hidden" name="action" value="更新" form="form_update_now_{{ $k }}">
                    </form>
                    <form action="{{ route('report_students.update_report_student', ['report_student' => $k]) }}" method="POST" id="form_copy_now_{{ $k }}">
                        @csrf
                        <input type="hidden" name="action" value="複製" form="form_copy_now_{{ $k }}">
                    </form>
                @endforeach
                
                @foreach($not_report as $k=>$v)
                    <form action="{{ route('report_students.update_report_student', ['report_student' => $k]) }}" method="POST" id="form_update_old_{{ $k }}">
                        @csrf
                        <input type="hidden" name="action" value="更新" form="form_update_old_{{ $k }}">
                    </form>
                    <form action="{{ route('report_students.update_report_student', ['report_student' => $k]) }}" method="POST" id="form_copy_old_{{ $k }}">
                        @csrf
                        <input type="hidden" name="action" value="複製" form="form_copy_old_{{ $k }}">
                    </form>
                @endforeach
            </div>

            <div class="card border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-body p-4">
                    
                    <h4 class="fw-bold text-dark mb-3">一、建立填報</h4>
                    
                    <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-4">
                        <table class="table align-middle mb-0">
                            <thead class="table-warning text-dark fw-bold">
                                <tr>
                                    <th scope="col" class="py-3 px-3">學期</th>
                                    <th scope="col" class="py-3 px-3">填報名稱</th>
                                    <th scope="col" class="py-3 px-3">開始日期</th>
                                    <th scope="col" class="py-3 px-3">結束日期</th>
                                    <th scope="col" class="py-3 px-3 text-end">動作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-3">
                                        <input type="text" class="form-control form-control-sm" name="semester" form="this_form1" required value="{{ $semester }}">
                                    </td>
                                    <td class="px-3">
                                        <input type="text" class="form-control form-control-sm" name="name" form="this_form1" required autofocus>
                                    </td>
                                    <td class="px-3">
                                        <input type="date" class="form-control form-control-sm" name="started_at" form="this_form1" required>
                                    </td>
                                    <td class="px-3">
                                        <input type="date" class="form-control form-control-sm" name="stopped_at" form="this_form1" required>
                                    </td>
                                    <td class="px-3 text-end">
                                        <button type="button" class="btn btn-success btn-sm fw-bold px-3 save-btn" data-form="this_form1">新增填報</button>
                                    </td>
                                </tr>
                            </tbody>                            
                        </table>
                    </div>
                    
                    <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-4">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-success text-dark fw-bold">
                                <tr>
                                    <th scope="col" class="py-3 px-3">學期</th>
                                    <th scope="col" class="py-3 px-3">填報名稱</th>
                                    <th scope="col" class="py-3 px-3">題目</th>
                                    <th scope="col" class="py-3 px-3">開始日期</th>
                                    <th scope="col" class="py-3 px-3">結束日期</th>
                                    <th scope="col" class="py-3 px-3">建立者</th>
                                    <th scope="col" class="py-3 px-3 text-end">動作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($now_report as $k=>$v)
                                    <tr>
                                        <td class="px-3">
                                            <input type="text" class="form-control form-control-sm" name="semester" form="form_update_now_{{ $k }}" value="{{ $v['semester'] }}">
                                        </td>
                                        <td class="px-3">
                                            <input type="text" class="form-control form-control-sm fw-semibold" name="name" form="form_update_now_{{ $k }}" value="{{ $v['name'] }}">
                                        </td>
                                        <td class="px-3">
                                            <?php $items = \App\Models\ReportStudentItem::where('report_student_id',$k)->get(); ?>
                                            <ul class="list-unstyled mb-0 small">
                                                @foreach($items as $item)
                                                    <li class="d-flex align-items-center gap-1">
                                                        <span>{{ $item->name }}</span>
                                                        <a href="#!" class="text-danger delete-btn1" data-url="{{ route('report_students.admin_item_delete',$item->id) }}">
                                                            <i class="fas fa-times-circle"></i>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="px-3">
                                            <input type="date" class="form-control form-control-sm" name="started_at" form="form_update_now_{{ $k }}" value="{{ $v['started_at'] }}">
                                        </td>
                                        <td class="px-3">
                                            <input type="date" class="form-control form-control-sm" name="stopped_at" form="form_update_now_{{ $k }}" value="{{ $v['stopped_at'] }}">                                            
                                        </td>
                                        <td class="px-3 text-secondary small">{{ $v['user'] }}</td>
                                        <td class="px-3 text-end">
                                            <div class="d-inline-flex gap-1">
                                                <a href="{{ route('report_students.admin_item', ['report_student' => $k]) }}" class="btn btn-primary btn-sm fw-semibold venobox" data-vbtype="iframe">項目管理</a>
                                                
                                                <button type="button" class="btn btn-sm btn-success fw-semibold save-btn" form="form_update_now_{{ $k }}" data-form="form_update_now_{{ $k }}">更新</button>
                                                <a href="{{ route('report_students.copy_report_student',$k)}}" class="btn btn-sm btn-info fw-semibold text-white venobox" data-vbtype="iframe">複製</button>                                            
                                                
                                                <a href="{{ route('report_students.admin_result',$k) }}" class="btn btn-sm btn-dark fw-semibold venobox" data-vbtype="iframe">成果</a>                                       
                                                <a href="#!" class="btn btn-sm btn-danger fw-semibold delete-btn1" data-url="{{ route('report_students.delete_report_student',$k) }}">刪除</a>
                                            </div>
                                        </td>
                                    </tr>             
                                @endforeach
                            </tbody>
                        </table>
                    </div>                    
                    
                    <div class="mb-4">
                        <button class="btn btn-primary fw-bold px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOldReports" aria-expanded="false" aria-controls="collapseOldReports">
                            顯示過期填報
                        </button>
                    </div>
                    
                    <div class="collapse mb-4" id="collapseOldReports">
                        <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm">
                            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                                <thead class="table-danger text-dark fw-bold">
                                    <tr>
                                        <th scope="col" class="py-3 px-3">學期</th>
                                        <th scope="col" class="py-3 px-3">填報名稱</th>
                                        <th scope="col" class="py-3 px-3">題目</th>
                                        <th scope="col" class="py-3 px-3">開始日期</th>
                                        <th scope="col" class="py-3 px-3">結束日期</th>
                                        <th scope="col" class="py-3 px-3">建立者</th>
                                        <th scope="col" class="py-3 px-3 text-end">動作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($not_report as $k=>$v)
                                        <tr>
                                            <td class="px-3">
                                                <input type="text" class="form-control form-control-sm" name="semester" form="form_update_old_{{ $k }}" value="{{ $v['semester'] }}">
                                            </td>
                                            <td class="px-3">
                                                <input type="text" class="form-control form-control-sm fw-semibold" name="name" form="form_update_old_{{ $k }}" value="{{ $v['name'] }}">
                                            </td>
                                            <td class="px-3">
                                                <?php $items = \App\Models\ReportStudentItem::where('report_student_id',$k)->get(); ?>
                                                <ul class="list-unstyled mb-0 small">
                                                    @foreach($items as $item)
                                                        <li class="d-flex align-items-center gap-1">
                                                            <span>{{ $item->name }}</span>
                                                            <a href="#!" class="text-danger delete-btn1" data-url="{{ route('report_students.admin_item_delete',$item->id) }}">
                                                                <i class="fas fa-times-circle"></i>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="px-3">
                                                <input type="date" class="form-control form-control-sm" name="started_at" form="form_update_old_{{ $k }}" value="{{ $v['started_at'] }}">
                                            </td>
                                            <td class="px-3">
                                                <input type="date" class="form-control form-control-sm" name="stopped_at" form="form_update_old_{{ $k }}" value="{{ $v['stopped_at'] }}">
                                            </td>
                                            <td class="px-3 text-secondary small">{{ $v['name'] }}</td>
                                            <td class="px-3 text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="{{ route('report_students.admin_item', ['report_student' => $k]) }}" class="btn btn-primary btn-sm fw-semibold venobox" data-vbtype="iframe">項目管理</a>
                                                    
                                                    <button type="button" class="btn btn-sm btn-success fw-semibold save-btn" form="form_update_old_{{ $k }}" data-form="form_update_old_{{ $k }}">更新</button>                                                    
                                                    <a href="{{ route('report_students.copy_report_student',$k)}}" class="btn btn-sm btn-info fw-semibold text-white venobox" data-vbtype="iframe">複製</button>                                            
                                                    
                                                    <a href="{{ route('report_students.admin_result',$k) }}" class="btn btn-sm btn-dark fw-semibold venobox" data-vbtype="iframe">成果</a>                                       
                                                    <a href="#!" class="btn btn-sm btn-danger fw-semibold delete-btn1" data-url="{{ route('report_students.delete_report_student',$k) }}">刪除</a>                                        
                                                </div>
                                            </td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>                    
                    
                    <hr class="text-muted opacity-25 my-4">
                    
                    <h4 class="fw-bold text-dark mb-3">二、學生資料</h4>
                    
                    <form action="{{ route('report_students.stu_import') }}" method="POST" enctype="multipart/form-data" id="this_form2" class="mb-3">
                        @csrf
                        <div class="card bg-light border border-secondary border-opacity-10 rounded-3 p-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto d-flex align-items-center gap-2">
                                    <label for="import_semester" class="form-label fw-bold mb-0 text-nowrap">學年</label>
                                    <input type="text" id="import_semester" name="semester" value="{{ get_date_semester(date('Y-m-d')) }}" class="form-control form-control-sm text-center" required maxlength="4" style="max-width: 80px;">
                                </div>
                                <div class="col-md-5">
                                    <input type="file" name="file" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-success btn-sm fw-bold px-3 save-btn" data-form="this_form2">匯入學生</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    @include('layouts.errors')
                    
                    <div class="mb-4">
                        <a href="{{ asset('images/cloudschool_club.png') }}" target="_blank" class="btn btn-link text-decoration-none small p-0">
                            請先至 cloudschool 下載列表
                        </a>
                    </div>                      
                    
                    <hr class="text-muted opacity-25 my-4">                    
                    
                    <div class="mb-3">
                        <button class="btn btn-primary fw-bold px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClassData" aria-expanded="false" aria-controls="collapseClassData">
                            顯示各學期已匯入學生班級資料
                        </button>
                    </div>
                    
                    <div class="collapse" id="collapseClassData">
                        <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-warning text-dark fw-bold">
                                    <tr>
                                        <th scope="col" class="py-3 px-4">學期</th>
                                        <th scope="col" class="py-3 px-3">班級數</th>
                                        <th scope="col" class="py-3 px-4">學生數</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($class_num as $k=>$v)                      
                                        <tr>
                                            <td class="px-4 fw-semibold text-dark">{{ $k }}</td>
                                            <td class="px-3">
                                                @if(isset($class_num[$k]))
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span>{{ $class_num[$k] }}</span>
                                                        <a href="{{ route('report_students.stu_adm_more',['semester'=>$k,'student_class_id'=>null]) }}" class="btn btn-info btn-sm text-white fw-semibold venobox" data-vbtype="iframe">詳細資料</a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4">
                                                @if(isset($club_student_num[$k]))
                                                    {{ $club_student_num[$k] }}   
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
    </div>
@endsection