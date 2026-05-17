@extends('layouts.master_clean')

@section('title', '編輯圖片連結 | ')

@section('content')
    <div class="col-md-11">
        <h1 class="mb-3">修改連結</h1>
    </div>
    @include('layouts.errors')
    
    {{-- 將 Form::open 改回原生 HTML 表單，並補上 @csrf 與 PATCH 方法 --}}
    <form action="{{ route('photo_links.update', $photo_link->id) }}" method="post" enctype="multipart/form-data" id="this_form1">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <table class="table table-striped align-middle" style="word-break:break-all;">
                <tbody>
                <tr>
                    <td>
                        <label for="photo_type_id" class="form-label fw-bold">類別</label>
                        <?php 
                            $selected0 = ($photo_link->photo_type_id == null) ? "selected" : null;
                        ?>
                        {{-- Bootstrap 5 下拉選單建議使用 form-select --}}
                        <select name="photo_type_id" id="photo_type_id" class="form-select">
                            <option value="" {{ $selected0 }}>不分類</option>
                            @foreach($photo_types as $photo_type)
                                <?php 
                                    $selected = ($photo_link->photo_type_id == $photo_type->id) ? "selected" : null;
                                ?>
                                <option value="{{ $photo_type->id }}" {{ $selected }}>{{ $photo_type->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <label for="order_by" class="form-label fw-bold">排序</label>
                        <input type="text" name="order_by" id="order_by" class="form-control" value="{{ $photo_link->order_by }}" placeholder="排序數字">
                    </td>
                    <td>
                        <label for="image" class="form-label fw-bold">代表圖片</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <small class="text-secondary">(不改照片則免填，圖片有暫存的問題)</small>
                    </td>                
                </tr>
                <tr>
                    <td>
                        <label for="name" class="form-label fw-bold">名稱</label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ $photo_link->name }}" placeholder="名稱">
                    </td>
                    <td colspan="2">
                        <label for="url" class="form-label fw-bold">網址</label>
                        <input type="text" name="url" id="url" class="form-control" required value="{{ $photo_link->url }}" placeholder="https://">
                    </td>
                </tr>
                </tbody>
            </table>
            
            <button type="button" class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                <i class="fas fa-save"></i> 修改連結
            </button>
        </div>
    </form>
@endsection