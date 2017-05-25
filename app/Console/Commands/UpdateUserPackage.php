<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UpdateUserPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update User Package Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $package = DB::table('package')->select('id', 'transfer', 'type')->where('status', 1)->get();

        foreach ($package as $k => $v) {
            $package_array[$v->id] = [
                'transfer' => $v->transfer,
                'type' => $v->type,
            ];
        }

        $user_package = DB::table('user_package')->where('progress', 2)->get();

        //当前月份
        $now_months = date('m', time());

        foreach ($user_package as $k => $v) {

            $current_package = $package_array[$v->package_id];

            if ($current_package['type'] == 1) {

                if ($now_months != $v->last_update) {

                    DB::table('user_package')->where('id', $v->id)->update([
                        'last_update' => $now_months,
                    ]);

                    DB::table('user')->where('id', $v->user_id)->update([
                        'u' => 0,
                        'd' => 0,
                        'transfer_enable' => $current_package['transfer'] * 1024 * 1024 * 1024
                    ]);
                }

            }
        }

        Log::info('run time:' . date('Y-m-d H:i:s', time()));
    }
}
