@extends('layouts.master_clean')

@section('title', '編輯樹狀連結 | ')

@section('content')
    @include('layouts.errors')
    
    {{-- 原生 HTML 表單宣告，並補上 CSRF 及 PATCH 方法 --}}
    <form action="{{ route('trees.update', $tree->id) }}" method="post" id="this_form1">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <h1 class="mb-4">修改名稱</h1>
            <table class="table table-striped align-middle" style="word-break:break-all;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">排序</th>
                        <th>名稱</th>
                        <th style="width: 140px;">類別</th>
                        <th>所屬目錄</th>
                        <th>連結(建目錄免填)</th>
                        <th style="width: 110px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        {{-- 排序 --}}
                        <td>
                            <input type="number" name="order_by" id="order_by" class="form-control" value="{{ $tree->order_by }}" placeholder="排序">
                        </td>
                        
                        {{-- 名稱 --}}
                        <td>
                            <input type="text" name="name" id="name" class="form-control" required value="{{ $tree->name }}" placeholder="名稱">
                        </td>
                        
                        {{-- 類別 (Radio) --}}
                        <td>
                            <?php
                                $check1 = ($tree->type == "1") ? "checked" : "";
                                $check2 = ($tree->type == "2") ? "checked" : "";
                            ?>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="type" id="radio1" value="1" {{ $check1 }}>
                                <label class="form-check-label" for="radio1">
                                    <i class="fas fa-folder-open text-warning"></i> 子目錄
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="radio2" value="2" {{ $check2 }}>
                                <label class="form-check-label" for="radio2">
                                    <i class="fas fa-file text-secondary"></i> 連結
                                </label>
                            </div>
                        </td>
                        
                        {{-- 所屬目錄 --}}
                        <td>
                            {{-- 升級為 Bootstrap 5 的 form-select 類別 --}}
                            <select name="folder_id" id="folder_id" class="form-select">
                                @foreach($folders as $k => $v)
                                    <?php $selected = ($tree->folder_id == $k) ? "selected" : ""; ?>
                                    <option value="{{ $k }}" {{ $selected }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </td>
                        
                        {{-- 網址 --}}
                        <td>
                            <input type="text" name="url" id="url" class="form-control" value="{{ $tree->url }}" placeholder="http://...(選目錄免填)">
                        </td>
                        
                        {{-- 儲存按鈕 --}}
                        <td>
                            <button type="button" class="btn btn-primary btn-sm w-100 save-btn" data-form="this_form1">
                                <i class="fas fa-save"></i> 儲存區塊
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
@endsection