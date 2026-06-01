@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '報修系統 | ')

@section('content')
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">報修系統</h1>
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">報修列表</li>
                </ol>
            </nav>
            
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <a href="{{ route('fixes.index') }}" class="btn btn-dark btn-sm fw-bold px-3">
                    <i class="fas fa-check-square me-1"></i> 全部列表
                </a>
                @include('fixes.nav',['situation'=>null])
            </div>
            
            <hr class="text-muted opacity-25">
            
            @if($fix_admin)
                <div class="card border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-light py-2.5 px-3">
                        <span class="fw-bold text-secondary small"><i class="fas fa-cog me-1"></i> 管理員通知設定</span>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('fixes.store_notify') }}" method="POST" id="this_form1">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        <i class="fab fa-line text-success"></i> LINE BOT
                                        <span class="ms-1">
                                            [<a href="{{ asset('line_bot.pdf') }}" target="_blank" class="text-decoration-none small">教學</a>]
                                            [<a href="https://www.youtube.com/watch?v=PgYwIH2bHO0" target="_blank" class="text-decoration-none small">影片</a>]
                                        </span>
                                    </label>
                                    <div class="d-flex flex-column gap-2">
                                        <input type="text" class="form-control form-control-sm" id="line_bot_token" name="line_bot_token" value="{{ auth()->user()->line_bot_token }}" placeholder="line bot token">
                                        <input type="text" class="form-control form-control-sm" id="line_user_id" name="line_user_id" value="{{ auth()->user()->line_user_id }}" placeholder="user_id">
                                    </div>
                                </div>
                                
                                <div class="col-md-5">
                                    <label for="exampleInputEmail1" class="form-label fw-bold text-dark small mb-1">
                                        <i class="fas fa-envelope-square text-primary"></i> Email通知
                                    </label>
                                    <input type="email" class="form-control form-control-sm" id="exampleInputEmail1" name="email" value="{{ auth()->user()->email }}" required>
                                    <div id="emailHelp" class="form-text small mt-1">新張貼會發email通知給你.</div>
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary btn-sm fw-bold w-100 save-btn" data-form="this_form1">
                                        <i class="fas fa-save me-1"></i> 儲存
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            
            <div class="mb-3">
                <a href="{{ route('fixes.create') }}" class="btn btn-success btn-sm fw-bold px-3 shadow-sm venobox" data-vbtype="iframe">
                    <i class="fas fa-plus me-1"></i> 新增報修
                </a>
            </div>
            
            <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-4">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="py-3 px-3">
                                類別 
                                @if($fix_admin)
                                    <a href="{{ route('fixes.edit_class') }}" class="btn btn-outline-secondary btn-sm fw-semibold ms-2 py-0.5 px-2 venobox" data-vbtype="iframe"> 
                                        <i class="fas fa-edit me-1"></i> 編輯類別
                                    </a>
                                @endif
                            </th>
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
                                <td class="px-3">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        @if($fix_admin)
                                            <form action="{{ route('fixes.destroy', $fix->id) }}" method="POST" id="delete_fix_{{ $fix->id }}" class="form-fix-delete m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn p-0 border-0 bg-transparent btn-fix-delete-submit align-middle delete-btn2" data-form="delete_fix_{{ $fix->id }}">
                                                    <i class="fas fa-times-circle text-danger fs-5"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <span class="fw-semibold text-dark">{{ $types[$fix->type] }}</span>
                                    </div>
                                </td>
                                <td class="px-3">
                                    <?php
                                    $situation=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中'];
                                    $icon = [
                                        '1'=>'<i class="fas fa-check-square text-success me-1"></i>',
                                        '2'=>'<i class="fas fa-exclamation-triangle text-warning me-1"></i>',
                                        '3'=>'<i class="fas fa-phone-square text-danger me-1"></i>'
                                    ];
                                    ?>
                                    <div class="d-inline-flex align-items-center">
                                        {!! $icon[$fix->situation] !!} 
                                        <span class="text-secondary fw-medium">{{ $situation[$fix->situation] }}</span>
                                    </div>
                                </td>
                                <td class="text-muted small px-3">
                                    {{ substr($fix->created_at,0,10) }}
                                </td>
                                <td class="text-dark px-3">
                                    {{ $fix->user->name }}
                                </td>
                                <td class="px-3">
                                    <a href="{{ route('fixes.show',$fix->id) }}" class="text-decoration-none fw-semibold link-primary venobox" data-vbtype="iframe">
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
                {{ $fixes->links('layouts.pagination') }}
            </div>
        </div>
    </div>
@endsection