@extends('layouts.master')

@section('nav_open_files_active', 'active')
<?php $openfile_name = (empty($setup->openfile_name)) ? "檔案庫" : $setup->openfile_name; ?>
@section('title', $openfile_name.' | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3">
                @if(empty($setup->openfile_name))
                    檔案庫
                @else
                    {{ $setup->openfile_name }}
                @endif
            </h1>
            <?php
            $final = end($folder_path);
            $final_key = key($folder_path);
            $p = "";
            $f = "app/public/".$school_code."/open_files";
            $last_folder = "";
            ?>
            
            <div class="mb-3 text-muted">
                <span>路徑：</span>
                @auth
                    <?php
                        $check_exec = \App\Models\UserGroup::where('user_id', auth()->user()->id)
                        ->where('group_id', 1)
                        ->first();                                                          
                    ?>
                @endauth            
                @foreach($folder_path as $k => $v)
                    <?php
                    if($k == "0"){
                        $k = null;
                    } else {
                        $p .= '&'.$k;
                        $f .= '/'.$v;
                    }
                    if($k != $final_key && !empty($k)){
                        $last_folder .= '&'.$k;
                    }
                    ?>
                    @if($v == $final)
                        <i class="fa fa-folder-open text-warning"></i> <a href="{{ route('open_files.index', $p) }}" class="text-decoration-none">{{ $v }}</a> /
                    @else
                        <i class="fa fa-folder text-warning"></i> <a href="{{ route('open_files.index', $p) }}" class="text-decoration-none">{{ $v }}</a> /
                    @endif
                @endforeach
            </div>

            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>目錄 / 檔案名稱</th>
                        <th style="width: 140px;">類型</th>
                        <th style="width: 120px;">數量</th>
                        <th style="width: 140px;">建立者</th>
                        <th style="width: 180px;">建立時間</th>
                    </tr>
                </thead>
                <tbody>
                @if($path != null)
                    <tr>
                        <td colspan="5">
                            <i class="fas fa-arrow-circle-left text-secondary"></i> 
                            <a href="{{ route('open_files.index', $last_folder) }}" class="text-decoration-none fw-bold">上一層</a>
                        </td>
                    </tr>
                @endif
                
                {{-- 1. 目錄列表 --}}
                @foreach($folders as $folder)
                    <?php $folder_p = $path.'&'.$folder->id; ?>
                    <tr>
                        <td>
                            <i class="fas fa-folder text-warning me-1"></i> 
                            <a href="{{ route('open_files.index', $folder_p) }}" class="text-decoration-none">{{ $folder->name }}</a>
                        </td>
                        <td>
                            <?php $n = \App\Models\Upload::where('folder_id', $folder->id)->count(); ?>
                            <span class="badge bg-secondary me-1">目錄</span>
                            @auth                            
                                @if(($folder->user_id == auth()->user()->id && !empty($check_exec)) || auth()->user()->admin == 1)
                                    <a href="{{ route('open_files.edit', [$folder->id, $folder_p]) }}" class="btn-open-window text-dark me-1 venobox" data-vbtype="iframe"><i class='fas fa-edit'></i></a>
                                    @if($n == 0)
                                        <a href="#!" class="btn-delete-confirm text-danger delete-btn1" data-msg="確定刪除目錄嗎？" data-url="{{ route('open_files.delete', $folder_p) }}"><i class="fas fa-minus-square"></i></a>
                                    @endif
                                @endif
                            @endauth
                        </td>
                        <td>{{ $n }} 個項目</td>
                        <td>
                            @if(!empty($folder->job_title))
                                {{ $folder->job_title }}
                            @else
                                {{ ($folder->user->name == "系統管理員") ? "系統管理員" : $folder->user->title }}
                            @endif                            
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$folder->name)))
                                {{ date("Y-m-d H:i:s", filemtime(storage_path($f.'/'.$folder->name))) }}
                            @endif
                        </td>
                    </tr>
                @endforeach

                {{-- 2. 檔案列表 --}}
                @foreach($files as $file)
                    <?php $file_p = $path.'&'.$file->id; ?>
                    <tr>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                <?php $f2 = str_replace('app/public', '', $f); ?>
                                <i class="fas fa-file text-info me-1"></i> 
                                <a href="{{ asset('storage'.$f2.'/'.$file->name) }}" target="_blank" class="text-decoration-none">{{ $file->name }}</a>
                            @else
                                <span class="text-danger"><i class="fas fa-file me-1"></i> {{ $file->name }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info text-dark me-1">檔案</span>
                            @auth
                                @if(($file->user_id == auth()->user()->id && !empty($check_exec)) || auth()->user()->admin == 1)
                                    <a href="{{ route('open_files.edit', [$file->id, $file_p]) }}" class="btn-open-window text-dark me-1 venobox" data-vbtype="iframe"><i class='fas fa-edit'></i></a>
                                    <a href="#!" class="btn-delete-confirm text-danger delete-btn1" data-msg="確定刪除檔案嗎？" data-url="{{ route('open_files.delete', $file_p) }}" title="刪除"><i class="fas fa-minus-square"></i></a>
                                @endif
                            @endauth
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                {{ filesizekb(storage_path($f.'/'.$file->name)) }} KB
                            @else
                                <small class="text-danger">已遺失</small>
                            @endif
                        </td>
                        <td>
                            @if(!empty($file->job_title))
                                {{ $file->job_title }}
                            @else
                                {{ ($file->user->name == "系統管理員") ? "系統管理員" : $file->user->title }}
                            @endif                            
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                {{ date("Y-m-d H:i:s", filemtime(storage_path($f.'/'.$file->name))) }}
                            @else
                                <small class="text-danger">已遺失</small>
                            @endif
                        </td>
                    </tr>
                @endforeach

                {{-- 3. 雲端連結列表 --}}
                @foreach($clouds as $cloud)
                    <?php $file_p = $path.'&'.$cloud->id; ?>
                    <tr>
                        <td>
                            <i class="fas fa-cloud text-primary me-1"></i> 
                            <a href="{{ $cloud->url }}" target="_blank" class="text-decoration-none">{{ $cloud->name }}</a>
                        </td>
                        <td>
                            <span class="badge bg-primary me-1">雲端</span>
                            @auth
                                @if(($cloud->user_id == auth()->user()->id && !empty($check_exec)) || auth()->user()->admin == 1)
                                    <a href="{{ route('open_files.edit', [$cloud->id, $file_p]) }}" class="btn-open-window text-dark me-1 venobox" data-vbtype="iframe"><i class='fas fa-edit'></i></a>
                                    <a href="#!" class="btn-delete-confirm text-danger delete-btn1" data-msg="確定刪除雲端連結嗎？" data-url="{{ route('open_files.delete', $file_p) }}"><i class="fas fa-minus-square"></i></a>
                                @endif
                            @endauth
                        </td>
                        <td></td>
                        <td>
                            @if(!empty($cloud->job_title))
                                {{ $cloud->job_title }}
                            @else
                                {{ ($cloud->user->name == "系統管理員") ? "系統管理員" : $cloud->user->title }}
                            @endif                            
                        </td>
                        <td>{{ $cloud->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
            <hr>
            
            {{-- 新增區塊區 --}}
            @can('create', \App\Models\Upload::class)
                <div class="card my-4 shadow-sm">
                    <h3 class="card-header bg-light fs-5 fw-bold py-3">新增管理項目</h3>
                    <div class="card-body p-4">
                        @include('layouts.hd')
                        
                        {{-- 表單 1：子目錄 --}}
                        <form action="{{ route('open_files.create_folder') }}" method="POST" id="this_form1" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold text-success">1. 子目錄名稱</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="請輸入子目錄名稱" required>
                            </div>
                            <div class="mb-3">
                                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                <input type="hidden" name="path" value="{{ $path }}">
                                <button type="button" class="btn btn-success btn-sm btn-submit-protection save-btn" data-form="this_form1">
                                    <i class="fas fa-plus me-1"></i> 新增子目錄
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        @include('layouts.errors')
                        
                        {{-- 表單 2：檔案上傳 --}}
                        @if($per < 100)
                            <form action="{{ route('open_files.upload_file') }}" method="POST" id="this_form2" enctype="multipart/form-data" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <label for="files" class="form-label fw-bold text-info">
                                        2. 檔案上傳
                                        <span class="text-muted fw-normal fs-7">(不大於 10MB，若為文字檔，請優先儲存為 [ <a href="https://www.ndc.gov.tw/cp.aspx?n=d6d0a9e658098ca2" target="_blank" class="text-decoration-none">ODF格式</a> ] )</span>
                                    </label>
                                    <input type="file" name="files[]" id="files" class="form-control" multiple required>
                                    <div class="form-text text-secondary">允許格式：csv, txt, zip, jpeg, png, pdf, odt, ods, mp3 檔</div>
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                    <input type="hidden" name="path" value="{{ $path }}">
                                    <button type="button" class="btn btn-info btn-sm text-dark btn-submit-protection save-btn" data-form="this_form2">
                                        <i class="fas fa-plus me-1"></i> 新增檔案
                                    </button>
                                </div>
                            </form>
                            <hr class="my-4">
                        @endif

                        {{-- 表單 3：雲端連結 --}}
                        <form action="{{ route('open_files.upload_cloud') }}" method="POST" id="this_form3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary">3. 雲端連結登記</label>
                                <input type="text" name="name" class="form-control mb-2" placeholder="請輸入連結顯示名稱" required>
                                <input type="text" name="url" class="form-control" placeholder="https://...(請輸入完整雲端網址)" required>
                            </div>
                            <div class="mb-3">
                                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                <input type="hidden" name="path" value="{{ $path }}">
                                <button type="button" class="btn btn-primary btn-sm btn-submit-protection save-btn" data-form="this_form3">
                                    <i class="fas fa-plus me-1"></i> 新增雲端連結
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>    
@endsection