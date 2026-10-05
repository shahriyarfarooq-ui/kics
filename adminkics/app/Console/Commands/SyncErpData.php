<?php

namespace App\Console\Commands;

use App\Services\ErpSyncService;
use Illuminate\Console\Command;

class SyncErpData extends Command
{
    protected $signature = 'erp:sync';
    protected $description = 'Sync ERP departments and projects from KICS API';

    public function handle(ErpSyncService $service)
    {
        $result = $service->sync();
        $this->info("ERP sync complete: {$result['departments']} departments, {$result['projects']} projects.");
    }
}
