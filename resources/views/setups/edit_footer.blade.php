@extends('layouts.master_clean')

@section('title', '編輯置底 | ')

@section('content')
    @include('layouts.errors')
    <form action="{{ route('setups.update_footer') }}" method="POST" id="this_form" onsubmit="return false">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="footer" class="form-label fw-bold">置底內容</label>
            <textarea name="footer" id="footer" class="form-control" rows="5">{{ $setup->footer }}</textarea>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存？','this_form')">
                <i class="fas fa-save me-1"></i> 儲存置底
            </button>
        </div>
    </form>
@endsection
