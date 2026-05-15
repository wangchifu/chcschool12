<?php

namespace App\Http\Controllers;

use App\Models\RssFeed;
use Illuminate\Http\Request;

class RssFeedController extends Controller
{
    public function index()
    {
        $rss_feeds = RssFeed::all();
        $data = [
            'rss_feeds' => $rss_feeds,
        ];
        return view('rss_feeds.index', $data);
    }

    public function edit(RssFeed $rss_feed)
    {        
        $data = [
            'rss_feed' => $rss_feed,
        ];
        return view('rss_feeds.edit', $data);
    }

    public function update(Request $request,RssFeed $rss_feed)
    {
        $att = $request->all();
        $rss_feed->update($att);
        echo "
            <script>
            // 確保頁面加載完成後執行
            window.onload = function() {
                // 檢查父頁面是否存在且可以訪問 jQuery
                if (window.parent && window.parent.$) {
                    // 關閉 venobox 視窗
                    if (typeof window.parent.$.venobox !== 'undefined') {
                        window.parent.$.venobox.close();  // 關閉 venobox 視窗
                    }

                    // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                    window.parent.location.reload();                
                }
            };
            </script>";
    }

    public function store(Request $request)
    {
        $att = $request->all();
        RssFeed::create($att);
        return redirect()->route('rss_feeds.index');
    }

    public function destory(RssFeed $rss_feed)
    {
        $rss_feed->delete();
        return redirect()->route('rss_feeds.index');
    }
}
