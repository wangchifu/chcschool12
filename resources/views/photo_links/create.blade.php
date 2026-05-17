@extends('layouts.master_clean')

@section('nav_setup_active', 'active')

@section('title', '新增圖片連結 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">新增圖片連結</h1>            
            
            {{-- 改回原生 HTML 表單，並加上檔案上傳必備的 enctype --}}
            <form action="{{ route('photo_links.store') }}" method="POST" id="this_form1" enctype="multipart/form-data">
                @csrf
                
                <div class="card my-4">
                    <h3 class="card-header h5">連結資料</h3>
                    <div class="card-body">
                        
                        {{-- 類別 --}}
                        <div class="mb-3">
                            <label for="photo_type_id" class="form-label fw-bold">類別</label>
                            {{-- 符合 BS5 的 form-select，並移除 inline onclick --}}
                            <select name="photo_type_id" class="form-select" id="photo_type_id">
                                <option value="0">不分類</option>
                                @foreach($photo_types as $photo_type)
                                    <?php $selected = ($photo_type_id == $photo_type->id) ? "selected" : null; ?>
                                    <option value="{{ $photo_type->id }}" {{ $selected }}>{{ $photo_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- 排序 --}}
                        <div class="mb-3">
                            <label for="order_by" class="form-label fw-bold">排序*</label>
                            <input type="number" name="order_by" id="order_by" class="form-control" value="{{ reset($new_link_order_by) }}" placeholder="數字">
                        </div>
                        
                        {{-- 代表圖片 --}}
                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">代表圖片*</label>
                            <input type="file" name="image" id="image" required class="form-control">
                        </div>                    
                        
                        {{-- 名稱 --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">名稱*</label>
                            <input type="text" name="name" id="name" class="form-control" required placeholder="名稱">
                        </div>
                        
                        {{-- 網址 --}}
                        <div class="mb-3">
                            <label for="url" class="form-label fw-bold">網址*</label>
                            <input type="text" name="url" id="url" class="form-control" required placeholder="https://">
                        </div>
                        
                        {{-- 按鈕 --}}
                        <div class="mt-4">
                            <button type="button" class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                                <i class="fas fa-save"></i> 儲存設定
                            </button>
                        </div>
                        
                        <div class="mt-3">
                            @include('layouts.errors')
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 將所有 Script 集中在最下方，並套用 nonce 符合 CSP --}}
    <script nonce="{{ $csp_nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            // 初始化表單驗證
            var validator = $("#this_form").validate();

            // 準備 Laravel 丟過來的陣列對應資料
            const orderArray = {};
            @foreach($new_link_order_by as $k => $v)
                orderArray[{{ $k }}] = {{ $v }};
            @endforeach

            const typeSelect = document.getElementById('photo_type_id');
            const orderByInput = document.getElementById('order_by');

            // 符合 CSP 的事件監聽器方式 (取代舊有 onclick)
            if (typeSelect && orderByInput) {
                typeSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    if (orderArray[selectedValue] !== undefined) {
                        orderByInput.value = orderArray[selectedValue];
                    }
                });
            }
        });
    </script>
@endsection