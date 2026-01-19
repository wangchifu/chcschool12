<?php
//檢查有無新版本的sql檔
$sqls = get_files(database_path('sqls'));

if (isset($_SERVER['HTTP_HOST'])) {
    $install_sqls = \App\Models\Sql::where('install', 1)->pluck('name')->toArray();

    foreach ($sqls as $k => $v) {
        if (!in_array($v, $install_sqls)) {
            $file = database_path('sqls') . '/' . $v;
            \Illuminate\Support\Facades\DB::unprepared(file_get_contents($file));
            $att['name'] = $v;
            $att['install'] = 1;
            \App\Models\Sql::create($att);
        }
    }
}

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MLoginController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CalendarWeekController;
use App\Http\Controllers\ClassroomOrderController;
use App\Http\Controllers\ClubsController;
use App\Http\Controllers\ContentsController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FixController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InsideFilesController;
use App\Http\Controllers\LendsController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\LunchController;
use App\Http\Controllers\LunchListController;
use App\Http\Controllers\LunchOrderController;
use App\Http\Controllers\LunchSetupController;
use App\Http\Controllers\LunchSpecialController;
use App\Http\Controllers\LunchStuController;
use App\Http\Controllers\LunchTodayController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MonthlyCalendarController;
use App\Http\Controllers\OpenFileController;
use App\Http\Controllers\OpenIDController;
use App\Http\Controllers\PhotoLinksController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RssFeedController;
use App\Http\Controllers\SchoolMarqueeController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\SportMeetingController;
use App\Http\Controllers\TaskController;
//use App\Http\Controllers\TeacherAbsentController;
use App\Http\Controllers\TreesController;
use App\Http\Controllers\UserPowerController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WrenchController;

Route::post('webhook', [HomeController::class, 'webhook'])->name('webhook');
Route::get('close', [SetupController::class, 'close'])->name('close');
Route::get('/', [HomeController::class, 'index'])->name('index');

// --- DNS 驗證檔案與機器人檢查 ---
Route::get('.well-known/pki-validation/DN_CHECK_FILE.htm', [HomeController::class, 'check_file'])->name('check_file');
Route::get('.well-known/pki-validation/whois.txt', [HomeController::class, 'whois'])->name('whois_check');
Route::post('not_bot', [HomeController::class, 'not_bot'])->name('not_bot');

// --- 一般使用者登入 (MLoginController) ---
Route::get('logins', [MLoginController::class, 'logins'])->name('logins');
Route::get('login', [MLoginController::class, 'showLoginForm'])->name('admin_login');
Route::get('login_close', [MLoginController::class, 'showLoginForm_close'])->name('admin_login_close');
Route::post('login', [MLoginController::class, 'auth'])->name('auth');

// --- 登出 ---
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// --- OpenID 登入 ---
Route::get('sso', [OpenIDController::class, 'sso'])->name('sso');
Route::get('auth/callback', [OpenIDController::class, 'callback'])->name('callback');

// --- 總系統管理員 (GLoginController) ---
Route::get('sys', [GLoginController::class, 'sys'])->name('sys');
Route::post('sys_auth', [GLoginController::class, 'sys_auth'])->name('sys_auth');
Route::get('sys_logout', [GLoginController::class, 'sys_logout'])->name('sys_logout');
Route::get('sys_index', [GLoginController::class, 'sys_index'])->name('sys_index');
Route::get('sys_sim/{user}', [GLoginController::class, 'sys_sim'])->name('sys_sim');

// --- 基本設定 ---
Route::get('pic', [SetupController::class, 'pic'])->name('pic');
Route::get('voice', [SetupController::class, 'voice'])->name('voice');

// --- 公告系統 ---
Route::get('posts', [PostsController::class, 'index'])->name('posts.index');
Route::get('posts/{post}', [PostsController::class, 'show'])->where('post', '[0-9]+')->name('posts.show');
Route::get('posts/{post}/show_clean', [PostsController::class, 'show_clean'])->where('post', '[0-9]+')->name('posts.show_clean');
Route::match(['post', 'get'], 'posts/search/{search?}', [PostsController::class, 'search'])->name('posts.search');
Route::get('posts/{job_title}/job_title', [PostsController::class, 'job_title'])->name('posts.job_title');
Route::get('posts/{type}/type', [PostsController::class, 'type'])->name('posts.type');
Route::get('posts/{type}/type_clean', [PostsController::class, 'type_clean'])->name('posts.type_clean');
Route::post('posts/select_type', [PostsController::class, 'select_type'])->name('posts.select_type');

Route::get('rss', [HomeController::class, 'rss'])->name('rss');

// --- 公開文件 ---
Route::get('open_files/{path?}', [OpenFileController::class, 'index'])->name('open_files.index');
Route::get('open_files_download/{path}', [OpenFileController::class, 'download'])->name('open_files.download');

// --- 圖片連結 ---
Route::get('photo_links/show/{photo_type_id?}', [PhotoLinksController::class, 'show'])->name('photo_links.show');

// --- 內容頁面 ---
Route::get('contents/{content}/show', [ContentsController::class, 'show'])->where('content', '[0-9]+')->name('contents.show');

// --- 處室 ---
Route::get('departments/{department}/show', [DepartmentController::class, 'show'])->name('departments.show');

// --- 校務行事曆 ---
Route::get('calendars/index/{semester?}', [CalendarController::class, 'index'])->name('calendars.index');
Route::get('calendars/print/{semester}', [CalendarController::class, 'print'])->name('calendars.print');

// --- 廠商頁面 ---
Route::match(['get', 'post'], 'lunch_lists/factory/{lunch_order_id?}', [LunchListController::class, 'factory'])->name('lunch_lists.factory');
Route::get('lunch_lists/change_factory/', [LunchListController::class, 'change_factory'])->name('lunch_lists.change_factory');

// --- 社團家長頁面 ---
Route::get('clubs/semester_select', [ClubsController::class, 'semester_select'])->name('clubs.semester_select');
Route::get('clubs/{semester}/{class_id}/show_clubs', [ClubsController::class, 'show_clubs'])->name('clubs.show_clubs');
Route::get('clubs/{semester}/{class_id}/parents_login', [ClubsController::class, 'parents_login'])->name('clubs.parents_login');
Route::get('clubs/{semester}/parents_login_test', [ClubsController::class, 'parents_login_test'])->name('clubs.parents_login_test');
Route::post('clubs/do_login', [ClubsController::class, 'do_login'])->name('clubs.do_login');
Route::post('clubs/do_login_test', [ClubsController::class, 'do_login_test'])->name('clubs.do_login_test');
Route::get('clubs/parents_do/{class_id}', [ClubsController::class, 'parents_do'])->name('clubs.parents_do');
Route::get('clubs/parents_logout', [ClubsController::class, 'parents_logout'])->name('clubs.parents_logout');
Route::get('clubs/{class_id}/change_pwd', [ClubsController::class, 'change_pwd'])->name('clubs.change_pwd');
Route::patch('clubs/change_pwd_do', [ClubsController::class, 'change_pwd_do'])->name('clubs.change_pwd_do');
Route::post('clubs/{club_student}/get_telephone', [ClubsController::class, 'get_telephone'])->name('clubs.get_telephone');
Route::get('clubs/{club}/show_club', [ClubsController::class, 'show_club'])->name('clubs.show_club');
Route::get('clubs/{club}/sign_up', [ClubsController::class, 'sign_up'])->name('clubs.sign_up');
Route::get('clubs/{club_id}/sign_down', [ClubsController::class, 'sign_down'])->name('clubs.sign_down');
Route::get('clubs/{club}/{class_id}/sign_show', [ClubsController::class, 'sign_show'])->name('clubs.sign_show');

