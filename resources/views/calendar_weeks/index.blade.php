@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-週次設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3">校務行事曆-週次設定</h1>
            
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h4 class="h5 fw-bold mb-0 text-dark">新學期開學日設定</h4>
                </div>
                <div class="card-body p-4">
                    <form name="myform" action="{{ route('calendar_weeks.create') }}" method="post" id="this_form" class="mb-4">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="open_date" class="form-label fw-bold text-secondary">
                                請輸入第一週的週日
                            </label>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-5">
                                    <input type="date" name="open_date" id="open_date" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="set_week" class="form-label fw-bold text-secondary">
                                請輸入要設定的週次
                            </label>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-5">
                                    <input type="number" name="set_week" id="set_week" value="22" class="form-control form-control-sm" placeholder="請輸入週數" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <button class="btn btn-success btn-sm shadow-sm d-inline-flex align-items-center">
                                <i class="fas fa-cog me-1"></i> 開始設定
                            </button>
                        </div>
                    </form>
                    
                    <h5 class="h6 fw-bold mb-3 text-primary border-start border-4 border-primary ps-2">已設定之學期列表</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-dark text-center">
                            <tr>
                                <th scope="col">已設定之學期</th>
                                <th width="120" scope="col">動作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($semesters as $v)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $v }}
                                    </td>
                                    <td class="text-center">
                                        @auth
                                            @if(auth()->user()->admin==1)
                                                <a href="#!" class="btn btn-danger btn-sm shadow-sm delete-btn1" data-url="{{ route('calendar_weeks.destroy',$v) }}" data-msg="確定要刪除學期【{{ $v }}】的所有週次設定嗎？">刪除</a>
                                            @endif
                                        @endauth
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