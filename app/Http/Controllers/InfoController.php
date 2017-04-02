<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class InfoController extends Controller
{
    protected function home (Request $request)
    {
        $id = $request->get('user_id');

        if(empty($id)){
            return ['type' => 'error', 'data' => 'not_user_id'];
        }

        $home_info = User::where('id', $id)->select('transfer_enable','t','u','d','port', 'method','passwd', 'is_admin')->first();

        if($home_info){
            return ['type' => 'success', 'data' => $home_info];
        }else{
            return ['type' => 'error', 'data' => 'not_info'];
        }


    }
}
