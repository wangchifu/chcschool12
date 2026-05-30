@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-新增行事曆 | ')

@section('content')    
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3"><i class="fas fa-calendar"></i> 校務行事曆-新增行事曆</h1>
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h4 class="h5 fw-bold mb-0 text-dark">行事曆資料</h4>
                </div>
                
                <form action="{{ route('calendars.store') }}" method="POST" id="this_form1">
                    @csrf
                    
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="calendar_kind" class="form-label"><strong class="text-danger">1.先選校務類別</strong></label>
                            
                            <select name="calendar_kind" id="calendar_kind" class="form-select">
                                @foreach(config('chcschool.calendar_kind') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <h3 class="h5 fw-bold text-primary mb-3 border-start border-4 border-primary ps-2">{{ $semester }} 學期的週次</h3>
                        <table class="table table-hover align-middle">
                            <thead class="table-dark text-center">
                            <tr>
                                <th width="80" scope="col">週別</th>
                                <th width="120" scope="col">起迄</th>
                                <th scope="col">2.再填內容</th>
                            </tr>
                            </thead>
                            <tbody id="calendar-tbody">
                            @foreach($calendar_weeks as $calendar_week)
                                <tr>
                                    <td class="text-nowrap text-center fw-bold">
                                        第 {{ $calendar_week->week }} 週
                                    </td>
                                    <td class="text-nowrap text-center text-muted small">
                                        {{ $calendar_week->start_end }}
                                    </td>
                                    <td>
                                        <div id="show{{ $calendar_week->week }}">
                                            <p class="calendar-item-group">
                                                <label for="date{{ $calendar_week->week }}_0">本週行事1：</label>
                                                <input type="date" id="date{{ $calendar_week->week }}_0" name="date{{ $calendar_week->week }}[]" maxlength="10" width="180" placeholder="非必填">
                                                <small class="text-secondary">*(非必填)左邊指定日期可順便填入校務月曆*</small>

                                                <input type="text" name="w{{ $calendar_week->week }}_content[]" class="form-control" placeholder="填寫本週行事1">
                                                
                                                <button type="button" class="btn btn-outline-secondary btn-sm btn-add-row" data-week="{{ $calendar_week->week }}">+增加</button>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        
                        <input type="hidden" name="semester" value="{{ $semester }}">
                        
                        @if(!empty($calendar_weeks))
                            <button type="button" class="btn btn-primary btn-sm shadow-sm save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存設定
                            </button>
                        @else
                            <a href="#!" class="btn btn-danger btn-sm shadow-sm disabled">尚未設定週次</a>
                        @endif
                    </div>
                    
                </form>
            </div>
        </div>
    </div>

<script nonce="{{ $csp_nonce }}">
    document.addEventListener('DOMContentLoaded', function () {
        
        // 🎯 改用動態 Object 計數器，完美防禦 23 週以後 item[t]++ 變成 NaN 導致沒反應的問題
        var itemCounters = {};

        const tbody = document.getElementById('calendar-tbody');
        
        if (tbody) {
            // 使用非侵入式監聽，監聽整個 tbody 的點擊事件
            tbody.addEventListener('click', function (e) {
                
                // 1. 處理【+增加】按鈕點擊
                const addBtn = e.target.closest('.btn-add-row');
                if (addBtn) {
                    const t = addBtn.getAttribute('data-week');
                    const container = document.getElementById('show' + t);
                    
                    if (container) {
                        // 初始化該週次的計數
                        if (!itemCounters[t]) {
                            itemCounters[t] = 1;
                        }
                        
                        itemCounters[t]++;
                        var currentCount = itemCounters[t];
                        var n = currentCount - 1;

                        // 🎯 採用標準字串拼接，完美避開 Blade 引擎對 $ 符號的誤判解析
                        var content = '<p class="calendar-item-group">' +
                            '<label for="var' + currentCount + '">本週行事' + currentCount + '：</label> ' +
                            '<input type="date" id="date' + t + '_' + n + '" name="date' + t + '[]" maxlength="10" width="180"> ' +
                            '<small class="text-secondary">*上面指定日期可順便填入校務月曆*</small>' +
                            '<input type="text" name="w' + t + '_content[]" class="form-control" placeholder="填寫本週行事' + currentCount + '"> ' +
                            // 🎯 移除內聯 onclick，改綁 btn-remove-row
                            '<i class="fas fa-trash text-danger btn-remove-row" style="cursor:pointer;"></i>' +
                            '</p>';
                        
                        // 原生的 jQuery 動態追加與初始化 datepicker 邏輯
                        $(container).append(content);
                        $('#date' + t + '_' + n).datepicker({
                            uiLibrary: 'bootstrap4',
                            format: 'yyyy-mm-dd',
                            locale: 'zh-TW',
                        });
                    }
                }

                // 2. 處理動態產生的【垃圾桶刪除】點擊
                const removeBtn = e.target.closest('.btn-remove-row');
                if (removeBtn) {
                    // 找到包裹此列欄位的段落標籤並移除
                    const pGroup = removeBtn.closest('.calendar-item-group');
                    if (pGroup) {
                        pGroup.remove();
                    }
                }
            });
        }        

    });
</script>
@endsection