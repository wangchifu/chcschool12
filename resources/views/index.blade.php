@extends('layouts.master')

@section('nav_home_active', 'active')

@if($setup->title_image_style==2)
    @section('in_head')
        <style>
            /* 使用 BS5 的命名規範：start/end 代替 left/right */
            .carousel-fade .carousel-inner .carousel-item {
                opacity: 0;
                transition-property: opacity;
                transition-duration: 1s; /* 你可以自定義時間 */
                transition-timing-function: ease;
            }

            .carousel-fade .carousel-inner .active {
                opacity: 1;
            }

            /* 修正 BS5 的位置變換類別 */
            .carousel-fade .carousel-inner .carousel-item-next,
            .carousel-fade .carousel-inner .carousel-item-prev,
            .carousel-fade .carousel-inner .carousel-item.active,
            .carousel-fade .carousel-inner .active.carousel-item-start, /* 改這裡 */
            .carousel-fade .carousel-inner .active.carousel-item-end {   /* 改這裡 */
                transform: translateX(0);
            }
        </style>        
    @endsection
@endif

@section('top_image')
    @if($setup->title_image)
        @if(!empty($photo_data))
            <?php $carousel_fade =($setup->title_image_style ==2 )?"carousel-fade":""; ?>
            <div id="carouselExampleIndicators" class="carousel slide {{ $carousel_fade }}" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php $n=0; ?>
                    @foreach($photo_data as $k1=>$v1)
                        @foreach($v1 as $k2=>$v2)
                            <?php $active = ($n==0)?"active":""; ?>
                            <?php $aria_current = ($n==0)?"true":"false"; ?>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $n }}" class="{{ $active }}" aria-current="{{ $aria_current }}" aria-label="Slide {{ $n + 1 }}"></button>
                            <?php $n++; ?>
                        @endforeach
                    @endforeach
                </div>

                <div class="carousel-inner">
                    <?php $n=0; ?>
                    @foreach($photo_data as $k1=>$v1)
                        @foreach($v1 as $k2=>$v2)
                            <?php $active = ($n==0)?"active":""; ?>
                            <div class="carousel-item {{ $active }}">
                                @if($v2['link'] != null)
                                    <a href="{{ $v2['link'] }}" target="_blank">
                                        <img class="d-block w-100" src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" alt="有連結的橫幅{{ $k1 }}">
                                    </a>
                                @else
                                    <img class="d-block w-100" src="{{ asset('storage/'.$school_code.'/title_image/random/'.$k2) }}" alt="橫幅{{ $k1 }}">
                                @endif
                                
                                <div class="carousel-caption d-none d-md-block">
                                    @if($v2['title'] != null)
                                        <h1 class="display-4">{{ $v2['title'] }}</h1>
                                    @endif
                                    @if($v2['desc'] != null)
                                        <p class="fs-5"><strong>{{ $v2['desc'] }}</strong></p>
                                    @endif
                                </div>
                            </div>
                            <?php $n++; ?>
                        @endforeach
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span> </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
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
                    <div class="alert alert-{{ $school_marquee_color }}" style="margin-top: -15px;">
                        <marquee behavior="{{ $school_marquee_behavior }}" direction="{{ $school_marquee_direction }}" scrollamount="{{ $school_marquee_scrollamount }}" height="20px">
                            @if($school_marquee_direction=="up" or $school_marquee_direction=="down")
                                @foreach($school_marquees as $school_marquee)
                                    <p>{{ $school_marquee->title }}</p>                                                
                                @endforeach
                            @endif
                            @if($school_marquee_direction=="left" or $school_marquee_direction=="right")
                                @foreach($school_marquees as $school_marquee)
                                    <span>{{ $school_marquee->title }}</span>
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
                                    $title=str_replace('(系統區塊)','',$title); 
                                    $title = str_replace("_圖文版","",$title);
                                    
                                    // BS5 改動: text-left 改為 text-start
                                    $block_position = ($block->block_position==null)?"text-start":$block->block_position;
                                    if($block->block_position=="text-left") $block_position = "text-start";
                                    if($block->block_position=="text-right") $block_position = "text-end";
                                    
                                    if($block->block_position=="disable") $block_position = null;
                                ?>
                                <h5 class="{{ $block_position }} py-2 px-3 mb-0">
                                    @if($block_position) 
                                        {{ $title }}
                                    @endif
                                    
                                    @auth
                                        @if(auth()->user()->admin==1)
                                            <div class="float-end pe-2">
                                                <a href="javascript:open_window('{{ route('setups.edit_block',$block->id) }}','新視窗')" class="text-decoration-none">📝</a>
                                            </div>
                                        @endif
                                    @endauth
                                </h5>
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
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=800');
        }
    </script>
