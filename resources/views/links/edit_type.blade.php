@extends('layouts.master_clean')

@section('title', '修改類別 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">修改類別</h1>
            @include('layouts.errors')
            <form action="{{ route('links.update_type', $type->id) }}" method="post" id="this_form1">
                @csrf
                @method('patch')
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>排序</th>
                            <th>名稱</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 100px;">
                                <input type="number" name="order_by" value="{{ $type->order_by }}" id="order_by" class="form-control" placeholder="數字">
                            </td>
                            <td>
                                <input type="text" name="name" value="{{ $type->name }}" id="name" class="form-control" required placeholder="名稱">
                            </td>                            
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary save-btn" data-form="this_form1"><i class="fas fa-save"></i> 儲存修改</button>                                
            </form>            
        </div>
    </div>
@endsection
