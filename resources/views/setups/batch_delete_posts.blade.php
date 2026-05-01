@extends('layouts.master_clean')

@section('title', '批次刪除公告 | ')

@section('content')
    <div class="alert alert-danger shadow-sm border-start border-5 border-danger mb-4">
        <h3 class="text-center fw-bold mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>強烈注意！！這是大量刪除公告，做錯了沒人可以救你！！
        </h3>
    </div>

    <form action="{{ route('setups.batch_delete') }}" method="POST" id="delete_form" onsubmit="return false">
        @csrf
        @method('DELETE')

        <div class="mb-4">
            <label for="post_no" class="form-label fw-bold">1. 要從哪一篇文章的公告開始刪到最前面？</label>
            <div class="mb-3">
                <img src="{{ asset('images/post_no.png') }}" class="img-fluid border rounded shadow-sm" alt="公告編號範例">
            </div>
            <input type="number" name="post_no" id="post_no" class="form-control" placeholder="請填公告的編號" required>
        </div>

        <div class="mb-4">
            <label for="insite" class="form-label fw-bold text-danger">2. 請選擇要刪除的公告類別*</label>
            <select name="insite" id="insite" class="form-select" required>
                @foreach($types as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-5 pt-3">
            <button class="btn btn-danger btn-lg w-100 shadow" type="submit" onclick="sw_confirm2('真的確定嗎？太大量的話，請等一下，不要再亂按了','delete_form')">
                <i class="fas fa-trash-alt me-1"></i> 確定不能挽回的刪除大量公告
            </button>
            <p class="text-muted text-center mt-2 small">
                <i class="fas fa-info-circle me-1"></i> 刪除過程可能較久，請按一次後耐心等待，切勿重複點擊。
            </p>
        </div>
    </form>    
@endsection
