@extends('layouts.master_clean')

@section('title', '編輯項目 | ')

@section('content')
<div class="row justify-content-center g-4 my-3">
    <div class="col-md-11">
        
        <br>
        <h1>{{ $report_student->semester }} {{ $report_student->name }}</h1>
        
        <form action="{{ route('report_students.admin_item_store') }}" method="post" id="this_form1">
            @csrf
            
            <table class="table table-striped">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 100px;">序號</th>
                        <th>題目</th>
                        <th style="width: 100px;">操作</th>
                    </tr>
                </thead>
                <tbody id="questionBody">
                    {{-- 🎯 核心邏輯：計算題目數量，若不足 5 行，後面自動用空白新輸入框補滿 --}}
                    @php
                        $maxRows = 5;
                        $itemsCount = count($items);
                        $totalRows = $itemsCount > $maxRows ? $itemsCount : $maxRows;
                    @endphp

                    @for ($i = 0; $i < $totalRows; $i++)
                        @php
                            $item = isset($items[$i]) ? $items[$i] : null;
                            $rowNum = $i + 1;
                        @endphp
                        
                        @if ($item)
                            {{-- 🎯 狀況 A：原本就已經有值的舊題目行（不用 input，直接秀值） --}}
                            <tr class="old-item-row">
                                <td class="row-index">{{ $rowNum }}</td>
                                <td>
                                    <span class="fw-medium text-dark">{{ $item->name }}</span>
                                    
                                    <input type="hidden" name="old_item_ids[]" value="{{ $item->id }}">
                                </td>
                                <td>
                                    </td>                
                            </tr>
                        @else
                            {{-- 🎯 狀況 B：沒有值的空白行，供使用者填寫新題目 --}}
                            <tr class="new-item-row">
                                <td class="row-index">{{ $rowNum }}</td>
                                <td>
                                    <input type="text" class="form-control" value="" name="name[]" placeholder="請輸入全新題目內容...">
                                </td>
                                <td>
                                    @if ($rowNum > 1)
                                        <button type="button" class="btn btn-danger btn-sm delete-row">移除</button>
                                    @endif
                                </td>                
                            </tr>
                        @endif
                    @endfor
                </tbody>
            </table>    
            
            <input type="hidden" name="report_student_id" value="{{ $report_student->id }}">
            <a href="#!" id="addRow" class="btn btn-primary">多一行題目</a>
            
            <button type="button" class="btn btn-success save-btn" data-form="this_form1">儲存</button>
        </form>
        
    </div>
</div>

{{-- 🎯 完美綁定 CSP Nonce，只保留最純粹的動態增刪列 JS --}}
<script nonce="{{ $csp_nonce }}">   
// 1. 動態新增行功能（一律生出 class="new-item-row" 的可輸入新行）
document.getElementById('addRow').addEventListener('click', function(e) {
    e.preventDefault();
    const tbody = document.getElementById('questionBody');
    if (!tbody) return;

    const rowCount = tbody.rows.length + 1;

    const newRow = `
        <tr class="new-item-row">
            <td class="row-index">${rowCount}</td>
            <td><input type="text" class="form-control" value="" name="name[]" placeholder="請輸入全新題目內容..."></td>
            <td><button type="button" class="btn btn-danger btn-sm delete-row">移除</button></td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('beforeend', newRow);
    tbody.lastElementChild.querySelector('input').focus();
});

// 2. 刪除行功能
document.getElementById('questionBody').addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-row')) {
        const row = e.target.closest('tr');
        row.remove();
        
        // 重新計算所有行的序號
        reindexRows();
    }
});

// 3. 重新編號與新行防呆處理的函數
function reindexRows() {
    const rows = document.querySelectorAll('#questionBody tr');
    rows.forEach((row, index) => {
        const indexCell = row.querySelector('.row-index');
        if (indexCell) {
            indexCell.innerText = index + 1;
        }

        // 如果全部刪到只剩下一行，而且它剛好是新行，就把移除按鈕拔掉
        const actionCell = row.cells[2];
        if (index === 0 && actionCell && row.classList.contains('new-item-row')) {
            actionCell.innerHTML = ''; 
        }
    });
}
</script>    
@endsection