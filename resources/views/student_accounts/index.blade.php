@extends('layouts.master')

@section('title', '學生帳號 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="fw-bold text-dark mb-3">學生帳號</h1>
            @if($admin)  
                <div class="card shadow-sm border border-secondary border-opacity-10">
                    <div class="card-body p-4">
                        
                        <div class="mb-3">
                            <span class="text-secondary fw-semibold">XLSX檔上傳。</span>
                            [<a href="{{ asset('student_sample.xlsx') }}" target="_blank" class="text-decoration-none"><i class="fas fa-download me-1"></i>範本下載</a>]
                        </div>

                        {{-- 🎯 修正：將舊版 Form::open 換成符合 CSP 且最純粹的 HTML5 標準表單 --}}
                        <form action="{{ route('student_account.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                            @csrf             
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                                <input type="file" name="file" accept=".xlsx" class="form-control d-inline-block w-auto" required>
                                <button type="submit" class="btn btn-success fw-bold px-3">
                                    <i class="fas fa-file-import me-1"></i>匯入學生帳號清單
                                </button>
                                <a href="{{ route('student_account.check') }}" class="btn btn-info text-white fw-bold px-3" target="_blank">
                                    <i class="fas fa-search me-1"></i>學生帳號查詢頁面
                                </a>
                            </div>
                        </form>

                        @include('layouts.errors')
                        <hr class="text-muted opacity-25 my-4">
                        
                        <div class="mb-3 fw-bold text-secondary">
                            已上傳檔案，只能存在一個，其餘請刪除：
                        </div>
                        <div class="mb-4 p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                            @forelse($files as $file)
                                <div class="d-flex align-items-center gap-2 my-2">
                                    {{-- 🎯 修正：徹底移除 inline onclick，加上指定的 class "delete-file-btn" 讓底部的安全監聽器抓取 --}}
                                    <a href="#!" class="text-decoration-none delete-btn1" data-url="{{ route('student_account.delete', ['file' => $file]) }}">
                                        <i class="fas fa-times-circle text-danger fa-lg"></i>
                                    </a>                                     
                                    {{ $file }}                                    
                                </div>
                            @empty
                                <span class="text-muted small fst-italic">目前無歷史上傳檔案</span>
                            @endforelse
                        </div>

                        <hr class="text-muted opacity-25 my-4">
                        
                        <div class="mb-2 fw-bold text-secondary">學生帳號清單：</div>
                        <div class="table-responsive border border-secondary border-opacity-10 rounded-3 shadow-sm">
                            <table class="table table-hover table-bordered align-middle mb-0 text-center">
                                <thead class="table-primary text-dark fw-bold text-nowrap">
                                    <tr>
                                        <th scope="col" style="width: 80px;" class="py-3">#</th>
                                        <th scope="col" class="py-3">學生班級座號</th>
                                        <th scope="col" class="py-3">西元生日</th>
                                        <th scope="col" class="py-3">帳號</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($all_students as $index => $student)
                                        <tr>
                                            <td class="bg-light text-secondary">{{ $index + 1 }}</td>
                                            <td class="fw-medium text-dark">{{ $student['classnum'] ?? '--' }}</td>
                                            <td class="text-secondary small">{{ $student['birthday'] ?? '--' }}</td>
                                            <td>
                                                <code class="fw-bold bg-light border border-secondary border-opacity-25 px-2 py-1 rounded text-danger" style="font-size: 0.95rem;">
                                                    {{ $student['account'] ?? '未設定' }}
                                                </code>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="fas fa-users-slash fa-2x mb-3 d-block opacity-50"></i>
                                                目前沒有學生資料
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>                        
                        </div>

                    </div>
                </div>
            @endif            
        </div>
    </div>
@endsection