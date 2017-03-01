<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class LoginController extends Controller
{

    /**
     * 验证用户账号密码
     * @param array $user
     * @return bool
     */
    protected function verify(array $user)
    {
        $user_info = User::where(['email' => $user['email']])->first();

        if($user_info){
            if(password_verify($user['pass'], $user_info['pass'])){
                return 'success';
            }
        }else{
            return 'error';
        }
    }
}
