@extends('layouts.master_clean')

@section('title', '修改RSS | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">修改RSS</h1>
            @include('layouts.errors')
            <form action="{{ route('rss_feeds.update',$rss_feed->id) }}" method="post" id="this_form1">
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
                            <input type="text" class="form-control" name="title"  value="{{ $rss_feed->title }}" required>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="url" value="{{ $rss_feed->url }}" required>
                        </td>
                        <td>
                            {{-- Bootstrap 5 下拉選單建議使用 form-select --}}
                            <select name="type" class="form-select" required>
                                <option value="1" {{ $rss_feed->type == 1 ? 'selected' : '' }}>條列式標題</option>
                                <option value="2" {{ $rss_feed->type == 2 ? 'selected' : '' }}>圖片式描述</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="num" value="{{ $rss_feed->num }}" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm save-btn" data-form="this_form1">送出</button>
                        </td>                    
                    </tr>
                </table>
            </form>                    
        </div>
    </div>
@endsection
