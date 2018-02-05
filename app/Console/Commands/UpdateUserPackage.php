<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\User;

class UpdateUserPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:User transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新流量';

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
        $num = User::update([
            'u'               => 0,
            'd'               => 0,
            'transfer_enable' => 5000 * 1024 * 1024 * 1024
        ]);

        echo '更新了'.$num;
    }
}
