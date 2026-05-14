@extends('layouts.master_clean')

@section('title', '新增選單連結 | ')

@section('in_head')        
    <style nonce="{{ $csp_nonce }}">
        /* 預覽區塊樣式 */
        .icon-display {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #0d6efd;
            border-radius: 0.5rem;
            background-color: #fff;
            margin-right: 15px;
            color: #0d6efd;
        }
        /* Modal 內圖示清單樣式 */
        #icon-list-container {
            max-height: 450px;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 15px;
            background-color: #f1f3f5;
            border-radius: 8px;
        }
        .select-icon-btn {
            padding: 10px 0 !important;
            margin-bottom: 5px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .select-icon-btn:hover {
            background-color: #0d6efd !important;
            color: white !important;
            transform: scale(1.1);
            z-index: 10;
        }
        .icon-name-label {
            font-size: 0.65rem;
            display: block;
            margin-top: 5px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')    
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3">新增選單連結</h1>
            @include('layouts.errors')

            <form action="{{ route('links.store') }}" method="POST" id="this_form1">
                @csrf                
                <div class="card shadow-sm my-4">
                    <h3 class="card-header bg-primary text-white">連結資料</h3>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">類別*</label>
                                <select name="type_id" id="type_id" class="form-select" required onchange="change_order_by()">
                                    @foreach($type_array as $k => $v)
                                        <option value="{{ $k }}" {{ ($type_id ?? '') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">排序</label>
                                <input type="number" name="order_by" id="order_by" class="form-control" value="{{ reset($new_link_order_by) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">選擇圖示*</label>
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="icon-display shadow-sm">
                                    <i id="show_icon" class="fas fa-icons text-muted"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control bg-white" name="icon" id="this_input" placeholder="請點擊右側按鈕選擇" readonly required>
                                </div>
                                <button type="button" class="btn btn-secondary ms-3 px-4" data-bs-toggle="modal" data-bs-target="#iconModal">
                                    <i class="fas fa-search me-1"></i> 選擇圖示
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">名稱*</label>
                            <input type="text" name="name" class="form-control" required placeholder="例如: 官方網站">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">網址*</label>
                            <input type="text" name="url" class="form-control" required placeholder="https://">
                        </div>

                        <hr>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">開啟方式</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="target" id="target1" checked value="">
                                <label class="form-check-label" for="target1">開啟新視窗</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="target" id="target2" value="_self">
                                <label class="form-check-label" for="target2">本視窗開啟</label>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-success btn-lg save-btn" data-form="this_form1">
                                <i class="fas fa-save me-2"></i> 儲存設定
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="iconModalLabel fw-bold"><i class="fas fa-shapes me-2"></i>選擇常用圖示 (Font Awesome 6)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="icon-list-container">
                        <div class="row g-2 text-center">
                        @php
                            $icons = [
                                // 1-12
                                'fas fa-house', 'fas fa-user', 'fas fa-users', 'fas fa-gear', 'fas fa-magnifying-glass', 'fas fa-bell', 'fas fa-info', 'fas fa-question', 'fas fa-check', 'fas fa-xmark', 'fas fa-plus', 'fas fa-minus',
                                // 13-24
                                'fas fa-bars', 'fas fa-ellipsis', 'fas fa-link', 'fas fa-paperclip', 'fas fa-share-nodes', 'fas fa-location-dot', 'fas fa-globe', 'fas fa-house-user', 'fas fa-user-tie', 'fas fa-user-group', 'fas fa-user-gear', 'fas fa-user-shield',
                                // 25-36
                                'fas fa-circle-user', 'fas fa-id-card', 'fas fa-sliders', 'fas fa-filter', 'fas fa-eye', 'fas fa-eye-slash', 'fas fa-trash', 'fas fa-trash-arrow-up', 'fas fa-bookmark', 'fas fa-flag', 'fas fa-circle-check', 'fas fa-circle-xmark',
                                // 37-48
                                'fas fa-envelope', 'fas fa-phone', 'fas fa-comment', 'fas fa-comments', 'fas fa-paper-plane', 'fas fa-at', 'fas fa-wifi', 'fas fa-rss', 'fas fa-address-book', 'fas fa-user-plus', 'fas fa-envelope-open', 'fas fa-headset',
                                // 49-60
                                'fab fa-facebook', 'fab fa-facebook-messenger', 'fab fa-line', 'fab fa-youtube', 'fab fa-instagram', 'fab fa-google', 'fab fa-github', 'fab fa-apple', 'fab fa-android', 'fab fa-windows', 'fab fa-twitter', 'fab fa-linkedin',
                                // 61-72
                                'fab fa-whatsapp', 'fab fa-skype', 'fab fa-telegram', 'fab fa-weixin', 'fab fa-tiktok', 'fab fa-discord', 'fab fa-slack', 'fab fa-wordpress', 'fab fa-pushed', 'fab fa-chromecast', 'fab fa-vimeo-v', 'fab fa-reddit',
                                // 73-84
                                'fas fa-file', 'fas fa-file-lines', 'fas fa-file-pdf', 'fas fa-file-excel', 'fas fa-file-word', 'fas fa-file-powerpoint', 'fas fa-file-csv', 'fas fa-file-zipper', 'fas fa-file-image', 'fas fa-file-video', 'fas fa-file-audio', 'fas fa-file-code',
                                // 85-96
                                'fas fa-folder', 'fas fa-folder-open', 'fas fa-box-archive', 'fas fa-clipboard', 'fas fa-database', 'fas fa-table-list', 'fas fa-pen-to-square', 'fas fa-trash-can', 'fas fa-floppy-disk', 'fas fa-print', 'fas fa-copy', 'fas fa-paste',
                                // 97-108
                                'fas fa-scissors', 'fas fa-eraser', 'fas fa-highlighter', 'fas fa-font', 'fas fa-paragraph', 'fas fa-list', 'fas fa-list-ol', 'fas fa-indent', 'fas fa-outdent', 'fas fa-align-left', 'fas fa-align-center', 'fas fa-align-right',
                                // 109-120
                                'fas fa-cart-shopping', 'fas fa-bag-shopping', 'fas fa-credit-card', 'fas fa-money-bill', 'fas fa-coins', 'fas fa-wallet', 'fas fa-store', 'fas fa-briefcase', 'fas fa-chart-line', 'fas fa-chart-pie', 'fas fa-bullhorn', 'fas fa-award',
                                // 121-132
                                'fas fa-certificate', 'fas fa-tag', 'fas fa-tags', 'fas fa-truck', 'fas fa-barcode', 'fas fa-calculator', 'fas fa-scale-balanced', 'fas fa-gem', 'fas fa-handshake', 'fas fa-building-columns', 'fas fa-shop', 'fas fa-vault',
                                // 133-144
                                'fas fa-piggy-bank', 'fas fa-receipt', 'fas fa-file-invoice', 'fas fa-file-invoice-dollar', 'fas fa-landmark', 'fas fa-sign-hanging', 'fas fa-percent', 'fas fa-gift', 'fas fa-boxes-stacked', 'fas fa-truck-fast', 'fas fa-industry', 'fas fa-city',
                                // 145-156
                                'fas fa-image', 'fas fa-images', 'fas fa-camera', 'fas fa-video', 'fas fa-film', 'fas fa-music', 'fas fa-headphones', 'fas fa-microphone', 'fas fa-play', 'fas fa-pause', 'fas fa-stop', 'fas fa-volume-high',
                                // 157-168
                                'fas fa-volume-xmark', 'fas fa-gamepad', 'fas fa-tv', 'fas fa-ticket', 'fas fa-compact-disc', 'fas fa-radio', 'fas fa-podcast', 'fas fa-clapperboard', 'fas fa-backward', 'fas fa-forward', 'fas fa-step-backward', 'fas fa-step-forward',
                                // 169-180
                                'fas fa-shuffle', 'fas fa-repeat', 'fas fa-expand', 'fas fa-compress', 'fas fa-closed-captioning', 'fas fa-photo-film', 'fas fa-video-slash', 'fas fa-microphone-slash', 'fas fa-camera-rotate', 'fas fa-circle-play', 'fas fa-circle-stop', 'fas fa-sliders-h',
                                // 181-192
                                'fas fa-heart', 'fas fa-star', 'fas fa-thumbs-up', 'fas fa-thumbs-down', 'fas fa-sun', 'fas fa-moon', 'fas fa-cloud', 'fas fa-bolt', 'fas fa-snowflake', 'fas fa-fire', 'fas fa-droplet', 'fas fa-utensils',
                                // 193-204
                                'fas fa-mug-hot', 'fas fa-hospital', 'fas fa-stethoscope', 'fas fa-book', 'fas fa-graduation-cap', 'fas fa-tree', 'fas fa-bicycle', 'fas fa-car', 'fas fa-plane', 'fas fa-ship', 'fas fa-bus', 'fas fa-train',
                                // 205-216
                                'fas fa-kit-medical', 'fas fa-pills', 'fas fa-briefcase-medical', 'fas fa-dna', 'fas fa-virus', 'fas fa-face-smile', 'fas fa-face-meh', 'fas fa-face-frown', 'fas fa-glass-water', 'fas fa-ice-cream', 'fas fa-pizza-slice', 'fas fa-burger',
                                // 217-228
                                'fas fa-desktop', 'fas fa-laptop', 'fas fa-mobile-screen', 'fas fa-tablet-screen-button', 'fas fa-keyboard', 'fas fa-mouse', 'fas fa-key', 'fas fa-lock', 'fas fa-unlock-keyhole', 'fas fa-shield-halved', 'fas fa-server', 'fas fa-cloud-arrow-up',
                                // 229-240
                                'fas fa-cloud-arrow-down', 'fas fa-hard-drive', 'fas fa-fingerprint', 'fas fa-microchip', 'fas fa-usb', 'fas fa-battery-full', 'fas fa-plug', 'fas fa-power-off', 'fas fa-screwdriver-wrench', 'fas fa-hammer', 'fas fa-wrench', 'fas fa-calendar',
                            ];
                        @endphp                                                       
                            @foreach($icons as $icon)
                                <div class="col-lg-1 col-md-2 col-3">
                                    <button type="button" class="btn btn-light w-100 select-icon-btn shadow-sm" data-icon="{{ $icon }}">
                                        <i class="{{ $icon }} fa-xl"></i>
                                        <span class="icon-name-label">{{ str_replace(['fas fa-', 'fab fa-'], '', $icon) }}</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('down_body')                
    <script nonce="{{ $csp_nonce }}">
        // 排序自動切換
        function change_order_by(){
            var id = $('#type_id').val();
            var arr = @json($new_link_order_by);
            if(arr[id]) {
                $('#order_by').val(arr[id]);
            }
        }

        $(document).ready(function() {
            // 圖示選擇邏輯
            $('.select-icon-btn').on('click', function() {
                const iconClass = $(this).data('icon');
                
                // 更新輸入框與預覽
                $('#this_input').val(iconClass);
                $('#show_icon').attr('class', iconClass);
                
                // 視覺回饋：改一下顏色
                $('#show_icon').parent().css('border-color', '#198754');
                $('#show_icon').css('color', '#198754');

                // 關閉視窗
                $('#iconModal').modal('hide');
            });
        });
    </script>
@endsection