// --- 校園部落格 ---
Route::get('blogs', [BlogsController::class, 'index'])->name('blogs.index');
Route::get('blogs/{blog}', [BlogsController::class, 'show'])->where('blog', '[0-9]+')->name('blogs.show');

// --- 行政待辦 ---
Route::get('tasks/index', [TaskController::class, 'index'])->name('tasks.index');
Route::get('tasks/index2', [TaskController::class, 'index2'])->name('tasks.index2');
Route::get('tasks/index3', [TaskController::class, 'index3'])->name('tasks.index3');
Route::get('tasks/self', [TaskController::class, 'self'])->name('tasks.self');
Route::get('tasks/logout', [TaskController::class, 'logout'])->name('tasks.logout');
Route::get('tasks/{task}/disable', [TaskController::class, 'disable'])->name('tasks.disable');
Route::post('tasks/store', [TaskController::class, 'store'])->name('tasks.store');
Route::post('tasks/self_store', [TaskController::class, 'self_store'])->name('tasks.self_store');
Route::post('tasks/user_condition', [TaskController::class, 'user_condition'])->name('tasks.user_condition');

// --- 借用系統 ---
Route::get('lends/clean/{lend_class_id?}/{this_date?}', [LendsController::class, 'index'])->name('lends.clean');
Route::get('lends/list_clean', [LendsController::class, 'list_clean'])->name('lends.list_clean');
Route::get('lends/check_order_month/{this_date}', [LendsController::class, 'check_order_month'])->name('lends.check_order_month');
Route::get('lends/check_order_out_clean/{this_date}/{action}', [LendsController::class, 'check_order_out_clean'])->name('lends.check_order_out_clean');
Route::post('lends/download_excel', [LendsController::class, 'download_excel'])->name('lends.download_excel');
Route::post('lends/print_lend', [LendsController::class, 'print_lend'])->name('lends.print_lend');

// --- 報修系統 ---
Route::get('fixes/{fix}/show_clean', [FixController::class, 'show_clean'])->where('fix', '[0-9]+')->name('fixes.show_clean');

// 登入的使用者可用
Route::group(['middleware' => 'auth'], function () {

    Route::get('edit_title', [HomeController::class, 'edit_title'])->name('edit_title');
    Route::patch('update_title/{user}', [HomeController::class, 'update_title'])->name('update_title');
    
    // 共同編輯
    Route::get('contents_together/{content}/edit', [ContentsController::class, 'together_edit'])->name('contents.together_edit');
    Route::patch('contents_together/{content}', [ContentsController::class, 'together_update'])->name('contents.together_update');

    // 共同編輯學校介紹
    Route::get('departments_together/{department}/edit', [DepartmentController::class, 'together_edit'])->name('departments.together_edit');
    Route::patch('departments_together/{department}', [DepartmentController::class, 'together_update'])->name('departments.together_update');

    Route::get('posts/index_my', [PostsController::class, 'index_my'])->name('posts.index_my');
    
    // 結束模擬
    Route::get('sims/impersonate_leave', [SimulationController::class, 'impersonate_leave'])->name('sims.impersonate_leave');

    // 檔案操作
    Route::get('file/{file}', [HomeController::class, 'getFile'])->name('getFile');
    Route::get('file_open/{file}', [HomeController::class, 'openFile'])->name('openFile');
    Route::get('img/{path}', [HomeController::class, 'getImg'])->name('getImg');

    // 會議文稿
    Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->where('meeting', '[0-9]+')->name('meetings.show');
    Route::get('meetings/{meeting}/download', [MeetingController::class, 'txtDown'])->name('meetings.txtDown');

    // 報修系統
    Route::get('fixes', [FixController::class, 'index'])->name('fixes.index');
    Route::get('fixes_search/{situation}/type', [FixController::class, 'search'])->name('fixes.search');
    Route::get('fixes/{fix}', [FixController::class, 'show'])->where('fix', '[0-9]+')->name('fixes.show');
    Route::get('fixes/create', [FixController::class, 'create'])->name('fixes.create');
    Route::post('fixes', [FixController::class, 'store'])->name('fixes.store');
    Route::post('fixes/store_notify', [FixController::class, 'store_notify'])->name('fixes.store_notify');
    Route::match(['delete', 'get'], 'fixes/{fix}/delete', [FixController::class, 'destroy'])->name('fixes.destroy');
    Route::match(['delete', 'get'], 'fixes/{fix}/delete_clean', [FixController::class, 'destroy_clean'])->name('fixes.destroy_clean');

    // 教室預約
    Route::get('classroom_orders/index', [ClassroomOrderController::class, 'index'])->name('classroom_orders.index');
    Route::get('classroom_orders/{classroom}/show/{select_sunday?}', [ClassroomOrderController::class, 'show'])->name('classroom_orders.show');
    Route::get('classroom_orders/{classroom_id}/{section}/{order_date}/select', [ClassroomOrderController::class, 'select'])->name('classroom_orders.select');
    Route::delete('classroom_orders/destroy', [ClassroomOrderController::class, 'destroy'])->name('classroom_orders.destroy');
    Route::get('classroom_orders/admin', [ClassroomOrderController::class, 'admin'])->name('classroom_orders.admin');
    Route::get('classroom_orders/admin_create', [ClassroomOrderController::class, 'admin_create'])->name('classroom_orders.admin_create');
    Route::get('classroom_orders/{classroom}/admin_edit', [ClassroomOrderController::class, 'admin_edit'])->name('classroom_orders.admin_edit');
    Route::patch('classroom_orders/{classroom}/admin_update', [ClassroomOrderController::class, 'admin_update'])->name('classroom_orders.admin_update');
    Route::post('classroom_orders/admin_store', [ClassroomOrderController::class, 'admin_store'])->name('classroom_orders.admin_store');
    Route::get('classroom_orders/{classroom}/admin_destroy', [ClassroomOrderController::class, 'admin_destroy'])->name('classroom_orders.admin_destroy');

    // 午餐系統
    Route::get('lunches/{lunch_order_id?}', [LunchController::class, 'index'])->name('lunches.index');
    Route::post('lunches', [LunchController::class, 'store'])->name('lunches.store');
    Route::patch('lunches', [LunchController::class, 'update'])->name('lunches.update');

    // 午餐設定 (LunchSetup)
    Route::get('lunch_setup', [LunchSetupController::class, 'index'])->name('lunch_setups.index');
    Route::get('lunch_setup/create', [LunchSetupController::class, 'create'])->name('lunch_setups.create');
    Route::post('lunch_setup/store', [LunchSetupController::class, 'store'])->name('lunch_setups.store');
    Route::get('lunch_setup/{lunch_setup}/edit', [LunchSetupController::class, 'edit'])->name('lunch_setups.edit');
    Route::patch('lunch_setup/{lunch_setup}/update', [LunchSetupController::class, 'update'])->name('lunch_setups.update');
    Route::delete('lunch_setup/{lunch_setup}/destroy', [LunchSetupController::class, 'destroy'])->name('lunch_setups.destroy');
    Route::get('lunch_setup/{path}/{id}/del_file', [LunchSetupController::class, 'del_file'])->name('lunch_setups.del_file');
    Route::post('lunch_setup/place_add', [LunchSetupController::class, 'place_add'])->name('lunch_setups.place_add');
    Route::patch('lunch_setup/{lunch_place}/place_update', [LunchSetupController::class, 'place_update'])->name('lunch_setups.place_update');
    Route::post('lunch_setup/factory_add', [LunchSetupController::class, 'factory_add'])->name('lunch_setups.factory_add');
    Route::patch('lunch_setup/{lunch_factory}/factory_update', [LunchSetupController::class, 'factory_update'])->name('lunch_setups.factory_update');
    Route::post('lunch_setup/stu_store', [LunchSetupController::class, 'stu_store'])->name('lunch_setups.stu_store');
    Route::get('lunch_setup/{semester}/stu_more/{student_class_id?}', [LunchSetupController::class, 'stu_more'])->name('lunch_setups.stu_more');

    // 午餐訂單與清單 (LunchOrder / LunchList)
    Route::get('lunch_orders/index', [LunchOrderController::class, 'index'])->name('lunch_orders.index');
    Route::get('lunch_orders/{semester}/create', [LunchOrderController::class, 'create'])->name('lunch_orders.create');
    Route::post('lunch_orders/store', [LunchOrderController::class, 'store'])->name('lunch_orders.store');
    Route::get('lunch_orders/{semester}/edit', [LunchOrderController::class, 'edit'])->name('lunch_orders.edit');
    Route::get('lunch_orders/{lunch_order}/edit_order', [LunchOrderController::class, 'edit_order'])->name('lunch_orders.edit_order');
    Route::patch('lunch_orders/{lunch_order}/order_save', [LunchOrderController::class, 'order_save'])->name('lunch_orders.order_save');
    Route::patch('lunch_orders/update', [LunchOrderController::class, 'update'])->name('lunch_orders.update');

    Route::get('lunch_lists/index', [LunchListController::class, 'index'])->name('lunch_lists.index');
    Route::get('lunch_lists/more_list_factory/{lunch_order_id}/{factory_id}', [LunchListController::class, 'more_list_factory'])->name('lunch_lists.more_list_factory');
    Route::get('lunch_lists/every_day/{lunch_order_id?}', [LunchListController::class, 'every_day'])->name('lunch_lists.every_day');
    Route::get('lunch_lists/teacher_money_print/{lunch_order_id}', [LunchListController::class, 'teacher_money_print'])->name('lunch_lists.teacher_money_print');
    Route::get('lunch_lists/every_day_download/{lunch_order_id}', [LunchListController::class, 'every_day_download'])->name('lunch_lists.every_day_download');
    Route::get('lunch_lists/call_money/{lunch_order_id}', [LunchListController::class, 'call_money'])->name('lunch_lists.call_money');
    Route::get('lunch_lists/get_money/{lunch_order_id}', [LunchListController::class, 'get_money'])->name('lunch_lists.get_money');
    Route::get('lunch_lists/all_semester', [LunchListController::class, 'all_semester'])->name('lunch_lists.all_semester');
    Route::post('lunch_lists/semester_print', [LunchListController::class, 'semester_print'])->name('lunch_lists.semester_print');

    // 午餐學生 (LunchStu)
    Route::get('lunch_stus/index/{lunch_order_id?}/{sample_date?}', [LunchStuController::class, 'index'])->name('lunch_stus.index');
    Route::get('lunch_stus/delete/{lunch_order_id}', [LunchStuController::class, 'delete'])->name('lunch_stus.delete');
    Route::post('lunch_stus/store/{lunch_order_id}', [LunchStuController::class, 'store'])->name('lunch_stus.store');
    Route::post('lunch_stus/change_num', [LunchStuController::class, 'change_num'])->name('lunch_stus.change_num');
    Route::post('lunch_stus/store_ps/{lunch_order}', [LunchStuController::class, 'store_ps'])->name('lunch_stus.store_ps');

    // 假別管理 (TeacherAbsent)
    /**
    Route::get('teacher_absents/index/{select_semester?}', [TeacherAbsentController::class, 'index'])->name('teacher_absents.index');
    Route::get('teacher_absents/create', [TeacherAbsentController::class, 'create'])->name('teacher_absents.create');
    Route::post('teacher_absents/store', [TeacherAbsentController::class, 'store'])->name('teacher_absents.store');
    Route::get('teacher_absents/{teacher_absent}/edit', [TeacherAbsentController::class, 'edit'])->name('teacher_absents.edit');
    Route::get('teacher_absents/{filename}/{teacher_absent}/{type}/delete_file', [TeacherAbsentController::class, 'delete_file'])->name('teacher_absents.delete_file');
    Route::get('teacher_absents/{teacher_absent}/destroy', [TeacherAbsentController::class, 'destroy'])->name('teacher_absents.destroy');
    Route::patch('teacher_absents/{teacher_absent}/update', [TeacherAbsentController::class, 'update'])->name('teacher_absents.update');
    Route::get('teacher_absents/deputy/{select_semester?}', [TeacherAbsentController::class, 'deputy'])->name('teacher_absents.deputy');
    Route::get('teacher_absents/check/{type}/{teacher_absent}/', [TeacherAbsentController::class, 'check'])->name('teacher_absents.check');
    Route::get('teacher_absents/sir/{select_semester?}', [TeacherAbsentController::class, 'sir'])->name('teacher_absents.sir');
    Route::get('teacher_absents/total/{select_semester?}/{select_month?}', [TeacherAbsentController::class, 'total'])->name('teacher_absents.total');
    Route::get('teacher_absents/list/{select_semester?}/{select_teacher?}/{select_abs?}/{select_month?}', [TeacherAbsentController::class, 'list'])->name('teacher_absents.list');
    Route::get('teacher_absents/{teacher_absent}/back', [TeacherAbsentController::class, 'back'])->name('teacher_absents.back');
    Route::post('teacher_absents/{teacher_absent}/store_back', [TeacherAbsentController::class, 'store_back'])->name('teacher_absents.store_back');
    Route::get('teacher_absents/{teacher_absent}/admin_edit', [TeacherAbsentController::class, 'admin_edit'])->name('teacher_absents.admin_edit');
    Route::patch('teacher_absents/{teacher_absent}/admin_update', [TeacherAbsentController::class, 'admin_update'])->name('teacher_absents.admin_update');
    Route::get('teacher_absents/travel/{select_semester?}', [TeacherAbsentController::class, 'travel'])->name('teacher_absents.travel');
    Route::get('teacher_absents/travel/{teacher_absent}/outlay', [TeacherAbsentController::class, 'outlay'])->name('teacher_absents.outlay');
    Route::post('teacher_absents/travel/store_outlay', [TeacherAbsentController::class, 'store_outlay'])->name('teacher_absents.store_outlay');
    Route::get('teacher_absents/travel/{teacher_absent_outlay}/delete_outlay', [TeacherAbsentController::class, 'delete_outlay'])->name('teacher_absents.delete_outlay');
    Route::get('teacher_absents/travel/{teacher_absent_outlay}/edit_outlay', [TeacherAbsentController::class, 'edit_outlay'])->name('teacher_absents.edit_outlay');
    Route::post('teacher_absents/travel/{teacher_absent_outlay}/update_outlay', [TeacherAbsentController::class, 'update_outlay'])->name('teacher_absents.update_outlay');
    Route::post('teacher_absents/travel/travel_print', [TeacherAbsentController::class, 'travel_print'])->name('teacher_absents.travel_print');
     */

    // 內部文件
    Route::get('inside_files/{path?}', [InsideFilesController::class, 'index'])->name('inside_files.index');
    Route::get('inside_files_download/{path}', [InsideFilesController::class, 'download'])->name('inside_files.download');

    // 社團管理 (Clubs)
    Route::get('clubs', [ClubsController::class, 'index'])->name('clubs.index');
    Route::get('clubs/semester_create', [ClubsController::class, 'semester_create'])->name('clubs.semester_create');
    Route::post('clubs/semester_store', [ClubsController::class, 'semester_store'])->name('clubs.semester_store');
    Route::get('clubs/{semester}/semester_delete', [ClubsController::class, 'semester_delete'])->name('clubs.semester_delete');
    Route::get('clubs/{club_semester}/semester_edit', [ClubsController::class, 'semester_edit'])->name('clubs.semester_edit');
    Route::patch('clubs/{club_semester}/semester_update', [ClubsController::class, 'semester_update'])->name('clubs.semester_update');
    Route::get('clubs/setup/{semester?}', [ClubsController::class, 'setup'])->name('clubs.setup');
    Route::get('clubs/{semester}/club_create', [ClubsController::class, 'club_create'])->name('clubs.club_create');
    Route::post('clubs/club_store', [ClubsController::class, 'club_store'])->name('clubs.club_store');
    Route::post('clubs/club_copy', [ClubsController::class, 'club_copy'])->name('clubs.club_copy');
    Route::get('clubs/{club}/club_edit', [ClubsController::class, 'club_edit'])->name('clubs.club_edit');
    Route::patch('clubs/{club}/club_update', [ClubsController::class, 'club_update'])->name('clubs.club_update');
    Route::get('clubs/{club}/club_delete', [ClubsController::class, 'club_delete'])->name('clubs.club_delete');
    Route::get('clubs/{semester}/stu_adm', [ClubsController::class, 'stu_adm'])->name('clubs.stu_adm');
    Route::get('clubs/{semester}/stu_adm_more/{student_class_id?}', [ClubsController::class, 'stu_adm_more'])->name('clubs.stu_adm_more');
    Route::post('clubs/{semester}/stu_import', [ClubsController::class, 'stu_import'])->name('clubs.stu_import');
    Route::get('clubs/{semester}/stu_create/{student_class}', [ClubsController::class, 'stu_create'])->name('clubs.stu_create');
    Route::post('clubs/{semester}/stu_store', [ClubsController::class, 'stu_store'])->name('clubs.stu_store');
    Route::get('clubs/{club_student}/stu_edit/{student_class}', [ClubsController::class, 'stu_edit'])->name('clubs.stu_edit');
    Route::patch('clubs/{club_student}/stu_update', [ClubsController::class, 'stu_update'])->name('clubs.stu_update');
    Route::get('clubs/{club_student}/stu_delete/{student_class_id}', [ClubsController::class, 'stu_delete'])->name('clubs.stu_delete');
    Route::get('clubs/{club_student}/stu_disable/{student_class_id}', [ClubsController::class, 'stu_disable'])->name('clubs.stu_disable');
    Route::get('clubs/{club_student}/stu_enable/{student_class_id}', [ClubsController::class, 'stu_enable'])->name('clubs.stu_enable');
    Route::get('clubs/{club_student}/stu_backPWD/{student_class_id}', [ClubsController::class, 'stu_backPWD'])->name('clubs.stu_backPWD');
    Route::get('clubs/report_situation/{semester?}', [ClubsController::class, 'report_situation'])->name('clubs.report_situation');
    Route::get('clubs/report_not_situation/{semester?}', [ClubsController::class, 'report_not_situation'])->name('clubs.report_not_situation');
    Route::get('clubs/{semester}/report_situation_download/{class_id}', [ClubsController::class, 'report_situation_download'])->name('clubs.report_situation_download');
    Route::get('clubs/{club_register}/report_register_delete', [ClubsController::class, 'report_register_delete'])->name('clubs.report_register_delete');
    Route::get('clubs/report_money/{semester?}', [ClubsController::class, 'report_money'])->name('clubs.report_money');
    Route::get('clubs/{semester}/{class_id}/report_money_download', [ClubsController::class, 'report_money_download'])->name('clubs.report_money_download');
    Route::get('clubs/{semester}/{class_id}/report_money_download2', [ClubsController::class, 'report_money_download2'])->name('clubs.report_money_download2');
    Route::get('clubs/{semester}/{class_id}/report_money2_print', [ClubsController::class, 'report_money2_print'])->name('clubs.report_money2_print');
    Route::get('clubs/report', [ClubsController::class, 'report'])->name('clubs.report');
    Route::post('clubs/black', [ClubsController::class, 'black'])->name('clubs.store_black');
    Route::get('clubs/{semester}/{club_black}/destroy_black', [ClubsController::class, 'destroy_black'])->name('clubs.destroy_black');

    // 報錯系統
    Route::get('wrench/index/{page?}', [WrenchController::class, 'index'])->name('wrench.index');
    Route::post('wrench/store', [WrenchController::class, 'store'])->name('wrench.store');
    Route::get('wrench/download/{wrench_id}/{filename}', [WrenchController::class, 'download'])->name('wrench.download');

    // 借用系統 (Lends)
    Route::get('lends/index/{lend_class_id?}/{this_date?}', [LendsController::class, 'index'])->name('lends.index');
    Route::get('lends/list', [LendsController::class, 'list'])->name('lends.list');
    Route::get('lends/my_list', [LendsController::class, 'my_list'])->name('lends.my_list');
    Route::get('lends/admin/{lend_class_id?}', [LendsController::class, 'admin'])->name('lends.admin');
    Route::post('lends/store_class', [LendsController::class, 'store_class'])->name('lends.store_class');
    Route::post('lends/update_class/{lend_class}', [LendsController::class, 'update_class'])->name('lends.update_class');
    Route::get('lends/delete_class/{lend_class}', [LendsController::class, 'delete_class'])->name('lends.delete_class');
    Route::post('lends/store_item', [LendsController::class, 'store_item'])->name('lends.store_item');
    Route::get('lends/delete_item/{lend_item}', [LendsController::class, 'delete_item'])->name('lends.delete_item');
    Route::get('lends/admin_edit/{lend_item}', [LendsController::class, 'admin_edit'])->name('lends.admin_edit');
    Route::post('lends/update_item/{lend_item}', [LendsController::class, 'update_item'])->name('lends.update_item');
    Route::get('lends/check_item_num/{lend_item}', [LendsController::class, 'check_item_num'])->name('lends.check_item_num');
    Route::get('lends/check_order_out/{this_date}/{action}', [LendsController::class, 'check_order_out'])->name('lends.check_order_out');
    Route::post('lends/order', [LendsController::class, 'order'])->name('lends.order');
    Route::get('lends/delete_my_order/{lend_order}', [LendsController::class, 'delete_my_order'])->name('lends.delete_my_order');
    Route::get('lends/delete_order/{lend_order}', [LendsController::class, 'delete_order'])->name('lends.delete_order');
    Route::post('lends/update_other_order/{lend_order}', [LendsController::class, 'update_other_order'])->name('lends.update_other_order');
    Route::post('store_line_notify', [LendsController::class, 'store_line_notify'])->name('store_line_notify');

    // 運動會報名 (SportMeeting)
    Route::get('sport_meeting/admin', [SportMeetingController::class, 'admin'])->name('sport_meeting.admin');
    Route::get('sport_meeting/index/{action_id?}', [SportMeetingController::class, 'index'])->name('sport_meeting.index');
    Route::post('sport_meeting/stu_import', [SportMeetingController::class, 'stu_import'])->name('sport_meeting.stu_import');
    Route::get('sport_meeting/{club_student}/stu_disable/{student_class_id}', [SportMeetingController::class, 'stu_disable'])->name('sport_meeting.stu_disable');
    Route::get('sport_meeting/user', [SportMeetingController::class, 'user'])->name('sport_meeting.user');
    Route::get('sport_meeting/action', [SportMeetingController::class, 'action'])->name('sport_meeting.action');
    Route::get('sport_meeting/action_create', [SportMeetingController::class, 'action_create'])->name('sport_meeting.action_create');
    Route::post('sport_meeting/action_add', [SportMeetingController::class, 'action_add'])->name('sport_meeting.action_add');
    Route::get('sport_meeting/action_show/{action}', [SportMeetingController::class, 'action_show'])->name('sport_meeting.action_show');
    Route::post('sport_meeting/get_students', [SportMeetingController::class, 'get_students'])->name('sport_meeting.get_students');
    Route::post('sport_meeting/get_students_do', [SportMeetingController::class, 'get_students_do'])->name('sport_meeting.get_students_do');
    Route::get('sport_meeting/get_students_delete/{student_sign}', [SportMeetingController::class, 'get_students_delete'])->name('sport_meeting.get_students_delete');
    Route::get('sport_meeting/action_set_number/{action}', [SportMeetingController::class, 'action_set_number'])->name('sport_meeting.action_set_number');
    Route::get('sport_meeting/action_set_number_null/{action}', [SportMeetingController::class, 'action_set_number_null'])->name('sport_meeting.action_set_number_null');
    Route::get('sport_meeting/action_edit/{action}', [SportMeetingController::class, 'action_edit'])->name('sport_meeting.action_edit');
    Route::patch('sport_meeting/action/{action}/update', [SportMeetingController::class, 'action_update'])->name('sport_meeting.action_update');
    Route::get('sport_meeting/action_delete/{action}', [SportMeetingController::class, 'action_delete'])->name('sport_meeting.action_delete');
    Route::get('sport_meeting/action_destroy/{action}', [SportMeetingController::class, 'action_destroy'])->name('sport_meeting.action_destroy');
    Route::get('sport_meeting/action/enable/{action}', [SportMeetingController::class, 'action_enable'])->name('sport_meeting.action_enable');
    Route::get('sport_meeting/item/{action_id?}', [SportMeetingController::class, 'item'])->name('sport_meeting.item');
    Route::get('sport_meeting/item/{action}/create', [SportMeetingController::class, 'item_create'])->name('sport_meeting.item_create');
    Route::post('sport_meeting/item/add', [SportMeetingController::class, 'item_add'])->name('sport_meeting.item_add');
    Route::post('sport_meeting/item/import', [SportMeetingController::class, 'item_import'])->name('sport_meeting.item_import');
    Route::get('sport_meeting/item/{item}/edit', [SportMeetingController::class, 'item_edit'])->name('sport_meeting.item_edit');
    Route::patch('sport_meeting/item/{item}/update', [SportMeetingController::class, 'item_update'])->name('sport_meeting.item_update');
    Route::get('sport_meeting/item/{item}/delete', [SportMeetingController::class, 'item_delete'])->name('sport_meeting.item_delete');
    Route::get('sport_meeting/item/{item}/destroy', [SportMeetingController::class, 'item_destroy'])->name('sport_meeting.item_destroy');
    Route::get('sport_meeting/item/{item}/enable', [SportMeetingController::class, 'item_enable'])->name('sport_meeting.item_enable');
    Route::get('sport_meeting/teacher', [SportMeetingController::class, 'teacher'])->name('sport_meeting.teacher');
    Route::get('sport_meeting/{action}/sign_up_do', [SportMeetingController::class, 'sign_up_do'])->name('sport_meeting.sign_up_do');
    Route::post('sport_meeting/sign_up_add', [SportMeetingController::class, 'sign_up_add'])->name('sport_meeting.sign_up_add');
    Route::get('sport_meeting/{action}/sign_up_show', [SportMeetingController::class, 'sign_up_show'])->name('sport_meeting.sign_up_show');
    Route::get('sport_meeting/list/{action_id?}', [SportMeetingController::class, 'list'])->name('sport_meeting.list');
    Route::get('sport_meeting/{semester}/stu_adm_more/{student_class_id?}', [SportMeetingController::class, 'stu_adm_more'])->name('sport_meeting.stu_adm_more');
    Route::get('sport_meeting/{student_sign}/sign_up_delete', [SportMeetingController::class, 'sign_up_delete'])->name('sport_meeting.sign_up_delete');
    Route::patch('sport_meeting/student_sign_update', [SportMeetingController::class, 'student_sign_update'])->name('sport_meeting.student_sign_update');
    Route::post('sport_meeting/student_sign_make', [SportMeetingController::class, 'student_sign_make'])->name('sport_meeting.student_sign_make');
    Route::get('sport_meeting/score_input/{action_id?}', [SportMeetingController::class, 'score_input'])->name('sport_meeting.score_input');
    Route::match(['post','get'],'sport_meeting/score_input_do', [SportMeetingController::class, 'score_input_do'])->name('sport_meeting.score_input_do');
    Route::get('sport_meeting/score_input/{action}/print/{item}/{year}/{sex}', [SportMeetingController::class, 'score_input_print'])->name('sport_meeting.score_input_print');
    Route::get('sport_meeting/score_input/{action}/print2/{item}/{year}/{sex}', [SportMeetingController::class, 'score_input_print2'])->name('sport_meeting.score_input_print2');
    Route::post('sport_meeting/score_input_update', [SportMeetingController::class, 'score_input_update'])->name('sport_meeting.score_input_update');
    Route::get('sport_meeting/score', [SportMeetingController::class, 'score'])->name('sport_meeting.score');
    Route::post('sport_meeting/print_extra', [SportMeetingController::class, 'print_extra'])->name('sport_meeting.print_extra');
    Route::post('sport_meeting/demo_upload', [SportMeetingController::class, 'demo_upload'])->name('sport_meeting.demo_upload');
    Route::get('sport_meeting/score_print/{action_id?}', [SportMeetingController::class, 'score_print'])->name('sport_meeting.score_print');
    Route::get('sport_meeting/all_scores/{action_id?}', [SportMeetingController::class, 'all_scores'])->name('sport_meeting.all_scores');
    Route::get('sport_meeting/all_scores_print/{action}', [SportMeetingController::class, 'all_scores_print'])->name('sport_meeting.all_scores_print');
    Route::match(['post','get'],'sport_meeting/scores_do', [SportMeetingController::class, 'scores_do'])->name('sport_meeting.scores_do');
    Route::post('sport_meeting/scores_update', [SportMeetingController::class, 'scores_update'])->name('sport_meeting.scores_update');
    Route::get('sport_meeting/scores_print/{action}/{item}/{year}/{sex}', [SportMeetingController::class, 'scores_print'])->name('sport_meeting.scores_print');
    Route::get('sport_meeting/total_scores/{action_id?}', [SportMeetingController::class, 'total_scores'])->name('sport_meeting.total_scores');
    Route::get('sport_meeting/total_scores_print/{action}', [SportMeetingController::class, 'total_scores_print'])->name('sport_meeting.total_scores_print');
    Route::get('sport_meeting/records/{action_id?}', [SportMeetingController::class, 'records'])->name('sport_meeting.records');
    Route::get('sport_meeting/scores/{action_id?}', [SportMeetingController::class, 'scores'])->name('sport_meeting.scores');
    Route::get('sport_meeting/download_records/{action}', [SportMeetingController::class, 'download_records'])->name('sport_meeting.download_records');
    Route::get('sport_meeting/{action}/action_set_number', [SportMeetingController::class, 'action_set_number'])->name('sport_meeting.action_set_number');
    Route::get('sport_meeting/{action}/action_set_number_null', [SportMeetingController::class, 'action_set_number_null'])->name('sport_meeting.action_set_number_null');
});