@endsection

@section('footer')
    @if(!empty($setup->footer))
        <style>
            #footer{background-color:#f8f9fa;}
            #footer_bottom{background-color: #CCCCCC;}
        </style>
        <footer class="font-small py-4" id="footer">
            <div class="container-fluid text-center text-md-start">
                <div class="row justify-content-center">
                    <div class="col-md-11">                            
                        @auth
                            @if(auth()->user()->admin==1)  
                                <div class="float-end">
                                    <a href="javascript:open_window('{{ route('setups.edit_footer') }}','新視窗')" class="text-decoration-none">📝</a>
                                </div>
                            @endif
                        @endauth
                        
                        <div class="footer-content">
                            {!! $setup->footer !!}
                        </div>                            
                    </div>
                </div>
            </div>
        </footer>        
    @endif
    @if($setup->disable_right==null)
        <div class="footer-copyright text-center py-4 bg-body-secondary border-top" id="footer_bottom">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 text-secondary">
                    
                    <div class="fw-medium">
                        &copy; {{ date('Y') }} 
                        <a href="{{ route('index','index') }}" class="text-dark text-decoration-none hover-link">
                            {{ $setup->site_name }}
                        </a> 
                        <span class="mx-1">All Rights Reserved.</span>
                    </div>

                    <span class="d-none d-md-inline opacity-25">|</span>

                    <div class="d-flex align-items-center bg-white px-3 py-1 rounded-pill shadow-sm">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        <span class="small">訪客人次：</span>
                        <span class="fw-bold text-dark">{{ number_format($setup->views) }}</span>
                    </div>

                    <div class="d-flex align-items-center bg-white px-3 py-1 rounded-pill shadow-sm">
                        <i class="fas fa-network-wired text-info me-2"></i>
                        <span class="small">您的 IP：</span>
                        <span class="fw-bold text-dark">{{ GetIP() }}</span>
                    </div>

                </div>
            </div>
        </div>

        <style>
            /* 增加一點點滑過連結的效果 */
            .hover-link {
                transition: color 0.2s;
            }
            .hover-link:hover {
                color: #dd0f20 !important; /* 或是你學校的主題紅 */
                text-decoration: underline !important;
            }
        </style>                
    @endif

    <!-- 
    警告
    -->
    <?php $admin = \App\Models\User::where('username','admin')->first(); ?>
    @auth
        @if(auth()->user()->admin==1)
            @if(Hash::check('demo1234', $admin->password))
            <script>
                $(document).ready(function(){
                  $("#myModal").modal('show');
                });
              </script>
            <div class="modal" tabindex="-1" id="myModal">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title text-danger">嚴重資安危險!</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        請你立即變更本機帳號 admin 的密碼，不得使用預設密碼。若未變更而發生資安事件，貴校須負相關責任！
                        <br>步驟為：
                        <br>1.本機登入 admin 帳號
                        <br>2.右上角 <i class="fas fa-user"></i> 符號按一下，選擇「更改密碼」
                        <br>3.輸入舊密碼，與兩次新密碼，「送出」完成變更。
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">我知道了</button>
                    </div>
                  </div>
                </div>
            </div>
            @endif
        @endif
    @endauth    
@endsection
