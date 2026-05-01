@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '模組功能 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">網站設定</h1>
            <?php
                $active[1] = "";
                $active[2] = "";
                $active[3] = "";
                $active[4] = "";
                $active[5] = "active";
                $active[6] = "";
                $module_setup = get_module_setup();
            ?>
            @include('setups.nav',$active)

            <div class="card my-4 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h3>模組功能管理</h3>
                    <span class="badge bg-primary">總共 {{ count($modules) }} 個模組</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('setups.update_module') }}" method="POST" id="module_form" onsubmit="return false">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px;">模組名稱</th>
                                        <th style="width: 200px;" class="text-center">狀態切換</th>
                                        <th>管理權限說明 / 權限指定</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($modules as $k=>$v)
                                    <?php
                                        $check1 = (isset($module_setup[$v])) ? "checked" : "";
                                        $check2 = (isset($module_setup[$v])) ? "" : "checked";
                                    ?>
                                    <tr>
                                        <td class="fw-bold">
                                            <i class="fas fa-cube text-secondary me-2"></i>{{ $v }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Status Toggle">
                                                <input type="radio" class="btn-check" name="module[{{ $v }}]" id="on_{{ $k }}" value="1" autocomplete="off" {{ $check1 }}>
                                                <label class="btn btn-outline-success px-3" for="on_{{ $k }}">啟用</label>

                                                <input type="radio" class="btn-check" name="module[{{ $v }}]" id="off_{{ $k }}" value="" autocomplete="off" {{ $check2 }}>
                                                <label class="btn btn-outline-danger px-3" for="off_{{ $k }}">停用</label>
                                            </div>
                                        </td>
                                        <td>
                                            @if($v=="公告系統")
                                                <small class="text-muted">行政人員可發公告，管理員可置頂</small>
                                            @elseif($v=="檔案庫")
                                                <small class="text-muted">行政人員可掛檔案</small>
                                            @elseif($v=="好站連結")
                                                <small class="text-muted">管理員可綁連結</small>
                                            @elseif($v=="內部文件")
                                                <small class="text-muted">行政人員可增加檔案</small>
                                            @elseif($v=="會議文稿")
                                                <small class="text-muted">行政人員可報告事項</small>
                                            @elseif($v=="校園部落格")
                                                <small class="text-muted">行政人員可編輯新文章，管理員可刪除任一文章</small>    
                                            @elseif($v=="校務月曆")
                                                <small class="text-muted">行政人員可編行事</small>                                            
                                            @elseif($v=="校務行政")
                                                <span class="text-muted">--</span>
                                            @elseif($v=="處室介紹")
                                                <small class="text-muted">管理員編修</small>
                                            
                                            {{-- 具有權限指定功能的模組 --}}
                                            @elseif(in_array($v, ["報修系統", "午餐系統", "社團報名", "教室預約", "借用系統", "運動會報名", "學生帳號", "填報學生"]))
                                                @php
                                                    $types_map = [
                                                        '報修系統' => ['A' => '可回覆'],
                                                        '午餐系統' => ['A' => '午餐業務'],
                                                        '社團報名' => ['A' => '社團業務'],
                                                        '教室預約' => ['A' => '可編輯教室'],
                                                        '借用系統' => ['A' => '可管理借用'],
                                                        '學生帳號' => ['A' => '可系統管理'],
                                                        '運動會報名' => ['A' => '可系統管理', 'B' => '可輸入成績'],
                                                        '填報學生' => ['A' => '可系統管理']
                                                    ];
                                                @endphp
                                                
                                                @foreach($types_map[$v] as $type_code => $type_label)
                                                    <div class="mb-2">
                                                        <a href="{{ route('user_powers.create',['module'=>$v,'type'=>$type_code]) }}" class="btn btn-outline-info btn-sm venobox" data-vbtype="iframe">
                                                            <i class="fas fa-plus-circle me-1"></i>指定「{{ $type_label }}」
                                                        </a>
                                                        @php
                                                            $power_model = ($v == "學生帳號") ? \App\Models\UserPower::class : \App\Models\UserPower::class;
                                                            $powers = $power_model::where('name',$v)->where('type',$type_code)->get();
                                                        @endphp
                                                        @foreach($powers as $up)
                                                            <span class="badge bg-light text-dark border ms-1 fw-normal">
                                                                {{ $up->user->name }}
                                                                <a href="#!" class="text-danger ms-1" onclick="sw_confirm1('確定刪除權限？','{{ route('user_powers.destroy',$up->id) }}')">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endforeach                                            
                                            @else
                                                <small class="text-muted">基本模組</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-white py-3">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm" onclick="sw_confirm2('確定儲存模組設定？','module_form')">
                                <i class="fas fa-save me-2"></i>儲存模組狀態
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
@endsection
