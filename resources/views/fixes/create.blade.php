@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '新增報修 | ')

@section('content')
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">新增報修</h1>            
            
            <form action="{{ route('fixes.store') }}" method="POST" enctype="multipart/form-data" id="this_form1">
                @csrf
                
                <div class="card border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden my-4">
                    <h3 class="card-header bg-light fs-5 fw-bold py-3 px-4 text-dark border-bottom">
                        報修資料
                    </h3>
                    <div class="card-body p-4">
                        @include('layouts.errors')
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-primary">填 EMail 可收回覆信件</label>
                            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="form-control">
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold text-dark">類別*</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="" disabled selected hidden></option>
                                @foreach($types as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold text-dark">標題*</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="請輸入標題" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold text-dark">內文*</label>
                            <textarea name="content" id="content" class="form-control" rows="10" placeholder="請寫清楚發生位置和情況" required>{{ '設備地點：'."\r\n".'待修狀況：' }}</textarea>
                        </div>
                        
                        <div class="d-flex gap-2 pt-2">                            
                            <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm btn-fix-store-submit save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection