<?php $module_setup = get_module_setup(); ?>
<style nonce="<?php echo $csp_nonce; ?>">
    .navbar-custom {
        background: linear-gradient(135deg, {{ $navbar_custom[0] }} 0%, #1e293b 100%) !important;
    }
    
    .navbar-custom .navbar-brand,
    .navbar-custom .navbar-text {
        color: {{ $navbar_custom[1] }};
    }
    
    .navbar-custom .navbar-nav .nav-link {
        color: {{ $navbar_custom[2] }};
    }
    
    .navbar-custom .navbar-nav .nav-link.active, 
    .navbar-custom .navbar-nav .nav-link:hover,
    .navbar-custom .navbar-nav .nav-link:focus {
        color: {{ $navbar_custom[3] }};
    }

    .custom-toggler.navbar-toggler {
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        padding: 0.4rem 0.65rem;
        border-radius: 0.5rem;
    }

    .custom-toggler .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,0.9)' stroke-width='2.5' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
    }

    /* 多層選單無障礙 CSS：支援 Hover 與 Focus 自動顯示 */
    .dropdown-submenu {
        position: relative;
    }
    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
        display: none;
    }
    .dropdown-submenu:hover > .dropdown-menu,
    .dropdown-submenu:focus-within > .dropdown-menu {
        display: block;
    }

    @media (max-width: 991.98px) {
        .navbar-collapse {
            max-height: calc(100vh - 65px); 
            overflow-y: auto; 
            -webkit-overflow-scrolling: touch;
            padding: 1rem 0;
        }
        .dropdown-submenu .dropdown-menu {
            left: 0;
        }
    }
</style>
<?php
    $fixed_top = ($setup->fixed_nav)?"sticky-top ":null;
?>

<nav class="navbar navbar-expand-lg {{ $nav_color }} {{ $fixed_top }}" id="mainNav" aria-label="主要導覽區">
    <div class="container-fluid px-3 px-lg-4">
        <!-- 首頁連結：合併圖文 -->
        <a href="{{ route('index') }}" class="d-flex align-items-center me-2 text-decoration-none navbar-brand js-scroll-trigger fs-5" aria-label="{{ $setup->site_name }} 首頁">
            @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="32" height="32" class="d-inline-block align-top me-2" alt="">
            @else
                <img src="{{ asset('images/site_logo.png') }}" width="32" height="32" class="d-inline-block align-top me-2" alt="">
            @endif
            <span style="white-space:pre-wrap;">{{ $setup->site_name }}</span>
        </a>
        
        @auth
            <div class="session-timer text-light text-center px-3 py-1 rounded-pill bg-dark bg-opacity-75 ms-2 me-auto fs-7 border border-light border-opacity-25" aria-live="polite">
                剩餘時間: -- 後登出
            </div>
        @endauth
        
        <!-- 手機版選單切換按鈕 -->
        <button class="navbar-toggler custom-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="切換導覽選單">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto fw-semibold">
                <li class="nav-item @yield('nav_home_active')">
                    <?php $homepage_name = ($setup->homepage_name)?$setup->homepage_name:"首頁"; ?>
                    <a class="nav-link px-3" href="{{ route('index') }}">{{ $homepage_name }}</a>
                </li>
                @if(isset($module_setup['公告系統']))
                    <li class="nav-item @yield('nav_post_active')">
                        <?php $post_name = ($setup->post_name)?$setup->post_name:"公告系統"; ?>
                        <a class="nav-link px-3" href="{{ route('posts.index') }}">{{ $post_name }}</a>
                    </li>
                @endif
                @if(isset($module_setup['檔案庫']))
                    <li class="nav-item @yield('nav_open_files_active')">
                        <?php $openfile_name = ($setup->openfile_name)?$setup->openfile_name:"檔案庫"; ?>
                        <a class="nav-link px-3" href="{{ route('open_files.index') }}">{{ $openfile_name }}</a>
                    </li>
                @endif
                @if(isset($module_setup['學校介紹']))
                    <li class="nav-item dropdown @yield('nav_departments_active')">
                        <?php $department_name = ($setup->department_name)?$setup->department_name:"學校介紹"; ?>
                        <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDropdownDepartments" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $department_name }}
                        </a>
                        <?php $departments = \App\Models\Department::orderBy('order_by')->get(); ?>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownDepartments">
                            @foreach($departments as $department)
                                <li>
                                    <a class="dropdown-item" href="{{ route('departments.show',$department->id) }}">
                                        <i class="fas fa-puzzle-piece me-2 text-primary" aria-hidden="true"></i> {{ $department->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if(isset($module_setup['選單連結']))
                    <?php 
                        $types = \App\Models\Type::where('type_id',null)->orderBy('order_by')->get();
                        $type2s = \App\Models\Type::where('type_id','<>',null)->orderBy('order_by')->get();
                        $links = \App\Models\Link::orderBy('order_by')->get();
                        $type2_data = [];
                        foreach($type2s as $type2){
                            $type2_data[$type2->type_id][$type2->id]['name'] = $type2->name;
                        }
                        
                        $link_data = [];
                        foreach($links as $link){
                            $link_data[$link->type_id][$link->id]['target'] = $link->target;
                            $link_data[$link->type_id][$link->id]['url'] = $link->url;
                            $link_data[$link->type_id][$link->id]['icon'] = $link->icon;
                            $link_data[$link->type_id][$link->id]['name'] = $link->name;
                        }
                    ?>
                    @foreach($types as $type)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDropdown{{ $type->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $type->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown{{ $type->id }}">
                                @if(isset($type2_data[$type->id]))
                                    @foreach($type2_data[$type->id] as $k=>$v)
                                    <li class="dropdown-submenu">
                                        <button type="button" class="dropdown-item dropdown-toggle border-0 bg-transparent text-start w-100" aria-expanded="false">
                                            <i class="fas fa-folder me-2 text-warning" aria-hidden="true"></i> {{ $v['name'] }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(isset($link_data[$k]))
                                                @foreach($link_data[$k] as $k2=>$v2)
                                                <?php $target = ($v2['target'] == null) ? "_blank" : "_self"; ?>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ $v2['url'] }}" target="{{ $target }}" @if($target == '_blank') rel="noopener noreferrer" @endif>
                                                            <i class="{{ $v2['icon'] ?? 'fas fa-globe' }} me-2 text-secondary" aria-hidden="true"></i>
                                                            {{ $v2['name'] }}
                                                            @if($v2['target'] == null)
                                                                <i class="fas fa-external-link-alt ms-1 fs-7" aria-hidden="true"></i>
                                                                <span class="visually-hidden">（開新分頁）</span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                    @endforeach
                                @endif
                                @if(isset($link_data[$type->id]))
                                    @foreach($link_data[$type->id] as $k=>$v)
                                        <?php $target = ($v['target'] == null) ? "_blank" : "_self"; ?>
                                        <li>
                                            <a class="dropdown-item" href="{{ $v['url'] }}" target="{{ $target }}" @if($target == '_blank') rel="noopener noreferrer" @endif>
                                                <i class="{{ $v['icon'] ?? 'fas fa-globe' }} me-2 text-secondary" aria-hidden="true"></i>
                                                {{ $v['name'] }}
                                                @if($v['target'] == null)
                                                    <i class="fas fa-external-link-alt ms-1 fs-7" aria-hidden="true"></i>
                                                    <span class="visually-hidden">（開新分頁）</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </li>
                    @endforeach
                @endif
            </ul>

            <ul class="navbar-nav fw-semibold ms-lg-2">                
                @auth
                    @if(isset($module_setup['校務行政']))
                        <li class="nav-item dropdown @yield('nav_school_active')">
                            <?php $schoolexec_name = ($setup->schoolexec_name)?$setup->schoolexec_name:"校務行政"; ?>
                            <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDropdownSchool" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $schoolexec_name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownSchool">
                                @if(isset($module_setup['校務行事曆']))
                                    <li><a class="dropdown-item" href="{{ route('calendars.index') }}"><i class="fas fa-calendar me-2 text-primary" aria-hidden="true"></i> 校務行事曆</a></li>
                                @endif
                                @if(isset($module_setup['校務月曆']))
                                    <li><a class="dropdown-item" href="{{ route('monthly_calendars.index') }}"><i class="fas fa-calendar-alt me-2 text-primary" aria-hidden="true"></i> 校務月曆</a></li>
                                @endif
                                @if(isset($module_setup['內部文件']))
                                    <li><a class="dropdown-item" href="{{ route('inside_files.index') }}"><i class="fab fa-linkedin-in me-2 text-info" aria-hidden="true"></i> 內部文件</a></li>
                                @endif
                                @if(isset($module_setup['會議文稿']))
                                    <li><a class="dropdown-item" href="{{ route('meetings.index') }}"><i class="fas fa-comments me-2 text-success" aria-hidden="true"></i> 會議文稿</a></li>
                                @endif
                                @if(isset($module_setup['報修系統']))
                                    <li><a class="dropdown-item" href="{{ route('fixes.index') }}"><i class="fas fa-wrench me-2 text-warning" aria-hidden="true"></i> 報修系統</a></li>
                                @endif
                                @if(isset($module_setup['教室預約']))
                                    <li><a class="dropdown-item" href="{{ route('classroom_orders.index') }}"><i class="fas fa-chess-rook me-2 text-danger" aria-hidden="true"></i> 教室預約</a></li>
                                @endif
                                @if(isset($module_setup['午餐系統']))
                                    <li><a class="dropdown-item" href="{{ route('lunches.index') }}"><i class="fas fa-utensils me-2 text-warning" aria-hidden="true"></i> 午餐系統</a></li>
                                @endif
                                @if(isset($module_setup['社團報名']))
                                    <li><a class="dropdown-item" href="{{ route('clubs.index') }}"><i class="fas fa-table-tennis me-2 text-info" aria-hidden="true"></i> 社團報名</a></li>
                                @endif
                                @if(isset($module_setup['校園部落格']))
                                    <li><a class="dropdown-item" href="{{ route('blogs.index') }}"><i class="fas fa-newspaper me-2 text-primary" aria-hidden="true"></i> 校園部落格</a></li>
                                @endif
                                @if(isset($module_setup['行政待辦']))
                                    <li><a class="dropdown-item" href="{{ route('tasks.index') }}"><i class="fas fa-tasks me-2 text-success" aria-hidden="true"></i> 行政待辦</a></li>
                                @endif
                                @if(isset($module_setup['借用系統']))
                                    <li><a class="dropdown-item" href="{{ route('lends.index') }}"><i class="fas fa-archive me-2 text-secondary" aria-hidden="true"></i> 借用系統</a></li>
                                @endif
                                @if(isset($module_setup['校園跑馬燈']))
                                    <li><a class="dropdown-item" href="{{ route('school_marquee.index') }}"><i class="fas fa-running me-2 text-danger" aria-hidden="true"></i> 校園跑馬燈</a></li>
                                @endif
                                @if(isset($module_setup['運動會報名']))
                                    <li><a class="dropdown-item" href="{{ route('sport_meeting.index') }}"><i class="fas fa-volleyball-ball me-2 text-warning" aria-hidden="true"></i> 運動會報名</a></li>
                                @endif        
                                @if(isset($module_setup['填報學生']))
                                    <li><a class="dropdown-item" href="{{ route('report_students.index') }}"><i class="fas fa-child me-2 text-info" aria-hidden="true"></i> 填報學生</a></li>
                                @endif                      
                                @if(isset($module_setup['學生帳號']))
                                    <li><a class="dropdown-item" href="{{ route('student_account.index') }}"><i class="far fa-user-circle me-2 text-primary" aria-hidden="true"></i> 學生帳號</a></li>
                                @endif                                    
                                <li><a class="dropdown-item" href="{{ route('photo_links.index') }}"><i class="fas fa-image me-2 text-success" aria-hidden="true"></i> 圖片連結</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(auth()->user()->admin)
                        <li class="nav-item dropdown @yield('nav_setup_active')">
                            <?php $setup_name = ($setup->setup_name)?$setup->setup_name:"系統設定"; ?>
                            <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDropdownSetup" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $setup_name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownSetup">
                                <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-user me-2 text-primary" aria-hidden="true"></i> 帳號管理</a></li>
                                <li><a class="dropdown-item" href="{{ route('groups.index') }}"><i class="fas fa-users me-2 text-info" aria-hidden="true"></i> 群組管理</a></li>
                                <li><a class="dropdown-item" href="{{ route('departments.index') }}"><i class="fas fa-puzzle-piece me-2 text-warning" aria-hidden="true"></i> 學校介紹管理</a></li>
                                <li><a class="dropdown-item" href="{{ route('school_marquee.setup') }}"><i class="fas fa-running me-2 text-danger" aria-hidden="true"></i> 校園跑馬燈管理</a></li>
                                <li><a class="dropdown-item" href="{{ route('contents.index') }}"><i class="fas fa-file-alt me-2 text-secondary" aria-hidden="true"></i> 內容管理</a></li>
                                <li><a class="dropdown-item" href="{{ route('links.index') }}"><i class="fas fa-link me-2 text-success" aria-hidden="true"></i> 選單連結</a></li>
                                <li><a class="dropdown-item" href="{{ route('photo_links.index') }}"><i class="fas fa-image me-2 text-info" aria-hidden="true"></i> 圖片連結</a></li>
                                <li><a class="dropdown-item" href="{{ route('trees.index') }}"><i class="fas fa-tree me-2 text-success" aria-hidden="true"></i> 樹狀目錄</a></li>
                                <li><a class="dropdown-item" href="{{ route('rss_feeds.index') }}"><i class="fas fa-rss me-2 text-warning" aria-hidden="true"></i> RSS 訊息</a></li>
                                <li><a class="dropdown-item" href="{{ route('setups.index') }}"><i class="fas fa-desktop me-2 text-dark" aria-hidden="true"></i> 網站設定</a></li>
                            </ul>
                        </li>
                    @endif

                    <li class="nav-item dropdown @yield('nav_user_active')">
                        <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="使用者選單">
                            <i class="fas fa-user-circle fs-5" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                            <li><span class="dropdown-item-text fw-bold text-primary"><i class="fas fa-user-tag me-2" aria-hidden="true"></i> {{ auth()->user()->title }} {{ auth()->user()->name }}</span></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @if(auth()->user()->login_type=="openID")
                                <li><a class="dropdown-item" href="{{ route('edit_title') }}"><i class="fas fa-pen me-2 text-secondary" aria-hidden="true"></i> 更改職稱</a></li>
                            @endif
                            @if(auth()->user()->login_type=="local")
                                <li><a class="dropdown-item" href="{{ route('edit_password') }}"><i class="fas fa-key me-2 text-warning" aria-hidden="true"></i> 更改密碼</a></li>
                            @endif
                            @if(auth()->user()->admin)
                                <li><a class="dropdown-item" href="{{ route('teach_system') }}"><i class="fas fa-graduation-cap me-2 text-info" aria-hidden="true"></i> 系統教學</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('wrench.index') }}"><i class="fas fa-wrench me-2 text-danger" aria-hidden="true"></i> 系統報錯與建議</a></li>
                            @impersonating
                                <li><a class="dropdown-item" href="{{ route('sims.impersonate_leave') }}" onclick="return confirm('確定返回原本帳號？')"><i class="fas fa-user-ninja me-2 text-dark" aria-hidden="true"></i> 結束模擬</a></li>
                            @endImpersonating
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <button type="button" class="dropdown-item text-danger fw-bold delete-btn2 border-0 bg-transparent w-100 text-start" data-form="logout-form" data-msg="確定要登出嗎？">
                                    <i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i> 登出
                                </button>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endauth
                @guest
                    <?php $login_name = ($setup->login_name)?$setup->login_name:"登入"; ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 btn btn-outline-light rounded-pill btn-sm my-1 ms-lg-2" href="{{ route('logins') }}">{{ $login_name }}</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<script nonce="<?php echo $csp_nonce; ?>">
    document.addEventListener('DOMContentLoaded', function () {
        // 多層選單 (Submenu) 鍵盤 Focus 時同步 aria-expanded 屬性
        const submenus = document.querySelectorAll('.dropdown-submenu');
        submenus.forEach(function (submenu) {
            const toggleBtn = submenu.querySelector('.dropdown-toggle');
            if (toggleBtn) {
                submenu.addEventListener('focusin', function () {
                    toggleBtn.setAttribute('aria-expanded', 'true');
                });
                submenu.addEventListener('focusout', function () {
                    toggleBtn.setAttribute('aria-expanded', 'false');
                });
            }
        });
    });
</script>