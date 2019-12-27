<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WildCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wild:card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migration & User Table seed & Local Server run in one commend.';

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
         // create users table 
        if (!Schema::hasTable('users')) {
            Artisan::call('migrate', array('--path' => 'database/migrations', '--force' => true));
        }
        // create users table seed
        if (Schema::hasTable('users')) {
            if (DB::table('users')->count() == 0) {
                Artisan::call('db:seed');
            }
        }

        
        // create tasks table seed
        if (Schema::hasTable('tasks')) {
            if (DB::table('tasks')->count() == 0) {
                Artisan::call('db:seed');
            }
        }
        Artisan::call('serve --port=8080');
        echo 'Server run...';
    }
}