// --- 行政人員可用 ---
Route::group(['middleware' => 'exec'], function () {

    Route::get('school_marquee/index', [SchoolMarqueeController::class, 'index'])->name('school_marquee.index');
    Route::get('school_marquee/create', [SchoolMarqueeController::class, 'create'])->name('school_marquee.create');
    Route::post('school_marquee/store', [SchoolMarqueeController::class, 'store'])->name('school_marquee.store');
    Route::get('school_marquee/{school_marquee}/edit', [SchoolMarqueeController::class, 'edit'])->name('school_marquee.edit');
    Route::post('school_marquee/{school_marquee}/update', [SchoolMarqueeController::class, 'update'])->name('school_marquee.update');
    Route::delete('school_marquee/{school_marquee}/destroy', [SchoolMarqueeController::class, 'destroy'])->name('school_marquee.destroy');

    Route::get('posts/create', [PostsController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostsController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/delete_title_image', [PostsController::class, 'delete_title_image'])->name('posts.delete_title_image');
    Route::get('posts/{post}/delete_file/{filename}', [PostsController::class, 'delete_file'])->name('posts.delete_file');
    Route::get('posts/{post}/delete_photo/{filename}', [PostsController::class, 'delete_photo'])->name('posts.delete_photo');

    // 公開文件
    Route::get('open_files_create', [OpenFileController::class, 'create'])->name('open_files.create');
    Route::post('open_files_create_folder', [OpenFileController::class, 'create_folder'])->name('open_files.create_folder');
    Route::post('open_files_upload_file', [OpenFileController::class, 'upload_file'])->name('open_files.upload_file');
    Route::post('open_files_upload_cloud', [OpenFileController::class, 'upload_cloud'])->name('open_files.upload_cloud');

    // 內部文件
    Route::get('inside_files_create', [InsideFilesController::class, 'create'])->name('inside_files.create');
    Route::post('inside_files_create_folder', [InsideFilesController::class, 'create_folder'])->name('inside_files.create_folder');
    Route::post('inside_files_upload_file', [InsideFilesController::class, 'upload_file'])->name('inside_files.upload_file');
    Route::post('inside_files_upload_cloud', [InsideFilesController::class, 'upload_cloud'])->name('inside_files.upload_cloud');

    // 報修回復
    Route::patch('fixes/{fix}', [FixController::class, 'update'])->name('fixes.update');
    Route::patch('fixes/{fix}/update_clean', [FixController::class, 'update_clean'])->name('fixes.update_clean');
    Route::get('fixes/edit_class', [FixController::class, 'edit_class'])->name('fixes.edit_class');
    Route::post('fixes/edit_class/{fix_class}', [FixController::class, 'update_class'])->name('fixes.update_class');
    Route::post('fixes/store_class', [FixController::class, 'store_class'])->name('fixes.store_class');

    // 會議文稿與報告
    Route::get('meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
    Route::post('meetings', [MeetingController::class, 'store'])->name('meetings.store');
    Route::get('meetings_reports/{meeting}/create', [ReportController::class, 'create'])->name('meetings_reports.create');
    Route::post('meetings_reports', [ReportController::class, 'store'])->name('meetings_reports.store');
    Route::get('meetings_reports/{report}/edit', [ReportController::class, 'edit'])->name('meetings_reports.edit');
    Route::patch('meetings_reports/{report}', [ReportController::class, 'update'])->name('meetings_reports.update');
    Route::delete('meetings_reports/{report}', [ReportController::class, 'destroy'])->name('meetings_reports.destroy');
    Route::get('meetings_reports/{file}/fileDel', [ReportController::class, 'fileDel'])->name('meetings_reports.fileDel');

    // 行事曆
    Route::get('calendars/{semester}/create', [CalendarController::class, 'create'])->name('calendars.create');
    Route::post('calendars', [CalendarController::class, 'store'])->name('calendars.store');
    Route::get('calendars/{calendar}/edit', [CalendarController::class, 'edit'])->name('calendars.edit');
    Route::patch('calendars/{calendar}', [CalendarController::class, 'update'])->name('calendars.update');
    Route::delete('calendars/{calendar}', [CalendarController::class, 'destroy'])->name('calendars.destroy');
    Route::get('calendars/{calendar}/delete', [CalendarController::class, 'delete'])->name('calendars.delete');
    Route::post('calendars/update', [CalendarController::class, 'update'])->name('calendars.update');

    // 月曆
    Route::get('monthly_calendars/index/{month?}', [MonthlyCalendarController::class, 'index'])->name('monthly_calendars.index');
    Route::post('monthly_calendars/store', [MonthlyCalendarController::class, 'store'])->name('monthly_calendars.store');
    Route::post('monthly_calendars/block_store', [MonthlyCalendarController::class, 'block_store'])->name('monthly_calendars.block_store');
    Route::post('monthly_calendars/file', [MonthlyCalendarController::class, 'file'])->name('monthly_calendars.file');
    Route::post('monthly_calendars/file/store', [MonthlyCalendarController::class, 'file_store'])->name('monthly_calendars.file_store');
    Route::get('monthly_calendars/destroy/{monthly_calendar}', [MonthlyCalendarController::class, 'destroy'])->name('monthly_calendars.destroy');
    Route::get('monthly_calendars/block_destroy/{monthly_calendar}', [MonthlyCalendarController::class, 'block_destroy'])->name('monthly_calendars.block_destroy');

    // 部落格
    Route::get('blogs/create', [BlogsController::class, 'create'])->name('blogs.create');
    Route::post('blogs', [BlogsController::class, 'store'])->name('blogs.store');
    Route::get('blogs/{blog}/edit', [BlogsController::class, 'edit'])->name('blogs.edit');
    Route::patch('blogs/{blog}', [BlogsController::class, 'update'])->name('blogs.update');
    Route::get('blogs/{blog}/delete_title_image', [BlogsController::class, 'delete_title_image'])->name('blogs.delete_title_image');
});

// --- 行政人員及管理者 ---
Route::group(['middleware' => 'admin_exec'], function () {
    Route::get('posts/{post}/edit', [PostsController::class, 'edit'])->name('posts.edit');
    Route::patch('posts/{post}', [PostsController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostsController::class, 'destroy'])->name('posts.destroy');

    Route::get('open_files_delete/{path}', [OpenFileController::class, 'delete'])->name('open_files.delete');
    Route::get('open_files_edit/{upload}/{path}', [OpenFileController::class, 'edit'])->name('open_files.edit');
    Route::patch('open_files_update/{upload}', [OpenFileController::class, 'update'])->name('open_files.update');

    Route::get('inside_files_delete/{path}', [InsideFilesController::class, 'delete'])->name('inside_files.delete');
    Route::get('inside_files_edit/{inside_file}/{path}', [InsideFilesController::class, 'edit'])->name('inside_files.edit');
    Route::patch('inside_files_update/{inside_file}', [InsideFilesController::class, 'update'])->name('inside_files.update');

    // Laravel Filemanager (外部套件)
    Route::get('/laravel-filemanager', [\UniSharp\LaravelFilemanager\Controllers\LfmController::class, 'show']);
    Route::post('/laravel-filemanager/upload', [\UniSharp\LaravelFilemanager\Controllers\UploadController::class, 'upload']);

    // 圖片連結管理
    Route::get('photo_links/index/{photo_type_id?}', [PhotoLinksController::class, 'index'])->name('photo_links.index');
    Route::get('photo_links/create/{photo_type_id?}', [PhotoLinksController::class, 'create'])->name('photo_links.create');
    Route::post('photo_links', [PhotoLinksController::class, 'store'])->name('photo_links.store');
    Route::post('photo_links/type_store', [PhotoLinksController::class, 'type_store'])->name('photo_links.type_store');
    Route::patch('photo_links/type_update/{photo_type}/{photo_type_id?}', [PhotoLinksController::class, 'type_update'])->name('photo_links.type_update');
    Route::get('photo_links/type_delete/{photo_type}', [PhotoLinksController::class, 'type_delete'])->name('photo_links.type_delete');
    Route::delete('photo_links/{photo_link}', [PhotoLinksController::class, 'destroy'])->name('photo_links.destroy');
    Route::get('photo_links/{photo_link}/edit', [PhotoLinksController::class, 'edit'])->name('photo_links.edit');
    Route::patch('photo_links/{photo_link}', [PhotoLinksController::class, 'update'])->name('photo_links.update');

    Route::delete('blogs/{blog}', [BlogsController::class, 'destroy'])->name('blogs.destroy');
});

// --- 管理者可用 ---
Route::group(['middleware' => 'admin'], function () {

    Route::get('school_marquee/setup', [SchoolMarqueeController::class, 'setup'])->name('school_marquee.setup');
    Route::post('school_marquee/setup_store', [SchoolMarqueeController::class, 'setup_store'])->name('school_marquee.setup_store');

    Route::get('sims/{user}/impersonate', [SimulationController::class, 'impersonate'])->name('sims.impersonate');

    // 網站管理
    Route::get('setups', [SetupController::class, 'index'])->name('setups.index');
    Route::get('setups/edit_footer', [SetupController::class, 'edit_footer'])->name('setups.edit_footer');
    Route::patch('setups/update_footer', [SetupController::class, 'update_footer'])->name('setups.update_footer');
    Route::post('setups/photo_link_number', [SetupController::class, 'photo_link_number'])->name('setups.photo_link_number');
    Route::post('setups/add_logo', [SetupController::class, 'add_logo'])->name('setups.add_logo');
    Route::post('setups/add_imgs', [SetupController::class, 'add_imgs'])->name('setups.add_imgs');
    Route::get('setups/{folder}/del_img/{filename}', [SetupController::class, 'del_img'])->name('setups.del_img');
    Route::get('setups/photo', [SetupController::class, 'photo'])->name('setups.photo');
    Route::post('setups/photo_desc', [SetupController::class, 'photo_desc'])->name('setups.photo_desc');
    Route::patch('setups/{setup}/photo/update_title_image', [SetupController::class, 'update_title_image'])->name('setups.update_title_image');
    Route::patch('setups/{setup}/nav_color', [SetupController::class, 'nav_color'])->where('setup', '[0-9]+')->name('setups.nav_color');
    Route::get('setups/nav_default/', [SetupController::class, 'nav_default'])->name('setups.nav_default');
    Route::patch('setups/{setup}/text', [SetupController::class, 'text'])->name('setups.text');
    Route::get('setups/col', [SetupController::class, 'col'])->name('setups.col');
    Route::get('setups/add_col_table', [SetupController::class, 'add_col_table'])->name('setups.add_col_table');
    Route::post('setups/add_col', [SetupController::class, 'add_col'])->name('setups.add_col');
    Route::get('setups/{setup_col}/edit_col', [SetupController::class, 'edit_col'])->name('setups.edit_col');
    Route::patch('setups/{setup_col}/update_col', [SetupController::class, 'update_col'])->name('setups.update_col');
    Route::delete('setups/{setup_col}/delete_col', [SetupController::class, 'delete_col'])->name('setups.delete_col');
    Route::post('setups/all_post', [SetupController::class, 'all_post'])->name('setups.all_post');
    Route::post('setups/post_show_number', [SetupController::class, 'post_show_number'])->name('setups.post_show_number');
    Route::post('setups/post_line_token', [SetupController::class, 'post_line_token'])->name('setups.post_line_token');

    // 區塊與模組
    Route::get('setups/block', [SetupController::class, 'block'])->name('setups.block');
    Route::get('setups/add_block_table', [SetupController::class, 'add_block_table'])->name('setups.add_block_table');
    Route::post('setups/add_block', [SetupController::class, 'add_block'])->name('setups.add_block');
    Route::get('setups/{block}/edit_block', [SetupController::class, 'edit_block'])->name('setups.edit_block');
    Route::patch('setups/{block}/update_block', [SetupController::class, 'update_block'])->name('setups.update_block');
    Route::delete('setups/{block}/delete_block', [SetupController::class, 'delete_block'])->name('setups.delete_block');
    Route::get('setups/block_color', [SetupController::class, 'block_color'])->name('setups.block_color');
    Route::get('setups/module', [SetupController::class, 'module'])->name('setups.module');
    Route::post('setups/module', [SetupController::class, 'update_module'])->name('setups.update_module');
    Route::get('setups/quota', [SetupController::class, 'quota'])->name('setups.quota');
    Route::get('setups/batch_delete_posts', [SetupController::class, 'batch_delete_posts'])->name('setups.batch_delete_posts');
    Route::delete('delete/batch_delete', [SetupController::class, 'batch_delete'])->name('setups.batch_delete');

    // 使用者權限與管理
    Route::get('user_powers/{module}/{type}', [UserPowerController::class, 'create'])->name('user_powers.create');
    Route::post('user_powers', [UserPowerController::class, 'store'])->name('user_powers.store');
    Route::get('user_powers_destroy/{user_power}', [UserPowerController::class, 'destroy'])->name('user_powers.destroy');

    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
    Route::get('users/back_pwd/{user}', [UsersController::class, 'back_pwd'])->name('users.back_pwd');
    Route::post('users/store', [UsersController::class, 'store'])->name('users.store');
    Route::get('users/leave', [UsersController::class, 'leave'])->name('users.leave');
    Route::get('users/{user}', [UsersController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}/update', [UsersController::class, 'update'])->name('users.update');

    Route::get('groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('groups', [GroupController::class, 'store'])->name('groups.store');
    Route::delete('groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::get('groups/{group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::patch('groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::get('users_groups/{group}', [GroupController::class, 'users_groups'])->name('users_groups');
    Route::post('users_groups', [GroupController::class, 'users_groups_store'])->name('users_groups.store');
    Route::delete('users_groups', [GroupController::class, 'users_groups_destroy'])->name('users_groups.destroy');

    // 處室與內容管理
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::get('departments/show_log/{id}', [DepartmentController::class, 'show_log'])->name('departments.show_log');
    Route::get('departments/delete_log/{log}', [DepartmentController::class, 'delete_log'])->name('departments.delete_log');
    Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::patch('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');

    Route::get('contents', [ContentsController::class, 'index'])->name('contents.index');
    Route::get('contents/search/{tag}', [ContentsController::class, 'search'])->name('contents.search');
    Route::get('contents/create', [ContentsController::class, 'create'])->name('contents.create');
    Route::get('contents/show_log/{id}', [ContentsController::class, 'show_log'])->name('contents.show_log');
    Route::get('contents/delete_log/{log}', [ContentsController::class, 'delete_log'])->name('contents.delete_log');
    Route::post('contents/store', [ContentsController::class, 'store'])->name('contents.store');
    Route::delete('contents/{content}', [ContentsController::class, 'destroy'])->name('contents.destroy');
    Route::get('contents/{content}/edit', [ContentsController::class, 'edit'])->name('contents.edit');
    Route::patch('contents/{content}/update', [ContentsController::class, 'update'])->name('contents.update');

    // 連結、類別與樹狀目錄
    Route::post('types', [LinksController::class, 'store_type'])->name('links.store_type');
    Route::delete('types/{type}', [LinksController::class, 'destroy_type'])->name('links.destroy_type');
    Route::patch('types/{type}', [LinksController::class, 'update_type'])->name('links.update_type');
    Route::get('links/index/{type_id?}', [LinksController::class, 'index'])->name('links.index');
    Route::get('links/create/{type_id?}', [LinksController::class, 'create'])->name('links.create');
    Route::post('links', [LinksController::class, 'store'])->name('links.store');
    Route::delete('links/{link}', [LinksController::class, 'destroy'])->name('links.destroy');
    Route::get('links/{link}/delete', [LinksController::class, 'delete'])->name('links.delete');
    Route::get('links/{link}/edit', [LinksController::class, 'edit'])->name('links.edit');
    Route::patch('links/{link}/{type_id?}', [LinksController::class, 'update'])->name('links.update');

    Route::get('trees', [TreesController::class, 'index'])->name('trees.index');
    Route::post('trees/store', [TreesController::class, 'store'])->name('trees.store');
    Route::get('trees/{tree}/edit', [TreesController::class, 'edit'])->name('trees.edit');
    Route::patch('trees/{tree}/update', [TreesController::class, 'update'])->name('trees.update');
    Route::get('trees/{tree}/delete', [TreesController::class, 'delete'])->name('trees.delete');

    // 公告與會議進階操作
    Route::get('posts/{post}/top_up', [PostsController::class, 'top_up'])->name('posts.top_up');
    Route::post('posts/{post}/top_up2', [PostsController::class, 'top_up2'])->name('posts.top_up2');
    Route::get('posts/{post}/top_down', [PostsController::class, 'top_down'])->name('posts.top_down');
    Route::get('posts/show_type', [PostsController::class, 'show_type'])->name('posts.show_type');
    Route::post('posts/store_type', [PostsController::class, 'store_type'])->name('posts.store_type');
    Route::patch('posts/{post_type}/update_type', [PostsController::class, 'update_type'])->name('posts.update_type');
    Route::get('posts/{post_type}/delete_type', [PostsController::class, 'delete_type'])->name('posts.delete_type');
    Route::get('posts/{post_type}/disable_type', [PostsController::class, 'disable_type'])->name('posts.disable_type');
    Route::get('posts/{post}/inbox', [PostsController::class, 'inbox'])->name('posts.inbox');

    Route::get('meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
    Route::patch('meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
    Route::delete('meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');

    // 其他管理功能
    Route::get('calendar_weeks/index', [CalendarWeekController::class, 'index'])->name('calendar_weeks.index');
    Route::get('calendar_weeks/{semester}/edit', [CalendarWeekController::class, 'edit'])->name('calendar_weeks.edit');
    Route::post('calendar_weeks/update', [CalendarWeekController::class, 'update'])->name('calendar_weeks.update');
    Route::post('calendar_weeks/create', [CalendarWeekController::class, 'create'])->name('calendar_weeks.create');
    Route::post('calendar_weeks/store', [CalendarWeekController::class, 'store'])->name('calendar_weeks.store');
    Route::get('calendar_weeks/{semester}/destroy', [CalendarWeekController::class, 'destroy'])->name('calendar_weeks.destroy');

    Route::get('lunch_today/index', [LunchTodayController::class, 'index'])->name('lunch_todays.index');
    Route::post('lunch_today/update', [LunchTodayController::class, 'update'])->name('lunch_todays.update');
    Route::get('lunch_today/{lunch_today}/delete', [LunchTodayController::class, 'delete'])->name('lunch_todays.delete');

    Route::get('rss_feed/index', [RssFeedController::class, 'index'])->name('rss_feeds.index');
    Route::post('rss_feed/store', [RssFeedController::class, 'store'])->name('rss_feeds.store');
    Route::get('rss_feed/{rss_feed}/destory', [RssFeedController::class, 'destory'])->name('rss_feeds.destory');

    Route::post('wrench/reply', [WrenchController::class, 'reply'])->name('wrench.reply');
    Route::get('wrench/set_show/{id}', [WrenchController::class, 'set_show'])->name('wrench.set_show');
    Route::get('wrench/destroy/{id}', [WrenchController::class, 'destroy'])->name('wrench.destroy');

    Route::get('teach_system', [HomeController::class, 'teach_system'])->name('teach_system');
});

// --- 本地網路/特定權限可用 ---
Route::group(['middleware' => 'local'], function () {
    // 更改密碼
    Route::get('edit_password', [HomeController::class, 'edit_password'])->name('edit_password');
    Route::patch('update_password', [HomeController::class, 'update_password'])->name('update_password');
});

// --- 午餐系統 AJAX 資料回傳 ---
Route::post('lunch_today/return_date1', [LunchTodayController::class, 'return_date1'])->name('lunch_todays.return_date1');
Route::post('lunch_today/return_date2', [LunchTodayController::class, 'return_date2'])->name('lunch_todays.return_date2');
Route::post('lunch_today/return_date3', [LunchTodayController::class, 'return_date3'])->name('lunch_todays.return_date3');
Route::post('lunch_today/return_date4', [LunchTodayController::class, 'return_date4'])->name('lunch_todays.return_date4');

// --- 其他 AJAX 工具 ---
Route::post('monthly_calendars/return_month', [MonthlyCalendarController::class, 'return_month'])->name('monthly_calendars.return_month');
Route::post('classroom_orders/block_show', [ClassroomOrderController::class, 'block_show'])->name('classroom_orders.block_show');
