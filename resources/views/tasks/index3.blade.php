@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '行政待辦 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            @include('tasks.form')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index') }}">待辦</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index2') }}">完成</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('tasks.index3') }}">無關</a>
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
                    {{-- 🛠️ Form::open 改為標準 <form> 並補上 @csrf --}}
                    <form action="{{ route('tasks.user_condition', $user_task->id) }}" method="POST" id="user_condition{{ $user_task->id }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="user_task_id" value="{{ $user_task->id }}">
                        <input type="hidden" name="old_condition" value="{{ $user_task->condition }}">
                        <input type="hidden" name="condition" value="1">
                    </form>

                    @if($user_task->task->disable)
                    <span style="text-decoration:line-through;text-decoration-color: red;word-wrap:break-word;">
                        {{-- 🛠️ 移除 onclick，改加 class="task-ban-icon" 做原生全域事件委派 --}}
                        <i class="fas fa-ban text-warning task-ban-icon" style="cursor: pointer;"></i> 
                        {{-- 🛠️ Bootstrap 5 樣式修正：badge badge-danger -> badge bg-danger --}}
                        <span class="badge bg-danger">已廢</span> {{ $user_task->task->title }}
                    </span>
                    @else
                    <span style="text-decoration:line-through;word-wrap:break-word;">
                        <i class="fas fa-ban text-warning task-ban-icon" style="cursor: pointer;"></i> {{ $user_task->task->title }}
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
                                {{-- 🛠️ Bootstrap 5 樣式修正：badge badge-primary -> badge bg-primary text-decoration-none --}}
                                <a href="{{ url('file_open/'.$file) }}" class="badge bg-primary text-decoration-none" target="_blank"><i class="fas fa-download"></i> 檔{{ $n }}</a>
                            <?php $n++; ?>
                            @endforeach
                        @endif
                    <br>
                    <small class="text-secondary">({{ $user_task->task->user->name }} {{ $user_task->task->created_at }})</small>
                    @if($user_task->task->user->id == $user->id and $user_task->task->disable==null)
                        {{-- 🛠️ 移除舊 onclick，統一改用你的自訂 sweet alert class: delete-btn1 --}}
                        <a href="#!" class="delete-btn1" data-msg="作廢嗎?" data-url="{{ route('tasks.disable',$user_task->task_id) }}"><i class="fas fa-times-circle text-danger"></i></a>
                    @endif
                    <hr>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 🛠️ 補上 CSP 安全所需的 nonce 權杖 --}}
    <script nonce="{{ $csp_nonce }}">
        // ─── 1. 原生全域事件委派監聽（完全相容 CSP，重繪後也絕對有效） ───
        
        // A. 監聽黃色禁止符號的點擊（把無關改回待辦）
        document.addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('task-ban-icon')) {
                // 尋找跟禁止符號同層、前方的那個表單
                var form = event.target.closest('span').previousElementSibling;
                if (!form || form.tagName !== 'FORM') {
                    form = event.target.parentElement.previousElementSibling;
                }
                
                if (form && form.tagName === 'FORM') {
                    var formData = new FormData(form);
                    go_submit_native(formData);
                }
            }
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
                    // 🔄 重新生成完整的 HTML 清單並刷回畫面
                    var total_data = show_conntent(result);
                    document.getElementById('task_content').innerHTML = total_data;

                    // ✨【關鍵】：利用你專案原有的初始化函式，重掛新長出來的 .delete-btn1 的 SweetAlert 監聽
                    if (typeof init_delete_btn === 'function') {
                        init_delete_btn();
                    }
                }
            })
            .catch(function(error) {
                alert('失敗！');
                console.error(error);
            });
        }


        // ─── 3. 原生動態渲染畫面（重繪清單 HTML） ───
        function show_conntent(result){
            var data = '';
            var routeUrl = "{{ route('tasks.disable', 'PLACEHOLDER') }}";

            for(var k in result['user_task']){
                data = data + '<form method="POST" action="{{ route('tasks.user_condition') }}'+result['user_task'][k]['user_task_id']+'" accept-charset="UTF-8" id="user_condition'+result['user_task'][k]['user_task_id']+'">';
                data = data + '<input name="_token" type="hidden" value="'+result['token']+'">';
                data = data + '<input type="hidden" name="user_id" value="{{ $user->id }}">';
                data = data + '<input type="hidden" name="user_task_id" value="' + result['user_task'][k]['user_task_id'] + '">';
                data = data + '<input type="hidden" name="old_condition" value="'+result['old_condition']+'">';
                data = data + '<input type="hidden" name="condition" value="1">';
                data = data + '</form>';
                
                if(result['user_task'][k]['disable'] == 1){
                    data = data + '<span style="text-decoration:line-through;text-decoration-color: red;word-wrap:break-word;">';
                    data = data + '<i class="fas fa-ban text-warning task-ban-icon" style="cursor: pointer;"></i> <span class="badge bg-danger">已廢</span> ' + result['user_task'][k]['title'];
                    data = data + '</span>';
                } else {
                    data = data + '<span style="text-decoration:line-through;word-wrap:break-word;">';
                    data = data + '<i class="fas fa-ban text-warning task-ban-icon" style="cursor: pointer;"></i> ' + result['user_task'][k]['title'];
                    data = data + '</span>';
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
                    // 保持你最原本乾淨的 SweetAlert 專用格式
                    data = data + ' <a href="#!" class="delete-btn1" data-msg="作廢嗎?" data-url="' + finalUrl + '"><i class="fas fa-times-circle text-danger"></i></a>';
                }

                data = data + '<hr>';
            }
            
            if(result['count'] > 20){
                data = data + '<a href="?page=2" class="btn btn-secondary">更多...</a><br><br>';
            }
            
            return data;
        }
    </script>
@endsection