<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function logout(){
        session(['sys_login' => null]);
        Auth::logout();
        $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
        $post_logout_redirect_uri = url('/');        
        $id_token_hint = session('id_token');
        $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
        Session::flush();
        return redirect($link);
    }

    public function username()
    {
        return 'username';
    }
}
