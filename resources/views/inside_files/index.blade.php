@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '內部文件 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>內部文件</h1>
            <?php
            $final = end($folder_path);
            $final_key = key($folder_path);
            $p="";
            $f="app/privacy/".$school_code."/inside_files";
            $last_folder = "";
            ?>
            
            <div class="mb-3 text-secondary">
                路徑：
                @foreach($folder_path as $k=>$v)
                    <?php
                    if($k=="0"){
                        $k = null;
                    }else{
                        $p .= '&'.$k;
                        $f .=  '/'.$v;
                    }
                    if($k != $final_key and !empty($k)){
                        $last_folder .= '&'.$k;
                    }
                    ?>
                    @if($v == $final)
                        <i class="fa fa-folder-open text-warning"></i> <a href="{{ route('inside_files.index',$p) }}" class="text-decoration-none fw-bold text-dark">{{$v}}</a>/
                    @else
                        <i class="fa fa-folder text-warning"></i> <a href="{{ route('inside_files.index',$p) }}" class="text-decoration-none">{{$v}}</a>/
                    @endif
                @endforeach
            </div>
            
            <hr class="text-muted opacity-25">
            
            <div class="container-fluid px-0">
                <div class="row g-3 text-center">
                    
                    @foreach($folders as $folder)
                        <?php
                        $folder_p = $path.'&'.$folder->id;
                        ?>
                        <?php $n = \App\Models\InsideFile::where('folder_id',$folder->id)->count();?>
                        <div class="col-lg-1 col-md-2 col-sm-3 col-4">
                            <a href="{{ route('inside_files.index',$folder_p) }}" class="text-decoration-none text-dark d-block mb-1">
                                <img src="{{ asset('images/folder.svg') }}" class="img-fluid mb-1" alt="folder">
                                <small class="d-block text-truncate">{{ $folder->name }}({{ $n }})</small>
                            </a>
                            @if($folder->user_id == auth()->user()->id or auth()->user()->admin==1)
                                <a href="{{ route('inside_files.edit',[$folder->id,$folder_p]) }}" class="btn-inside-edit text-secondary p-1 venobox" data-vbtype="iframe"><i class='fas fa-edit'></i></a>
                                @if($n == 0)
                                    <a href="#!" class="btn-inside-delete text-danger p-1 delete-btn1" data-url="{{ route('inside_files.delete',$folder_p) }}"><i class="fas fa-minus-square"></i></a>
                                @endif
                            @endif
                        </div>
                    @endforeach
                    
                    @foreach($files as $file)
                        <?php
                        $file_p = $path.'&'.$file->id;
                        ?>
                        <div class="col-lg-1 col-md-2 col-sm-3 col-4">
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                <a href="{{ route('inside_files.download',$file_p) }}" class="text-decoration-none text-dark d-block mb-1">
                                    <img src="{{ asset('images/file.svg') }}" class="img-fluid mb-1" alt="file">
                                    <small class="d-block text-truncate">{{ $file->name }}</small>
                                </a>
                            @else
                                <div class="mb-1">
                                    <img src="{{ asset('images/file.svg') }}" class="img-fluid mb-1 opacity-50" alt="file lost">
                                    <small class="d-block text-danger text-truncate fw-bold">{{ $file->name }} (已遺失)</small>
                                </div>
                            @endif
                            @if($file->user_id == auth()->user()->id or auth()->user()->admin==1)
                                <a href="{{ route('inside_files.edit',[$file->id,$file_p]) }}" class="btn-inside-edit text-secondary p-1 venobox" data-vbtype="iframe"><i class='fas fa-edit'></i></a>
                                <a href="#!" class="btn-inside-delete text-danger p-1 delete-btn1" data-url="{{ route('inside_files.delete',$file_p) }}"><i class="fas fa-minus-square"></i></a>
                            @endif
                        </div>
                    @endforeach
                    
                    @foreach($clouds as $cloud)
                        <?php
                        $file_p = $path.'&'.$cloud->id;
                        ?>
                        <div class="col-lg-1 col-md-2 col-sm-3 col-4">
                            <a href="{{ $cloud->url }}" target="_blank" class="text-decoration-none text-primary d-block mb-1">
                                <img src="{{ asset('images/cloud.svg') }}" class="img-fluid mb-1" alt="cloud">
                                <small class="d-block text-truncatefw-semibold">{{ $cloud->name }}</small>
                            </a>
                            @if($cloud->user_id == auth()->user()->id or auth()->user()->admin==1)
                                <a href="{{ route('inside_files.edit',[$cloud->id,$file_p]) }}" class="btn-inside-edit text-secondary p-1 venobox" data-vbtype="iframe"><i class='fas fa-edit'></i></a>
                                <a href="#!" class="btn-inside-delete text-danger p-1 delete-btn1" data-url="{{ route('inside_files.delete',$file_p) }}"><i class="fas fa-minus-square"></i></a>
                            @endif
                        </div>
                    @endforeach
                    
                </div>
            </div>
            
            <hr class="text-muted opacity-25 my-4">
            
            @can('create',\App\Models\Upload::class)
                <div class="card my-4 border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden">
                    <h3 class="card-header bg-light fs-5 fw-bold py-3 px-4 text-dark border-bottom">新增</h3>
                    <div class="card-body p-4">
                        @include('layouts.hd')
                        
                        <form action="{{ route('inside_files.create_folder') }}" method="POST" id="this_form1" class="mb-4 form-inside-submit">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold text-secondary">1.子目錄</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="名稱" required>
                            </div>
                            <div class="mb-3">
                                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                <input type="hidden" name="path" value="{{ $path }}">
                                <button type="button" class="btn btn-success btn-sm fw-bold px-3 save-btn" data-form="this_form1">
                                    <i class="fas fa-plus me-1"></i> 新增子目錄
                                </button>
                            </div>
                        </form>
                        
                        <hr class="text-muted opacity-25 my-4">
                        @include('layouts.errors')
                        
                        @if($per < 100)
                            <form action="{{ route('inside_files.upload_file') }}" method="POST" enctype="multipart/form-data" id="this_form2" class="mb-4 form-inside-submit">
                                @csrf
                                <div class="mb-3">
                                    <label for="files" class="form-label fw-bold text-secondary">2.檔案</label>
                                    <input type="file" name="files[]" id="files" class="form-control" multiple required>
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                    <input type="hidden" name="path" value="{{ $path }}">
                                    <button type="button" class="btn btn-success btn-sm fw-bold px-3 save-btn" data-form="this_form2">
                                        <i class="fas fa-plus me-1"></i> 新增檔案
                                    </button>
                                </div>
                            </form>
                        @endif
                        
                        <form action="{{ route('inside_files.upload_cloud') }}" method="POST" id="this_form3" class="form-inside-submit">
                            @csrf
                            <div class="mb-3">
                                <label for="cloud_name" class="form-label fw-bold text-secondary">3.雲端連結</label>
                                <input type="text" name="name" id="cloud_name" class="form-control mb-2" placeholder="名稱" required>
                                <input type="url" name="url" id="url" class="form-control" placeholder="連結" required>
                            </div>
                            <div class="mb-3">
                                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                                <input type="hidden" name="path" value="{{ $path }}">
                                <button type="button" class="btn btn-success btn-sm fw-bold px-3 save-btn" data-form="this_form3">
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