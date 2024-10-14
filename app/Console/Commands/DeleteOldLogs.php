<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DeleteOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oldLogs:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Old logs of lms_logs table';

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
       $lmsLogs = DB::table('lms_logs')->where('created_at',"<",Carbon::now()->subMonths(1))->take(2000)->delete();
       
       $this->info(now().": ✔︎ LMS Logs cleared {$lmsLogs} rows");

       $activityLogs = DB::table('activity_log')->where('created_at',"<",Carbon::now()->subMonths(2))->take(2000)->delete();
       $this->info(now().": ✔︎ Activity Logs cleared {$activityLogs} rows");

    }
}
