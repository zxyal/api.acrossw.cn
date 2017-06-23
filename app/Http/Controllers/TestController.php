<?php

namespace App\Http\Controllers;

use App\Custom\Pay;
use App\Mail\Notify;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function test()
    {
        $package = DB::table('package')->select('id', 'transfer', 'type')->where('status', 1)->get();

        foreach ($package as $k => $v) {
            $package_array[$v->id] = [
                'transfer' => $v->transfer,
                'type'     => $v->type,
            ];
        }

        $user_package = DB::table('user_package')->where('progress', 2)->get();

        foreach ($user_package as $k => $v) {
            $now_time        = time();
            $current_package = $package_array[$v->package_id];

            if ($current_package['type'] == 1) {

                $now_months         = floor(($now_time - $v->buy_time) / 86400 / 30);
                $last_update_months = floor(($v->last_update - $v->buy_time) / 86400 / 30);

                if ($now_months > $last_update_months) {
                    DB::table('user_package')->where('id', $v->id)->update([
                        'last_update' => time(),
                    ]);
                    DB::table('user')->where('id', $v->user_id)->update([
                        'u'               => 0,
                        'd'               => 0,
                        'transfer_enable' => $current_package['transfer'] * 1024 * 1024 * 1024
                    ]);

                    DB::table('package_update_log')->insert([
                        'user_id'            => $v->user_id,
                        'package_id'         => $v->id,
                        'now_months'         => $now_months,
                        'last_update_months' => $last_update_months,
                        'created_time'       => time()
                    ]);
                } else if ($now_months >= $v->buy_number || $last_update_months >= $v->buy_number) {
                    //过期
                    DB::table('user_package')->where('id', $v->id)->update([
                        'progress' => 0,
                    ]);

                    DB::table('package_update_log')->insert([
                        'user_id'            => $v->user_id,
                        'package_id'         => $v->id,
                        'now_months'         => $now_months,
                        'last_update_months' => $last_update_months,
                        'created_time'       => time()
                    ]);
                }

            } else if ($current_package['type'] == 2) {
                $one_year = (86400 * 30 * 12);
            }
        }
    }

    public function email()
    {
        $userInfo = [
            'name' => '赵旭阳',
        ];

        Mail::to('1170013162@qq.com')->send(new Notify($userInfo));
    }
}
