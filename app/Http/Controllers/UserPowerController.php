<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPower;
use Illuminate\Http\Request;

class UserPowerController extends Controller
{
    public function create($module,$type)
    {
        $users = User::where('disable',null)
            ->where('username','<>','admin')
            ->orderBy('order_by')
            ->pluck('name','id')
            ->toArray()
            ;

        $data = [
            'users'=>$users,
            'module'=>$module,
            'type'=>$type,
        ];

        return view('user_powers.create',$data);
    }

    public function store(Request $request)
    {
        UserPower::create($request->all());
        echo "
            <script>
            // 確保頁面加載完成後執行
            window.onload = function() {
                // 檢查父頁面是否存在且可以訪問 jQuery
                if (window.parent && window.parent.$) {
                    // 關閉 venobox 視窗
                    if (typeof window.parent.$.venobox !== 'undefined') {
                        window.parent.$.venobox.close();  // 關閉 venobox 視窗
                    }

                    // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                    window.parent.location.reload();                
                }
            };
            </script>";
    }

    public function destroy(UserPower $user_power)
    {
        $user_power->delete();
        return redirect()->route('setups.module');
    }
}
