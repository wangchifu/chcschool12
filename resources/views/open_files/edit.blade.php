@extends('layouts.master_clean')
<?php $openfile_name = (empty($setup->openfile_name)) ? "檔案庫" : $setup->openfile_name; ?>
@section('title', '編輯'.$openfile_name.' | ')

@section('content')
    @include('layouts.errors')
    
    {{-- 改為原生 HTML 表單宣告，並帶入必要方法 --}}
    <form action="{{ route('open_files.update', $upload->id) }}" method="POST" id="this_form1">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <h1>修改名稱</h1>
            <table class="table table-striped align-middle" style="word-break:break-all;">
                <thead class="table-light">
                    <tr>
                        <th>名稱</th>
                        <th style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{-- 名稱輸入框 --}}
                            <input type="text" name="name" id="name" class="form-control mb-2" value="{{ $upload->name }}" required placeholder="名稱">
                            
                            {{-- 雲端連結網址輸入框 (當 type 為 3 時顯示) --}}
                            @if($upload->type == 3)
                                <input type="text" name="url" id="url" class="form-control" value="{{ $upload->url }}" required placeholder="連結">
                            @endif
                        </td>
                        <td>
                            {{-- 移除內聯 onclick 事件，交由下方監聽器接管 --}}
                            <button type="button" class="btn btn-primary btn-sm w-100 save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        {{-- 隱藏欄位 --}}
        <input type="hidden" name="path" value="{{ $path }}">
    </form>
@endsection