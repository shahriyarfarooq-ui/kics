<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Http;

class EmployeeImportService
{
    protected string $url = 'https://kics.uet.edu.pk/web_api/get_employees';
    protected string $departmentsUrl = 'https://kics.uet.edu.pk/web_api/get_departments';
    protected string $loginUrl = 'https://kics.uet.edu.pk/web/login';
    protected ?string $username;
    protected ?string $password;

    public function __construct()
    {
        $this->username = env('KICS_USERNAME');
        $this->password = env('KICS_PASSWORD');
    }

    public function import(): array
    {
        $cookieJar = new \GuzzleHttp\Cookie\CookieJar();
        $client = Http::withOptions(['cookies' => $cookieJar])->timeout(60);

        // If credentials provided, attempt to login first and preserve cookies
        if ($this->username && $this->password) {
            // GET login page to capture CSRF token and form details
            $loginPageResp = $client->get($this->loginUrl);
            $loginPageBody = $loginPageResp->body();
            // debug dump of login page HTML for inspection
            try {
                file_put_contents(storage_path('app/kics_login_page.html'), $loginPageBody);
            } catch (\Throwable $e) {
                // ignore write failures
            }

            // parse form action (fallback to loginUrl)
            $action = $this->loginUrl;
            if (preg_match('/<form[^>]*action=["\']([^"\']+)["\']/i', $loginPageBody, $m)) {
                $action = $m[1];
                if (strpos($action, 'http') !== 0) {
                    $parts = parse_url($this->loginUrl);
                    $schemeHost = $parts['scheme'] . '://' . $parts['host'];
                    if (!empty($parts['port'])) {
                        $schemeHost .= ':' . $parts['port'];
                    }
                    if (strpos($action, '/') === 0) {
                        // root-relative
                        $action = $schemeHost . $action;
                    } else {
                        // relative to login URL
                        $action = rtrim(dirname($this->loginUrl), '/') . '/' . ltrim($action, '/');
                    }
                }
            }

            // collect input fields
            $formInputs = [];
            if (preg_match_all('/<input[^>]+>/i', $loginPageBody, $inputs)) {
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

            // detect username and password field names
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
            $usernameField = $usernameField ?? 'username';
            $passwordField = $passwordField ?? 'password';

            $formInputs[$usernameField] = $this->username;
            $formInputs[$passwordField] = $this->password;
            // ensure redirect present
            if (! isset($formInputs['redirect'])) {
                $formInputs['redirect'] = $this->url;
            }

            $loginResp = $client->asForm()->post($action, $formInputs);
            // debug dump of login response
            try {
                file_put_contents(storage_path('app/kics_login_response.html'), $loginResp->body());
            } catch (\Throwable $e) {
            }

            if (! $loginResp->successful() && ! in_array($loginResp->status(), [200, 302, 303])) {
                throw new \RuntimeException('Failed to authenticate to KICS: HTTP ' . $loginResp->status());
            }
        }

        $response = $client->get($this->url);
        $body = $response->body();

        if (! $response->successful()) {
            throw new \RuntimeException('Employee API request failed: ' . $response->status());
        }

        $contentType = $response->header('Content-Type', '');
        if (! str_contains(strtolower($contentType), 'xml') && strpos(trim($body), '<?xml') !== 0) {
            throw new \RuntimeException(
                'Employee API did not return XML. Content-Type=' . $contentType . '. First 500 chars: ' . substr($body, 0, 500)
            );
        }

        // 1. Strip script tags
        $body = preg_replace('/<script\b[^>]*>/i', '', $body);
        $body = preg_replace('/<\/script>/i', '', $body);

        // 2. Escape stray "&" that aren't already part of a valid XML entity
        //    (&amp; &lt; &gt; &quot; &apos; or numeric &#123; / &#x1F;)
        $body = preg_replace('/&(?!amp;|lt;|gt;|quot;|apos;|#\d+;|#x[0-9A-Fa-f]+;)/', '&amp;', $body);

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($body, \SimpleXMLElement::class, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NOCDATA);

        if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            throw new \RuntimeException(
                'Failed to parse employee XML: ' . json_encode(array_map(fn($e) => trim($e->message) . ' (line ' . $e->line . ')', $errors))
            );
        }

        if (!isset($xml->employees->employee)) {
            throw new \RuntimeException(
                'Parsed XML but could not find employees->employee. Root element: '
                . $xml->getName() . '. First 500 chars: ' . substr($body, 0, 500)
            );
        }

        $count = 0;
        foreach ($xml->employees->employee as $emp) {
            if (empty((string) $emp->id)) {
                continue;
            }

            Employee::updateOrCreate(
                ['emp_id' => (int) $emp->id],
                [
                    'campus_id'     => (int) $emp->campus_id ?: null,
                    'department_id' => (int) $emp->department_id ?: null,
                    'name'          => (string) $emp->name,
                    'complete_name' => (string) $emp->complete_name,
                    'prefix'        => (string) $emp->prefix,
                    'father_name'   => (string) $emp->father_name,
                    'job_title'     => trim((string) $emp->job_title),
                    'department'    => (string) $emp->department,
                    'campus'        => (string) $emp->campus,
                    'manager'       => (string) $emp->manager,
                    'coach'         => (string) $emp->coach,
                    'work_phone'    => (string) $emp->work_phone,
                    'work_email'    => (string) $emp->work_email,
                    'mobile_phone'  => (string) $emp->mobile_phone,
                    'state'         => (string) $emp->state,
                    'active'        => strtolower((string) $emp->active) === 'true',
                    'is_active'     => strtolower((string) $emp->is_active) === 'true',
                    'joining_date'  => (string) $emp->joining_date ?: null,
                    'experience'    => (float) $emp->experience,
                    'type'          => (string) $emp->type,
                ]
            );
            $count++;
        }

        return ['imported' => $count];
    }

