<?php

namespace App\Console\Commands;

use App\Services\EmployeeImportService;
use Illuminate\Console\Command;

class ImportDepartments extends Command
{
    protected $signature = 'departments:import';
    protected $description = 'Import departments from KICS API (does not persist yet)';

    public function handle(EmployeeImportService $service)
    {
        $result = $service->importDepartments();
        $this->info("Fetched {$result['imported']} departments.");
        foreach ($result['departments'] as $d) {
            $this->line("- {$d['id']}: {$d['name']}");
        }
    }
}
