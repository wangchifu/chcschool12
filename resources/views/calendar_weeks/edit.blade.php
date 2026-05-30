@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-週次修改 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3">校務行事曆-週次設定</h1>             
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h4 class="h5 fw-bold mb-0 text-dark">{{ $semester }}學期 週次修改</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('calendar_weeks.update') }}" method="POST" id="this_form1">
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            @foreach($calendar_weeks as $calendar_week)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light fw-bold text-secondary">
                                            第 {{ $calendar_week->week }} 週
                                        </span>
                                        <input type="text" 
                                               name="week_id[{{ $calendar_week->id }}]" 
                                               value="{{ $calendar_week->start_end }}" 
                                               class="form-control" 
                                               maxlength="11" 
                                               required>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="btn btn-primary btn-sm shadow-sm save-btn" data-form="this_form1">
                            <i class="fas fa-save me-1"></i> 儲存設定
                        </button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection