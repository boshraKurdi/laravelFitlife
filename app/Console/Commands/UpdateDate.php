<?php

namespace App\Console\Commands;

use App\Observers\GoalPlanObserver;
use Illuminate\Console\Command;

class UpdateDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $observer = new GoalPlanObserver();
        $observer->update();
    }
}
