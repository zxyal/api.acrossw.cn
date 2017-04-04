<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use App\User;

class AdminController extends Controller
{

    public function verify(Request $request)
    {
        $id = $request->get('user_id');

        if($id){
            $is_admin =  User::where('id',$id)->select('is_admin')->first();
            return ['type' => 'success', 'mes' => $is_admin];
        }else{
            return ['type' => 'fail', 'mes' => 'not_user_id'];
        }

    }

    /**
     * 套餐
     * @return array
     */
    public function package()
    {
        $package = Package::where(['status' => 1])->get();

        if(empty($package)){
            return ['type' => 'fail', 'data' => ''];
        }else{
            return ['type' => 'success', 'data' => $package];
        }
    }

    /**
     * 新的套餐
     * @param Request $request
     * @return array
     */
    public function create_package(Request $request)
    {
        $title          = $request->get('title');
        $explain_text   = $request->get('explain_text');
        $transfer       = $request->get('transfer', 0);
        $type           = $request->get('type', 0);
        $amount           = $request->get('amount', 0);
        $price           = $request->get('price', 0);

        $id = Package::create([
            'title'         =>  $title,
            'explain_text'  =>  $explain_text,
            'transfer'      =>  $transfer,
            'type'          =>  $type,
            'amount'        =>  $amount,
            'price'         =>  $price
        ]);

        if($id){
            return ['type' => 'success', 'data' => '创建成功'];
        }else{
            return ['type' => 'fail', 'data' => '失败~'];
        }
    }

    /**
     * 删除套餐
     * @param Request $request
     * @return array
     */
    public function delete_package(Request $request)
    {
        $id = $request->get('id');

        $change_line = Package::where('id', $id)->update(['status' => 0]);

        if($change_line){
            return ['type' => 'success', 'data' => '更新成功'];
        }else{
            return ['type' => 'fail', 'data' => '失败~'];
        }
    }
}
