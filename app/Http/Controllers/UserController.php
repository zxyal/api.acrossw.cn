<?php

namespace App\Http\Controllers;

use Crypt;
use Illuminate\Http\Request;
use App\User;
use Validator;
use withErrors;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public $error_msg = [
        'email.email' => '请输入正确的E-Mail',
        'email.required' => '请填写E-Mail',
        'password.required' => '请填写密码',
        'password.alpha_dash' => '密码只能包含数字、字母和下划线',
        'max' => '超过最大输入限制',
        'password.min' => '密码不能小于6位'
    ];

    /**
     * Login
     * @param Request $request
     * @return array
     */
    protected function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
            'password' => 'required|alpha_dash|max:50|min:6',
        ], $this->error_msg);

        if ($validator->fails()) {
            return ['type' => 'fail', 'mes' => $validator->errors()->all()];
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user_info = User::where('email', $email)->first();

        //生成token
        $token = Crypt::encrypt($user_info['id'] . '/'. (time()+(3*24*60*60)));

        $response_user_info = [
            'overdue_time'  =>  (time()+(3*24*60*60)),
            'email'         =>  $user_info['email'],
        ];

        if (password_verify($password, $user_info['pass'])) {
            return ['type' => 'success', 'token' => $token, 'info' => json_encode($response_user_info)];
        } else {
            return ['type' => 'fail', 'mes' => ['E-Mail或密码错误']];
        }

    }

    /**
     * 注册新用户
     * @param Request $request
     * @return mixed
     */
    protected function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
            'password' => 'required|alpha_dash|max:50|min:6',
        ], $this->error_msg);

        if ($validator->fails()) {
            return ['type' => 'fail', 'mes' => $validator->errors()->all()];
        }

        $email      = $request->input('email');
        $password   = $request->input('password');
        $rpassword  = $request->input('rpassword');

        if($password != $rpassword){
            return ['type' => 'fail', 'mes' => ['两次输入的密码不一致']];
        }

        $user_exist = User::where('email' , $email)->first();

        if($user_exist){
            return ['type' => 'fail', 'mes' => ['E-Mail已被注册'], 'debug' => $user_exist];
        }

        $ip = $request->getClientIp();

        $create_user_state = DB::transaction(function () use ($email, $password, $ip) {
            $max_port = User::select('port')->orderBy('port', 'desc')->first();

            return User::create([
                'user_name' => $email,
                'email'     => $email,
                'pass'      => password_hash($password, PASSWORD_DEFAULT),
                'passwd'    => $password,
                'port'      => ($max_port['port']+1),
                'reg_ip'    => $ip
            ]);
        });

        $token = Crypt::encrypt($create_user_state['id'] . '/'. (time()+(3*24*60*60)));


        $response_user_info = [
            'overdue_time'  =>  (time()+(3*24*60*60)),
            'email'         =>  $create_user_state['email'],
        ];


        if($create_user_state){
            return ['type' => 'success', 'token' => $token, 'info' => json_encode($response_user_info)];
        }else{
            return ['type' => 'error', 'mes' => '注册失败，未知错误'];
        }
    }
}
