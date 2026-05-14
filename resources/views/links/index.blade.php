@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '選單連結 | ')

@section('content')
    <style>
        .table-break { word-break: break-all; }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">第一層連結類別</div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('links.store_type') }}" method="post" id="this_form1">
                        @csrf
                        <table class="table table-striped table-break">
                            <thead>
                                <tr>
                                    <th>排序</th>
                                    <th>名稱</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="100">                                        
                                        <input type="number" name="order_by" value="{{ $new_type_order_by }}" id="order_by" class="form-control" placeholder="排序">
                                    </td>
                                    <td>                                        
                                        <input type="text" name="name" id="name" class="form-control" required placeholder="名稱">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm save-btn" data-form="this_form1"><i class="fas fa-plus"></i> 新增</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>

                    <table class="table table-striped table-break">
                        <tbody>
                        @foreach($types as $type)
                            <tr>
                                <td colspan="3" class="p-0 border-0">                                   
                                    <table class="table mb-0">
                                        <tr>
                                            <td width="100">
                                                {{ $type->order_by }}
                                            </td>
                                            <td>
                                                {{ $type->name }}
                                            </td>
                                            <td nowrap>
                                                <a href="{{ route('links.edit_type', $type->id) }}" class="venobox" data-vbtype="iframe"><i class="fas fa-edit"></i></a>
                                                <a href="#!" class="text-danger delete-btn2"                                                     
                                                    data-form="delete_form{{ $type->id }}" data-msg="會連同底下的連結一同刪除喔！">
                                                <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>                                    
                                    <form action="{{ route('links.destroy_type', $type->id) }}" method="post" id="delete_form{{ $type->id }}" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-header">第二層連結子類別</div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('links.store_type') }}" method="post" id="this_form2">
                        @csrf
                        <table class="table table-striped table-break">
                            <thead>
                                <tr>
                                    <th>類別</th>
                                    <th>排序</th>
                                    <th>子類別名稱</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="120">                                    
                                        <select name="type_id" id="type_id2" class="form-control" required>
                                            @foreach($type_array as $k => $v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="100">                                    
                                        <input type="number" name="order_by" id="order_by2" class="form-control" placeholder="排序">
                                    </td>
                                    <td>                                    
                                        <input type="text" name="name" id="name2" class="form-control" required placeholder="名稱">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm save-btn" data-form="this_form2"><i class="fas fa-plus"></i> 新增</button>
                                    </td>
                                </tr>
                            </tbody>                            
                        </table>
                    </form>

                    <table class="table table-striped table-break">
                        <tbody>
                        @foreach($type2s as $type2)
                            <tr>
                                <td colspan="4" class="p-0 border-0">                                    
                                    <table class="table mb-0 align-middle">
                                        <tbody>
                                            <tr>
                                                <td width="120">
                                                    {{-- 顯示類別名稱 --}}
                                                    {{ $type_array[$type2->type_id] ?? '' }}
                                                </td>
                                                <td width="100">
                                                    {{-- 顯示排序數字 --}}
                                                    {{ $type2->order_by }}
                                                </td>
                                                <td>
                                                    {{-- 顯示名稱 --}}
                                                    {{ $type2->name }}
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="{{ route('links.edit_type2', $type2->id) }}" class="venobox" data-vbtype="iframe"><i class="fas fa-edit"></i></a>
                                                    <a href="#!" class="text-danger delete-btn2"                                                     
                                                        data-form="delete_form{{ $type2->id }}" data-msg="會連同底下的連結一同刪除喔！">
                                                    <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>                                                                           
                                    <form action="{{ route('links.destroy_type', $type2->id) }}" method="post" id="delete_form{{ $type2->id }}" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <a href="{{ route('links.create', $type_id) }}" class="btn btn-success btn-sm venobox" id="go_create" data-vbtype="iframe"><i class="fas fa-plus"></i> 新增連結</a><br><br>
            
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php $p=1; ?>
                @foreach($types as $type)
                    @if($p==1)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true" onclick="change_create_id({{ $type->id }})">{{ $type->name }}</button>
                        </li>
                    @else
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="link{{ $p }}-tab" data-bs-toggle="tab" data-bs-target="#link{{ $p }}" type="button" role="tab" aria-controls="link{{ $p }}" aria-selected="false" onclick="change_create_id({{ $type->id }})">{{ $type->name}}</button>
                        </li>
                    @endif
                    <?php $p++; ?>
                @endforeach
            </ul>

            <div class="tab-content" id="myTabContent">
                <?php $p=1; ?>
                @foreach($types as $type)
                    @if($p==1)
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <table class="table table-striped table-break">
                                <thead class="table-light">
                                    <tr>
                                        <th width="100">類別</th>
                                        <th width="60">排序</th>
                                        <th>圖示+名稱</th>
                                        <th>目標</th>
                                        <th>動作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $folders = \App\Models\Type::where('type_id',$type->id)->orderBy('order_by')->get(); @endphp
                                    @foreach($folders as $folder)
                                        <tr>
                                            <td>{{ $type->name }}</td>
                                            <td>{{ $folder->order_by }}</td>
                                            <td><i class="fas fa-folder"></i> {{ $folder->name }}</td>
                                            <td></td>
                                            <td>1</td>
                                        </tr>
                                        @if(isset($link_data[$folder->id]))
                                            @foreach($link_data[$folder->id] as $k=>$v)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $v['order_by'] }}</td>
                                                    <td>
                                                        ---->
                                                        <i class="{{ $v['icon'] ?? 'fas fa-globe' }}"></i>
                                                        <a href="{{ $v['url'] }}" target="_blank">{{ $v['name'] }}</a>
                                                    </td>
                                                    <td>
                                                        @if($v['target']==null) 開新視窗 <i class="fas fa-level-up-alt"></i>
                                                        @elseif($v['target']=="_self") 本視窗 @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('links.edit',$v['id']) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe"><i class="fas fa-edit"></i> 修改</a>
                                                        <a href="#!" class="btn btn-danger btn-sm delete-btn1" data-url="{{ route('links.delete',$v['id']) }}" data-msg="確定刪除？"><i class="fas fa-trash"></i> 刪除</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    {{-- 原本末尾的 link_data 邏輯 --}}
                                    @if(isset($link_data[$type->id]))
                                        @foreach($link_data[$type->id] as $k=>$v)
                                            <tr>
                                                <td>{{ $type->name }}</td>
                                                <td>{{ $v['order_by'] }}</td>
                                                <td>
                                                    <i class="{{ $v['icon'] ?? 'fas fa-globe' }}"></i>
                                                    <a href="{{ $v['url'] }}" target="_blank">{{ $v['name'] }}</a>
                                                </td>
                                                <td>
                                                    @if($v['target']==null) 開新視窗 <i class="fas fa-level-up-alt"></i>
                                                    @elseif($v['target']=="_self") 本視窗 @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('links.edit',$v['id']) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe"><i class="fas fa-edit"></i> 修改</a>
                                                    <a href="#!" class="btn btn-danger btn-sm delete-btn1" data-url="{{ route('links.delete',$v['id']) }}" data-msg="確定刪除？"><i class="fas fa-trash"></i> 刪除</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="tab-pane fade" id="link{{ $p }}" role="tabpanel" aria-labelledby="link{{ $p }}-tab">
                            {{-- 此處 logic 同上，為求簡潔省略重複表格內容 --}}
                            <table class="table table-striped table-break">
                                <thead class="table-light">
                                    <tr>
                                        <th width="100">類別</th>
                                        <th width="60">排序</th>
                                        <th>圖示+名稱</th>
                                        <th>目標</th>
                                        <th>動作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $folders = \App\Models\Type::where('type_id',$type->id)->orderBy('order_by')->get(); @endphp
                                    @foreach($folders as $folder)
                                        <tr>
                                            <td>{{ $type->name }}</td>
                                            <td>{{ $folder->order_by }}</td>
                                            <td><i class="fas fa-folder"></i> {{ $folder->name }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @if(isset($link_data[$folder->id]))
                                            @foreach($link_data[$folder->id] as $k=>$v)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $v['order_by'] }}</td>
                                                    <td>
                                                        ---->
                                                        <i class="{{ $v['icon'] ?? 'fas fa-globe' }}"></i>
                                                        <a href="{{ $v['url'] }}" target="_blank">{{ $v['name'] }}</a>
                                                    </td>
                                                    <td>
                                                        @if($v['target']==null) 開新視窗 <i class="fas fa-level-up-alt"></i>
                                                        @elseif($v['target']=="_self") 本視窗 @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('links.edit',$v['id']) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe"><i class="fas fa-edit"></i> 修改</a>
                                                        <a href="#!" class="btn btn-danger btn-sm delete-btn1" data-url="{{ route('links.delete',$v['id']) }}"><i class="fas fa-trash"></i> 刪除</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <?php $p++; ?>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 完全保留原始 JavaScript --}}
    <script nonce="{{ $csp_nonce }}">        
        function change_create_id(id){
            $('#go_create').attr('href', '{{ route("links.create") }}'+'/'+id);
        }
    </script>
    @if(!empty($type_id))    
        <?php $p=1; ?>
        @foreach($types as $type)
            @if($type->id == $type_id)
                @if($p != 1)  
                    <script nonce="{{ $csp_nonce }}">                                          
                        $("#home-tab").removeClass("active");
                        $("#home").removeClass("show");
                        $("#home").removeClass("active");
                        $("#link{{ $p }}-tab").addClass("active");
                        $("#link{{ $p }}").addClass("show");
                        $("#link{{ $p }}").addClass("active");
                    </script>
                @endif
            @endif
            <?php $p++; ?>
        @endforeach    
    @endif
@endsection