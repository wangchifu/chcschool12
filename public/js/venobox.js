var vb = new VenoBox({
    selector: '.venobox',                
    numeration: true,
    infinigall: true,
    //share: ['facebook', 'twitter', 'linkedin', 'pinterest', 'download'],
    spinner: 'rotating-plane',
    maxWidth: '100%',
    maxHeight: '90%',
    onPostOpen: function(el, type, item, data){
    // 找到 iframe 並讓它取得焦點
    const iframe = document.querySelector('.vbox-content iframe');
    if (iframe) {
        iframe.focus();
    }
}
});

$(document).on('click', '.vbox-close', function() {
    vb.close();
});

$(document).on('click', '#closeVeno', function(e) {
            e.preventDefault();
            
            // 檢查目前是否處於 iframe 內嵌狀態
            if (window.self !== window.top) {
                // 策略 1：模擬點擊父視窗（外層網頁）中 VenoBox 的原生關閉按鈕
                var parentCloseBtn = window.parent.document.querySelector('.vbox-close');
                if (parentCloseBtn) {
                    parentCloseBtn.click();
                    return;
                }
                
                // 策略 2：如果找不到關閉鈕，直接呼叫父視窗全域的 vb 物件關閉法
                if (window.parent.vb && typeof window.parent.vb.close === 'function') {
                    window.parent.vb.close();
                    return;
                }
            }

            // 備用防禦：如果這頁不是被當成 iframe，而是單獨開啟時，用來關閉自己內文點開的照片燈箱
            if ($('.vbox-close').length > 0) {
                $('.vbox-close').trigger('click');
            }
        });
