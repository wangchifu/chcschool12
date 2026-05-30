@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務行事曆 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h2 text-dark mb-1 fw-bold">校務行事曆</h1>
                    <p class="text-muted small mb-0">全校學期重要活動與日程概覽</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                
                <div class="card-header bg-light border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center">
                        <form name="myform" class="row g-2 align-items-center">
                            @csrf
                            <div class="col-auto">
                                <label for="semester-select" class="fw-bold text-secondary mb-0">
                                    <i class="fas fa-filter me-1"></i> 學期選單：
                                </label>
                            </div>
                            <div class="col-auto">
                                <select id="semester-select" name="semester" title="請選擇年度學期" class="form-select form-select-sm border-secondary-subtle">
                                    <option value="">--請選擇--</option>
                                    @foreach($semesters as $v)
                                        <option value="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>                        
                    </div>

                    <div class="d-flex gap-2">
                        @if($has_week)
                            @can('create',\App\Models\Post::class)
                                <a href="{{ route('calendars.create',$semester) }}" class="btn btn-success btn-sm shadow-sm d-inline-flex align-items-center venobox" data-vbtype="iframe">
                                    <i class="fas fa-plus me-1"></i> 新增{{ $semester }}學期行事
                                </a>
                            @endcan
                        @endif
                        @auth
                            @if(auth()->user()->admin)
                                <a href="{{ route('calendar_weeks.index') }}" class="btn btn-primary btn-sm shadow-sm d-inline-flex align-items-center venobox" data-vbtype="iframe">
                                    <i class="fas fa-cogs me-1"></i> 學期管理
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h5 fw-bold mb-0 text-primary border-start border-4 border-primary ps-2">
                            {{ $semester }} 學期校務行事表
                        </h3>
                        <a href="{{ route('calendars.print',$semester) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center" target="_blank">
                            <i class="fas fa-print me-1"></i> 列印本頁
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th width="90" scope="col">週別</th>
                                    <th width="140" scope="col">起迄期間</th>
                                    @foreach(config('chcschool.calendar_kind') as $v)
                                        <th scope="col" class="text-nowrap" style="min-width: 150px;">{{ $v }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calendar_weeks as $calendar_week)
                                    <tr>
                                        <td class="text-nowrap text-center fw-bold bg-light-subtle">
                                            第 {{ $calendar_week->week }} 週
                                        </td>
                                        <td class="text-nowrap text-center text-muted small bg-light-subtle">
                                            {{ $calendar_week->start_end }}
                                            @auth
                                                @if(auth()->user()->admin)
                                                    <div class="mt-1">
                                                        <a href="{{ route('calendar_weeks.edit',$semester) }}" class="badge bg-info text-dark decoration-none venobox" data-vbtype="iframe">修改</a>
                                                    </div>
                                                @endif
                                            @endauth
                                        </td>
                                        @foreach(config('chcschool.calendar_kind') as $k => $v)
                                            <td scope="col" class="p-2">
                                                @if(!empty($calendar_data[$calendar_week->id][$k]))
                                                    <?php $i=1; ?>
                                                    @foreach($calendar_data[$calendar_week->id][$k] as $k => $v)
                                                        <div class="p-1 rounded mb-1 d-flex align-items-start justify-content-between bg-light-subtle">
                                                            <span class="small text-dark">
                                                                <strong class="text-primary">{{ $i }}.</strong>{{ $v['content'] }}
                                                            </span>
                                                            
                                                            @auth
                                                                @if($v['user_id'] == auth()->user()->id or auth()->user()->admin==1)
                                                                    <span class="ms-2 text-nowrap">
                                                                        <a href="{{ route('calendars.edit',$k) }}" class="text-info me-1 venobox" title="編輯" data-vbtype="iframe"><i class="fas fa-edit small"></i></a>
                                                                        <a href="#!" class="text-danger delete-btn1" data-url="{{ route('calendars.delete',$k) }}" title="刪除"><i class="fas fa-minus-square small"></i></a>
                                                                    </span>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>    
    <script nonce="{{ $csp_nonce }}">
        document.addEventListener('DOMContentLoaded', function () {
            const semesterSelect = document.getElementById('semester-select');
            if (semesterSelect) {
                semesterSelect.addEventListener('change', function () {
                    const value = this.value;
                    if (value !== '') {
                        location.href = "/calendars/index/" + value;
                    }
                });
            }
        });
    </script>
@endsection