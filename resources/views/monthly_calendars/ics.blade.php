@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務月曆 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="mb-4">
                <h1 class="fw-bold text-dark mb-2">校務月曆</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('monthly_calendars.index') }}" class="text-decoration-none">校務月曆</a></li>
                        <li class="breadcrumb-item active" aria-current="page">確認匯入項目</li>
                    </ol>
                </nav>
            </div>

            <form action="{{ route('monthly_calendars.file_store') }}" method="POST" id="this_form1">
                @csrf

                <div class="d-flex align-items-center justify-content-between mb-3 bg-light p-2 px-3 rounded-3 border">
                    <div class="form-check mb-0 shadow-none">
                        <input class="form-check-input" type="checkbox" id="checkAll" checked style="cursor: pointer;">
                        <label class="form-check-label fw-bold text-secondary small" for="checkAll" style="cursor: pointer;">
                            全選 / 全不選
                        </label>
                    </div>
                    <button type="button" class="btn btn-success btn-sm fw-bold px-3 shadow-sm save-btn" data-form="this_form1">
                        <i class="fas fa-save me-1"></i> 把勾選的匯入
                    </button>
                </div>

                <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3">
                    <div class="card-header bg-dark bg-opacity-10 py-2 px-3">
                        <h6 class="mb-0 fw-bold text-dark small">
                            <i class="far fa-list-alt me-2"></i> 偵測到的 Google 日曆行程預覽 (僅顯示今日及未來的行程)
                        </h6>
                    </div>
                    
                    <div class="card-body bg-light bg-opacity-25 p-3">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
                            @foreach($item as $v)
                                @foreach($v as $k => $v2)
                                
                                    @if(date('Y-m-d', strtotime($v2['DTSTART'])) < date('Y-m-d'))
                                        @continue
                                    @endif

                                    <div class="col">
                                        <label class="d-flex align-items-center justify-content-between p-2 px-3 bg-white border rounded shadow-sm h-100" style="cursor: pointer;">
                                            <div class="me-2" style="min-width: 0;">
                                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 11px; padding: 2px 5px;">
                                                    {{ $v2['DTSTART'] }}
                                                </span>
                                                <div class="text-dark fw-semibold mt-1 text-truncate" style="font-size: 14px;" title="{{ $v2['SUMMARY'] }}">
                                                    {{ $v2['SUMMARY'] }}
                                                </div>
                                            </div>
                                            
                                            <div class="form-check form-switch mb-0 flex-shrink-0">
                                                <input type="checkbox" class="form-check-input input-item input-items" id="exampleCheck{{ $k }}" checked name="items[{{ $v2['DTSTART'] }}]" value="{{ $v2['SUMMARY'] }}" style="cursor: pointer;">
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-5">
                    <button type="button" class="btn btn-success btn-sm fw-bold px-4 py-2 shadow-sm save-btn" data-form="this_form1">
                        <i class="fas fa-save me-1"></i> 把勾選的匯入
                    </button>
                </div>

            </form>
        </div>
    </div>
    <script nonce="{{ $csp_nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('input[name^="items"]');

            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = checkAll.checked;
                    });
                });
            }
        });
    </script>
@endsection