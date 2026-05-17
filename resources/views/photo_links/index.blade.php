@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '圖片連結 | ')

@section('content')
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="mb-3">圖片連結</h1>
        </div>

        {{-- 左側：基本設定 --}}
        <div class="col-md-4">
            <h2 class="h4 mb-3">基本設定</h2>
            <table class="table table-striped align-middle" style="word-break:break-all;">
                <thead class="table-light">
                    <tr>
                        @if(auth()->user()->admin)
                            <th>顯示圖片的數目</th>
                        @endif
                        <th>新建類別</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $setup = \App\Models\Setup::first(); ?>
                    <tr>
                        @if(auth()->user()->admin)
                            <td>                               
                                <form action="{{ route('setups.photo_link_number') }}" method="post" id="this_form1">
                                    @csrf
                                    <div class="mb-2">
                                        <input type="number" name="photo_link_number" id="photo_link_number" class="form-control" value="{{ $setup->photo_link_number }}" placeholder="6的倍數為佳">
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm save-btn" data-form="this_form1">修改</button>    
                                </form>                                
                            </td>
                        @endif
                        <td>
                            <form action="{{ route('photo_links.type_store') }}" method="post" id="this_form2">
                                @csrf
                                <div class="mb-2">
                                    <label for="order_by" class="form-label small fw-bold">排序：</label>
                                    <input type="number" name="order_by" id="order_by" class="form-control" required value="{{ $new_order_by }}" placeholder="排序">
                                </div>
                                <div class="mb-2">
                                    <label for="name" class="form-label small fw-bold">名稱：</label>
                                    <input type="text" name="name" id="name" class="form-control" required placeholder="名稱">
                                </div>
                                <button type="button" class="btn btn-primary btn-sm save-btn" data-form="this_form2">新增</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-striped align-middle mt-4" style="word-break:break-all;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">排序</th>
                        <th>名稱</th>                        
                        <th style="width: 90px;" class="text-nowrap">動作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($photo_types as $photo_type)
                        <?php $readonly = (auth()->user()->admin != 1 && $photo_type->user_id != auth()->user()->id) ? "readonly" : null; ?>
                        <tr>
                            <td colspan="3" class="p-0">
                                <form action="{{ route('photo_links.type_update', $photo_type->id) }}" method="post" id="update_type{{ $photo_type->id }}" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <table class="table table-borderless m-0 align-middle">
                                        <tr>
                                            <td style="width: 80px; border:none;">
                                                <input type="number" name="order_by" class="form-control form-control-sm" value="{{ $photo_type->order_by }}" placeholder="數字" {{ $readonly }}>
                                            </td>
                                            <td style="border:none;">
                                                <input type="text" name="name" class="form-control form-control-sm" required value="{{ $photo_type->name }}" placeholder="名稱" {{ $readonly }}>
                                            </td>                            
                                            <td style="width: 90px;" class="text-nowrap border-none">
                                                @if(auth()->user()->admin || $photo_type->user_id == auth()->user()->id)
                                                    <button type="button" class="btn btn-primary btn-sm px-2 save-btn" data-form="update_type{{ $photo_type->id }}"><i class="fas fa-save"></i></button>                                
                                                    <a href="#!" class="btn btn-link btn-sm p-0 ms-1 delete-btn1" data-msg="確定刪除？底下這個分類的連結，將改為「不分類」喔！" data-url="{{ route('photo_links.type_delete', $photo_type->id) }}"><i class="fas fa-times-circle text-danger fa-lg"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 右側：連結設定 --}}
        <div class="col-md-7">
            <h2 class="h4 mb-3">連結設定</h2>
            <div class="mb-3">
                <a href="{{ route('photo_links.create', $photo_type_id ?? '') }}" class="btn btn-success btn-sm venobox" id="go_create" data-vbtype="iframe"><i class="fas fa-plus"></i> 新增連結</a>
            </div>
            
            {{-- Bootstrap 5 Tab 結構 --}}
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true" onclick="change_create_id('')">不分類</button>
                </li>
                <?php $p = 1; ?>
                @foreach($photo_types as $photo_type)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="photo_link{{ $p }}-tab" data-bs-toggle="tab" data-bs-target="#photo_link{{ $p }}" type="button" role="tab" aria-controls="photo_link{{ $p }}" aria-selected="false" onclick="change_create_id({{ $photo_type->id }})">{{  $photo_type->name }}</button>
                    </li>
                    <?php $p++; ?>
                @endforeach
            </ul>
            
            <div class="tab-content border border-top-0 p-3 bg-white" id="myTabContent">
                {{-- 不分類面板 --}}
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <table class="table table-striped align-middle" style="word-break:break-all;">   
                        <thead class="table-light">
                            <tr>
                                <th style="width: 120px;">類別</th>
                                <th style="width: 80px;">排序</th>
                                <th>代表圖片</th>                                
                                <th>名稱</th>
                                <th style="width: 150px;">動作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($photo_link_data[0]))
                            @foreach($photo_link_data[0] as $k => $v)
                                <tr>
                                    <td>{{ $photo_type_array[0] }}</td>
                                    <td>{{ $v['order_by'] }}</td>
                                    <td>
                                        <?php
                                            $school_code = school_code();
                                            $img = "storage/".$school_code.'/photo_links/'.$v['image'];
                                        ?>
                                        <a href="{{ $v['url'] }}" target="_blank"><img src="{{ asset($img) }}" height="50" alt="連結縮圖" class="img-thumbnail"></a>
                                    </td>                                    
                                    <td>
                                        <a href="{{ $v['url'] }}" target="_blank" class="text-decoration-none">{{ $v['name'] }}</a>
                                    </td>
                                    <td>
                                        @if(auth()->user()->admin || $v['user_id'] == auth()->user()->id)
                                            <a href="{{ route('photo_links.edit', $k) }}" class="btn btn-outline-primary btn-sm mb-1 venobox" data-vbtype="iframe"><i class="fas fa-edit"></i> 修改</a>
                                            <button type="button" class="btn btn-danger btn-sm mb-1 delete-btn2" data-msg="確定刪除？不能刪別人建的喔！" data-form="delete_link{{ $k }}"><i class="fas fa-trash"></i> 刪除</button>
                                            
                                            <form action="{{ route('photo_links.destroy', $k) }}" method="post" id="delete_link{{ $k }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                {{-- 各分類面板 --}}
                <?php $p = 1; ?>
                @foreach($photo_types as $photo_type)
                    <div class="tab-pane fade" id="photo_link{{ $p }}" role="tabpanel" aria-labelledby="photo_link{{ $p }}-tab">
                        <table class="table table-striped align-middle" style="word-break:break-all;">   
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 120px;">類別</th>
                                    <th style="width: 80px;">排序</th>
                                    <th>代表圖片</th>                                    
                                    <th>名稱</th>
                                    <th style="width: 150px;">動作</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($photo_link_data[$photo_type->id]))
                                @foreach($photo_link_data[$photo_type->id] as $k => $v)
                                    <tr>
                                        <td>{{ $photo_type_array[$photo_type->id] }}</td>
                                        <td>{{ $v['order_by'] }}</td>
                                        <td>
                                            <?php
                                                $school_code = school_code();
                                                $img = "storage/".$school_code.'/photo_links/'.$v['image'];
                                            ?>
                                            <a href="{{ $v['url'] }}" target="_blank"><img src="{{ asset($img) }}" height="50" alt="連結縮圖" class="img-thumbnail"></a>
                                        </td>                                        
                                        <td>
                                            <a href="{{ $v['url'] }}" target="_blank" class="text-decoration-none">{{ $v['name'] }}</a>
                                        </td>
                                        <td>
                                            @if(auth()->user()->admin || $v['user_id'] == auth()->user()->id)
                                                <a href="{{ route('photo_links.edit', $k) }}" class="btn btn-outline-primary btn-sm mb-1 venobox" data-vbtype="iframe"><i class="fas fa-edit"></i> 修改</a>
                                                <button type="button" class="btn btn-danger btn-sm mb-1 delete-btn2" data-msg="確定刪除？不能刪別人建的喔！" data-form="delete_link{{ $k }}"><i class="fas fa-trash"></i> 刪除</button>
                                                
                                                <form action="{{ route('photo_links.destroy', $k) }}" method="post" id="delete_link{{ $k }}" class="d-none" onsubmit="return false;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <?php $p++; ?>
                @endforeach
            </div>
        </div>
    </div>

    <script nonce="{{ $csp_nonce }}">
        function open_window(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=300');
        }

        var validator = $("#this_form1").validate();
        function change_create_id(id) {
            // 避免未定義 id 導致路徑字串錯誤
            var targetId = id !== undefined ? id : '';
            $('#go_create').attr('href', '{{ route("photo_links.create") }}' + '/' + targetId);
        }
    </script>

    @if(!empty($photo_type_id))    
        <?php $p = 1; ?>
        @foreach($photo_types as $photo_type)
            @if($photo_type->id == $photo_type_id) 
                <script nonce="{{ $csp_nonce }}">                                  
                    $("#home-tab").removeClass("active");
                    $("#home").removeClass("show active");
                    $("#photo_link{{ $p }}-tab").addClass("active");
                    $("#photo_link{{ $p }}").addClass("show active");
                </script>
            @endif
            <?php $p++; ?>
        @endforeach    
    @endif
@endsection