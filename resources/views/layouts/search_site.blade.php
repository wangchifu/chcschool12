{{-- 🎯 1. 表單部分：移除了 <table>，改用 Bootstrap 5 專門處理這種排版的 input-group --}}
<form method="get" action="{{ asset('search_site.php') }}" target="_blank" id="key_form">
    <div class="input-group">
        <input type="text" name="key_word" class="form-control" id="key_word" required placeholder="請輸入關鍵字">
        
        {{-- 維持 type="submit"，並將 btn-sm 移除以配合 input-group 的標準高度 --}}
        <button type="submit" class="btn btn-primary" id="search_btn">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>

{{-- 🎯 2. JavaScript 部分：加入 nonce 憑證，並用安全的方式監聽表單送出行為 --}}
<script nonce="{{ $csp_nonce }}">
    $(document).ready(function() {
        
        // 監聽表單的 submit 事件（不論是點擊搜尋按鈕，還是在輸入框按 Enter 鍵都會觸發）
        $('#key_form').on('submit', function(e) {
            var keywordInput = $('#key_word');
            var keywordValue = $.trim(keywordInput.val()); // 移除字串前後的空白
            
            if (keywordValue === "") {
                e.preventDefault(); // 如果只有空白空白，阻止表單送出
                alert('沒有輸入關鍵字');
            } else {
                // 🎯 因為 target="_blank" 會在新分頁打開搜尋結果
                // 這裡使用 setTimeout 延遲 100 毫秒，等瀏覽器分頁開出去後，再清空原本這一頁的輸入框
                setTimeout(function() {
                    keywordInput.val("");
                }, 100);
            }
        });
        
    });
</script>