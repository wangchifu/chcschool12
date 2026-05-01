@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">網站設定</h1>
            <?php
            $active[1] = "";
            $active[2] = "active";
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            ?>
            @include('setups.nav',$active)

            <div class="card my-4 shadow-sm">
                <h3 class="card-header">網站小圖示</h3>
                <div class="card-body">
                    @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                        <div class="clearfix" style="padding: 10px;">
                            <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="50" class="img-thumbnail">
                            <a href="#!" id="del_logo" onclick="sw_confirm1('確定移除小圖示嗎？','{{ route('setups.del_img',['folder'=>'title_image','filename'=>'logo.ico']) }}')" class="ms-2">
                                <i class="fas fa-times-circle text-danger"></i>
                            </a>
                        </div>
                    @else
                        <form action="{{ route('setups.add_logo') }}" method="POST" id="this_form1" enctype="multipart/form-data" onsubmit="return false">
                            @csrf
                            <div class="mb-3">
                                <label for="logo" class="form-label fw-bold">圖檔( .ico .png )</label>
                                <input type="file" name="logo" id="logo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary btn-sm" onclick="sw_confirm2('確定上傳？','this_form1')">
                                    <i class="fas fa-save"></i> 儲存設定
                                </button>
                            </div>
                            @include('layouts.errors')
                        </form>
                    @endif
                </div>
            </div>

            <div class="card my-4 shadow-sm">
                <h3 class="card-header">輪播照片</h3>
                <div class="card-body">
                    <form action="{{ route('setups.update_title_image', $setup->id) }}" method="POST" id="this_form2" onsubmit="return false">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <?php
                            $check1 = $setup->title_image ? "checked" : "";
                            $check2 = !$setup->title_image ? "checked" : "";
                            
                            $title_image_style_check1 = ($setup->title_image_style == 1 || $setup->title_image_style == null) ? "checked" : "";
                            $title_image_style_check2 = ($setup->title_image_style == 2) ? "checked" : "";
                            ?>

                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" name="title_image" id="enable_switch" 
                                    value="1" {{ $check1 }} role="switch" style="cursor: pointer; width: 3em; height: 1.5em;">
                                
                                <label class="form-check-label fw-bold ms-2 mt-1" for="enable_switch" style="cursor: pointer;">
                                    啟用輪播照片功能
                                    @if($setup->title_image)
                                        <span class="badge bg-success-subtle text-success fw-normal ms-1">運行中</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary fw-normal ms-1">已關閉</span>
                                    @endif
                                </label>
                            </div>

                            <br>

                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" name="title_image_style" id="style_switch" 
                                    value="2" {{ ($setup->title_image_style == 2) ? 'checked' : '' }} role="switch" style="cursor: pointer; width: 3em; height: 1.5em;">
                                
                                <label class="form-check-label fw-bold ms-2 mt-1" for="style_switch" style="cursor: pointer;">
                                    使用「淡出淡入」特效
                                    <span class="text-muted fw-normal small ms-1">(未開啟則預設為滑動)</span>
                                </label>
                            </div>                            
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？','this_form2')">
                                <i class="fas fa-save"></i> 儲存設定
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <form action="{{ route('setups.add_imgs') }}" method="POST" id="this_form3" enctype="multipart/form-data" onsubmit="return false">
                        @csrf
                        <div class="mb-3">
                            <label for="files" class="form-label fw-bold">圖檔( 2000 x 400 )</label>
                            <input type="file" name="files[]" id="files" class="form-control" multiple required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？','this_form3')">
                                <i class="fas fa-save"></i> 儲存設定
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <form method="post" action="{{ route('setups.photo_desc') }}" onsubmit="return false" id="this_form4" onsubmit="return false">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 120px;">出現順序</th>
                                        <th style="width: 150px;">狀態</th>
                                        <th>圖片</th>
                                        <th>連結</th>
                                        <th>標題</th>
                                        <th>說明</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($photo_data as $k1=>$v1)
                                        @foreach($v1 as $k2=>$v2)
                                            <tr>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm" name="order_by[{{ $k2 }}]" value="{{ $k1 }}">
                                                </td>
                                                <td>
                                                    <?php
                                                    $checked1 = ($v2['disable']==null)?"checked":null;
                                                    $checked2 = ($v2['disable'])?"checked":null;
                                                    ?>
                                                    <div class="btn-group btn-group-sm w-100" role="group">
                                                        <input type="radio" class="btn-check" name="disable[{{ $k2 }}]" id="enable{{ $k2 }}" value="" {{ $checked1 }} autocomplete="off">
                                                        <label class="btn btn-outline-success" for="enable{{ $k2 }}">啟用</label>

                                                        <input type="radio" class="btn-check" name="disable[{{ $k2 }}]" id="disable{{ $k2 }}" value="1" {{ $checked2 }} autocomplete="off">
                                                        <label class="btn btn-outline-danger" for="disable{{ $k2 }}">停用</label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <img src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" width="150" class="img-thumbnail mb-1">
                                                    <div class="small text-muted">{{ $k2 }}</div>
                                                    <a href="#!" class="btn btn-link btn-sm text-danger text-decoration-none" onclick="sw_confirm1('確定移除輪播圖片嗎','{{ route('setups.del_img',['folder'=>'title_image&random','filename'=>$k2]) }}')">
                                                        <i class="fas fa-trash-alt"></i> 移除
                                                    </a>
                                                </td>
                                                <td><input type="text" class="form-control form-control-sm" name="link[{{ $k2 }}]" value="{{ $v2['link'] }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="title[{{ $k2 }}]" value="{{ $v2['title'] }}"></td>
                                                <td><input type="text" class="form-control form-control-sm" name="desc[{{ $k2 }}]" value="{{ $v2['desc'] }}"></td>
                                            </tr>
                                            <input type="hidden" name="image_name[{{ $k2 }}]" value="{{ $k2 }}">
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table> 
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm shadow" onclick="sw_confirm2('確定？','this_form4')">
                            <i class="fas fa-save me-1"></i> 全部儲存
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var validator1 = $("#this_form1").validate();
        var validator2 = $("#this_form2").validate();
        var validator3 = $("#this_form3").validate();
        var validator4 = $("#this_form4").validate();
    </script>
@endsection
