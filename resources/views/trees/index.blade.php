@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '樹狀目錄 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">樹狀目錄</h1>
            @include('layouts.errors')
            
            <form action="{{ route('trees.store') }}" method="post" id="this_form1" class="mb-4" id="this_form1">
                @csrf
                <table class="table table-striped align-middle" style="word-break:break-all;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 100px;">排序</th>
                            <th>名稱</th>
                            <th style="width: 140px;">類別</th>
                            <th>所屬目錄</th>
                            <th>連結(建目錄免填)</th>
                            <th style="width: 90px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="number" name="order_by" id="order_by" class="form-control" value="{{ reset($new_tree_order_by) }}" placeholder="排序">
                            </td>
                            <td>
                                <input type="text" name="name" id="name" class="form-control" required placeholder="名稱">
                            </td>
                            <td>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="type" id="radio1" value="1" checked>
                                    <label class="form-check-label" for="radio1">
                                        <i class="fas fa-folder-open text-warning"></i> 子目錄
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="radio2" value="2">
                                    <label class="form-check-label" for="radio2">
                                        <i class="fas fa-file text-secondary"></i> 連結
                                    </label>
                                </div>
                            </td>
                            <td>
                                {{-- 改用 Bootstrap 5 規範的 form-select，並移除內聯 onchange --}}
                                <select name="folder_id" id="folder_id" class="form-select">
                                    @foreach($folders as $k => $v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="url" id="url" class="form-control" placeholder="http://...(選目錄免填)">
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm w-100 save-btn" data-form="this_form1">
                                    <i class="fas fa-plus"></i> 新增
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">
                    目錄結構
                </div>
                <div class="card-body">
                    <span class="fw-bold">根目錄：</span><br>
                    <div class="mt-2 text-break">
                        {{ get_tree($trees, 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 完全符合 CSP 規範的安全 JavaScript 區塊 --}}
    <script nonce="{{ $csp_nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            // 初始化 jQuery Validate
            var validator = $("#this_form1").validate();

            // 準備 Laravel 丟過來的排序對應陣列
            const treeOrderArray = {};
            @foreach($new_tree_order_by as $k => $v)
                treeOrderArray[{{ $k }}] = {{ $v }};
            @endforeach

            const folderSelect = document.getElementById('folder_id');
            const orderByInput = document.getElementById('order_by');

            // 符合 CSP 的非內聯事件監聽器
            if (folderSelect && orderByInput) {
                folderSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    if (treeOrderArray[selectedValue] !== undefined) {
                        orderByInput.value = treeOrderArray[selectedValue];
                    }
                });
            }
        });

        // 彈出新視窗函式
        function open_window(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=300');
        }
    </script>
@endsection