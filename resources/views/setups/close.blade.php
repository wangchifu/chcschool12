@extends('layouts.master_close')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="text-center my-5">
            <h1 class="display-4 fw-bold text-danger mb-4">
                {{ $setup->close_website }}
            </h1>
            <img src="{{ asset('images/closed.png') }}" class="img-fluid rounded shadow-sm" alt="Website Closed">
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('admin_login_close') }}" class="text-secondary text-decoration-none">
                <i class="fas fa-cog fa-lg"></i>
            </a>
        </div>
    </div>
</div>
@endsection