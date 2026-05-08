@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '內容管理 | ')

@section('content')
    {{-- 統一使用 py-4 增加與導覽列的間距 --}}
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3">內容管理</h1>
            
            {{-- 麵包屑導覽 --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none">首頁</a></li>
                    @if(empty($tag))
                        <li class="breadcrumb-item active" aria-current="page">內容列表</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('contents.index') }}" class="text-decoration-none">內容列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">標籤「{{ $tag }}」</li>
                    @endif
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                {{-- 將 d-flex 的 justify-content-between 移除，讓按鈕靠左緊跟標題 --}}
                <div class="card-header bg-white py-3 d-flex align-items-center">                    
                    {{-- 按鈕靠左 --}}
                    <a href="{{ route('contents.create') }}" class="btn btn-success btn-sm px-3 shadow-sm venobox" data-vbtype="iframe">
                        <i class="fas fa-plus me-1"></i> 新增內容
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="word-break:break-all;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th style="width: 110px;">權限</th>
                                    <th style="width: 120px;">共編群組</th>
                                    <th>標題</th>
                                    <th>標籤</th>
                                    <th class="text-center" style="width: 180px;">動作</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($contents as $content)
                                <tr>
                                    <td class="text-secondary ps-3">{{ $content->id }}</td>
                                    <td>
                                        @if($content->power == null)
                                            <span class="badge bg-success">公開</span>
                                        @elseif($content->power == 2)
                                            <span class="badge bg-warning text-dark">校內 | 登入</span>
                                        @elseif($content->power == 3)
                                            <span class="badge bg-info text-dark">登入</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $group_id = $content->group_id ?: "1"; @endphp
                                        <small class="text-muted fw-bold">{{ $group_array[$group_id] }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('contents.show', $content->id) }}" target="_blank" class="text-decoration-none fw-bold text-dark">
                                            {{ $content->title }}
                                            <i class="fas fa-external-link-alt small ms-1 text-muted"></i>
                                        </a>
                                    </td>
                                    <td>
                                        {{-- 處理標籤顯示 --}}
                                        @php 
                                            // 分割標籤並過濾掉空值
                                            $tags = array_filter(explode(',', $content->tags)); 
                                        @endphp
                                        @foreach($tags as $v)
                                            <a class="badge rounded-pill bg-light text-dark border text-decoration-none me-1 mb-1" href="{{ route('contents.search', $v) }}">
                                                #{{ $v }}
                                            </a>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            {{-- 修改按鈕 --}}
                                            <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                                <i class="fas fa-edit"></i> 修改
                                            </a>
                                            
                                            {{-- 刪除按鈕：對接您在 my_js.js 定義的 delete-btn1 邏輯 --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn2"                                                     
                                                    data-form="delete_form{{ $content->id }}">
                                                <i class="fas fa-trash"></i> 刪除
                                            </button>
                                        </div>

                                        {{-- 純 HTML 刪除表單 --}}
                                        <form action="{{ route('contents.destroy', $content->id) }}" method="POST" id="delete_form{{ $content->id }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 如果有分頁功能可在此加入 --}}
                    @if(method_exists($contents, 'links'))
                        <div class="mt-4">
                            {{ $contents->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection