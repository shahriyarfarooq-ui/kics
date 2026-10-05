<?php

namespace App\Services;

use App\Models\ErpDepartment;
use App\Models\ErpProject;
use Illuminate\Support\Facades\Http;

class ErpSyncService
{
    protected string $departmentsUrl = 'https://kics.uet.edu.pk/web_api/get_departments';
    protected string $loginUrl = 'https://kics.uet.edu.pk/web/login';
    protected ?string $username;
    protected ?string $password;

    public function __construct()
    {
        $this->username = env('KICS_USERNAME');
        $this->password = env('KICS_PASSWORD');
    }

    public function sync(): array
    {
        $cookieJar = new \GuzzleHttp\Cookie\CookieJar();
        $client = Http::withOptions(['cookies' => $cookieJar])->timeout(120);

        if ($this->username && $this->password) {
            $this->login($client);
        }

        $response = $client->get($this->departmentsUrl);
        if (! $response->successful()) {
            throw new \RuntimeException('ERP departments API request failed: ' . $response->status());
        }

        $body = $response->body();
        $body = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $body);
        $body = preg_replace('/&(?!amp;|lt;|gt;|quot;|apos;|#\d+;|#x[0-9A-Fa-f]+;)/', '&amp;', $body);

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($body);
        if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            throw new \RuntimeException('ERP departments XML parse failed: ' . json_encode(array_map(fn($e) => trim($e->message), $errors)));
        }

        $departmentNodes = [];
        if (isset($xml->departments->department)) {
            $departmentNodes = $xml->departments->department;
        } elseif (isset($xml->department)) {
            $departmentNodes = $xml->department;
        }

        if (empty($departmentNodes)) {
            throw new \RuntimeException('ERP departments XML did not contain any department nodes.');
        }

        $departmentCount = 0;
        $projectCount = 0;

        foreach ($departmentNodes as $departmentNode) {
            $deptData = [
                'kics_id' => (int) $departmentNode->id,
                'campus_id' => (int) $departmentNode->campus_id ?: null,
                'parent_department_id' => (int) $departmentNode->parent_department_id ?: null,
                'name' => (string) $departmentNode->name,
                'complete_name' => (string) $departmentNode->complete_name,
                'parent_department' => (string) $departmentNode->parent_department,
                'manager' => (string) $departmentNode->manager,
                'campus' => (string) $departmentNode->campus,
                'active' => strtolower((string) $departmentNode->active) === 'true',
                'dept_code' => (string) $departmentNode->dept_code,
                'dept_type' => (string) $departmentNode->dept_type,
                'department_type' => (string) $departmentNode->department_type,
                'web_department_name' => (string) $departmentNode->web_department_name,
                'web_detail_description' => (string) $departmentNode->web_detail_description,
                'vision' => (string) $departmentNode->vision,
                'mission' => (string) $departmentNode->mission,
                'appraisals_to_process' => (string) $departmentNode->appraisals_to_process,
            ];

            $erpDepartment = ErpDepartment::updateOrCreate(
                ['kics_id' => $deptData['kics_id']],
                $deptData
            );
            $departmentCount++;

            if (isset($departmentNode->projects->project)) {
                foreach ($departmentNode->projects->project as $projectNode) {
                    $projectData = [
                        'kics_id' => (int) $projectNode->id,
                        'campus_id' => (int) $projectNode->campus_id ?: null,
                        'department_kics_id' => (int) $projectNode->department_id ?: null,
                        'erp_department_id' => $erpDepartment->id,
                        'name' => (string) $projectNode->name,
                        'campus' => (string) $projectNode->campus,
                        'project_manager' => (string) $projectNode->project_manager,
                        'project_coordinator' => (string) $projectNode->project_coordinator,
                        'project_sponser' => (string) $projectNode->project_sponser,
                        'customer' => (string) $projectNode->customer,
                        'project_states' => (string) $projectNode->project_states,
                        'project_type' => (string) $projectNode->project_type,
                        'system_generated' => strtolower((string) $projectNode->system_generated) === 'true',
                        'active' => strtolower((string) $projectNode->active) === 'true',
                    ];

                    ErpProject::updateOrCreate([
                        'kics_id' => $projectData['kics_id'],
                    ], $projectData);
                    $projectCount++;
                }
            }
        }

        return ['departments' => $departmentCount, 'projects' => $projectCount];
    }

    protected function login($client): void
    {
        $page = $client->get($this->loginUrl)->body();
        $action = $this->loginUrl;
        if (preg_match('/<form[^>]*action=["\']([^"\']+)["\']/i', $page, $m)) {
            $action = $m[1];
            if (strpos($action, 'http') !== 0) {
                $parts = parse_url($this->loginUrl);
                $port = $parts['port'] ?? null;
                $action = $parts['scheme'] . '://' . $parts['host'] . ($port ? ':' . $port : '') . '/' . ltrim($action, '/');
            }
        }

        $formInputs = [];
        if (preg_match_all('/<input[^>]+>/i', $page, $inputs)) {
            foreach ($inputs[0] as $tag) {
                if (preg_match('/name=["\']([^"\']+)["\']/i', $tag, $n)) {
                    $name = $n[1];
                    $value = '';
                    if (preg_match('/value=["\']([^"\']*)["\']/i', $tag, $v)) {
                        $value = $v[1];
                    }
                    $formInputs[$name] = $value;
                }
            }
        }

        $usernameField = null;
        $passwordField = null;
        foreach (array_keys($formInputs) as $fname) {
            if ($usernameField === null && preg_match('/user|email|login/i', $fname)) {
                $usernameField = $fname;
            }
            if ($passwordField === null && preg_match('/pass/i', $fname)) {
                $passwordField = $fname;
            }
        }
        $formInputs[$usernameField ?? 'username'] = $this->username;
        $formInputs[$passwordField ?? 'password'] = $this->password;
        $formInputs['redirect'] = $this->departmentsUrl;

        $loginResp = $client->asForm()->post($action, $formInputs);
        if (! $loginResp->successful() && ! in_array($loginResp->status(), [200, 302, 303])) {
            throw new \RuntimeException('KICS login failed: ' . $loginResp->status());
        }
    }
}
