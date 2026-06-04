@extends('layouts.master_clean')

@section('nav_post_active', 'active')

@section('title', '複製填報學生管理 | ')

@section('content')
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">填報學生 - 複製填報管理</h1>
            <h4>將複製原先填報的項目過來</h4>
            
            <div class="d-none">
                <form action="{{ route('report_students.do_copy_report_student',$report_student->id) }}" method="POST" id="this_form1">
                    @csrf
                </form>
            </div>

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
                                <input type="text" class="form-control form-control-sm" name="semester" form="this_form1" required autofocus value="{{ $report_student->semester }}">
                            </td>
                            <td class="px-3">
                                <input type="text" class="form-control form-control-sm" name="name" form="this_form1" required value="{{ $report_student->name }}">
                            </td>
                            <td class="px-3">
                                <input type="date" class="form-control form-control-sm" name="started_at" form="this_form1" required value="{{ $report_student->started_at }}">
                            </td>
                            <td class="px-3">
                                <input type="date" class="form-control form-control-sm" name="stopped_at" form="this_form1" required value="{{ $report_student->stopped_at }}">
                            </td>
                            <td class="px-3 text-end">
                                <button type="button" class="btn btn-success btn-sm fw-bold px-3 save-btn" data-form="this_form1">儲存複製</button>
                            </td>
                        </tr>
                    </tbody>                            
                </table>
                <hr>
                <h3>已有之項目：</h3>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2" style="max-width: 300px;">
                    @foreach($items as $item)
                        <li class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light border border-secondary border-opacity-10 shadow-sm transition-all item-hover-card">                            
                            <div class="d-flex align-items-center gap-2 text-dark">
                                <i class="fas fa-file-alt text-primary opacity-75 small"></i>
                                <span class="fw-medium small text-truncate" style="max-width: 200px;" title="{{ $item->name }}">
                                    {{ $item->name }}
                                </span>
                            </div>                            
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection