@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '區塊內容 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">
                網站設定
            </h1>
            <?php
            $active[1] = "";
            $active[2] = "";
            $active[3] = "";
            $active[4] = "active";
            $active[5] = "";
            $active[6] = "";
            ?>
            @include('setups.nav',$active)
            <div class="card my-4">
                <h3 class="card-header">區塊內容</h3>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('setups.add_block_table') }}" class="btn btn-success btn-sm venobox" data-vbtype="iframe">
                            <i class="fas fa-plus me-1"></i> 新增區塊
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>
                                    放置欄位名稱 (id)
                                </th>
                                <th>
                                    排序
                                </th>
                                <th>
                                    名稱
                                </th>
                                <th>
                                    css id
                                </th>
                                <th>
                                    編輯
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($down_blocks as $k=>$v)
                                <tr>
                                    <td>
                                        <small class="text-secondary">{{ $v['col'] }}</small>
                                    </td>
                                    <td>
                                        {{ $v['order_by'] }}
                                    </td>
                                    <?php
                                        if(str_contains($v['title'],"(系統區塊)")==true or str_contains($v['title'],"榮譽榜跑馬燈")==true){
                                            $text_color = "text-info";
                                        }else{
                                            $text_color = "text-dark";
                                        };
                                    ?>
                                    <td class="{{ $text_color }}">                                    
                                        {{ $v['title'] }}
                                    </td>
                                    <td>
                                        <code>id="block{{ $k }}"</code>
                                    </td>
                                    <td>
                                        <a href="{{ route('setups.edit_block',$k) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                            <i class="fas fa-edit me-1"></i> 編輯
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @foreach($up_blocks as $k=>$v)
                                @foreach($v as $k1=>$v1)
                                <tr>
                                    <td>
                                        {{ $v1['col'] }}
                                    </td>
                                    <td>
                                        {{ $v1['order_by'] }}
                                    </td>
                                    <?php
                                        if(str_contains($v1['title'],"(系統區塊)")==true or str_contains($v1['title'],"榮譽榜跑馬燈")==true){
                                            $text_color = "text-info";
                                        }else{
                                            $text_color = "text-dark";
                                        };
                                    ?>
                                    <td class="{{ $text_color }}">                                    
                                        {{ $v1['title'] }}
                                    </td>
                                    <td>
                                        <code>id="block{{ $k1 }}"</code>
                                    </td>
                                    <td>
                                        <a href="{{ route('setups.edit_block',$k1) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                            <i class="fas fa-edit me-1"></i> 編輯
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
