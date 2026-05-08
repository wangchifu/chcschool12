@extends('layouts.master')

@section('title', '校園跑馬燈設定 | ')

@section('content')        
    <div class="row justify-content-center py-4">
        <div class="col-md-11">
            <h1 class="fw-bold mb-3"><i class="fas fa-running me-2"></i>校園跑馬燈</h1>
            
            {{-- 分頁導覽 --}}
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_marquee.setup') }}"><i class="fas fa-cog"></i> 管理設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_marquee.index') }}"><i class="fas fa-list"></i> 跑馬燈列表</a>
                </li>
            </ul>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3 text-center">
                    <h3 class="card-title mb-0 fw-bold">跑馬燈效果設定</h3>
                </div>
                <div class="card-body p-4">
                    {{-- 預覽區域 --}}
                    @if($school_marquees->count() > 0)                    
                        @php
                            $m_width = $setup->school_marquee_width ?? "12";
                            $m_color = $setup->school_marquee_color ?? "warning";
                            $m_behavior = $setup->school_marquee_behavior ?? "scroll";
                            $m_direction = $setup->school_marquee_direction ?? "up";
                            $m_speed = $setup->school_marquee_scrollamount ?? "2";
                        @endphp
                        
                        <div class="row justify-content-center mb-5">
                            <div class="col-lg-{{ $m_width }}">
                                <div class="alert alert-{{ $m_color }} shadow-sm">
                                    <h6 class="alert-heading small mb-2 text-muted fw-bold">效果預覽：</h6>
                                    <marquee behavior="{{ $m_behavior }}" direction="{{ $m_direction }}" scrollamount="{{ $m_speed }}" style="height: 24px;">
                                        @foreach($school_marquees as $marquee)
                                            @if($m_direction == "up" || $m_direction == "down")
                                                <p class="mb-0">{{ $marquee->title }}</p>                                                
                                            @else
                                                <span class="me-5"><i class="fas fa-bullhorn me-1 text-danger"></i> {{ $marquee->title }}</span>
                                            @endif
                                        @endforeach
                                    </marquee>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('school_marquee.setup_store') }}" method="POST" id="this_form1">
                        @csrf
                        <div class="row">
                            {{-- 寬度設定 --}}
                            <div class="col-md-6 mb-3">
                                <label for="school_marquee_width" class="form-label fw-bold">顯示寬度</label>
                                <select class="form-select" name="school_marquee_width" id="school_marquee_width">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ (isset($setup->school_marquee_width) && $setup->school_marquee_width == $i) ? 'selected' : '' }}>
                                            {{ $i }} 格 (Grid)
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- 顏色設定 --}}
                            <div class="col-md-6 mb-3">
                                <label for="school_marquee_color" class="form-label fw-bold">背景顏色樣式</label>
                                <select class="form-select" name="school_marquee_color" id="school_marquee_color">
                                    @foreach(['primary'=>'藍色 (Primary)', 'secondary'=>'灰色 (Secondary)', 'success'=>'綠色 (Success)', 'danger'=>'紅色 (Danger)', 'warning'=>'黃色 (Warning)', 'info'=>'青色 (Info)', 'light'=>'白色 (Light)', 'dark'=>'深色 (Dark)'] as $val => $label)
                                        <option value="{{ $val }}" {{ (isset($setup->school_marquee_color) && $setup->school_marquee_color == $val) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 移動樣式 --}}
                            <div class="col-md-4 mb-3">
                                <label for="school_marquee_behavior" class="form-label fw-bold">移動樣式</label>
                                <select class="form-select" name="school_marquee_behavior" id="school_marquee_behavior">
                                    @foreach(['scroll'=>'滾動 (Scroll)', 'slide'=>'滑動 (Slide)', 'alternate'=>'交替 (Alternate)'] as $val => $label)
                                        <option value="{{ $val }}" {{ (isset($setup->school_marquee_behavior) && $setup->school_marquee_behavior == $val) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 方向 --}}
                            <div class="col-md-4 mb-3">
                                <label for="school_marquee_direction" class="form-label fw-bold">移動方向</label>
                                <select class="form-select" name="school_marquee_direction" id="school_marquee_direction">
                                    @foreach(['left'=>'向左 ←', 'right'=>'向右 →', 'up'=>'向上 ↑', 'down'=>'向下 ↓'] as $val => $label)
                                        <option value="{{ $val }}" {{ (isset($setup->school_marquee_direction) && $setup->school_marquee_direction == $val) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 速度 --}}
                            <div class="col-md-4 mb-3">
                                <label for="school_marquee_scrollamount" class="form-label fw-bold">移動速度</label>
                                <select class="form-select" name="school_marquee_scrollamount" id="school_marquee_scrollamount">
                                    @foreach([2, 4, 6, 8, 10, 12, 14, 16] as $speed)
                                        <option value="{{ $speed }}" {{ (isset($setup->school_marquee_scrollamount) && $setup->school_marquee_scrollamount == $speed) ? 'selected' : '' }}>
                                            {{ $speed }} {{ $speed <= 4 ? '(慢)' : ($speed >= 12 ? '(快)' : '') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="text-center">
                            {{-- 使用 save-btn 類別對接全域 SweetAlert 邏輯 --}}
                            <span class="btn btn-primary px-5 save-btn" data-form="this_form1">
                                <i class="fas fa-save me-1"></i> 儲存設定
                            </span>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
@endsection