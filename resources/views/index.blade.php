@extends('layouts.master')

@section('nav_home_active', 'active')

@if($setup->title_image_style==2)
    @section('in_head')
        <style nonce="<?php echo $csp_nonce; ?>">
            /* 使用 BS5 的命名規範：start/end 代替 left/right */
            .carousel-fade .carousel-inner .carousel-item {
                opacity: 0;
                transition-property: opacity;
                transition-duration: 1s;
                transition-timing-function: ease;
            }

            .carousel-fade .carousel-inner .active {
                opacity: 1;
            }

            .carousel-fade .carousel-inner .carousel-item-next,
            .carousel-fade .carousel-inner .carousel-item-prev,
            .carousel-fade .carousel-inner .carousel-item.active,
            .carousel-fade .carousel-inner .active.carousel-item-start,
            .carousel-fade .carousel-inner .active.carousel-item-end {
                transform: translateX(0);
            }
        </style>        
    @endsection
@endif

@section('top_image')
    @if($setup->title_image)
        @if(!empty($photo_data))
            <?php $carousel_fade = ($setup->title_image_style == 2) ? "carousel-fade" : ""; ?>
            <div id="carouselExampleIndicators" class="carousel slide {{ $carousel_fade }}" data-bs-ride="carousel" aria-label="首頁焦點輪播圖">
                <div class="carousel-indicators">
                    <?php $n=0; ?>
                    @foreach($photo_data as $k1=>$v1)
                        @foreach($v1 as $k2=>$v2)
                            <?php 
                                $active = ($n==0) ? "active" : ""; 
                                $aria_current = ($n==0) ? "true" : "false"; 
                            ?>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $n }}" class="{{ $active }}" aria-current="{{ $aria_current }}" aria-label="第 {{ $n + 1 }} 張輪播圖片"></button>
                            <?php $n++; ?>
                        @endforeach
                    @endforeach
                </div>

                <div class="carousel-inner">
                    <?php $n=0; ?>
                    @foreach($photo_data as $k1=>$v1)
                        @foreach($v1 as $k2=>$v2)
                            <?php 
                                $active = ($n==0) ? "active" : ""; 
                                $img_alt = !empty($v2['title']) ? $v2['title'] : ($v2['desc'] ? $v2['desc'] : '首頁輪播橫幅圖 ' . ($n + 1));
                            ?>
                            <div class="carousel-item {{ $active }}">
                                @if($v2['link'] != null)
                                    <a href="{{ $v2['link'] }}" target="_blank" aria-label="前往輪播圖連結：{{ $img_alt }}">
                                        <img class="d-block w-100" src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" alt="{{ $img_alt }}">
                                    </a>
                                @else
                                    <img class="d-block w-100" src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" alt="{{ $img_alt }}">
                                @endif
                                
                                <div class="carousel-caption d-none d-md-block">
                                    @if($v2['title'] != null)
                                        <h2 class="display-6 fw-bold text-shadow">{{ $v2['title'] }}</h2>
                                    @endif
                                    @if($v2['desc'] != null)
                                        <p class="fs-5 fw-semibold text-shadow">{{ $v2['desc'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <?php $n++; ?>
                        @endforeach
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev" aria-label="上一張輪播圖">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">上一張</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next" aria-label="下一張輪播圖">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">下一張</span>
                </button>
            </div>
        @endif
    @endif
@endsection

@section('content')
    <link href="{{ asset('css/block_style.css') }}" rel="stylesheet">
    <?php $module_setup = get_module_setup(); ?>
    @if(isset($module_setup['校園跑馬燈']))
        <?php
            $school_marquee_width = (empty($setup->school_marquee_width))?"12":$setup->school_marquee_width;
            $school_marquee_color = (empty($setup->school_marquee_color))?"warning":$setup->school_marquee_color;
            $school_marquee_behavior = (empty($setup->school_marquee_behavior))?"scroll":$setup->school_marquee_behavior;
            $school_marquee_direction = (empty($setup->school_marquee_direction))?"up":$setup->school_marquee_direction;
            $school_marquee_scrollamount = (empty($setup->school_marquee_scrollamount))?"2":$setup->school_marquee_scrollamount;
        ?>
        @if($school_marquees->count()>0)
            <div class="row justify-content-center">
                <div class="col-lg-{{ $school_marquee_width }}">
                    <div class="alert alert-{{ $school_marquee_color }}" style="margin-top: -15px;" role="region" aria-label="校園跑馬燈公告">
                        <!-- 無障礙 2.2.2：提供 Focus 與 Hover 暫停播放機制 -->
                        <marquee behavior="{{ $school_marquee_behavior }}" direction="{{ $school_marquee_direction }}" scrollamount="{{ $school_marquee_scrollamount }}" height="20px" onmouseover="this.stop();" onmouseout="this.start();" onfocus="this.stop();" onblur="this.start();" tabindex="0" title="校園訊息跑馬燈，滑鼠移入或聚焦可暫停">
                            @if($school_marquee_direction=="up" or $school_marquee_direction=="down")
                                @foreach($school_marquees as $school_marquee)
                                    <p class="mb-0">{{ $school_marquee->title }}</p>                                                
                                @endforeach
                            @endif
                            @if($school_marquee_direction=="left" or $school_marquee_direction=="right")
                                @foreach($school_marquees as $school_marquee)
                                    <span><i class="fas fa-bullhorn me-1 text-danger" aria-hidden="true"></i> {{ $school_marquee->title }}</span>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                @endforeach
                            @endif
                        </marquee>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div class="row justify-content-center">
        @foreach($setup_cols as $setup_col)
            <div class="col-lg-{{ $setup_col->num }}">
                @foreach($blocks[$setup_col->id] as $block)
                    <?php
                        if(!is_null($block->block_color)){
                            $block_color = explode(',',$block->block_color);
                        }else{
                            $block_color[0] = "original-block";
                            $block_color[1] = "original-title";
                        }
                        $rounded = ($block->disable_block_line == 1)?"rounded":null;
                    ?>

                    @if($block->title == "榮譽榜跑馬燈")
                        <div class="table-responsive">
                            <div>                                
                                @include('layouts.marquee')
                            </div>
                        </div>
                    @else
                        @if($block->disable_block_line != 1)
                            <div class="shadow rounded {{ $block_color[0] }}">
                        @endif

                        @if($block->block_position != "disable")
                            <div class="{{ $block_color[1] }} {{ $rounded }}">
                                <?php
                                    $title = (empty($block->new_title))?$block->title:$block->new_title;
                                    $title = str_replace('(系統區塊)','',$title); 
                                    $title = str_replace("_圖文版","",$title);
                                    
                                    $block_position = ($block->block_position==null)?"text-start":$block->block_position;
                                    if($block->block_position=="text-left") $block_position = "text-start";
                                    if($block->block_position=="text-right") $block_position = "text-end";
                                    
                                    if($block->block_position=="disable") $block_position = null;
                                ?>
                                <h2 class="h5 {{ $block_position }} py-2 px-3 mb-0">
                                    @if($block_position) 
                                        {{ $title }}
                                    @endif
                                    
                                    @auth
                                        @if(auth()->user()->admin==1)
                                            <div class="float-end pe-2">
                                                <a href="{{ route('setups.edit_block',$block->id) }}" class="text-decoration-none venobox" data-vbtype="iframe" aria-label="編輯區塊：{{ $title }}">📝</a>
                                            </div>
                                        @endif
                                    @endauth
                                </h2>
                            </div>
                        @endif

                        <div class="content2 mb-2 px-3 pb-2" id="block{{ $block->id }}">
                            <div class="table-responsive">
                                @if($block->title == "最新公告(系統區塊)")
                                    @include('layouts.news')
                                @elseif($block->title == "彰化空汙旗(系統區塊)")
                                    @include('layouts.chc_air')
                                @elseif($block->title == "樹狀目錄(系統區塊)")
                                    @include('layouts.dtree')
                                @elseif($block->title == "圖片連結(系統區塊)")
                                    @include('layouts.photo_link')
                                @elseif($block->title == "分類公告(系統區塊)")
                                    @include('layouts.post_type')
                                @elseif($block->title == "分類公告_圖文版(系統區塊)")
                                    @include('layouts.post_type2')
                                @elseif($block->title == "校園部落格(系統區塊)")
                                    @include('layouts.blog')
                                @elseif($block->title == "今日餐點1(系統區塊)")
                                    @include('layouts.lunch_today1')
                                @elseif($block->title == "今日餐點2(系統區塊)")
                                    @include('layouts.lunch_today2')
                                @elseif($block->title == "今日餐點3(系統區塊)")
                                    @include('layouts.lunch_today3')
                                @elseif($block->title == "今日餐點4(系統區塊)")
                                    @include('layouts.lunch_today4')
                                @elseif($block->title == "校務月曆(系統區塊)")
                                    @include('layouts.monthly_calendar')
                                @elseif($block->title == "教室預約(系統區塊)")
                                    @include('layouts.classroom_order')
                                @elseif($block->title == "RSS訊息(系統區塊)")
                                    @include('layouts.rss_feed')                           
                                @elseif($block->title == "借用狀態(系統區塊)")
                                    @include('layouts.lend_list')
                                @elseif($block->title == "常駐公告(系統區塊)")
                                    @include('layouts.inbox_posts')
                                @elseif($block->title == "待修通報(系統區塊)")
                                    @include('layouts.fix')
                                @elseif($block->title == "搜尋本站(系統區塊)")
                                    @include('layouts.search_site')
                                @else
                                    {!! $block->content !!}
                                @endif
                            </div>
                        </div>

                        @if($block->disable_block_line != 1)
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
@endsection

@section('footer')
    <style>
        #footer { 
            background-color: #f8f9fa; 
        }
        /* CSS Breakout 強制 #footer_bottom 突破父容器展開至 100% 視窗全版寬 */
        #footer_bottom {
            background-color: #e2e8f0 !important;
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            color: #1e293b !important;
        }
    </style>

    @if(!empty($setup->footer))
        <!-- 改用 div 避免與外部 master.blade.php 的 <footer> 重複嵌套 -->
        <div class="font-small py-4" id="footer">
            <div class="container-fluid text-center text-md-start">
                <div class="row justify-content-center">
                    <div class="col-md-11">                            
                        @auth
                            @if(auth()->user()->admin==1)  
                                <div class="float-end">
                                    <a href="{{ route('setups.edit_footer') }}" class="text-decoration-none venobox" data-vbtype="iframe" aria-label="編輯自訂頁尾內容">📝</a>
                                </div>
                            @endif
                        @endauth
                        
                        <div class="footer-content">
                            {!! $setup->footer !!}
                        </div>                            
                    </div>
                </div>
            </div>
        </div>        
    @endif

    @if($setup->disable_right==null)
        <!-- 全版寬底部版權列 (高對比無障礙配色) -->
        <div class="footer-copyright text-center py-4 border-top" id="footer_bottom">
            <div class="container-fluid px-3">
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 text-dark">
                    
                    <div class="fw-semibold">
                        &copy; {{ date('Y') }} 
                        <a href="{{ route('index','index') }}" class="text-dark fw-bold text-decoration-underline">
                            {{ $setup->site_name }}
                        </a> 
                        <span class="mx-1">All Rights Reserved.</span>
                    </div>

                    <span class="d-none d-md-inline opacity-50" aria-hidden="true">|</span>

                    <div class="d-flex align-items-center bg-white px-3 py-1 rounded-pill shadow-sm border">
                        <i class="fas fa-chart-line text-primary me-2" aria-hidden="true"></i>
                        <span class="small fw-bold">訪客人次：</span>
                        <span class="fw-bold text-dark">{{ number_format($setup->views) }}</span>
                    </div>

                    <div class="d-flex align-items-center bg-white px-3 py-1 rounded-pill shadow-sm border">
                        <i class="fas fa-network-wired text-info me-2" aria-hidden="true"></i>
                        <span class="small fw-bold">您的 IP：</span>
                        <span class="fw-bold text-dark">{{ GetIP() }}</span>
                    </div>

                </div>
            </div>
        </div>             
    @endif      
@endsection