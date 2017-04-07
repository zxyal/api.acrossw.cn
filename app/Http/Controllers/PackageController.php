<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UserPackage;
use App\Package;
use App\Custom\Pay;
use Illuminate\Support\Facades\URL;

class PackageController extends Controller
{

    public function index()
    {
        $package =  Package::select('id', 'transfer', 'title', 'price', 'type')->where('status', 1)->get();

        if($package){
            return ['type' => 'success', 'data' => $package];
        }else{
            return ['type' => 'fail', 'data' => '未获取到套餐信息'];
        }
    }


    public function buy(Request $request)
    {
        $package_id       = $request->get('package');
        $user_id    = $request->get('user_id');
//
//        $package_exits = UserPackage::where('user_id', $user_id)->first();//i exits
//
//        if($package_exits){
//
//        }else{
//            UserPackage::create([
//                'user_id'       =>  $user_id,
//                'package_id'    =>  $package_id,
//                'buy_time'      =>  time()
//            ]);
//        }

        //$pay = new Pay();

        return ['type' => 'success', 'data' => Url('/package/buy/jump').'?r='.rand(100,999)];
    }

    public function jump()
    {
        $pay = new Pay();

        echo $pay->create();
    }
}
