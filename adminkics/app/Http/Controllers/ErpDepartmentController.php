<?php

namespace App\Http\Controllers;

use App\Models\ErpDepartment;
use App\Models\ErpProject;
use App\Services\ErpSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ErpDepartmentController extends Controller
{
    protected function hasVisibleFlag(): bool
    {
        return Schema::hasColumn('erp_department', 'is_visible')
            || Schema::hasColumn('erp_projects', 'is_visible');
    }

    public function index(Request $request)
    {
        $query = ErpDepartment::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('web_department_name', 'like', "%{$search}%")
                  ->orWhere('dept_code', 'like', "%{$search}%")
                  ->orWhere('manager', 'like', "%{$search}%");
            });
        }

        if ($request->filled('visibility') && Schema::hasColumn('erp_department', 'is_visible')) {
            $query->where('is_visible', $request->visibility === 'visible');
        }

        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }

        $departments = $query->withCount('projects')
            ->orderBy('name')
            ->paginate(25, ['*'], 'page', (int) $request->query('page', 1));

        return view('admin.erp_departments.index', compact('departments'));
    }

    public function show(ErpDepartment $erpDepartment)
    {
        $projects = $erpDepartment->projects()
            ->orderBy('name')
            ->paginate(20);

        return view('admin.erp_departments.show', compact('erpDepartment', 'projects'));
    }

    /**
     * EDIT METHOD - Returns department data as JSON for the modal
     */
    public function edit(ErpDepartment $erpDepartment)
    {
        // Return ALL department data including images
        return response()->json($erpDepartment);
    }

    /**
     * UPDATE METHOD - Updates department data
     */
    public function update(Request $request, ErpDepartment $erpDepartment)
    {
        $validated = $request->validate([
            'web_department_name' => 'nullable|string|max:255',
            'web_detail_description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'manager' => 'nullable|string|max:255',
            'dept_code' => 'nullable|string|max:50',
            'admin_notes' => 'nullable|string',
            'active' => 'nullable|boolean',
            'is_visible' => 'nullable|boolean',
        ]);

        foreach (['admin_notes', 'logo', 'cover_image', 'is_visible'] as $field) {
            if (!Schema::hasColumn('erp_department', $field)) {
                unset($validated[$field]);
            }
        }

        $validated['active'] = $request->has('active') ? 1 : 0;

        if (Schema::hasColumn('erp_department', 'is_visible')) {
            $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;
        }

        if (Schema::hasColumn('erp_department', 'logo') && $request->hasFile('logo')) {
            if ($erpDepartment->logo) {
                Storage::disk('public')->delete($erpDepartment->logo);
            }
            $validated['logo'] = $this->uploadImage($request->file('logo'), 'erp/departments/logos');
        }

        if (Schema::hasColumn('erp_department', 'cover_image') && $request->hasFile('cover_image')) {
            if ($erpDepartment->cover_image) {
                Storage::disk('public')->delete($erpDepartment->cover_image);
            }
            $validated['cover_image'] = $this->uploadImage($request->file('cover_image'), 'erp/departments/covers');
        }

        $erpDepartment->update($validated);

        return redirect()
            ->route('admin.erp.departments.show', $erpDepartment)
            ->with('success', 'Department updated successfully.');
    }

    /**
     * UPDATE PROJECT - Updates individual project data
     */
    public function updateProject(Request $request, ErpProject $project)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'project_manager' => 'nullable|string|max:255',
            'project_coordinator' => 'nullable|string|max:255',
            'project_states' => 'nullable|string|max:50',
            'project_type' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'active' => 'nullable|boolean',
            'is_visible' => 'nullable|boolean',
        ]);

        foreach (['admin_notes', 'image', 'description', 'is_visible'] as $field) {
            if (!Schema::hasColumn('erp_projects', $field)) {
                unset($validated[$field]);
            }
        }

        $validated['active'] = $request->has('active') ? 1 : 0;

        if (Schema::hasColumn('erp_projects', 'is_visible')) {
            $validated['is_visible'] = $request->has('is_visible') ? 1 : 0;
        }

        if (Schema::hasColumn('erp_projects', 'image') && $request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = $this->uploadImage($request->file('image'), 'erp/projects');
        }

        $project->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Project updated successfully.');
    }

    /**
     * TOGGLE VISIBILITY - Toggle department/project visibility
     */
    public function toggleVisibility(Request $request, $id = null)
    {
        $requestId = $id ?? $request->input('id');

        $request->validate([
            'id' => 'sometimes|integer',
            'type' => 'required|in:department,project,employee'
        ]);

        $requestId = $requestId ?? $request->input('id');

        if (empty($requestId)) {
            return response()->json([
                'success' => false,
                'message' => 'Department, project, or employee ID is required.'
            ], 422);
        }

        if ($request->type === 'department') {
            $columnName = 'erp_department';
            $model = ErpDepartment::findOrFail($requestId);
        } elseif ($request->type === 'project') {
            $columnName = 'erp_projects';
            $model = ErpProject::findOrFail($requestId);
        } else {
            $columnName = 'employees';
            $model = \App\Models\Employee::findOrFail($requestId);
        }

        if (!Schema::hasColumn($columnName, 'is_visible')) {
            return response()->json([
                'success' => false,
                'message' => 'Visibility is not configured on this ERP table. Please run the ERP migration to add the is_visible column.'
            ], 422);
        }

        $model->is_visible = !$model->is_visible;
        $model->save();

        return response()->json([
            'success' => true,
            'is_visible' => $model->is_visible,
            'message' => 'Visibility toggled successfully.'
        ]);
    }

    /**
     * BULK ACTION - Perform bulk operations on departments
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'action' => 'required|in:visible,hidden,active,inactive,delete',
            'type' => 'required|in:department,project,employee'
        ]);

        $ids = explode(',', $request->ids);
        if ($request->type === 'department') {
            $model = ErpDepartment::class;
            $tableName = 'erp_department';
        } elseif ($request->type === 'project') {
            $model = ErpProject::class;
            $tableName = 'erp_projects';
        } else {
            $model = \App\Models\Employee::class;
            $tableName = 'employees';
        }

        $models = $model::whereIn('id', $ids);

        if (in_array($request->action, ['visible', 'hidden'], true) && !Schema::hasColumn($tableName, 'is_visible')) {
            return redirect()->back()->with('error', 'Visibility is not configured on this ERP table. Please run the ERP migration first.');
        }

        switch ($request->action) {
            case 'visible':
                $models->update(['is_visible' => 1]);
                $message = 'Items made visible successfully.';
                break;
            case 'hidden':
                $models->update(['is_visible' => 0]);
                $message = 'Items hidden successfully.';
                break;
            case 'active':
                $models->update(['active' => 1]);
                $message = 'Items activated successfully.';
                break;
            case 'inactive':
                $models->update(['active' => 0]);
                $message = 'Items deactivated successfully.';
                break;
            case 'delete':
                // Delete images first
                foreach ($models->get() as $item) {
                    if ($request->type === 'department') {
                        if ($item->logo) Storage::disk('public')->delete($item->logo);
                        if ($item->cover_image) Storage::disk('public')->delete($item->cover_image);
                    } else {
                        if ($item->image) Storage::disk('public')->delete($item->image);
                    }
                }
                $models->delete();
                $message = 'Items deleted successfully.';
                break;
            default:
                return redirect()->back()->with('error', 'Invalid action.');
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * DELETE IMAGE - Delete department logo or cover image
     */
    public function deleteImage(Request $request, $id)
    {
        $request->validate([
            'field' => 'required|in:logo,cover_image'
        ]);

        $department = ErpDepartment::findOrFail($id);
        $field = $request->field;
        
        if ($department->$field) {
            Storage::disk('public')->delete($department->$field);
            $department->update([$field => null]);
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image found to delete.'
        ], 404);
    }

    /**
     * SYNC - Sync data from ERP API
     */
    public function sync(ErpSyncService $service)
    {
        try {
            $result = $service->sync();
            return redirect()->route('admin.erp.departments.index')
                ->with('success', "ERP sync complete: {$result['departments']} departments, {$result['projects']} projects.");
        } catch (\Exception $e) {
            return redirect()->route('admin.erp.departments.index')
                ->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Upload image
     */
    protected function uploadImage($file, $path)
    {
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $filename, 'public');
    }
}