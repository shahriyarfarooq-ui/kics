<?php

namespace App\Console\Commands;

use App\Services\EmployeeImportService; // <-- this line is likely missing
use Illuminate\Console\Command;

class ImportEmployees extends Command
{
    protected $signature = 'employees:import';
    protected $description = 'Import employees from KICS API';

    public function handle(EmployeeImportService $service)
    {
        $result = $service->import();
        $this->info("Imported/updated {$result['imported']} employees.");
    }
}