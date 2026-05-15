@php
        //榮譽榜資料庫資料
$honors = \App\Models\Post::where('insite', '2')
        ->where(function ($query) {
            $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
        })->orderBy('top', 'DESC')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);    
@endphp
<style nonce="{{ $csp_nonce }}">
    /* 跑馬燈外框 */
    .honor-marquee-box {
        display: flex;
        align-items: center;
        height: 45px;
        background: #fff5f5;
        border: 2px solid #e3342f;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: 4px 4px 0px #f8d7da;
        margin-bottom: 15px;
    }

    /* 左側固定標籤 */
    .honor-label {
        background: #e3342f;
        color: white;
        padding: 0 20px;
        height: 100%;
        display: flex;
        align-items: center;
        font-weight: bold;
        font-size: 1.2rem;
        z-index: 10;
        white-space: nowrap;
    }

    /* 捲動容器 */
    .honor-container {
        flex: 1;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    /* 捲動內容本體 */
    .honor-content {
        display: flex;
        flex-direction: row;
        align-items: center;
        height: 100%;
        white-space: nowrap;
    }

    /* 單個項目間距 */
    .honor-item {
        margin-right: 40px;
        display: flex;
        align-items: center;
    }

    /* 連結樣式 */
    .honor-link {
        text-decoration: none !important;
        color: #333;
        font-weight: bold;
        font-size: 1.2rem;
        white-space: nowrap;
        transition: color 0.2s;
    }

    /* 懸停變色 (取代原本的 onmouseover) */
    .honor-link:hover {
        color: #e3342f !important;
    }
</style>

<div class="honor-marquee-box">
    <div class="honor-label">
        🏆 榮譽榜
    </div>

    <div id="honor-container" class="honor-container">
        <div id="honor-content" class="honor-content">
            @foreach($honors as $honor)
                <div class="honor-item">
                    <a href="{{ route('posts.show',$honor->id) }}" class="honor-link">
                        🎉 {{ $honor->title }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce }}">
document.addEventListener("DOMContentLoaded", function() {
    const direction = "left"; 
    const amount = 1.2;       

    const container = document.getElementById('honor-container');
    const content = document.getElementById('honor-content');

    if (!container || !content) return;

    content.style.position = 'absolute';
    content.style.display = 'flex';
    content.style.flexDirection = 'row';
    content.style.whiteSpace = 'nowrap';

    const containerWidth = container.offsetWidth;
    const contentWidth = content.offsetWidth;

    const animName = 'marqueeMoveHonorLeft';
    
    const keyframes = `@keyframes ${animName} { 
        0% { transform: translateX(${containerWidth}px); } 
        100% { transform: translateX(-${contentWidth}px); } 
    }`;

    const style = document.createElement('style');
    style.setAttribute('nonce', '{{ $csp_nonce }}'); // JS 動態產生的 style 也要 nonce
    style.innerHTML = keyframes;
    document.head.appendChild(style);

    const duration = (contentWidth + containerWidth) / (amount * 50);

    content.style.animation = `${animName} ${duration}s linear infinite`;

    container.onmouseover = () => content.style.animationPlayState = 'paused';
    container.onmouseout = () => content.style.animationPlayState = 'running';
});
</script>