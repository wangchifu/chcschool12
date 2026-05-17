{{-- 1. 符合 CSP 規範的全域安全 CSS 區塊 --}}
<style nonce="{{ $csp_nonce }}">
    /* 圖片固定 16:9 比例與裁切 */
    .fixed-size-img {
        width: 100%;            /* 寬度撐滿 Bootstrap 的欄位 */
        aspect-ratio: 16 / 9;    /* 設定為 16:9 比例 */
        object-fit: cover;       /* 圖片裁切以填滿容器，不變形 */
        object-position: center; /* 裁切時以中心點為準 */
        display: block;          /* 消除圖片下方微小間隙 */
        transition: opacity 0.2s ease-in-out; /* 增加平滑過渡效果 */
    }
    
    /* 滑鼠懸停時縮圖淡出效果 (取代舊式 filter 與 -moz-opacity) */
    .figure a:hover .fixed-size-img {
        opacity: 0.5;
    }
</style>

{{-- 2. Bootstrap 5 Tab 導航標籤 --}}
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="photo_type_home-tab" data-bs-toggle="tab" data-bs-target="#photo_type_home" type="button" role="tab" aria-controls="photo_type_home" aria-selected="true">全部</button>
    </li>
    <?php $p = 1; ?>
    @foreach($photo_types as $photo_type)
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="photo_type{{ $p }}-tab" data-bs-toggle="tab" data-bs-target="#photo_type{{ $p }}" type="button" role="tab" aria-controls="photo_type{{ $p }}" aria-selected="false">{{ $photo_type->name }}</button>
        </li>
        <?php $p++; ?>
    @endforeach
</ul>

{{-- 3. Tab 內容面板 --}}
<div class="tab-content border border-top-0 p-3 bg-white" id="myTabContent">
    
    {{-- 面板：全部 --}}
    <div class="tab-pane fade show active" id="photo_type_home" role="tabpanel" aria-labelledby="photo_type_home-tab">
        <div class="container-fluid">
            {{-- g-3 代表格線間距 --}}
            <div class="row justify-content-start g-3">        
                @foreach($photo_links as $photo_link)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <?php
                        $school_code = school_code();
                        $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                        ?>
                        <figure class="figure w-100 mb-2">
                            <a href="{{ $photo_link->url }}" target="_blank">
                                <img src="{{ asset($img) }}" class="figure-img img-fluid rounded fixed-size-img" alt="編號{{ $photo_link->id }}圖片連結的縮圖">
                            </a>
                            {{-- 使用 text-break 取代原有的 style 控制，更符合 BS5 規範 --}}
                            <figcaption class="figure-caption text-break">
                                <small>{{ $photo_link->name }}</small>
                            </figcaption>
                        </figure>
                    </div>                    
                @endforeach
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <small><a href="{{ route('photo_links.show') }}" class="text-decoration-none"><i class="far fa-hand-point-up"></i> 更多 圖片連結...</a></small>
                </div>
            </div>
        </div>
    </div>

    {{-- 面板：各分類迴圈 --}}
    <?php $p = 1; ?>
    @foreach($photo_types as $photo_type)
        <div class="tab-pane fade" id="photo_type{{ $p }}" role="tabpanel" aria-labelledby="photo_type{{ $p }}-tab">
            <div class="container-fluid">
                <div class="row justify-content-start g-3">
                    <?php 
                    $photo_links = \App\Models\PhotoLink::where('photo_type_id', $photo_type->id)
                                    ->orderBy('order_by', 'DESC')
                                    ->get();
                    ?>       
                    @foreach($photo_links as $photo_link)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <?php
                            $school_code = school_code();
                            $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                            ?>
                            <figure class="figure w-100 mb-2">
                                <a href="{{ $photo_link->url }}" target="_blank">
                                    <img src="{{ asset($img) }}" class="figure-img img-fluid rounded fixed-size-img" alt="編號{{ $photo_link->id }}圖片連結的縮圖">
                                </a>
                                <figcaption class="figure-caption text-break">
                                    <small>{{ $photo_link->name }}</small>
                                </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <small><a href="{{ route('photo_links.show', $photo_type->id) }}" class="text-decoration-none"><i class="far fa-hand-point-up"></i> 更多 {{ $photo_type->name }} 圖片連結...</a></small>
                    </div>
                </div>
            </div>
        </div>
        <?php $p++; ?>
    @endforeach
</div>