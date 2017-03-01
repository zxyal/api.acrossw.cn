<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class RegisterController extends Controller
{
    /**
     * 创建新用户
     * @param array $data
     * @return mixed
     */
    protected function create(array $data)
    {


        return User::create([
            'user_name'     => $data['email'],
            'email'         => $data['email'],
            'pass'          => password_hash($data['password'], PASSWORD_DEFAULT),
            'password'      => $data['password'],
        ]);
    }

}
