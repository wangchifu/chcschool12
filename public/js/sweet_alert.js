document.addEventListener('DOMContentLoaded', function() {        
        // 抓取畫面上所有 class 含有 save-btn 的元素		
		
		const saveBtns = document.querySelectorAll('.save-btn');    
		saveBtns.forEach(function(btn) {
			btn.addEventListener('click', function() {  
				const formId = this.getAttribute('data-form');
				const $form = $("#" + formId); // 取得 jQuery 物件

				// 1. 手動觸發 jquery-validate 驗證
				if ($form.valid()) {
					// 驗證成功：才隱藏按鈕並執行後續
					btn.style.display = 'none';
					sw_confirm2('確定儲存嗎？', formId, btn);
				} else {
					// 驗證失敗：按鈕保持顯示，提示訊息會由 jquery-validate 自動顯示在欄位旁
					console.log('表單驗證失敗，請檢查必填欄位');
				}
			});
		});
    

        // 抓取畫面上所有 class 含有 delete-btn 的元素 a 連結
		const deleteBtn1s = document.querySelectorAll('.delete-btn1');
		deleteBtn1s.forEach(function(btn) {
			btn.addEventListener('click', function(e) {                    
				e.preventDefault();                    				
				btn.style.display = 'none';              				    			
				const message = this.getAttribute('data-msg');
				const targetUrl = this.getAttribute('data-url');                                        
				if (typeof sw_confirm1 === 'function') {
					sw_confirm1(message,targetUrl,btn);
				}
			});
		});
		
		// 抓取畫面上所有 class 含有 save-btn 的元素 送 form
		const deleteBtn2s = document.querySelectorAll('.delete-btn2');
		deleteBtn2s.forEach(function(btn) {
			btn.addEventListener('click', function() {                    				    			
				btn.style.display = 'none';              				    			
				const message = this.getAttribute('data-msg') || '確定要刪除嗎？';
				const form = this.getAttribute('data-form');
				sw_confirm2(message,form,btn);                             				
			});
		});
});

function sw_confirm1(message,url,button) {
	Swal.fire({
		title: "操作確認",
		text: message,
		icon: 'question',
		showCancelButton: true,
		confirmButtonText:"確定",
		cancelButtonText:"取消",
	}).then(function(result) {
		if (result.value) {
		window.location = url;
		}
		else {
			// 使用者按取消：把按鈕顯示回來
            if (button) {
                button.style.display = ''; 
            }
			return false;
		}
	});
}
function sw_confirm2(message,id, button) {
	Swal.fire({
		title: "操作確認",
		text: message,
		icon: 'question',
		showCancelButton: true,
		confirmButtonText:"確定",
		cancelButtonText:"取消",
	}).then(function(result) {
		if (result.value) {
		//document.getElementById(id).submit();
		check_required(id);
		}
		else {
			// 使用者按取消：把按鈕顯示回來
            if (button) {
                button.style.display = ''; 
            }
			return false;
		}
	});
}

function sw_confirm3(message,fun) {
	Swal.fire({
		title: "操作確認",
		text: message,
		icon: 'question',
		showCancelButton: true,
		confirmButtonText:"確定",
		cancelButtonText:"取消",
	}).then(function(result) {
		if (result.value) {
		if (typeof fun === 'function') {
			fun(); // 呼叫傳進來的 function                    
		}                
		}
		else {
			return false;
		}
	});
}

function sw_confirm4(button, msg, form_id, action_value) {
// 先讓按鈕消失
	button.style.display = 'none';

	Swal.fire({
		title: msg,
		icon: 'question',
		showCancelButton: true,
		confirmButtonText: '確定',
		cancelButtonText: '取消',
	}).then((result) => {
		if (result.isConfirmed) {
			let form = document.getElementById(form_id);
			let hidden = document.createElement("input");
			hidden.type = "hidden";
			hidden.name = "form_action";
			hidden.value = action_value;
			form.appendChild(hidden);
		if (result.value) {
		//document.getElementById(id).submit();
			check_required(form_id,button);
		}
		else {
			return false;
		}
			//form.submit();
		} else {
			// 如果取消，要把按鈕再顯示回來
			button.style.display = '';
		}
	});
}        

function check_required(id,button) { 
	let form = document.getElementById(id); 
	let isValid = true; let missing = []; 
	// 記錄沒填的欄位名稱 
	$(form).find('input[required], select[required]').each(function() { 
		let val; 
		if ($(this).is('select')) { 
			val = $(this).find('option:selected').val(); 
		} else { 
			val = $(this).val().trim(); 
		} 
		let label = $(this).attr('id') ? $("label[for='" + $(this).attr('id') + "']").text().trim() : $(this).attr('name'); 
		if (val === '' || val === null) { 
			isValid = false; missing.push(label); $(this).css('border', '2px solid red'); 
		} else { 
			$(this).css('border', ''); 
		} 
	}); 
	if (!isValid) { event.preventDefault(); 
		let msg = "以下欄位尚未填寫：\r\n" + missing.join("\r\n"); button.style.display = ''; 
		sw_alert('錯誤！', msg); 
	} else { 
		form.submit(); 
	} 
}

function sw_alert(title,message){
	Swal.fire({
	title: title,
	text: message,
	icon: 'warning',
		});
	}
