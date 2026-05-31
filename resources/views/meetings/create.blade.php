@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '新增會議 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="mb-4">
                <h1 class="fw-bold text-dark mb-2">新增會議</h1>
            </div>
            
            @include('layouts.errors')
            
            <?php
            $default_date = date('Y-m-d');
            $default_name="教師晨會";
            ?>
            
            <form action="{{ route('meetings.store') }}" method="POST" id="this_form1">
                @csrf
                
                @include('meetings.form')
                
            </form>
        </div>
    </div>
@endsection