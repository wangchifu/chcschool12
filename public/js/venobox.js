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
