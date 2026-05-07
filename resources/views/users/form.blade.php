<div class="card my-4 shadow-sm">
    <h3 class="card-header bg-light">列表</h3>
    <div class="card-body">
        <div class="mb-3">
            {{-- 改為標準 href="#!" 並透過 id/class 綁定監聽是較安全的做法，這裡先維持 inline 呼叫但修正樣式 --}}
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm venobox" data-vbtype="iframe">
                <i class="fas fa-plus me-1"></i>新增本機帳號
            </a>
        </div>

        <div class="table-responsive">
            {{-- Bootstrap 5 表格：thead-light 已廢棄，改為 table-light 類別 --}}
            <table class="table table-striped align-middle" style="word-break:break-all;">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">排序</th>
                        <th>姓名(帳號)</th>
                        <th>職稱</th>
                        <th>群組</th>
                        <th>類別</th>
                        <th>動作</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="text-nowrap">
                            @if($user->disable)
                                <i class="fas fa-times-circle text-danger"></i>
                            @else
                                <i class="fas fa-check-circle text-success"></i>
                            @endif
                            {{ $user->order_by }}
                        </td>
                        <td>
                            @if($user->admin)
                                <i class="fas fa-crown text-warning"></i>
                            @endif
                            <strong>{{ $user->name }}</strong> <span class="text-muted">({{ $user->username }})</span>
                        </td>
                        <td>
                            {{ $user->title }}
                        </td>
                        <td>
                            @foreach($user->groups as $group)
                                <span class="badge bg-secondary">{{ $group->group->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->login_type=="local")
                                <span class="badge outline-primary text-primary border border-primary">本機帳號</span>
                            @elseif($user->login_type=="gsuite")
                                <span class="badge bg-info text-dark">gsuite帳號</span>
                            @elseif($user->login_type=="openID")
                                <span class="badge bg-primary">openID帳號</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('users.edit',$user->id) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                    <i class="fas fa-edit"></i> 修改
                                </a>
                                
                                @if($user->login_type=="local")
                                    {{-- 使用你的 sw_confirm1 邏輯替換原生 confirm 會更美觀，這裡先修正 BS5 樣式 --}}
                                    <a href="{{ route('users.back_pwd',$user->id) }}" class="btn btn-warning btn-sm delete-btn1" data-msg="確定還原密碼？" data-url="{{ route('users.back_pwd',$user->id) }}">
                                        還原密碼
                                    </a>
                                @endif

                                @if($user->id != auth()->user()->id)
                                    <a href="#!" class="btn btn-secondary btn-sm delete-btn1" data-msg="確定要模擬這個帳號？" data-url="{{ route('sims.impersonate',$user->id) }}">
                                        <i class="fas fa-user-ninja"></i> 模擬
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>