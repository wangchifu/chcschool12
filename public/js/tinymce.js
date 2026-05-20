tinymce.init({
	selector: 'textarea#my_editor',
	language: 'zh_TW', // 設置語言為繁體中文
	language_url: '/js/zh_TW.js', // 加這行
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

tinymce.init({
    selector: 'textarea#my_editor2',
    language: 'zh_TW', // 設置語言為繁體中文
    language_url: '/js/zh_TW.js',
    
    // 🛡️ 安全優化 1：只啟用最基礎且安全的套件，徹底移除 code (檢視原始碼)、table 與 image 功能
    plugins: 'fullscreen link lists paste', 
    
    // 🛡️ 安全優化 2：精簡工具列，提供基礎的文字樣式調整
    // fontsize (文字大小)、forecolor (文字顏色)、bold (粗體)、italic (斜體)
    toolbar: 'undo redo | fontsize | bold italic underline forecolor | alignleft aligncenter alignright | link unlink | bullist numlist | removeformat fullscreen',
    
    // 🛡️ 安全優化 3：防範 XSS 攻擊的安全設定
    // 徹底移除 valid_elements: '*[*]' 與 verify_html: false！
    // 讓 TinyMCE 自動幫你過濾掉不安全的 <script>、<iframe> 或 javascript: 偽協議。
    verify_html: true, 
    
    // 限制只能輸入基本的 HTML 標籤，進一步防止惡意攻擊者繞過檢查
    allowed_elements: 'p,span,br,strong,em,u,ul,ol,li,a',
    extended_valid_elements: 'a[href|target=_blank|title],span[style],p[style]',

    // 基礎排版設定保持一致
    forced_root_block: false,      // 避免自動包裹 `<p>` 標籤
    remove_trailing_brs: false,    // 不刪除尾部 <br>
    convert_urls: false,           // 禁止 TinyMCE 轉換圖片 URL
    relative_urls: false,          // 確保使用絕對 URL
    remove_script_host: false      // 保留完整的 URL
});
