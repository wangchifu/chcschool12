@extends('layouts.master_clean')

@section('title', '填報結果')

@section('content')    
    <div class="row justify-content-center g-4 my-3">
        <div class="col-md-11">
            
            {{-- 標題與下載按鈕區 --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h1 class="h2 fw-bold text-dark mb-0">
                    <i class="fas fa-poll-h text-primary me-2"></i>{{ $report_student->name }} - 填報結果
                </h1>          
                <a href="{{ route('report_students.admin_result_download', $report_student->id) }}" class="btn btn-success px-4 shadow-sm fw-bold">
                    <i class="fas fa-file-excel me-2"></i>下載 Excel 報表
                </a>
            </div>
            
            {{-- 🎯 加上 table-responsive 防止欄位過多時撐破網頁，並加上陰影外框 --}}
            <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm mb-4">
                <table class="table table-bordered table-striped table-hover align-middle mb-0 text-center">
                    <thead class="table-primary text-dark fw-bold text-nowrap">
                        <tr>
                            <th scope="col" class="py-3 px-4" style="width: 120px;">班級</th>
                            @foreach($report_student->items as $item)
                                <th scope="col" class="py-3 px-3">{{ $item->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($answer_data as $class_num => $items)
                            <tr>
                                <td class="fw-bold bg-light text-secondary">{{ $class_num }}</td>
                                
                                @foreach($report_student->items as $item)
                                    <td>
                                        @if(isset($items[$item->id]))
                                            {{-- 有答案時顯示學生姓名 --}}
                                            <span class="text-dark fw-medium">{{ $items[$item->id]['name'] }}</span>
                                        @else
                                            {{-- 🎯 優化：若該班級漏填此題，顯示灰字斜體，避免格子全空很突兀 --}}
                                            <span class="text-muted small fst-italic">--</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            {{-- 防呆：萬一目前完全沒有任何班級填報資料 --}}
                            <tr>
                                <td colspan="{{ count($report_student->items) + 1 }}" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-3 d-block opacity-50"></i>
                                    目前尚無任何班級填報資料。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>    
                </table>
            </div>

        </div>
    </div>
@endsection