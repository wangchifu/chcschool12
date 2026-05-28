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
                                // 1-12：基礎與網頁基本元件
                                'fas fa-house', 'fas fa-user', 'fas fa-users', 'fas fa-gear', 'fas fa-magnifying-glass', 'fas fa-bell', 'fas fa-info', 'fas fa-question', 'fas fa-check', 'fas fa-xmark', 'fas fa-plus', 'fas fa-minus',
                                // 13-24：導覽、連結與位置
                                'fas fa-bars', 'fas fa-ellipsis', 'fas fa-link', 'fas fa-paperclip', 'fas fa-share-nodes', 'fas fa-location-dot', 'fas fa-globe', 'fas fa-house-user', 'fas fa-user-tie', 'fas fa-user-group', 'fas fa-user-gear', 'fas fa-user-shield',
                                // 25-36：控制、視角與狀態
                                'fas fa-circle-user', 'fas fa-id-card', 'fas fa-sliders', 'fas fa-filter', 'fas fa-eye', 'fas fa-eye-slash', 'fas fa-trash', 'fas fa-trash-can', 'fas fa-bookmark', 'fas fa-flag', 'fas fa-circle-check', 'fas fa-circle-xmark',
                                // 37-48：通訊、訊息與聯絡
                                'fas fa-envelope', 'fas fa-phone', 'fas fa-comment', 'fas fa-comments', 'fas fa-paper-plane', 'fas fa-at', 'fas fa-wifi', 'fas fa-rss', 'fas fa-address-book', 'fas fa-user-plus', 'fas fa-envelope-open', 'fas fa-headset',
                                // 49-60：主流社群品牌 1
                                'fab fa-facebook', 'fab fa-facebook-messenger', 'fab fa-line', 'fab fa-youtube', 'fab fa-instagram', 'fab fa-google', 'fab fa-github', 'fab fa-apple', 'fab fa-android', 'fab fa-windows', 'fab fa-twitter', 'fab fa-linkedin',
                                // 61-72：主流社群品牌 2
                                'fab fa-whatsapp', 'fab fa-skype', 'fab fa-telegram', 'fab fa-weixin', 'fab fa-tiktok', 'fab fa-discord', 'fab fa-slack', 'fab fa-wordpress', 'fab fa-chromecast', 'fab fa-vimeo-v', 'fab fa-reddit', 'fab fa-usb',
                                // 73-84：檔案與文件類型
                                'fas fa-file', 'fas fa-file-lines', 'fas fa-file-pdf', 'fas fa-file-excel', 'fas fa-file-word', 'fas fa-file-powerpoint', 'fas fa-file-csv', 'fas fa-file-zipper', 'fas fa-file-image', 'fas fa-file-video', 'fas fa-file-audio', 'fas fa-file-code',
                                // 85-96：資料夾與文書編輯 1
                                'fas fa-folder', 'fas fa-folder-open', 'fas fa-box-archive', 'fas fa-clipboard', 'fas fa-database', 'fas fa-table-list', 'fas fa-pen-to-square', 'fas fa-floppy-disk', 'fas fa-print', 'fas fa-copy', 'fas fa-paste', 'fas fa-clone',
                                // 97-108：文書編輯與排版 2
                                'fas fa-scissors', 'fas fa-eraser', 'fas fa-highlighter', 'fas fa-font', 'fas fa-paragraph', 'fas fa-list', 'fas fa-list-ol', 'fas fa-indent', 'fas fa-outdent', 'fas fa-align-left', 'fas fa-align-center', 'fas fa-align-right',
                                // 109-120：商務、購物與金融 1
                                'fas fa-cart-shopping', 'fas fa-bag-shopping', 'fas fa-credit-card', 'fas fa-money-bill', 'fas fa-coins', 'fas fa-wallet', 'fas fa-store', 'fas fa-briefcase', 'fas fa-chart-line', 'fas fa-chart-pie', 'fas fa-bullhorn', 'fas fa-award',
                                // 121-132：商務、購物與金融 2 (🎯 在尾巴精密補上 'fas fa-ticket-simple' 湊滿 12 個)
                                'fas fa-certificate', 'fas fa-tag', 'fas fa-tags', 'fas fa-truck', 'fas fa-barcode', 'fas fa-calculator', 'fas fa-scale-balanced', 'fas fa-gem', 'fas fa-handshake', 'fas fa-building-columns', 'fas fa-shop', 'fas fa-vault', 'fas fa-ticket-simple',
                                // 133-144：商務、購物與金融 3
                                'fas fa-piggy-bank', 'fas fa-receipt', 'fas fa-file-invoice', 'fas fa-file-invoice-dollar', 'fas fa-landmark', 'fas fa-percent', 'fas fa-gift', 'fas fa-boxes-stacked', 'fas fa-truck-fast', 'fas fa-industry', 'fas fa-city', 'fas fa-money-bill-wave',
                                // 145-156：多媒體、影音播放 1
                                'fas fa-image', 'fas fa-images', 'fas fa-camera', 'fas fa-video', 'fas fa-film', 'fas fa-music', 'fas fa-headphones', 'fas fa-microphone', 'fas fa-play', 'fas fa-pause', 'fas fa-stop', 'fas fa-volume-high',
                                // 157-168：多媒體、影音播放 2
                                'fas fa-volume-xmark', 'fas fa-gamepad', 'fas fa-tv', 'fas fa-ticket', 'fas fa-compact-disc', 'fas fa-radio', 'fas fa-podcast', 'fas fa-clapperboard', 'fas fa-backward', 'fas fa-forward', 'fas fa-step-backward', 'fas fa-step-forward',
                                // 169-180：多媒體、影音播放 3
                                'fas fa-shuffle', 'fas fa-repeat', 'fas fa-expand', 'fas fa-compress', 'fas fa-closed-captioning', 'fas fa-video-slash', 'fas fa-microphone-slash', 'fas fa-circle-play', 'fas fa-circle-stop', 'fas fa-backward-step', 'fas fa-forward-step', 'fas fa-eject',
                                // 181-192：生活、自然與天氣 1
                                'fas fa-heart', 'fas fa-star', 'fas fa-thumbs-up', 'fas fa-thumbs-down', 'fas fa-sun', 'fas fa-moon', 'fas fa-cloud', 'fas fa-bolt', 'fas fa-snowflake', 'fas fa-fire', 'fas fa-droplet', 'fas fa-utensils',
                                // 193-204：生活、自然與交通 2
                                'fas fa-mug-hot', 'fas fa-hospital', 'fas fa-stethoscope', 'fas fa-book', 'fas fa-graduation-cap', 'fas fa-tree', 'fas fa-bicycle', 'fas fa-car', 'fas fa-plane', 'fas fa-ship', 'fas fa-bus', 'fas fa-train',
                                // 205-216：醫療衛生與飲食
                                'fas fa-kit-medical', 'fas fa-pills', 'fas fa-briefcase-medical', 'fas fa-dna', 'fas fa-virus', 'fas fa-face-smile', 'fas fa-face-meh', 'fas fa-face-frown', 'fas fa-glass-water', 'fas fa-ice-cream', 'fas fa-pizza-slice', 'fas fa-burger',
                                // 217-228：科技產品與資訊安全 1
                                'fas fa-desktop', 'fas fa-laptop', 'fas fa-mobile-screen', 'fas fa-tablet-screen-button', 'fas fa-keyboard', 'fas fa-mouse', 'fas fa-key', 'fas fa-lock', 'fas fa-unlock-keyhole', 'fas fa-shield-halved', 'fas fa-server', 'fas fa-cloud-arrow-up',
                                // 229-240：科技產品與硬體維護 2
                                'fas fa-cloud-arrow-down', 'fas fa-hard-drive', 'fas fa-fingerprint', 'fas fa-microchip', 'fas fa-battery-full', 'fas fa-plug', 'fas fa-power-off', 'fas fa-screwdriver-wrench', 'fas fa-hammer', 'fas fa-wrench', 'fas fa-calendar',
                                // 241-252：時間、計時與日程
                                'fas fa-calendar-days', 'fas fa-calendar-plus', 'fas fa-calendar-minus', 'fas fa-calendar-check', 'fas fa-calendar-xmark', 'fas fa-clock', 'fas fa-hourglass', 'fas fa-hourglass-start', 'fas fa-stopwatch', 'fas fa-calendar-day', 'fas fa-calendar-week', 'fas fa-business-time',
                                // 253-264：地理、地圖與公共建築
                                'fas fa-map', 'fas fa-map-pin', 'fas fa-compass', 'fas fa-signs-post', 'fas fa-route', 'fas fa-location-crosshairs', 'fas fa-location-arrow', 'fas fa-hotel', 'fas fa-church', 'fas fa-mosque', 'fas fa-school', 'fas fa-university',
                                // 265-276：交通、重工業與基礎設施
                                'fas fa-truck-pickup', 'fas fa-motorcycle', 'fas fa-helicopter', 'fas fa-rocket', 'fas fa-tractor', 'fas fa-subway', 'fas fa-wheelchair', 'fas fa-gas-pump', 'fas fa-charging-station', 'fas fa-road', 'fas fa-anchor', 'fas fa-gavel',
                                // 277-288：天文天氣、氣溫與自然現象
                                'fas fa-cloud-sun', 'fas fa-cloud-moon', 'fas fa-cloud-rain', 'fas fa-cloud-showers-heavy', 'fas fa-wind', 'fas fa-smog', 'fas fa-meteor', 'fas fa-temperature-high', 'fas fa-temperature-low', 'fas fa-rainbow', 'fas fa-seedling', 'fas fa-leaf',
                                // 289-300：警告、禁止與安全提示
                                'fas fa-triangle-exclamation', 'fas fa-circle-exclamation', 'fas fa-shield', 'fas fa-biohazard', 'fas fa-radiation', 'fas fa-skull-crossbones', 'fas fa-ban', 'fas fa-circle-minus', 'fas fa-hand', 'fas fa-eye-dropper', 'fas fa-universal-access', 'fas fa-lock-open',
                                // 301-312：手勢互動與點擊 1
                                'fas fa-hand-pointer', 'fas fa-hand-back-fist', 'fas fa-hand-scissors', 'fas fa-hand-fist', 'fas fa-hand', 'fas fa-hands-holding', 'fas fa-hands-praying', 'fas fa-hand-holding-dollar', 'fas fa-hand-holding-heart', 'fas fa-hand-holding-medical', 'fas fa-hands-clapping', 'fas fa-hands-bubbles',
                                // 313-324：居家用品、電器與衛浴
                                'fas fa-bed', 'fas fa-chair', 'fas fa-couch', 'fas fa-door-open', 'fas fa-door-closed', 'fas fa-toilet', 'fas fa-shower', 'fas fa-bath', 'fas fa-soap', 'fas fa-faucet', 'fas fa-faucet-drip', 'fas fa-lightbulb',
                                // 325-336：飲食文化、蔬果與輕食
                                'fas fa-spoon', 'fas fa-wine-glass', 'fas fa-bottle-water', 'fas fa-beer-mug-empty', 'fas fa-martini-glass', 'fas fa-cake-candles', 'fas fa-cookie', 'fas fa-bread-slice', 'fas fa-cheese', 'fas fa-egg', 'fas fa-apple-whole', 'fas fa-carrot',
                                // 337-348：大自然、地貌與山脈
                                'fas fa-clover', 'fas fa-cannabis', 'fas fa-mountain', 'fas fa-mountain-sun', 'fas fa-water', 'fas fa-wave-square', 'fas fa-fire-flame-curved', 'fas fa-fire-flame-simple', 'fas fa-campground', 'fas fa-sun-plant-wilt', 'fas fa-tree-city', 'fas fa-dungeon',
                                // 349-360：昆蟲與自然界動物
                                'fas fa-paw', 'fas fa-spider', 'fas fa-mosquito', 'fas fa-bug', 'fas fa-fish', 'fas fa-frog', 'fas fa-worm', 'fas fa-feather', 'fas fa-bone', 'fas fa-crow', 'fas fa-dove', 'fas fa-dragon',
                                // 361-372：體育項目與戶外休閒 1
                                'fas fa-football', 'fas fa-basketball', 'fas fa-bowling-ball', 'fas fa-dumbbell', 'fas fa-swimmer', 'fas fa-person-running', 'fas fa-person-walking', 'fas fa-person-hiking', 'fas fa-person-biking', 'fas fa-medal', 'fas fa-trophy', 'fas fa-ranking-star',
                                // 373-384：服飾、隨身配件與裝飾
                                'fas fa-shirt', 'fas fa-socks', 'fas fa-glasses', 'fas fa-mitten', 'fas fa-crown', 'fas fa-shoe-prints', 'fas fa-suitcase', 'fas fa-mask', 'fas fa-graduation-cap', 'fas fa-user-ninja', 'fas fa-user-astronaut', 'fas fa-user-secret',
                                // 385-396：軟體開發、原始碼與終端機
                                'fas fa-code-branch', 'fas fa-code-commit', 'fas fa-code-pull-request', 'fas fa-code-merge', 'fas fa-terminal', 'fas fa-network-wired', 'fas fa-ethernet', 'fas fa-satellite', 'fas fa-satellite-dish', 'fas fa-sim-card', 'fas fa-code', 'fas fa-cubes',
                                // 397-408：人體器官、醫療檢驗與生物科學
                                'fas fa-heart-pulse', 'fas fa-brain', 'fas fa-tooth', 'fas fa-lungs', 'fas fa-vial', 'fas fa-vials', 'fas fa-microscope', 'fas fa-weight-scale', 'fas fa-wheelchair-move', 'fas fa-clipboard-question', 'fas fa-user-doctor', 'fas fa-notes-medical',
                                // 409-420：各式商務物件、工業齒輪
                                'fas fa-money-check', 'fas fa-money-check-dollar', 'fas fa-money-bill-transfer', 'fas fa-money-bill-trend-up', 'fas fa-warehouse', 'fas fa-gears', 'fas fa-boxes-packing', 'fas fa-download', 'fas fa-upload', 'fas fa-cart-plus', 'fas fa-cash-register', 'fas fa-wallet',
                                // 421-432：美術工具、工程量尺與製圖
                                'fas fa-paint-roller', 'fas fa-brush', 'fas fa-palette', 'fas fa-pen', 'fas fa-pen-clip', 'fas fa-marker', 'fas fa-pencil', 'fas fa-ruler', 'fas fa-ruler-combined', 'fas fa-ruler-horizontal', 'fas fa-ruler-vertical', 'fas fa-compass-drafting',
                                // 433-444：幾何形狀與介面符號
                                'fas fa-circle', 'fas fa-square', 'fas fa-asterisk', 'fas fa-hashtag', 'fas fa-slash', 'fas fa-delete-left', 'fas fa-signature', 'fas fa-check-double', 'fas fa-square-check', 'fas fa-toggle-on', 'fas fa-toggle-off', 'fas fa-ellipsis-vertical',
                                // 445-456：經典線條箭頭 (上下左右)
                                'fas fa-arrow-up', 'fas fa-arrow-down', 'fas fa-arrow-left', 'fas fa-arrow-right', 'fas fa-arrow-up-long', 'fas fa-arrow-down-long', 'fas fa-arrow-left-long', 'fas fa-arrow-right-long', 'fas fa-arrows-up-down', 'fas fa-arrows-left-right', 'fas fa-arrows-up-down-left-right', 'fas fa-arrow-turn-up',
                                // 457-468：系統控制箭頭
                                'fas fa-arrow-turn-down', 'fas fa-right-from-bracket', 'fas fa-right-to-bracket', 'fas fa-reply', 'fas fa-reply-all', 'fas fa-share', 'fas fa-arrow-rotate-left', 'fas fa-arrow-rotate-right', 'fas fa-arrows-rotate', 'fas fa-arrows-spin', 'fas fa-arrow-trend-up', 'fas fa-arrow-trend-down',
                                // 469-480：圓圈箭頭與網頁選單控制
                                'fas fa-circle-arrow-up', 'fas fa-circle-arrow-down', 'fas fa-circle-arrow-left', 'fas fa-circle-arrow-right', 'fas fa-chevron-up', 'fas fa-chevron-down', 'fas fa-chevron-left', 'fas fa-chevron-right', 'fas fa-angles-up', 'fas fa-angles-down', 'fas fa-angles-left', 'fas fa-angles-right',
                                // 481-492：視窗控制、縮放與幾何按鈕
                                'fas fa-caret-up', 'fas fa-caret-down', 'fas fa-caret-left', 'fas fa-caret-right', 'fas fa-maximize', 'fas fa-minimize', 'fas fa-play', 'fas fa-circle-chevron-up', 'fas fa-circle-chevron-down', 'fas fa-circle-chevron-left', 'fas fa-circle-chevron-right', 'fas fa-compress',
                                // 493-504：跨國電商與多元支付品牌
                                'fab fa-amazon', 'fab fa-apple-pay', 'fab fa-google-pay', 'fab fa-paypal', 'fab fa-stripe', 'fab fa-cc-visa', 'fab fa-cc-mastercard', 'fab fa-cc-amex', 'fab fa-cc-discover', 'fab fa-cc-jcb', 'fab fa-shopify', 'fab fa-ebay',
                                // 505-516：技術論壇與大型品牌
                                'fab fa-microsoft', 'fab fa-stack-overflow', 'fab fa-stack-exchange', 'fab fa-medium', 'fab fa-quora', 'fab fa-snapchat', 'fab fa-tumblr', 'fab fa-twitch', 'fab fa-vimeo', 'fab fa-yahoo', 'fab fa-y-combinator', 'fab fa-blogger',
                                // 517-528：娛樂休閒、遊戲與卡牌符號
                                'fas fa-dice', 'fas fa-dice-five', 'fas fa-puzzle-piece', 'fas fa-chess', 'fas fa-chess-knight', 'fas fa-chess-king', 'fas fa-chess-queen', 'fas fa-heart-crack', 'fas fa-ghost', 'fas fa-clapperboard', 'fas fa-bowling-pins', 'fas fa-dice-one',
                                // 529-540：日常事物、生活道具
                                'fas fa-umbrella', 'fas fa-binoculars', 'fas fa-tent', 'fas fa-box', 'fas fa-boxes-stacked', 'fas fa-couch', 'fas fa-key', 'fas fa-compass', 'fas fa-calculator', 'fas fa-gavel', 'fas fa-anchor', 'fas fa-wrench',
                                // 541-552：雲端、工程開發周邊品牌 1
                                'fab fa-salesforce', 'fab fa-hubspot', 'fab fa-digital-ocean', 'fab fa-aws', 'fab fa-docker', 'fab fa-jenkins', 'fab fa-node-js', 'fab fa-npm', 'fab fa-react', 'fab fa-vuejs', 'fab fa-angular', 'fab fa-git-alt',
                                // 553-564：網頁周邊語系、框架品牌 2
                                'fab fa-sass', 'fab fa-less', 'fab fa-html5', 'fab fa-css3', 'fab fa-js', 'fab fa-php', 'fab fa-python', 'fab fa-java', 'fab fa-rust', 'fab fa-swift', 'fab fa-golang', 'fab fa-laravel',
                                // 565-576：生活小品、人物與職業狀態
                                'fas fa-user-nurse', 'fas fa-user-graduate', 'fas fa-user-clock', 'fas fa-user-check', 'fas fa-user-xmark', 'fas fa-user-minus', 'fas fa-user-lock', 'fas fa-user-injured', 'fas fa-user-pen', 'fas fa-people-group', 'fas fa-people-roof', 'fas fa-child',
                                // 577-588：公共服務、圖章、地標與指引
                                'fas fa-building', 'fas fa-building-shield', 'fas fa-building-un', 'fas fa-building-user', 'fas fa-landmark-flag', 'fas fa-monument', 'fas fa-place-of-worship', 'fas fa-toilet-paper', 'fas fa-truck-medical', 'fas fa-file-medical', 'fas fa-atom', 'fas fa-radiation',
                                // 589-600：商務趨勢、符號與指標擴充
                                'fas fa-arrow-trend-up', 'fas fa-arrow-trend-down', 'fas fa-chart-simple', 'fas fa-chart-gantt', 'fas fa-chart-bar', 'fas fa-arrow-up-right-dots', 'fas fa-arrow-up-from-ground-water', 'fas fa-bore-hole', 'fas fa-bucket', 'fas fa-clipboard-list', 'fas fa-clipboard-check', 'fas fa-circle-nodes'
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