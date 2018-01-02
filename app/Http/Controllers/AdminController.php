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

        if ($id) {
            $is_admin = User::where('id', $id)->select('is_admin')->first();
            return ['type' => 'success', 'mes' => $is_admin];
        } else {
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

        if (empty($package)) {
            return ['type' => 'fail', 'data' => ''];
        } else {
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
        $title        = $request->get('title');
        $explain_text = $request->get('explain_text');
        $transfer     = $request->get('transfer', 0);
        $type         = $request->get('type', 0);
        $amount       = $request->get('amount', 0);
        $price        = $request->get('price', 0);

        $id = Package::create([
            'title'        => $title,
            'explain_text' => $explain_text,
            'transfer'     => $transfer,
            'type'         => $type,
            'amount'       => $amount,
            'price'        => $price
        ]);

        if ($id) {
            return ['type' => 'success', 'data' => '创建成功'];
        } else {
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

        if ($change_line) {
            return ['type' => 'success', 'data' => '更新成功'];
        } else {
            return ['type' => 'fail', 'data' => '失败~'];
        }
    }

    public function user(Request $request)
    {
        $user_all = User::orderBy('t', 'desc')->get();

        foreach ($user_all as $k => $v) {
            $user_all[$k]['t']               = date('Y-m-d H:i:s', $v->t);
            $user_all[$k]['u']               = round($v->u / (1024 * 1024), 2);
            $user_all[$k]['d']               = round($v->d / (1024 * 1024), 2);
            $user_all[$k]['used']            = round($v->u + $v->d);
            $user_all[$k]['transfer_enable'] = round($v->transfer_enable / (1024 * 1024), 2);
        }

        if (empty($user_all)) {
            return ['type' => 'fail', 'data' => ''];
        } else {
            return ['type' => 'success', 'data' => $user_all];
        }
    }
}
