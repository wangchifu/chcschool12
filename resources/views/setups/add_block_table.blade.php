@extends('layouts.master_clean')

@section('title', '新增區塊 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @include('layouts.errors')
    <form action="{{ route('setups.add_block') }}" method="POST" id="this_form1">
        @csrf
        <table class="table">
            <tr>
                <td>
                    <div class="mb-3">
                        <label for="setup_col_id" class="form-label">1.放置欄位</label>
                        <select name="setup_col_id" id="setup_col_id" class="form-select">
                            <option value=""></option>
                            @foreach($setup_array as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="order_by" class="form-label">2.排序</label>
                        <input type="number" name="order_by" id="order_by" class="form-control" placeholder="數字">
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="title" class="form-label">3.名稱</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="mb-3">
                        <label for="block_color" class="form-label">4.<a href="{{ route('setups.block_color') }}" class="text-decoration-none">顏色</a></label>
                        <select name="block_color" id="block_color" class="form-select">
                            <option value=""></option>
                            @foreach($block_colors as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="block_position" class="form-label">5.標題位置</label>
                        <select name="block_position" id="block_position" class="form-select">
                            <option value="text-left">置左</option>
                            <option value="text-center">置中</option>
                            <option value="text-right">置右</option>
                            <option value="disable">不顯示標題</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="disable_block_line" class="form-label">6.框線</label>
                        <select name="disable_block_line" id="disable_block_line" class="form-select">
                            <option value="">*有*框線</option>
                            <option value="1">*無*框線</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="mb-3">
                        <label for="my-editor" class="form-label">7.內文*</label>
                        <textarea name="content" id="my_editor" class="form-control" required rows="5"></textarea>
                    </div>
                </td>
            </tr>
        </table>
        
        <div class="mb-3">
            <span class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                <i class="fas fa-save"></i> 新增區塊
            </span>            
        </div>
        
        <hr>
        
        <div class="mb-3">
            <p class="mb-1">標題底色參考：</p>
            <a href="{{ route('setups.block_color') }}">
                <img src="{{ asset('color.png') }}" class="img-thumbnail" alt="顏色參考">
            </a>
        </div>
    </form>    
@endsection
