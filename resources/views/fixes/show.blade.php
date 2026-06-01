@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '顯示報修 | ')

@section('content')
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">顯示報修</h1>        
            
            <div class="lead fs-5 fw-semibold mb-3">
                <?php
                $s=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中'];
                $icon = [
                    '1'=>'<i class="fas fa-check-square text-success me-1"></i>',
                    '2'=>'<i class="fas fa-exclamation-triangle text-warning me-1"></i>',
                    '3'=>'<i class="fas fa-phone-square text-danger me-1"></i>'
                ];
                ?>
                <span class="d-inline-flex align-items-center me-2">
                    {!! $icon[$fix->situation] !!} {{ $s[$fix->situation] }}
                </span>
                <span class="text-secondary">/</span>
                <span class="text-dark ms-2"><i class="fas fa-user me-1 text-secondary"></i>張貼者：{{ $fix->user->name }}</span>
            </div>
            
            <hr class="text-muted opacity-25">
            
            <p class="text-muted small mb-4">
                <i class="far fa-calendar-alt me-1"></i> 張貼日期：{{ $fix->created_at }}
            </p>
            
            <hr class="text-muted opacity-25">
            
            <h3 class="fw-bold text-dark mb-3">{{ $fix->title }}</h3>
            <div class="card bg-light border border-secondary border-opacity-10 shadow-sm rounded-3 p-4 mb-4">
                <p class="mb-0 fs-5 text-dark lh-base">
                    <?php $content = str_replace(chr(13) . chr(10), '<br>', $fix->content);?>
                    {!! $content !!}
                </p>
            </div>
            
            <hr class="text-muted opacity-25">
            
            @if(!empty($fix->reply))
                <?php $reply = str_replace(chr(13) . chr(10), '<br>', $fix->reply);?>
                <div class="card border border-danger border-opacity-25 bg-danger bg-opacity-10 shadow-sm rounded-3 p-4 mb-4">
                    <h4 class="text-danger fw-bold fs-5 mb-2"><i class="fas fa-comment-dots me-1"></i> 管理員回覆：</h4>
                    <p class="mb-0 fs-5 text-danger fw-medium lh-base">
                        {!! $reply !!}
                    </p>
                </div>
            @endif
            
            @if($fix_admin)
                <form action="{{ route('fixes.update', $fix->id) }}" method="POST" id="this_form1" class="mb-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="title" value="{{ $fix->title }}">
                    
                    <div class="card border border-primary border-opacity-25 shadow-sm rounded-3 overflow-hidden my-4">
                        <h3 class="card-header bg-light fs-5 fw-bold py-3 px-4 text-primary border-bottom">
                            <i class="fas fa-user-shield me-1"></i> 管理員回應
                        </h3>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold text-primary">填 EMail 可收回覆信件</label>
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label for="situation" class="form-label fw-bold text-dark">處理狀況*</label>
                                <select name="situation" id="situation" class="form-select">
                                    <option value="2" {{ $fix->situation == 2 ? 'selected' : '' }}>處理中</option>
                                    <option value="1" {{ $fix->situation == 1 ? 'selected' : '' }}>處理完畢</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="reply" class="form-label fw-bold text-dark">回覆*</label>
                                <textarea name="reply" id="reply" class="form-control" rows="5" placeholder="請輸入內容">{{ $fix->reply }}</textarea>
                            </div>
                            
                            <div>
                                <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm btn-fix-save-submit save-btn" data-form="this_form1">
                                    <i class="fas fa-save me-1"></i> 儲存設定
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
            
            @if($fix->user_id == auth()->user()->id and $fix->created_at == $fix->updated_at)
                <form action="{{ route('fixes.destroy', $fix->id) }}" method="POST" id="delete_fix_owner" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm fw-bold px-3 btn-fix-owner-delete-submit">
                        <i class="fas fa-trash me-1"></i> 刪除
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection