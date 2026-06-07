@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '行政待辦 | ')

@section('content')
    <style nonce="{{ $csp_nonce }}">
        .gif {
            position:absolute;
            top:20%;
            left:10%;
            z-index:10;
        }
        /* ✨ 新增這段：讓圖片預設隱藏，並在滑鼠移過去時顯示手指圖標 */
        .gif img {
            display: none;
            cursor: pointer;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-md-11">
            @include('tasks.form')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('tasks.index') }}">待辦</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index2') }}">完成</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index3') }}">無關</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.self') }}"><i class="fas fa-plus"></i> 自己</a>
                </li>
            </ul>
            <br>
            <div id="task_content">
                @foreach($user_tasks as $user_task)
                    <?php
                        $files = get_files(storage_path('app/privacy/'.$school_code.'/tasks/'.$user_task->task_id));
                    ?>
                    {{-- 🛠️ 1. Form::open 改為標準 <form> 標籤並補上 @csrf --}}
                    <form action="{{ route('tasks.user_condition', $user_task->id) }}" method="POST" id="user_condition{{ $user_task->id }}" onsubmit="return false">
                        @csrf
                        <div class="form-check form-check-inline">
                            <input class="form-check-input task-radio" type="radio" name="condition" id="inlineRadio{{ $user_task->id }}_2" value="2">
                            <label class="form-check-label text-success" for="inlineRadio{{ $user_task->id }}_2">完成</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input task-radio" type="radio" name="condition" id="inlineRadio{{ $user_task->id }}_3" value="3">
                            <label class="form-check-label text-primary" for="inlineRadio{{ $user_task->id }}_3">無關</label>
                        </div>
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="user_task_id" value="{{ $user_task->id }}">
                        <input type="hidden" name="old_condition" value="{{ $user_task->condition }}">
                    {{-- 🛠️ 2. Form::close() 改為 </form> --}}
                    </form>

                    @if($user_task->task->disable)
                    <span style="text-decoration:line-through;text-decoration-color: red;word-wrap:break-word;">
                        {{-- 🛠️ 3. badge badge-danger 改為 Bootstrap 5 的 badge bg-danger --}}
                        <span class="badge bg-danger">已廢</span> {{ $user_task->task->title }}
                    </span>
                    @else
                    <span style="word-wrap:break-word;">
                        {{ $user_task->task->title }}
                    </span>
                    @endif
                        @if(!empty($files))
                            <br>
                            附件：
                            <?php $n=1; ?>
                            @foreach($files as $k=>$v)
                                <?php
                                $file = $school_code."/tasks/".$user_task->task_id."/".$v;
                                $file = str_replace('/','&',$file);
                                ?>
                                {{-- 🛠️ 4. badge badge-primary 改為 Bootstrap 5 的 badge bg-primary --}}
                                <a href="{{ url('file_open/'.$file) }}" class="badge bg-primary text-decoration-none" target="_blank"><i class="fas fa-download"></i> 檔{{ $n }}</a>
                            <?php $n++; ?>
                            @endforeach
                        @endif
                    <br>
                    <small class="text-secondary">({{ $user_task->task->user->name }} {{ $user_task->task->created_at }})</small>
                    @if($user_task->task->user->id == $user->id and $user_task->task->disable==null)                        
                        <a href="#!" class="delete-btn1" data-msg="作廢嗎?" data-url="{{ route('tasks.disable',$user_task->task_id) }}"><i class="fas fa-times-circle text-danger"></i></a>
                    @endif
                    <hr>
                @endforeach
                @if(count($user_tasks) == 0)
                    乾乾淨淨
                @endif
            </div>
            <div class="gif">
                <img src="{{ asset('images/celebration.gif') }}" id="img2" onclick="hideGIF();">
            </div>
        </div>
    </div>
    <script nonce="{{ $csp_nonce }}">
        // ─── 1. 原生事件委派監聽器 ───
        
        // A. 監聽單選鈕（Radio）的變更事件（完全相容 CSP）
        document.addEventListener('change', function(event) {
            if (event.target && event.target.classList.contains('task-radio')) {
                var form = event.target.closest('form');
                if (form) {
                    var formData = new FormData(form);
                    go_submit_native(formData);
                }
            }
        });

        // B. 幫慶祝 GIF 圖片掛上安全點擊監聽
        document.getElementById('img2').addEventListener('click', function() {
            hideGIF();
        });


        // ─── 2. 原生 AJAX 提交函式 ───
        function go_submit_native(formData) {
            fetch('{{ route('tasks.user_condition') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(result) {
                if (result !== 'failed') {
                    // 🎉 觸發慶祝動畫
                    showGIF();
                    
                    // 🔄 重新生成完整的 HTML 清單並刷回畫面
                    var total_data = show_conntent(result);
                    document.getElementById('task_content').innerHTML = total_data;

                    // ✨【關鍵修正】：網頁結構被 innerHTML 洗掉後，在這裡主動呼叫你專案原有的初始化函式
                    // 讓新長出來的 .delete-btn1 重新掛上你寫好的 SweetAlert 監聽。
                    // 💡 請把下面的 init_delete_btn() 換成你原本專案裡用來綁定垃圾桶按鈕的函式名稱
                    if (typeof init_delete_btn === 'function') {
                        init_delete_btn();
                    }
                }
            })
            .catch(function(error) {
                sw_alert('更新失敗！');
                console.error(error);
            });
        }


        // ─── 3. 原生動態渲染畫面（重繪列表 HTML） ───
        function show_conntent(result){
            var data = '';
            var routeUrl = "{{ route('tasks.disable', 'PLACEHOLDER') }}";

            for(var k in result['user_task']){
                data = data + '<form method="POST" action="{{ route('tasks.user_condition') }}'+result['user_task'][k]['user_task_id']+'" accept-charset="UTF-8" id="user_condition'+result['user_task'][k]['user_task_id']+'">';
                data = data + '<input name="_token" type="hidden" value="'+result['token']+'">';
                
                data = data + '<div class="form-check form-check-inline">';
                data = data + '<input class="form-check-input task-radio" type="radio" name="condition" id="inlineRadio'+result['user_task'][k]['user_task_id']+'_2" value="2">';
                data = data + '<label class="form-check-label text-success" for="inlineRadio'+result['user_task'][k]['user_task_id']+'_2">完成</label>';
                data = data + '</div>';
                
                data = data + '<div class="form-check form-check-inline">';
                data = data + '<input class="form-check-input task-radio" type="radio" name="condition" id="inlineRadio'+result['user_task'][k]['user_task_id']+'_3" value="3">';
                data = data + '<label class="form-check-label text-primary" for="inlineRadio'+result['user_task'][k]['user_task_id']+'_3">無關</label>';
                data = data + '</div>';
                
                data = data + '<input type="hidden" name="user_id" value="{{ $user->id }}">';
                data = data + '<input type="hidden" name="user_task_id" value="' + result['user_task'][k]['user_task_id'] + '">';
                data = data + '<input type="hidden" name="old_condition" value="'+result['old_condition']+'">';
                data = data + '</form>';
                
                if(result['user_task'][k]['disable'] == 1){
                    data = data + '<span style="text-decoration:line-through;text-decoration-color: red;word-wrap:break-word;">';
                    data = data + '<span class="badge bg-danger">已廢</span> '+result['user_task'][k]['title'];
                    data = data + '</span>';
                } else {
                    data = data + '<span style="word-wrap:break-word;">';
                    data = data + result['user_task'][k]['title'];
                    data = data +'</span>';
                }
                
                if(result['files'][k] != null){
                    if(result['files'][k][1] != 0){
                        data = data + '<br>附件：';
                        for(var j in result['files'][k]){
                            data = data + '<a href="{{ url('file_open') }}/'+result['files'][k][j]+'" class="badge bg-primary text-decoration-none" target="_blank"><i class="fas fa-download"></i> 檔'+j+'</a> ';
                        }
                    }
                }
                
                data = data + '<br>';
                var t = result['user_task'][k]['created_at'].replace(',',' ');
                data = data + '<small class="text-secondary">('+result['user_task'][k]['name']+' '+t+')</small>';
                
                if(result['user_task'][k]['user_id'] == {{ $user->id }} && result['user_task'][k]['disable'] == null){
                    var finalUrl = routeUrl.replace('PLACEHOLDER', result['user_task'][k]['task_id']);
                    // 保持你原本的最乾淨格式，只帶有你 SweetAlert 所需的 class、data-msg 與 data-url
                    data = data + ' <a href="#!" class="delete-btn1" data-msg="作廢嗎?" data-url="' + finalUrl + '"><i class="fas fa-times-circle text-danger"></i></a>';
                }

                data = data + '<hr>';
            }

            if(result['count'] == 0){
                data = '乾乾淨淨';
            }

            return data;
        }


        // ─── 4. 原生 CSS Transition 動態動畫控制 ───
        var gifTimeout; 

        function showGIF(){
            var img = document.getElementById('img2');
            clearTimeout(gifTimeout);
            img.style.transition = 'none';
            img.style.opacity = '0';
            img.style.display = 'block';
            img.offsetHeight; 
            
            img.style.transition = 'opacity 0.2s ease';
            img.style.opacity = '1';
            
            gifTimeout = setTimeout(function() {
                img.style.transition = 'opacity 0.4s ease';
                img.style.opacity = '0';
                gifTimeout = setTimeout(function() {
                    img.style.display = 'none';
                }, 400);
            }, 1500);
        }

        function hideGIF(){
            clearTimeout(gifTimeout);
            var img = document.getElementById('img2');
            img.style.transition = 'none';
            img.style.opacity = '0';
            img.style.display = 'none';
        }        
    </script>
@endsection