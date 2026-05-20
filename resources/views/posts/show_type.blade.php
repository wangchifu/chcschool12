@extends('layouts.master_clean')

@section('title', '編輯公告類別 | ')

@section('content')
    {{-- 1. 類別管理表格區 --}}
    <div class="table-responsive mb-5">
        <h1>類別管理</h1>
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 120px;">排序</th>
                    <th>名稱</th>
                    <th style="width: 250px;">動作</th>
                </tr>
            </thead>
            <tbody>
                {{-- 新增類別表單 --}}
                <form action="{{ route('posts.store_type') }}" method="POST" class="m-0" id="this_form1">
                    @csrf
                    <tr>
                        <td>
                            <input type="text" name="order_by" id="order_by" class="form-control form-control-sm" placeholder="排序">
                        </td>
                        <td>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" required placeholder="名稱">
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm w-100 save-btn" data-form="this_form1">
                                <i class="fas fa-plus me-1"></i> 新增
                            </button>
                        </td>
                    </tr>
                </form>

                {{-- 修改/刪除/隱藏 類別迴圈 --}}
                @foreach($post_types as $post_type)
                    <form action="{{ route('posts.update_type', $post_type->id) }}" method="POST" class="m-0" id="update_type{{ $post_type->id }}">
                        @csrf
                        @method('PATCH')
                        <tr>
                            <td>
                                <input type="text" name="order_by" class="form-control form-control-sm" value="{{ $post_type->order_by }}" placeholder="排序">
                            </td>
                            <td>
                                @if($post_type->id != 1 && $post_type->id != 2 && $post_type->id != 0)
                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ $post_type->name }}" required placeholder="名稱">
                                @else
                                    {{-- 系統內建固定類別，禁止修改名稱 --}}
                                    @if($post_type->id == 0) <input type="hidden" name="name" value="一般公告"> @endif
                                    @if($post_type->id == 1) <input type="hidden" name="name" value="內部公告"> @endif
                                    @if($post_type->id == 2) <input type="hidden" name="name" value="榮譽榜"> @endif
                                    
                                    @if($post_type->disable == 1)
                                        <del class="text-muted">{{ $post_type->name }}</del>
                                    @else
                                        <span class="fw-bold text-dark">{{ $post_type->name }}</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-primary btn-sm save-btn" data-form="update_type{{ $post_type->id }}">儲存修改</button>
                                    
                                    @if($post_type->id != 1 && $post_type->id != 2 && $post_type->id != 0)
                                        <a href="#!" class="btn btn-danger btn-sm delete-btn1" data-url="{{ route('posts.delete_type', $post_type->id) }}">刪除</a>                                     
                                    @endif

                                    @if($post_type->disable == null)
                                        <a href="#!" class="btn btn-warning btn-sm delete-btn1" data-url="{{ route('posts.disable_type', $post_type->id) }}" data-msg="確定要隱藏嗎？">隱藏</a>
                                    @else
                                        <a href="#!" class="btn btn-success btn-sm delete-btn1" data-url="{{ route('posts.disable_type', $post_type->id) }}" data-msg="確定要再顯示嗎？">再顯示</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </form>
                @endforeach
            </tbody>
        </table>
    </div>

    <?php 
        $setup = \App\Models\Setup::first();
        $checked = ($setup->all_post) ? "checked" : null;
    ?>

    {{-- 2. 系統環境設定區：改採現代化卡片 (Cards) 與網格 (Grid) 排版，淘汰排版用表格 --}}
    <div class="row g-4 mb-4">
        
        {{-- 左側設定：預設畫面與顯示則數 --}}
        <div class="col-md-6">
            {{-- 設定項 A --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('setups.all_post') }}" method="POST" class="m-0" id="this_form2">
                        @csrf
                        <div class="form-check mb-2">
                            <input type="checkbox" name="all_post" class="form-check-input" id="customCheck1" {{ $checked }}>
                            <label class="form-check-label fw-md" for="customCheck1">
                                分類公告區塊中，預設顯示「全部公告」
                            </label>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm save-btn" data-form="this_form2">
                            <i class="fas fa-check-circle me-1"></i> 確定變更
                        </button>
                    </form>
                </div>
            </div>

            {{-- 設定項 B --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('setups.post_show_number') }}" method="POST" class="m-0" id="this_form3">
                        @csrf                
                        <div class="mb-3">
                            <label for="post_show_number" class="form-label fw-bold">公告的相關區塊中，一次顯示幾則？</label>
                            <input type="number" name="post_show_number" id="post_show_number" class="form-control form-control-sm" value="{{ $setup->post_show_number }}" placeholder="預設為10則" required min="1">
                        </div>
                        <button type="button" class="btn btn-primary btn-sm save-btn" data-form="this_form3">
                            <i class="fas fa-edit me-1"></i> 修改則數
                        </button>    
                    </form>   
                </div>
            </div>
        </div>

        {{-- 右側設定：Line Bot 權杖綁定 --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <form action="{{ route('setups.post_line_token') }}" method="POST" class="m-0" id="this_form4">
                        @csrf          
                        <div class="mb-3">
                            <label for="post_line_bot_token" class="form-label fw-bold d-block">
                                LINE Bot 聯動設定 
                                <span class="fw-normal text-muted fs-7">(發公告時，順便發送訊息。註：延後上架者不適用)</span>
                            </label>
                            <div class="fs-7 mb-2">
                                參考資料：
                                <a href="{{ asset('line_bot.pdf') }}" target="_blank" class="badge bg-light text-dark text-decoration-none border me-1"><i class="fas fa-file-pdf text-danger me-1"></i>PDF 教學</a>
                                <a href="https://www.youtube.com/watch?v=PgYwIH2bHO0" target="_blank" class="badge bg-light text-dark text-decoration-none border"><i class="fab fa-youtube text-danger me-1"></i>影片教學</a>
                            </div>
                            
                            <div class="mb-2">
                                <label for="post_line_bot_token" class="form-label small text-secondary mb-1">LINE Bot 權杖 (Token)</label>
                                <input type="text" name="post_line_bot_token" id="post_line_bot_token" class="form-control form-control-sm" value="{{ $setup->post_line_bot_token }}" placeholder="請輸入 line bot 權杖">
                            </div>
                            <div>
                                <label for="post_line_group_id" class="form-label small text-secondary mb-1">LINE 群組或使用者 ID (Group / User ID)</label>
                                <input type="text" name="post_line_group_id" id="post_line_group_id" class="form-control form-control-sm" value="{{ $setup->post_line_group_id }}" placeholder="請輸入 line group 或 user id">
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm save-btn" data-form="this_form4">
                            <i class="fas fa-save me-1"></i> 儲存權杖
                        </button>    
                    </form>
                </div>
            </div>
        </div>

    </div>

    @include('layouts.errors')   
@endsection