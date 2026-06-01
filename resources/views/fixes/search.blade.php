@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '分類搜尋報修 | ')

@section('content')
    <?php $situations=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中']; ?>
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">{{ $situations[$situation] }}</h1>
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.index') }}" class="text-decoration-none">報修列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">分類搜尋 - {{ $situations[$situation] }}</li>
                </ol>
            </nav>
            
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <a href="{{ route('fixes.index') }}" class="btn btn-outline-dark btn-sm fw-bold px-3">
                    <i class="fas fa-check-square me-1"></i> 全部列表
                </a>
                @include('fixes.nav',['situation'=>$situation])
            </div>
            
            <hr class="text-muted opacity-25">
            
            <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-4">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="py-3 px-3">類別</th>
                            <th scope="col" class="py-3 px-3">處理狀況</th>
                            <th scope="col" class="py-3 px-3">申報日期</th>
                            <th scope="col" class="py-3 px-3">申報人</th>
                            <th scope="col" class="py-3 px-3">標題</th>
                            <th scope="col" class="py-3 px-3">處理日期</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fixes as $fix)
                            <tr>
                                <td class="px-3 fw-semibold text-dark">
                                    {{ $types[$fix->type] }}
                                </td>
                                <td class="px-3">
                                    <?php
                                    $situation_list=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中'];
                                    $icon = [
                                        '1'=>'<i class="fas fa-check-square text-success me-1"></i>',
                                        '2'=>'<i class="fas fa-exclamation-triangle text-warning me-1"></i>',
                                        '3'=>'<i class="fas fa-phone-square text-danger me-1"></i>'
                                    ];
                                    ?>
                                    <div class="d-inline-flex align-items-center">
                                        {!! $icon[$fix->situation] !!} 
                                        <span class="text-secondary fw-medium">{{ $situation_list[$fix->situation] }}</span>
                                    </div>
                                </td>
                                <td class="text-muted small px-3">
                                    {{ substr($fix->created_at,0,10) }}
                                </td>
                                <td class="text-dark px-3">
                                    {{ $fix->user->name }}
                                </td>
                                <td class="px-3">
                                    <a href="{{ route('fixes.show',$fix->id) }}" class="text-decoration-none fw-semibold link-primary">
                                        {{ $fix->title }}
                                    </a>
                                </td>
                                <td class="text-muted small px-3">
                                    @if($fix->situation < 3)
                                        {{ substr($fix->updated_at,0,10) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $fixes->links() }}
            </div>
        </div>
    </div>
@endsection