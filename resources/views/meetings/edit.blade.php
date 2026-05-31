@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '修改會議 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="mb-4">
                <h1 class="fw-bold text-dark mb-2">修改會議</h1>
            </div>
            
            @include('layouts.errors')
            
            <?php
            $default_date = $meeting->open_date;
            $default_name = $meeting->name;
            ?>
            
            <form action="{{ route('meetings.update', $meeting->id) }}" method="POST" id="this_form1">
                @csrf
                @method('PATCH')
                
                @include('meetings.form')
                
            </form>
        </div>
    </div>
@endsection