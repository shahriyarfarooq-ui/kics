<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LegacyAdminController extends Controller
{
    private array $hiddenTables = [
        'cache',
        'cache_locks',
        'failed_jobs',
        'job_batches',
        'jobs',
        'migrations',
        'password_reset_tokens',
        'sessions',
    ];

    public function index()
    {
        $tables = collect($this->modules())->map(function ($module, $key) {
            $table = $module['table'];

            return [
                'key' => $key,
                'name' => $table,
                'label' => $module['title'] ?? $this->label($table),
                'count' => DB::table($table)->count(),
                'primaryKey' => $module['primary_key'] ?? $this->primaryKey($table),
            ];
        });

        return view('admin.legacy.index', compact('tables'));
    }

    public function table(Request $request, string $table)
    {
        $module = $this->module($table);
        $table = $module['table'];
        $this->guardTable($table);

        $columns = $this->columns($table);
        $primaryKey = $module['primary_key'] ?? $this->primaryKey($table);
        $displayColumns = $module['display'] ?? $this->displayColumns($columns, $primaryKey);
        $relations = $module['relations'] ?? [];
        $relationMaps = $this->relationMaps($relations);
        $query = trim((string) $request->query('q', ''));

        $records = DB::table($table)
            ->when($query !== '', function ($builder) use ($columns, $query) {
                $builder->where(function ($search) use ($columns, $query) {
                    foreach ($columns as $column) {
                        if ($this->isSearchable($column)) {
                            $search->orWhere($column->Field, 'like', '%' . $query . '%');
                        }
                    }
                });
            })
            ->when($primaryKey, fn ($builder) => $builder->orderByDesc($primaryKey))
            ->paginate(25, ['*'], 'page', (int) $request->query('page', 1))
            ->withQueryString();

        return view('admin.legacy.table', compact('table', 'module', 'columns', 'primaryKey', 'displayColumns', 'records', 'query', 'relations', 'relationMaps'));
    }

    public function create(string $table)
    {
        $module = $this->module($table);
        $table = $module['table'];
        $this->guardTable($table);

        $columns = $this->editableColumns($table);
        $record = null;
        $primaryKey = $module['primary_key'] ?? $this->primaryKey($table);
        $relations = $module['relations'] ?? [];
        $relationMaps = $this->relationMaps($relations);

        return view('admin.legacy.form', compact('table', 'module', 'columns', 'record', 'primaryKey', 'relations', 'relationMaps'));
    }

    public function store(Request $request, string $table)
    {
        $module = $this->module($table);
        $table = $module['table'];
        $this->guardTable($table);

        $data = $this->payload($request, $table, $module);
        DB::table($table)->insert($data);

        return redirect()
            ->route('admin.legacy.table', $this->moduleKey($module, $table))
            ->with('success', ($module['title'] ?? $this->label($table)) . ' record created.');
    }

    public function edit(string $table, string $id)
    {
        $module = $this->module($table);
        $table = $module['table'];
        $this->guardTable($table);

        $primaryKey = $module['primary_key'] ?? $this->requirePrimaryKey($table);
        $record = DB::table($table)->where($primaryKey, $id)->first();
        abort_if(!$record, 404);

        $columns = $this->editableColumns($table);
        $relations = $module['relations'] ?? [];
        $relationMaps = $this->relationMaps($relations);

        return view('admin.legacy.form', compact('table', 'module', 'columns', 'record', 'primaryKey', 'relations', 'relationMaps'));
    }

    public function update(Request $request, string $table, string $id)
    {
        $module = $this->module($table);
        $table = $module['table'];
        $this->guardTable($table);

        $primaryKey = $module['primary_key'] ?? $this->requirePrimaryKey($table);
        $data = $this->payload($request, $table, $module);

        DB::table($table)->where($primaryKey, $id)->update($data);

        return redirect()
            ->route('admin.legacy.table', $this->moduleKey($module, $table))
            ->with('success', ($module['title'] ?? $this->label($table)) . ' record updated.');
    }

    public function destroy(string $table, string $id)
    {
        $module = $this->module($table);
        $table = $module['table'];
        $this->guardTable($table);

        $primaryKey = $module['primary_key'] ?? $this->requirePrimaryKey($table);
        DB::table($table)->where($primaryKey, $id)->delete();

        return redirect()
            ->route('admin.legacy.table', $this->moduleKey($module, $table))
            ->with('success', ($module['title'] ?? $this->label($table)) . ' record deleted.');
    }

    private function tables(): array
    {
        return collect(DB::select('SHOW TABLES'))
            ->map(fn ($row) => array_values((array) $row)[0])
            ->reject(fn ($table) => in_array($table, $this->hiddenTables, true))
            ->sort()
            ->values()
            ->all();
    }

    private function modules(): array
    {
        $tables = $this->tables();

        $configured = collect(config('admin_modules', []))
            ->filter(fn ($module) => in_array($module['table'] ?? null, $tables, true))
            ->all();

        $configuredTables = collect($configured)->pluck('table')->all();

        $discovered = collect($tables)
            ->reject(fn ($table) => in_array($table, $configuredTables, true))
            ->mapWithKeys(fn ($table) => [
                $table => [
                    'title' => $this->label($table),
                    'table' => $table,
                    'primary_key' => $this->primaryKey($table),
                ],
            ])
            ->all();

        return $configured + $discovered;
    }

    private function module(string $key): array
    {
        $modules = $this->modules();

        if (isset($modules[$key])) {
            return $modules[$key] + ['key' => $key];
        }

        foreach ($modules as $moduleKey => $module) {
            if (($module['table'] ?? null) === $key) {
                return $module + ['key' => $moduleKey];
            }
        }

        abort(404);
    }

    private function moduleKey(array $module, string $table): string
    {
        return $module['key'] ?? $table;
    }

    private function columns(string $table): array
    {
        return DB::select('DESCRIBE `' . str_replace('`', '``', $table) . '`');
    }

    private function editableColumns(string $table): array
    {
        return collect($this->columns($table))
            ->reject(fn ($column) => str_contains(strtolower((string) $column->Extra), 'auto_increment'))
            ->values()
            ->all();
    }

    private function primaryKey(string $table): ?string
    {
        foreach ($this->columns($table) as $column) {
            if ($column->Key === 'PRI') {
                return $column->Field;
            }
        }

        return null;
    }

    private function requirePrimaryKey(string $table): string
    {
        $primaryKey = $this->primaryKey($table);
        abort_if(!$primaryKey, 422, 'This table has no primary key, so records cannot be edited safely.');

        return $primaryKey;
    }

    private function guardTable(string $table): void
    {
        abort_if(!in_array($table, $this->tables(), true) || !Schema::hasTable($table), 404);
    }

    private function payload(Request $request, string $table, array $module): array
    {
        $data = [];
        $relations = $module['relations'] ?? [];

        foreach ($this->editableColumns($table) as $column) {
            $name = $column->Field;

            if ($request->hasFile($name)) {
                $file = $request->file($name);
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $filename .= '.' . $file->getClientOriginalExtension();
                $data[$name] = $file->storeAs('legacy/' . $table, $filename, 'public');
                continue;
            }

            if (!$request->has($name)) {
                $data[$name] = $this->defaultValue($column);
                continue;
            }

            $value = $request->input($name);
            if (isset($relations[$name]) && $value === '') {
                $value = null;
            }

            $data[$name] = $value === null ? $this->defaultValue($column) : $value;
        }

        return $data;
    }

    private function defaultValue(object $column): mixed
    {
        if ($column->Default !== null) {
            return $column->Default;
        }

        return $column->Null === 'YES' ? null : '';
    }

    private function displayColumns(array $columns, ?string $primaryKey): array
    {
        $preferred = collect($columns)
            ->pluck('Field')
            ->filter(fn ($field) => $field === $primaryKey || preg_match('/(^id$|_id$|title|name|subject|email|date|status|order|seq)/i', $field))
            ->take(7)
            ->values()
            ->all();

        return $preferred ?: collect($columns)->pluck('Field')->take(7)->values()->all();
    }

    private function relationMaps(array $relations): array
    {
        $maps = [];

        foreach ($relations as $column => $relation) {
            if (!Schema::hasTable($relation['table'])) {
                continue;
            }

            $labelFields = is_array($relation['label'] ?? null)
                ? $relation['label']
                : [($relation['label'] ?? $relation['key'])];
            $select = array_values(array_unique(array_merge([$relation['key']], $labelFields)));

            $maps[$column] = DB::table($relation['table'])
                ->select($select)
                ->limit(500)
                ->get()
                ->mapWithKeys(fn ($row) => [
                    $row->{$relation['key']} => $this->rowLabel($row, $labelFields, $row->{$relation['key']}),
                ])
                ->all();
        }

        return $maps;
    }

    private function rowLabel(object $record, array $labelFields, mixed $fallback): string
    {
        $text = collect($labelFields)
            ->map(fn ($field) => $record->{$field} ?? null)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->implode(' ');

        return $text !== '' ? $text : (string) $fallback;
    }

    private function isSearchable(object $column): bool
    {
        return preg_match('/char|text|enum|set/i', $column->Type) === 1;
    }

    private function label(string $table): string
    {
        return Str::headline(str_replace(['kic_', 'tbl_'], '', $table));
    }
}
