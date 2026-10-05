<?php

namespace App\Http\Controllers;

use App\Models\KicGroupProjectlist;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\People;
use App\Models\Publication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class KicGroupProjectlistController extends Controller
{
    // Show all projects (one page)
    public function index()
    {
        $projects = KicGroupProjectlist::paginate(5, ['*'], 'page', (int) request()->query('page', 1));
        $groups = Group::orderBy('group_name')->get();
        return view('admin.project_list', compact('projects', 'groups'));
    }

    // Store a new project
    public function store(Request $request)
    {
        try {
            $request->validate([
                'projectlist_Name' => 'required|string|max:255',
                'projectlist_description' => 'required|string',
                'projectlist_small_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'group_id' => 'required|integer',
                'subgroup_id' => 'nullable|integer',
                'sub_site_id' => 'nullable|integer',
                'code' => 'nullable|string|max:255',
                'project_category' => 'nullable|string|max:255',
                'is_completed' => 'nullable|boolean',
                'fundedby' => 'nullable|string',
                'inactive' => 'nullable|boolean',
            ]);

            $input = $request->all();

            // Image upload
            if ($request->hasFile('projectlist_small_picture')) {
                $file = $request->file('projectlist_small_picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/projects'), $filename);
                $input['projectlist_small_picture'] = $filename;
            }

            KicGroupProjectlist::create($input);

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update project
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'projectlist_Name' => 'required|string|max:255',
                'projectlist_description' => 'required|string',
                'projectlist_small_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'group_id' => 'required|integer',
                'subgroup_id' => 'nullable|integer',
                'sub_site_id' => 'nullable|integer',
                'code' => 'nullable|string|max:255',
                'project_category' => 'nullable|string|max:255',
                'is_completed' => 'nullable|boolean',
                'fundedby' => 'nullable|string',
                'inactive' => 'nullable|boolean',
            ]);

            $project = KicGroupProjectlist::findOrFail($id);
            $input = $request->all();

            // Image upload
            if ($request->hasFile('projectlist_small_picture')) {
                $file = $request->file('projectlist_small_picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/projects'), $filename);
                $input['projectlist_small_picture'] = $filename;
            } else {
                unset($input['projectlist_small_picture']);
            }

            $project->update($input);

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete project
    public function destroy($id)
    {
        $project = KicGroupProjectlist::findOrFail($id);
        $project->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully!'
            ]);
        }

        return redirect()->route('project.list')->with('success', 'Project deleted successfully!');
    }

    // Edit (for modal)
    public function edit($id)
    {
        $project = KicGroupProjectlist::findOrFail($id);
        return response()->json($project);
    }

    public function manage(KicGroupProjectlist $project)
    {
        $project->load('group');

        $sections = $this->projectManageSections($project);
        $sectionRecords = [];

        foreach ($sections as $key => $section) {
            if (!Schema::hasTable($section['table'])) {
                $sectionRecords[$key] = collect();
                continue;
            }

            $sectionRecords[$key] = $this->scopedSectionQuery($project, $section)
                ->orderByDesc($section['primary_key'])
                ->get();
        }

        return view('admin.project_manage', compact('project', 'sections', 'sectionRecords'));
    }

    public function storeManageRecord(Request $request, KicGroupProjectlist $project, string $section)
    {
        $sectionConfig = $this->projectManageSection($project, $section);
        abort_if(!Schema::hasTable($sectionConfig['table']), 404);

        $data = $this->projectManagePayload($request, $sectionConfig);
        $data[$sectionConfig['scope_column']] = $this->sectionScopeValue($project, $sectionConfig);

        DB::table($sectionConfig['table'])->insert($data);

        return redirect()
            ->route('project.manage', $project)
            ->with('success', $sectionConfig['title'] . ' record added successfully.');
    }

    public function updateManageRecord(Request $request, KicGroupProjectlist $project, string $section, string $record)
    {
        $sectionConfig = $this->projectManageSection($project, $section);
        abort_if(!Schema::hasTable($sectionConfig['table']), 404);

        $existing = $this->scopedSectionQuery($project, $sectionConfig)
            ->where($sectionConfig['primary_key'], $record)
            ->first();
        abort_if(!$existing, 404);

        $data = $this->projectManagePayload($request, $sectionConfig, $existing);

        DB::table($sectionConfig['table'])
            ->where($sectionConfig['primary_key'], $record)
            ->update($data);

        return redirect()
            ->route('project.manage', $project)
            ->with('success', $sectionConfig['title'] . ' record updated successfully.');
    }

    public function destroyManageRecord(KicGroupProjectlist $project, string $section, string $record)
    {
        $sectionConfig = $this->projectManageSection($project, $section);
        abort_if(!Schema::hasTable($sectionConfig['table']), 404);

        $deleted = $this->scopedSectionQuery($project, $sectionConfig)
            ->where($sectionConfig['primary_key'], $record)
            ->delete();

        abort_if(!$deleted, 404);

        return redirect()
            ->route('project.manage', $project)
            ->with('success', $sectionConfig['title'] . ' record deleted successfully.');
    }

    public function delete($id)
    {
        return $this->destroy($id);
    }

    private function projectManageSection(KicGroupProjectlist $project, string $section): array
    {
        $sections = $this->projectManageSections($project);
        abort_if(!isset($sections[$section]), 404);

        return $sections[$section];
    }

    private function projectManageSections(KicGroupProjectlist $project): array
    {
        $peopleOptions = People::orderBy('fname')
            ->orderBy('lname')
            ->get(['people_id', 'fname', 'lname'])
            ->mapWithKeys(fn ($person) => [
                $person->people_id => trim($person->fname . ' ' . $person->lname) ?: ('Person #' . $person->people_id),
            ])
            ->all();

        $publicationOptions = Publication::orderByDesc('publication_year')
            ->get(['publication_id', 'publication_title', 'publication_year'])
            ->mapWithKeys(fn ($publication) => [
                $publication->publication_id => Str::limit(strip_tags($publication->publication_title), 90)
                    . ($publication->publication_year ? ' (' . $publication->publication_year . ')' : ''),
            ])
            ->all();

        $subgroupOptions = Schema::hasTable('kic_subgroup')
            ? DB::table('kic_subgroup')
                ->where('group_id', $project->group_id)
                ->orderBy('subgroup_name')
                ->pluck('subgroup_name', 'subgroup_id')
                ->all()
            : [];

        return [
            'content' => [
                'title' => 'Content',
                'table' => 'project_content',
                'primary_key' => 'id',
                'scope_column' => 'project_id',
                'scope' => 'project',
                'fields' => [
                    ['name' => 'tpr', 'label' => 'TPR', 'type' => 'textarea'],
                    ['name' => 'team', 'label' => 'Team Content', 'type' => 'textarea'],
                    ['name' => 'publications', 'label' => 'Publications Content', 'type' => 'textarea'],
                    ['name' => 'aad', 'label' => 'Activities And Deliverables', 'type' => 'textarea'],
                    ['name' => 'usecase', 'label' => 'Use Case Content', 'type' => 'textarea'],
                    ['name' => 'downloads', 'label' => 'Downloads Content', 'type' => 'textarea'],
                ],
            ],
            'team' => [
                'title' => 'Team',
                'table' => 'project_team',
                'primary_key' => 'id',
                'scope_column' => 'project_id',
                'scope' => 'project',
                'fields' => [
                    ['name' => 'member_id', 'label' => 'Member', 'type' => 'select', 'options' => $peopleOptions, 'required' => true],
                    ['name' => 'role', 'label' => 'Role', 'type' => 'text', 'required' => true],
                ],
            ],
            'plan' => [
                'title' => 'Plan',
                'table' => 'project_plan',
                'primary_key' => 'id',
                'scope_column' => 'project_id',
                'scope' => 'project',
                'fields' => [
                    ['name' => 'task', 'label' => 'Task', 'type' => 'text', 'required' => true],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ['name' => 'assigned_to', 'label' => 'Assigned To', 'type' => 'text'],
                    ['name' => 'start_date', 'label' => 'Start Date', 'type' => 'date'],
                    ['name' => 'expected', 'label' => 'Expected', 'type' => 'text'],
                ],
            ],
            'phases' => [
                'title' => 'Phases',
                'table' => 'project_phases',
                'primary_key' => 'id',
                'scope_column' => 'projectlist_id',
                'scope' => 'project',
                'fields' => [
                    ['name' => 'phase', 'label' => 'Phase', 'type' => 'text', 'required' => true],
                    ['name' => 'start_date', 'label' => 'Start Date', 'type' => 'date'],
                    ['name' => 'end_date', 'label' => 'End Date', 'type' => 'date'],
                    ['name' => 'extended_date', 'label' => 'Extended Date', 'type' => 'date'],
                    ['name' => 'is_complete', 'label' => 'Completed', 'type' => 'checkbox'],
                ],
            ],
            'activities' => [
                'title' => 'Activities',
                'table' => 'project_activities',
                'primary_key' => 'id',
                'scope_column' => 'project_id',
                'scope' => 'project',
                'fields' => [
                    ['name' => 'task', 'label' => 'Task', 'type' => 'text', 'required' => true],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ['name' => 'start_date', 'label' => 'Start Date', 'type' => 'date'],
                    ['name' => 'expected_date', 'label' => 'Expected Date', 'type' => 'date'],
                ],
            ],
            'downloads' => [
                'title' => 'Downloads',
                'table' => 'project_downloads',
                'primary_key' => 'id',
                'scope_column' => 'project_id',
                'scope' => 'project',
                'fields' => [
                    ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ['name' => 'file', 'label' => 'File', 'type' => 'file'],
                    ['name' => 'url', 'label' => 'External URL', 'type' => 'text'],
                ],
            ],
            'publications' => [
                'title' => 'Publications',
                'table' => 'project_publications',
                'primary_key' => 'id',
                'scope_column' => 'project_id',
                'scope' => 'project',
                'fields' => [
                    ['name' => 'publication_id', 'label' => 'Publication', 'type' => 'select', 'options' => $publicationOptions, 'required' => true],
                ],
            ],
            'subgroups' => [
                'title' => 'Subgroups',
                'table' => 'kic_subgroup',
                'primary_key' => 'subgroup_id',
                'scope_column' => 'group_id',
                'scope' => 'group',
                'fields' => [
                    ['name' => 'subgroup_name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                    ['name' => 'subgroup_seqno', 'label' => 'Sequence No', 'type' => 'number'],
                    ['name' => 'subgroup_description', 'label' => 'Description', 'type' => 'textarea'],
                    ['name' => 'subgroup_briefdescription', 'label' => 'Brief Description', 'type' => 'textarea'],
                    ['name' => 'subgroup_projectlist_check', 'label' => 'Project List Enabled', 'type' => 'checkbox'],
                    ['name' => 'subgroup_services_check', 'label' => 'Services Enabled', 'type' => 'checkbox'],
                    ['name' => 'subgroup_rdproject_check', 'label' => 'R&D Project Enabled', 'type' => 'checkbox'],
                ],
            ],
            'rdprojects' => [
                'title' => 'R&D Projects',
                'table' => 'kic_rdproject',
                'primary_key' => 'rdproject_id',
                'scope_column' => 'group_id',
                'scope' => 'group',
                'fields' => [
                    ['name' => 'rdproject_name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                    ['name' => 'rdproject_seqno', 'label' => 'Sequence No', 'type' => 'text'],
                    ['name' => 'rdproject_description', 'label' => 'Description', 'type' => 'textarea'],
                    ['name' => 'rdproject_small_picture', 'label' => 'Small Picture', 'type' => 'file'],
                    ['name' => 'subgroup_id', 'label' => 'Subgroup', 'type' => 'select', 'options' => $subgroupOptions],
                    ['name' => 'sub_site_id', 'label' => 'Sub Site ID', 'type' => 'number'],
                ],
            ],
        ];
    }

    private function scopedSectionQuery(KicGroupProjectlist $project, array $section)
    {
        return DB::table($section['table'])
            ->where($section['scope_column'], $this->sectionScopeValue($project, $section));
    }

    private function sectionScopeValue(KicGroupProjectlist $project, array $section): int
    {
        return $section['scope'] === 'group'
            ? (int) $project->group_id
            : (int) $project->projectlist_id;
    }

    private function projectManagePayload(Request $request, array $section, ?object $record = null): array
    {
        $data = [];

        foreach ($section['fields'] as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'text';

            if ($type === 'file') {
                if ($request->hasFile($name)) {
                    $data[$name] = $this->storeProjectManageFile($request->file($name));
                } elseif (!$record) {
                    $data[$name] = '';
                }
                continue;
            }

            if ($type === 'checkbox') {
                $data[$name] = $request->has($name) ? 1 : 0;
                continue;
            }

            $value = $request->input($name);

            if ($type === 'date' && $value === '') {
                $value = null;
            }

            if (($type === 'number' || $type === 'select') && $value === '') {
                $value = 0;
            }

            $data[$name] = $value ?? ($field['default'] ?? '');
        }

        return $data;
    }

    private function storeProjectManageFile($file): string
    {
        $directory = public_path('uploads/project-management');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = time() . '_' . Str::slug($name) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return $filename;
    }
}
