<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserPackage;
use App\Package;

class InfoController extends Controller
{
    protected function home (Request $request)
    {
        $user_id = $request->get('user_id');

        if(empty($user_id)){
            return ['type' => 'error', 'data' => 'not_user_id'];
        }

        $response = User::where('id', $user_id)
            ->select('transfer_enable','t','u','d','port', 'method','passwd', 'is_admin')
            ->first();

        $UserPackage = UserPackage::where(['user_id' => $user_id])
            ->select('package_id','progress')
            ->first();

        if($UserPackage){
            $Package = Package::where(['id' => $UserPackage['package_id']],['status' => 1])
                ->select('title', 'transfer', 'type')
                ->first();

            $response['user_package'] = $UserPackage;
            $response['package'] = $Package;
        }else{
            $response['package'] = 'no';
        }

        if($response){
            return ['type' => 'success', 'data' => $response];
        }else{
            return ['type' => 'error', 'data' => 'not_info'];
        }


    }
}
