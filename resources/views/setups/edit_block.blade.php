@extends('layouts.master_clean')

@section('title', '編輯區塊 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <h2 class="mb-4 fw-bold text-dark">
        <i class="fas fa-edit me-2"></i>修改區塊
    </h2>
    @include('layouts.errors')
    <form action="{{ route('setups.update_block', $block->id) }}" method="POST" id="this_form1">
        @csrf
        @method('PATCH')
        
        <table class="table align-middle">
            <tr>
                <td>
                    <div class="mb-3">
                        <label for="setup_col_id" class="form-label">1.放置欄位</label>
                        <select name="setup_col_id" id="setup_col_id" class="form-select">
                            <option value=""></option>
                            @foreach($setup_array as $key => $value)
                                <option value="{{ $key }}" {{ $block->setup_col_id == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="order_by" class="form-label">2.排序</label>
                        <input type="number" name="order_by" id="order_by" value="{{ $block->order_by }}" class="form-control" placeholder="數字">
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="title_input" class="form-label">3.標題名稱</label>
                        @if(str_contains($block->title,'系統區塊') or str_contains($block->title,'榮譽榜跑馬燈'))
                            <?php 
                                $new_title = (empty($block->new_title)) ? $block->title : $block->new_title;
                                $new_title = str_replace('(系統區塊)', '', $new_title); 
                            ?>
                            <input type="text" name="new_title" id="title_input" value="{{ $new_title }}" class="form-control" required>
                            <input type="hidden" name="title" value="{{ $block->title }}">
                        @else
                            <input type="text" name="title" id="title_input" value="{{ $block->title }}" class="form-control" required>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="mb-3">
                        <label for="block_color" class="form-label">4.<a href="{{ route('setups.block_color') }}" class="text-decoration-none">標題底色</a></label>
                        <select name="block_color" id="block_color" class="form-select">
                            <option value=""></option>
                            @foreach($block_colors as $key => $value)
                                <option value="{{ $key }}" {{ $block->block_color == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="block_position" class="form-label">5.標題位置</label>
                        <select name="block_position" id="block_position" class="form-select">
                            <option value="text-left" {{ (in_array($block->block_position, ['text-left', null])) ? 'selected' : '' }}>置左</option>
                            <option value="text-center" {{ $block->block_position == "text-center" ? 'selected' : '' }}>置中</option>
                            <option value="text-right" {{ $block->block_position == "text-right" ? 'selected' : '' }}>置右</option>
                            <option value="disable" {{ $block->block_position == "disable" ? 'selected' : '' }}>不顯示標題</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="disable_block_line" class="form-label">6.框線</label>
                        <select name="disable_block_line" id="disable_block_line" class="form-select">
                            <option value="" {{ $block->disable_block_line == null ? 'selected' : '' }}>*有*框線</option>
                            <option value="1" {{ $block->disable_block_line == "1" ? 'selected' : '' }}>*無*框線</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    @if(str_contains($block->title,'榮譽榜跑馬燈'))
                        <div class="mb-3">
                            <div class="table-responsive">
                                <div>                                
                                    @include('layouts.marquee')
                                </div>
                            </div>
                        </div>                         
                    @elseif(!str_contains($block->title,'系統區塊'))
                        <div class="mb-3">
                            <label for="my_editor" class="form-label">6.內文*</label>
                            <textarea name="content" id="my_editor" class="form-control" rows="10">{{ $block->content }}</textarea>
                        </div>
                    @endif
                </td>
            </tr>
        </table>    
        
        <div class="mt-3">
            <span class="btn btn-primary btn-sm save-btn" data-form="this_form1">
                <i class="fas fa-save"></i> 儲存區塊
            </span>            
        </div>
    </form>
    @if(strpos($block->title,"跑馬燈"))
        
    @elseif(!strpos($block->title,'系統區塊'))
        <div class="text-end">
            <form action="{{ route('setups.delete_block', $block->id) }}" method="post" id="delete_form">
                @csrf
                @method('delete')
                <span class="btn btn-danger btn-sm delete-btn2" data-form="delete_form">
                    <i class="fas fa-save"></i> 刪除
                </span>                
            </form>
        </div>
    @endif
    <hr>
    <div class="mt-4 pt-3 border-top">
        <p class="text-secondary fw-bold mb-2">
            <i class="fas fa-palette me-1"></i> 標題底色參考：
        </p>
        <a href="{{ route('setups.block_color') }}" class="d-inline-block shadow-sm hover-opacity">
            <img src="{{ asset('color.png') }}" class="img-thumbnail" alt="區塊顏色參考圖" style="max-width: 1200px;">
        </a>
    </div>
    <script>
        tinymce.init({
            selector: 'textarea#my_editor',
            language: 'zh_TW', // 設置語言為繁體中文
            language_url: '{{ asset('js/zh_TW.js') }}', // 加這行
            // 1. 確保 plugins 包含 'link' (有些版本文字顏色功能綁在核心或特定套件)
            plugins: 'fullscreen code table image link lists paste', 
            // 2. 在 toolbar 加入 forecolor (文字顏色) 和 backcolor (背景顏色)
            toolbar: 'fullscreen code undo redo | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | table image link unlink openlink | bullist numlist outdent indent | removeformat',            
            //plugins: 'fullscreen code table,image link lists image paste', // 啟用表格功能
            //toolbar: 'fullscreen code undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | table image link unlink openlink | bullist numlist outdent indent | removeformat',                
            //paste_data_images: true,//拖過去上傳
            //images_upload_url: '/contents/upload_image', // Laravel API
            automatic_uploads: true,
            // 不自動清理或修改 HTML
            valid_elements: '*[*]', 
            extended_valid_elements: '*[*]',
            verify_html: false,
            forced_root_block: false,  // 避免自動包裹 `<p>` 標籤
            remove_trailing_brs: false, // 不刪除尾部 <br>
            convert_urls: false, // 禁止 TinyMCE 轉換圖片 URL
            relative_urls: false, // 確保使用絕對 URL
            remove_script_host: false, // 保留完整的 URL，包括 http:// 或 https://

            // 改為使用 Promise 來處理圖片上傳
            images_upload_handler: function (blobInfo) {
                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                return fetch('/tinymce_upload_image', {//laravel API
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        return Promise.reject('伺服器回應錯誤，狀態碼：' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.location) {
                        // 返回圖片 URL，讓 TinyMCE 插入圖片
                        return data.location;
                    } else {
                        return Promise.reject('伺服器回傳的 JSON 不包含 `location` 欄位');
                    }
                })
                .catch(error => {
                    console.error('圖片上傳錯誤:', error);
                    return Promise.reject('圖片上傳失敗');
                });
            }
        });        
    </script>
@endsection
