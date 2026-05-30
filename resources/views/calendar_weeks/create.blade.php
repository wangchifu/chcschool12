@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-週次設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3">校務行事曆-週次設定</h1>
            
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h4 class="h5 fw-bold mb-0 text-dark">
                        {{ $semester }}學期 週次設定，請新增或移除多餘週次(保持空著)
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('calendar_weeks.store') }}" method="POST" id="this_form1">
                        @csrf
                        
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-dark text-center">
                                <tr>
                                    <th width="120" scope="col">操作</th>
                                    <th width="150" scope="col">週次</th>
                                    <th scope="col">起迄</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="text" name="week[d]" class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="text" name="start_end[d]" class="form-control form-control-sm">
                                    </td>
                                </tr>
                                @foreach($start_end as $k=>$v)
                                    <tr>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm shadow-sm btn-clean-row" 
                                                    data-target-week="week1{{ $k }}" 
                                                    data-target-date="week2{{ $k }}">
                                                清除此列
                                            </button>
                                        </td>
                                        <td>
                                            <input type="text" name="week[{{ $k }}]" value="{{ $k }}" class="form-control form-control-sm text-center" id="week1{{ $k }}">
                                        </td>
                                        <td>
                                            <input type="text" name="start_end[{{ $k }}]" value="{{ substr($v[0],5,5).'~'.substr($v[6],5,5) }}" class="form-control form-control-sm" id="week2{{ $k }}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <input type="hidden" name="semester" value="{{ $semester }}">
                        
                        <button type="button" class="btn btn-primary btn-sm shadow-sm save-btn" data-form="this_form1">
                            <i class="fas fa-save me-1"></i> 儲存設定
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ $csp_nonce }}">
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. 監聽所有「清除此列」按鈕的點擊事件
            document.querySelectorAll('.btn-clean-row').forEach(function (button) {
                button.addEventListener('click', function () {
                    const weekInputId = this.getAttribute('data-target-week');
                    const dateInputId = this.getAttribute('data-target-date');
                    
                    const weekInput = document.getElementById(weekInputId);
                    const dateInput = document.getElementById(dateInputId);
                    
                    if (weekInput) weekInput.value = '';
                    if (dateInput) dateInput.value = '';
                });
            });

            // 2. 🎯 不依賴按鈕 Class，直接透過表單的 id="save" 來監聽 submit 送出事件
            const saveForm = document.getElementById('save');
            if (saveForm) {
                saveForm.addEventListener('submit', function (e) {
                    if (!confirm('確定嗎？')) {
                        e.preventDefault(); // 使用者選取消時，直接攔截表單送出行為
                    }
                });
            }

        });
    </script>
@endsection