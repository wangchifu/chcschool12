@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', $content->title.' |')

@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            {{-- 標題區域 --}}
            <h1 class="fw-bold mb-4">
                {{ $content->title }}
            </h1>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light py-3 d-flex flex-wrap align-items-center">
                    {{-- 管理與編輯按鈕群組 --}}
                    @auth
                        @php 
                            // 判斷有無共同編輯權限
                            $can_edit = 0;
                            $user_id = auth()->user()->id;
                            $target_group = $content->group_id ?: 1; // 若無設定群組，預設為 1 (行政)
                            
                            if(\App\Models\UserGroup::where('user_id', $user_id)->where('group_id', $target_group)->exists()){
                                $can_edit = 1;
                            }
                        @endphp                        

                        <div class="btn-group btn-group-sm me-2 mb-1">
                            @if($can_edit)
                                <a href="{{ route('contents.together_edit', $content->id) }}" class="btn btn-primary venobox" data-vbtype="iframe">
                                    <i class="fas fa-users me-1"></i>共同編輯
                                </a>
                            @endif

                            @if(auth()->user()->admin)                        
                                <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-outline-primary venobox" data-vbtype="iframe">
                                    <i class="fas fa-edit"></i> 編輯
                                </a>
                                {{-- 使用 delete-btn1 對接全域 SweetAlert --}}
                                <button type="button" class="btn btn-outline-danger delete-btn2"                                         
                                        data-form="delete_form{{ $content->id }}">
                                    <i class="fas fa-trash"></i> 刪除
                                </button>
                            @endif
                        </div>

                        @if(auth()->user()->admin)
                            <a href="{{ route('contents.show_log', $content->id) }}" class="btn btn-info btn-sm me-2 mb-1 shadow-sm" target="_blank">
                                <i class="fas fa-history me-1"></i>Log ({{ $logs_count }})
                            </a>
                            {{-- 隱藏刪除表單 --}}
                            <form action="{{ route('contents.destroy', $content->id) }}" method="POST" id="delete_form{{ $content->id }}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endauth

                    {{-- 點閱數與狀態標籤 --}}
                    <div class="ms-auto d-flex align-items-center">
                        <span class="btn btn-dark btn-sm rounded-pill disabled me-2">
                            點閱 <span class="badge bg-light text-dark ms-1">{{ $content->views }}</span>
                        </span>                
                        
                        @if($content->power == null)
                            <span class="badge bg-success">公開</span>
                        @elseif($content->power == 2)
                            <span class="badge bg-success me-1">須登入</span> 
                            <span class="badge bg-warning text-dark">網內限定</span>
                        @elseif($content->power == 3)
                            <span class="badge bg-info text-dark">登入觀看</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="content-display" style="line-height: 1.8; font-size: 1.1rem;">
                        @php
                            $show_content = false;
                            if($content->power == null){
                                $show_content = true;
                            } elseif($content->power == 2) {
                                if(auth()->check() || (function_exists('check_ip') && check_ip())){
                                    $show_content = true;
                                }
                            } elseif($content->power == 3) {
                                if(auth()->check()){
                                    $show_content = true;
                                }
                            }
                        @endphp

                        @if($show_content)
                            <div class="table-responsive">
                                {!! $content->content !!}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                                <h3 class="text-danger">
                                    {{ $content->power == 2 ? '請登入，或於校網內觀看' : '此為限制內容，請先登入' }}
                                </h3>
                                @guest
                                    <a href="{{ route('login') }}" class="btn btn-primary mt-3 px-4">立即登入</a>
                                @endguest
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection