<?php
    // 取得所有 RSS 設定資料
    $rss_feeds = \App\Models\RssFeed::all();
?>

@foreach($rss_feeds as $rss_feed)
    <?php   
        libxml_use_internal_errors(true); // 開啟內部錯誤處理

        $rss = new DOMDocument();   

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        // 讀取 XML 內容
        $xmlContent = @file_get_contents($rss_feed->url, false, $context);

        if ($xmlContent) {
            // 👉 濾掉常見錯誤符號
            $xmlContent = preg_replace('/&(?!amp;|lt;|gt;|quot;|apos;)/', '&amp;', $xmlContent);

            if (!$rss->loadXML($xmlContent)) {
                libxml_clear_errors();
            }
        }
        
        $feeds = array();
        $i = 1;
        foreach ($rss->getElementsByTagName('item') as $node) {
            if($i > $rss_feed->num) break;
            
            // 處理 description
            if (!empty($node->getElementsByTagName('description')->item(0)->nodeValue)) {
                $desc = $node->getElementsByTagName('description')->item(0)->nodeValue;
            } else {
                $desc = "說明";
            }
            
            $item = array ( 
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'desc' => $desc,
                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
            );
            array_push($feeds, $item);
            $i++;
        }
    ?>

    <div class="mt-4 mb-2">
        <span class="fw-bold text-dark" style="font-size: 1.1rem;">
            <i class="fas fa-rss text-warning me-1"></i> {{ $rss_feed->title }}
        </span>
    </div>

    @if($rss_feed->type == 1)
        <div class="list-group mb-4 shadow-sm">
            @foreach($feeds as $k => $v)
                <a href="{{ $v['link'] }}" target="_blank" class="list-group-item list-group-item-action py-2">
                    {{ $v['title'] }}
                </a>
            @endforeach
        </div>
    @endif

    @if($rss_feed->type == 2)
        <div class="row g-3 mb-4 rss-content-wrapper">
            @foreach($feeds as $k => $v)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-light p-2 text-truncate" style="font-size: 0.85rem;" title="{{ $v['title'] }}">
                            {{ $v['title'] }}
                        </div>
                        <div class="card-body p-2" style="font-size: 0.9rem;">
                            <a href="{{ $v['link'] }}" target="_blank" class="text-decoration-none text-dark">
                                {!! $v['desc'] !!}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <script>
            $(document).ready(function() {
                // 針對當前 RSS 區塊內的圖片加上 Bootstrap 5 響應式類別
                $('.rss-content-wrapper img').addClass('img-fluid rounded mt-1');
            });
        </script>
    @endif

@endforeach