@extends('layouts.master_clean')

@section('title', '新增區塊 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('in_head')
    <script src=" https://cdn.jsdelivr.net/npm/tinymce@7.9.1/tinymce.min.js "></script>
@endsection

@section('content')
    @include('layouts.errors')
    <form action="{{ route('setups.add_block') }}" method="POST" id="this_form" onsubmit="return false">
        @csrf
        <table class="table">
            <tr>
                <td>
                    <div class="mb-3">
                        <label for="setup_col_id" class="form-label">1.放置欄位</label>
                        <select name="setup_col_id" id="setup_col_id" class="form-select">
                            <option value=""></option>
                            @foreach($setup_array as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="order_by" class="form-label">2.排序</label>
                        <input type="text" name="order_by" id="order_by" class="form-control" placeholder="數字">
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="title" class="form-label">3.名稱</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="mb-3">
                        <label for="block_color" class="form-label">4.<a href="{{ route('setups.block_color') }}" class="text-decoration-none">顏色</a></label>
                        <select name="block_color" id="block_color" class="form-select">
                            <option value=""></option>
                            @foreach($block_colors as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="block_position" class="form-label">5.標題位置</label>
                        <select name="block_position" id="block_position" class="form-select">
                            <option value="text-left">置左</option>
                            <option value="text-center">置中</option>
                            <option value="text-right">置右</option>
                            <option value="disable">不顯示標題</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="mb-3">
                        <label for="disable_block_line" class="form-label">6.框線</label>
                        <select name="disable_block_line" id="disable_block_line" class="form-select">
                            <option value="">*有*框線</option>
                            <option value="1">*無*框線</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="mb-3">
                        <label for="my-editor" class="form-label">7.內文*</label>
                        <textarea name="content" id="my_editor" class="form-control" required rows="5"></textarea>
                    </div>
                </td>
            </tr>
        </table>
        
        <div class="mb-3">
            <button type="submit" class="btn btn-success btn-sm" onclick="sw_confirm2('確定新增？','this_form')">
                <i class="fas fa-plus me-1"></i> 新增區塊
            </button>
        </div>
        
        <hr>
        
        <div class="mb-3">
            <p class="mb-1">標題底色參考：</p>
            <a href="{{ route('setups.block_color') }}">
                <img src="{{ asset('color.png') }}" class="img-thumbnail" alt="顏色參考">
            </a>
        </div>
    </form>
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
