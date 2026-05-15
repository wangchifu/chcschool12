@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', 'RSS訊息 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-3">
                RSS訊息
            </h1>      
            <h3>新增</h3>
            <form action="{{ route('rss_feeds.store') }}" method="post" id="this_form1">
                @csrf      
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>
                            標題
                        </th>
                        <th>
                            網址
                        </th>
                        <th>
                            類別
                        </th>
                        <th>
                            最多顯示幾則
                        </th>
                        <th>
                            動作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>                    
                        <td>
                            <input type="text" class="form-control" name="title" required>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="url" required>
                        </td>
                        <td>
                            {{-- Bootstrap 5 下拉選單建議使用 form-select --}}
                            <select name="type" class="form-select" required>
                                <option value="1">條列式標題</option>
                                <option value="2">圖片式描述</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="num" value="12" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm save-btn" data-form="this_form1">送出</button>
                        </td>                    
                    </tr>
                </table>
            </form>
            <h3>列表</h3>
            <table class="table table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>
                        標題
                    </th>
                    <th>
                        網址
                    </th>
                    <th>
                        類別
                    </th>
                    <th>
                        最多顯示幾則
                    </th>
                    <th>
                        動作
                    </th>
                </tr>
                </thead>
                @foreach($rss_feeds as $rss_feed)
                <tr>
                    <td>
                        {{ $rss_feed->title }}
                    </td>
                    <td>
                        <a href="{{ $rss_feed->url }}" target="_blank" class="btn btn-link btn-sm text-decoration-none">連結</a> 
                    </td>
                    <td>
                        @if($rss_feed->type==1)
                        條列式標題
                        @endif
                        @if($rss_feed->type==2)
                        圖片式描述
                        @endif
                    </td>
                    <td>
                        {{ $rss_feed->num }}
                    </td>
                    <td>
                        <a href="{{ route('rss_feeds.edit',$rss_feed->id) }}" class="btn btn-primary btn-sm venobox" data-vbtype="iframe">編輯</a>
                        <a href="#!" class="btn btn-danger btn-sm delete-btn1" data-url="{{ route('rss_feeds.destory',$rss_feed->id) }}">刪除</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection