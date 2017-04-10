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
        $start = microtime(true);

        $package = DB::table('package')->select('transfer', 'type')->where('status', 1)->get();

        $user_package = DB::table('user_package')->where(['progress', 2])->get();

        $end = microtime(true);

        $used_time = $start - $end;

        Log::info('Used time:'.$used_time.'/n debug:'.var_export($package));

    }
}
