<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CleanupGameTempTable extends Command
{
    protected $signature = 'cleanup:gameTemp';
    protected $description = 'Delete entries older than 10 minutes in the gameTemp table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $twentyMinutesAgo = Carbon::now()->subMinutes(10);

        DB::table('gameTemp')
            ->where('timestamp', '<', $twentyMinutesAgo)
            ->delete();

        $this->info('Deleted entries older than 10 minutes in gameTemp table.');
    }
}
