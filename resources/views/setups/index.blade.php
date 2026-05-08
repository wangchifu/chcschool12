@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '網站設定 | ')

@section('my_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')    
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="mb-4">網站設定</h1>
            <?php
            $active[1] = "active";
            $active[2] = ""; $active[3] = ""; $active[4] = ""; $active[5] = ""; $active[6] = "";
            $nav_color = explode(',',$setup->nav_color);
            $c1 = (empty($nav_color[0]))?"#DD0F20":$nav_color[0];
            $c2 = (empty($nav_color[1]))?"#F18A31":$nav_color[1];
            $c3 = (empty($nav_color[2]))?"#F8EB48":$nav_color[2];
            $c4 = (empty($nav_color[3]))?"#16813D":$nav_color[3];
            $c5 = (empty($setup->bg_color))?"#f0f1f6":$setup->bg_color;            
            ?>
            @include('setups.nav',$active)

            <div class="card border-success border-2 border-opacity-100 my-4 shadow-sm">
                <h3 class="card-header border-success border-2 border-opacity-100 bg-light">設定一、基本設定</h3>
                <div class="card-body">
                    @include('layouts.errors')    
                    <form action="{{ route('setups.text', $setup->id) }}" method="POST" id="this_form1">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="site_name" class="form-label fw-bold">網站名稱</label>                        
                            <input type="text" name="site_name" id="site_name" value="{{ $setup->site_name }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">學校真實IP範圍</label><br>
                            <div class="d-inline-flex gap-2 mb-2">
                                <a href="{{ asset('ipv4.xlsx') }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-file-excel me-1"></i> IPv4 參考文件
                                </a>
                                <a href="{{ asset('ipv6.xlsx') }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-file-excel me-1"></i> IPv6 參考文件
                                </a>
                            </div>
                            <table class="table table-sm table-borderless w-auto mt-2">
                                <tr>
                                    <td class="align-middle">IPv4 從</td>
                                    <td><input type="text" name="ip1" value="{{ old('ip1', $setup->ip1) }}" class="form-control"></td>
                                    <td class="align-middle">到</td>
                                    <td><input type="text" name="ip2" value="{{ old('ip2', $setup->ip2) }}" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td class="align-middle">IPv6</td>
                                    <td colspan="3">                                    
                                        <input type="text" name="ipv6" value="{{ old('ipv6', $setup->ipv6) }}" class="form-control" placeholder="如：2001:288:5637::/48">
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label for="views" class="form-label fw-bold">瀏覽人數</label>                        
                            <input type="text" name="views" id="views" value="{{ old('views', $setup->views) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <p class="mb-2 fw-bold d-flex align-items-center">
                            網頁背景色
                            <a href="https://www.toolskk.com/color" target="_blank" 
                            class="link-secondary link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover text-decoration-none small border border-secondary-subtle px-2 py-1 rounded">
                                <i class="fas fa-palette me-1 text-primary"></i> 線上色碼表
                            </a>                            
                        </p>

                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white text-secondary">#</span>
                                
                                <input type="text" class="form-control color-input" value="{{ $c5 }}" name="bg_color">
                                
                                <span class="input-group-text p-0 border-start-0 bg-white">
                                    <input type="color" class="form-control form-control-color border-0 color-picker" 
                                        value="{{ $c5 }}" 
                                        style="min-width: 50px; height: 100%; cursor: pointer;">
                                </span>
                            </div>
                            
                            <div class="form-text mt-1">
                                請輸入 6 位十六進位色碼或點擊右側方塊選色。
                            </div>
                        </div>                                                               

                        <div class="mb-3">
                            <label for="footer" class="form-label fw-bold">置底  <span class="badge bg-secondary-subtle text-secondary fw-bold ms-1 fs-6 border border-secondary-subtle">id="footer"</span></label>
                            <textarea name="footer" id="my_editor" class="form-control">{{ old('footer', $setup->footer) }}</textarea>
                        </div>                        

                        <?php 
                            $disable_right = ($setup->disable_right)?"checked":"";
                            $r1 = (empty($setup->close_website))?"checked":"";
                            $r2 = (empty($setup->close_website))?"":"checked";
                        ?>
                        
                        <div class="form-check mb-4 border p-3 rounded bg-light">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="disable_right" name="disable_right" {{ $disable_right }} value="1" role="switch" style="cursor: pointer;">
                                <label class="form-check-label fw-bold" for="disable_right" style="cursor: pointer;">
                                    隱藏版權列 <span class="badge bg-secondary-subtle text-secondary fw-bold ms-1 fs-6 border border-secondary-subtle">id="footer_bottom"</span>
                                </label>
                            </div>

                            <div class="mt-2 border rounded overflow-hidden shadow-sm bg-white" style="max-width: 1000px;">
                                <div class="p-2 bg-light border-bottom small text-muted fw-bold">
                                    <i class="fas fa-eye me-1"></i> 目前版權列樣式預覽：
                                </div>
                                
                                <div class="footer-copyright text-center py-3 bg-body-secondary" id="preview_footer_bottom">
                                    <div class="container-fluid">
                                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 text-secondary" style="font-size: 0.85rem;">
                                            
                                            <div class="fw-medium">
                                                &copy; {{ date('Y') }} 
                                                <span class="text-dark">{{ $setup->site_name }}</span> 
                                                <span class="mx-1">All Rights Reserved.</span>
                                            </div>

                                            <span class="opacity-25 d-none d-md-inline">•</span>

                                            <div class="d-flex align-items-center bg-white px-2 py-1 rounded-pill border">
                                                <i class="fas fa-chart-line text-primary me-1"></i>
                                                <span>訪客人次：</span>
                                                <span class="fw-bold text-dark">{{ number_format($setup->views) }}</span>
                                            </div>

                                            <div class="d-flex align-items-center bg-white px-2 py-1 rounded-pill border">
                                                <i class="fas fa-network-wired text-info me-1"></i>
                                                <span>您的 IP：</span>
                                                <span class="fw-bold text-dark">{{ GetIP() }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                網站運行狀態 <span class="text-danger small">(請注意！關閉後訪客將無法進入網站！)</span>
                            </label>

                            <div class="card border-danger border-3 shadow-sm">
                                <div class="card-body">
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" name="set_close_website" id="site_status_switch" 
                                            value="off" {{ $r2 }} role="switch" style="cursor: pointer; width: 3em; height: 1.5em;">
                                        
                                        <label class="form-check-label fw-bold ms-2 mt-1" for="site_status_switch" style="cursor: pointer;">
                                            設定為「網站關閉」
                                            <span class="badge bg-danger-subtle text-danger fw-normal ms-1">緊急停機</span>
                                        </label>
                                    </div>

                                    <div class="mt-3 p-3 bg-light rounded border border-danger-subtle">
                                        <label for="close_website" class="form-label small fw-bold text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i> 網站關閉時顯示的原因
                                        </label>
                                        <input type="text" name="close_website" id="close_website" 
                                            value="{{ old('close_website', $setup->close_website) }}" 
                                            class="form-control" 
                                            placeholder="例如：系統維護中，預計下午兩點開放">
                                        <div class="form-text mt-1 text-muted">
                                            ※ 此文字將會顯示在關站後的公告畫面上。
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>                        

                        <span class="btn btn-success btn-sm save-btn" data-form="this_form1">
                            <i class="fas fa-save"></i> 儲存設定一
                        </span>
                    </form>
                </div>
            </div>

            <form action="{{ route('setups.nav_color', $setup->id) }}" method="POST" id="this_form2" onsubmit="return false">
                @csrf
                @method('PATCH')
                <div class="card border-primary border-2 border-opacity-100 my-4 shadow-sm">
                    <h3 class="card-header border-primary border-2 border-opacity-100 bg-light">設定二、導覽列設定</h3>
                    <div class="card-body">                    
                        <?php $checked = ($setup->fixed_nav)?"checked":null; ?>
                        
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="fixed_nav" class="form-check-input" id="customCheck1" {{ $checked }}>
                            <label class="form-check-label" for="customCheck1">固定導覽列？</label>                        
                        </div>

                        <p class="mb-2 fw-bold d-flex align-items-center">
                            顏色設定 
                            <a href="https://www.toolskk.com/color" target="_blank" 
                            class="link-secondary link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover text-decoration-none small border border-secondary-subtle px-2 py-1 rounded">
                                <i class="fas fa-palette me-1 text-primary"></i> 線上色碼表
                            </a>                            
                        </p>
                        
                        @foreach([['c1', '導覽列顏色', 'cp1'], ['c2', '網站名稱文字顏色', 'cp2'], ['c3', '連結文字顏色', 'cp3'], ['c4', '連結文字移上時顏色', 'cp4']] as $item)
                            <div id="{{ $item[2] }}" class="input-group mb-3">
                                <span class="input-group-text">{{ $item[1] }}</span>
                                
                                <input type="text" class="form-control color-input" value="{{ ${$item[0]} }}" name="color[]">
                                
                                <span class="input-group-text p-0">
                                    <input type="color" class="form-control form-control-color border-0 color-picker" 
                                        value="{{ ${$item[0]} }}" 
                                        style="min-width: 50px; cursor: pointer;">
                                </span>
                            </div>
                        @endforeach

                        <div class="mb-3 mt-4">
                            <label class="form-label fw-bold">系統功能按鈕改名</label>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr class="small text-center">
                                            <th>首頁</th><th>公告系統</th><th>檔案庫</th><th>學校介紹</th><th>校務行政</th><th>系統設定</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="homepage_name" value="{{ old('homepage_name', $setup->homepage_name) }}" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="post_name" value="{{ old('post_name', $setup->post_name) }}" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="openfile_name" value="{{ old('openfile_name', $setup->openfile_name) }}" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="department_name" value="{{ old('department_name', $setup->department_name) }}" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="schoolexec_name" value="{{ old('schoolexec_name', $setup->schoolexec_name) }}" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="setup_name" value="{{ old('setup_name', $setup->setup_name) }}" class="form-control form-control-sm"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <span class="btn btn-primary btn-sm save-btn" data-form="this_form2">
                            <i class="fas fa-save"></i> 儲存設定二
                        </span>
                        @if($setup->nav_color != "#DD0F20,#F18A31,#F8EB48,#16813D")
                            <span id="saveBtn3" class="btn btn-danger btn-sm" data-url="{{ route('setups.nav_default') }}">
                                <i class="fa-solid fa-trash"></i> 還原「設定二」回預設
                            </span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>    
@endsection


@section('down_body')
    <script nonce="<?php echo $csp_nonce; ?>">        
        document.addEventListener('DOMContentLoaded', function() {
            // 1. 當色彩挑選器改變時，更新左邊的文字框
            document.querySelectorAll('.color-picker').forEach(function(picker) {
                picker.addEventListener('input', function() {
                    // 找到同一個 input-group 裡面的文字框
                    const textInput = this.closest('.input-group').querySelector('.color-input');
                    if (textInput) {
                        textInput.value = this.value.toUpperCase();
                    }
                });
            });

            // 2. 當文字框手動輸入時，更新右邊的色彩挑選器
            document.querySelectorAll('.color-input').forEach(function(input) {
                input.addEventListener('change', function() {
                    // 找到同一個 input-group 裡面的挑選器
                    const picker = this.closest('.input-group').querySelector('.color-picker');
                    const color = this.value;
                    // 檢查是否為有效的 Hex 格式才更新，避免報錯
                    if (picker && /^#[0-9A-F]{6}$/i.test(color)) {
                        picker.value = color;
                    }
                });
            });
        });          
    </script>
@endsection