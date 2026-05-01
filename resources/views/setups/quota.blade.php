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
            $active[3] = "";
            $active[4] = "";
            $active[5] = "";
            $active[6] = "active";
            ?>
            @include('setups.nav',$active)
            <div class="card my-4">
                <h3 class="card-header">空間管理</h3>
                <div class="card-body">
                    <div class="mb-4">
                        @include('layouts.hd')
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-folder-open text-primary me-2"></i>
                        <h4 class="mb-0">
                            全部公開目錄：<span class="badge bg-primary">{{ round($quota['public']['all']/1024,2) }} MB</span>
                        </h4>
                    </div>

                    <div class="table-responsive mb-5">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>位置</th>
                                <th>模組</th>
                                <th>所佔空間</th>
                                <th>備註</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($quota['public'] as $k=>$v)
                                @if($k != "all")
                                <tr>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">公開</span>
                                    </td>
                                    <td>
                                        {{ $k }}
                                    </td>
                                    <td class="fw-bold">
                                        {{ round($v/1024,2) }} MB
                                    </td>
                                    <td>
                                        @if($k=="公告附件")
                                            <a href="{{ route('setups.batch_delete_posts') }}" class="btn btn-danger btn-sm venobox" data-vbtype="iframe">
                                                <i class="fas fa-eraser me-1"></i> 批次刪除公告及附件
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-5">

                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-lock text-secondary me-2"></i>
                        <h4 class="mb-0">
                            全部不公開目錄：<span class="badge bg-secondary">{{ round($quota['privacy']['all']/1024,2) }} MB</span>
                        </h4>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>位置</th>
                                <th>模組</th>
                                <th>所佔空間</th>
                                <th style="width: 200px;">備註</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($quota['privacy'] as $k=>$v)
                                @if($k != "all")
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">不公開</span>
                                        </td>
                                        <td>
                                            {{ $k }}
                                        </td>
                                        <td class="fw-bold">
                                            {{ round($v/1024,2) }} MB
                                        </td>
                                        <td>
                                            </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    
@endsection
