@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '編輯類別 | ')

@section('content')
    <div class="row justify-content-center g-4">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">編輯類別</h1>
            
            <div class="d-none">
                @foreach($fix_classes as $fix_class)
                    <form method="POST" action="{{ route('fixes.update_class', $fix_class->id) }}" id="form_update_{{ $fix_class->id }}">
                        @csrf
                    </form>
                @endforeach
                
                <form method="POST" action="{{ route('fixes.store_class') }}" id="form_store_class">
                    @csrf
                </form>
            </div>

            <div class="card border border-secondary border-opacity-10 shadow-sm rounded-3 overflow-hidden my-4">
                <h3 class="card-header bg-light fs-5 fw-bold py-3 px-4 text-dark border-bottom">
                    編輯類別
                </h3>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="py-3 px-4">狀況</th>
                                    <th scope="col" class="py-3 px-3">排序</th>
                                    <th scope="col" class="py-3 px-3">啟用?</th>
                                    <th scope="col" class="py-3 px-3">名稱 <strong class="text-danger">*</strong></th>
                                    <th scope="col" class="py-3 px-4 text-end">動作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fix_classes as $fix_class)
                                    <tr>
                                        <td class="px-4">
                                            @if($fix_class->disable)
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-2 fw-bold">已停用</span> 
                                            @else
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-2 fw-bold">啟用</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-3">
                                            <input type="text" value="{{ $fix_class->order_by }}" name="order_by" form="form_update_{{ $fix_class->id }}" class="form-control form-control-sm">
                                        </td>
                                        
                                        <td class="px-3">
                                            <?php $disable = ($fix_class->disable) ? "checked" : null; ?>
                                            <div class="form-check form-switch mb-0">
                                                <input type="checkbox" name="disable" value="1" id="disable{{ $fix_class->id }}" form="form_update_{{ $fix_class->id }}" class="form-check-input" {{ $disable }}>
                                                <label class="form-check-label small text-secondary fw-medium" for="disable{{ $fix_class->id }}">停用</label>
                                            </div>
                                        </td>
                                        
                                        <td class="px-3">
                                            <input type="text" value="{{ $fix_class->name }}" name="name" form="form_update_{{ $fix_class->id }}" class="form-control form-control-sm fw-semibold" required>
                                        </td>
                                        
                                        <td class="px-4 text-end">
                                            <button type="button" data-form="form_update_{{ $fix_class->id }}" class="btn btn-primary btn-sm fw-bold px-3 btn-class-update-submit save-btn">
                                                <i class="fas fa-sync-alt me-1"></i> 更新此行
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-group-divider border-top-0">
                                <tr class="bg-light bg-opacity-50">
                                    <td class="px-4 fw-bold text-success">
                                        <i class="fas fa-plus-circle me-1"></i> 新增
                                    </td>
                                    
                                    <td class="px-3">
                                        <input type="text" name="order_by" form="form_store_class" class="form-control form-control-sm" placeholder="新增的排序">
                                    </td>
                                    
                                    <td class="px-3">
                                        <div class="form-check form-switch mb-0">
                                            <input type="checkbox" name="disable" value="1" id="disable_new" form="form_store_class" class="form-check-input">
                                            <label class="form-check-label small text-secondary fw-medium" for="disable_new">停用</label>
                                        </div>
                                    </td>
                                    
                                    <td class="px-3">
                                        <input type="text" name="name" form="form_store_class" class="form-control form-control-sm" required placeholder="新增的名稱">
                                    </td>
                                    
                                    <td class="px-4 text-end">
                                        <button type="button" data-form="form_store_class" class="btn btn-success btn-sm fw-bold px-3 btn-class-store-submit save-btn">
                                            <i class="fas fa-check me-1"></i> 新增
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection