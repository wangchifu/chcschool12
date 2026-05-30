@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-修改行事曆 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3"><i class="fas fa-calendar"></i> 校務行事曆-修改行事曆</h1>
            
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h4 class="h5 fw-bold mb-0 text-dark">行事曆資料</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('calendars.update',$calendar->id) }}" method="POST" id="this_form1">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <input type="text" name="content" value="{{ $calendar->content }}" class="form-control" required>
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
