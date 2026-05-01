@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">
                網站設定
            </h1>
            <?php
            $active[1] = "";
            $active[2] = "";
            $active[3] = "active";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "";
            ?>
            @include('setups.nav', $active)
            
            <div class="card my-4">
                <h3 class="card-header">首頁欄位</h3>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('setups.add_col_table') }}" class="btn btn-success btn-sm venobox" data-vbtype="iframe">
                            <i class="fas fa-plus me-1"></i> 新增欄位
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        排序
                                    </th>
                                    <th>
                                        名稱
                                    </th>
                                    <th>
                                        所佔比例 ( bootstrap 網頁一行佔 12 )
                                    </th>
                                    <th>
                                        動作
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($setup_cols as $setup_col)
                                    <tr>
                                        <td>
                                            {{ $setup_col->order_by }}
                                        </td>
                                        <td>
                                            {{ $setup_col->title }}
                                        </td>
                                        <td>
                                            {{ $setup_col->num }}
                                        </td>
                                        <td>
                                            <a href="{{ route('setups.edit_col',$setup_col->id) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                                <i class="fas fa-edit me-1"></i> 編輯
                                            </a>
                                        </td>
                                    </tr>
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
