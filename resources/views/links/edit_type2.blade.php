@extends('layouts.master_clean')

@section('title', '修改類別 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">修改子類別</h1>
            @include('layouts.errors')
            <form action="{{ route('links.update_type', $type->id) }}" method="post" id="this_form1">
                @csrf
                @method('patch')
                <table class="table table-borderless mb-0 align-middle">
                    <tr>
                        <td width="120">
                            <select name="type_id" class="form-control" required>
                                @foreach($type_array as $k => $v)
                                    <option value="{{ $k }}" {{ $type->type_id == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td width="100">
                            <input type="number" name="order_by" value="{{ $type->order_by }}" id="order_by" class="form-control" placeholder="數字">
                        </td>
                        <td>
                            <input type="text" name="name" value="{{ $type->name }}" id="name" class="form-control" required placeholder="名稱">
                        </td>
                        <td width="100" class="text-nowrap">                                                        
                            </a>
                        </td>
                    </tr>
                </table>
                {{-- 依照要求修改為 save-btn 格式 --}}
                <button type="button" class="btn btn-primary save-btn" data-form="this_form1"><i class="fas fa-save"></i> 儲存修改</button>                                
            </form>                                       
        </div>
    </div>
@endsection