    /**
     * Fetch departments from KICS and return parsed count and items.
     * Does not persist to database.
     */
    public function importDepartments(): array
    {
        $cookieJar = new \GuzzleHttp\Cookie\CookieJar();
        $client = Http::withOptions(['cookies' => $cookieJar])->timeout(60);

        if ($this->username && $this->password) {
            // reuse the same login flow as import()
            $loginPageResp = $client->get($this->loginUrl);
            $loginPageBody = $loginPageResp->body();

            // parse form action
            $action = $this->loginUrl;
            if (preg_match('/<form[^>]*action=["\']([^"\']+)["\']/i', $loginPageBody, $m)) {
                $action = $m[1];
                if (strpos($action, 'http') !== 0) {
                    $parts = parse_url($this->loginUrl);
                    $schemeHost = $parts['scheme'] . '://' . $parts['host'];
                    if (!empty($parts['port'])) {
                        $schemeHost .= ':' . $parts['port'];
                    }
                    if (strpos($action, '/') === 0) {
                        $action = $schemeHost . $action;
                    } else {
                        $action = rtrim(dirname($this->loginUrl), '/') . '/' . ltrim($action, '/');
                    }
                }
            }

            // collect inputs
            $formInputs = [];
            if (preg_match_all('/<input[^>]+>/i', $loginPageBody, $inputs)) {
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
            $usernameField = $usernameField ?? 'username';
            $passwordField = $passwordField ?? 'password';

            $formInputs[$usernameField] = $this->username;
            $formInputs[$passwordField] = $this->password;
            if (! isset($formInputs['redirect'])) {
                $formInputs['redirect'] = $this->departmentsUrl;
            }

            $loginResp = $client->asForm()->post($action, $formInputs);
            if (! $loginResp->successful() && ! in_array($loginResp->status(), [200, 302, 303])) {
                throw new \RuntimeException('Failed to authenticate to KICS: HTTP ' . $loginResp->status());
            }
        }

        $response = $client->get($this->departmentsUrl);
        $body = $response->body();

        if (! $response->successful()) {
            throw new \RuntimeException('Departments API request failed: ' . $response->status());
        }

        $contentType = $response->header('Content-Type', '');
        if (! str_contains(strtolower($contentType), 'xml') && strpos(trim($body), '<?xml') !== 0) {
            throw new \RuntimeException('Departments API did not return XML. Content-Type=' . $contentType . '.');
        }

        // sanitize and parse XML like import()
        $body = preg_replace('/<script\b[^>]*>/i', '', $body);
        $body = preg_replace('/<\/script>/i', '', $body);
        $body = preg_replace('/&(?!amp;|lt;|gt;|quot;|apos;|#\d+;|#x[0-9A-Fa-f]+;)/', '&amp;', $body);

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($body, \SimpleXMLElement::class, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NOCDATA);
        if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            throw new \RuntimeException('Failed to parse departments XML: ' . json_encode(array_map(fn($e) => trim($e->message), $errors)));
        }

        $items = [];
        if (isset($xml->departments->department)) {
            foreach ($xml->departments->department as $d) {
                $kicsId = (int) $d->id;
                $name = (string) $d->name;
                $code = (string) ($d->code ?? '');

                Department::updateOrCreate(
                    ['kics_id' => $kicsId],
                    ['name' => $name, 'code' => $code]
                );

                $items[] = ['id' => $kicsId, 'name' => $name, 'code' => $code];
            }
        }

        return ['imported' => count($items), 'departments' => $items];
    }
}