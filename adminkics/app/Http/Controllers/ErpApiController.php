<?php

namespace App\Http\Controllers;

use App\Models\ErpDepartment;
use App\Models\ErpProject;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ErpApiController extends Controller
{
    protected function departmentQuery()
    {
        $query = ErpDepartment::query();

        if (Schema::hasColumn('erp_department', 'is_visible')) {
            $query->where('is_visible', true);
        }

        return $query->orderBy('name');
    }

    protected function projectQuery()
    {
        $query = ErpProject::query();

        if (Schema::hasColumn('erp_projects', 'is_visible')) {
            $query->where('is_visible', true);
        }

        return $query->orderBy('name');
    }

    public function departments()
    {
        return response()->json($this->departmentQuery()->get());
    }

    public function department($id)
    {
        return response()->json($this->departmentQuery()->whereKey($id)->firstOrFail());
    }

    public function projects(Request $request)
    {
        $query = $this->projectQuery();

        if ($request->has('department_id')) {
            $query->where('erp_department_id', $request->query('department_id'));
        }

        return response()->json($query->get());
    }

    public function departmentProjects($id)
    {
        $department = ErpDepartment::findOrFail($id);

        $query = $department->projects();

        if (Schema::hasColumn('erp_projects', 'is_visible')) {
            $query->where('is_visible', true);
        }

        return response()->json($query->orderBy('name')->get());
    }

    public function employees(Request $request)
    {
        $query = \App\Models\Employee::query()
            ->where('state', 'Current');

        if ($request->has('department_id')) {
            $query->where('department_id', $request->query('department_id'));
        }

        if (Schema::hasColumn('employees', 'is_visible')) {
            $query->where('is_visible', true);
        }

        $employees = $query->orderBy('name')->get();
        $profileColumns = ['people_id', 'email'];
        foreach (['profile_visible', 'profile_photo_path', 'image_name'] as $column) {
            if (Schema::hasColumn('people', $column)) {
                $profileColumns[] = $column;
            }
        }

        $profilesByEmail = People::query()
            ->when(Schema::hasColumn('people', 'profile_visible'), fn ($profiles) => $profiles->where('profile_visible', true))
            ->whereNotNull('email')
            ->get($profileColumns)
            ->keyBy(fn ($person) => mb_strtolower(trim($person->email)));

        return response()->json($employees->map(function ($employee) use ($profilesByEmail) {
            $email = mb_strtolower(trim((string) ($employee->work_email ?? $employee->email ?? '')));
            $profile = $email !== '' ? $profilesByEmail->get($email) : null;
            $photoPath = $profile?->profile_photo_path;
            $legacyImage = $profile?->image_name;
            $relativeImage = $photoPath
                ? (str_starts_with($photoPath, 'staff-profiles/') ? $photoPath : 'staff-profiles/' . basename($photoPath))
                : ($legacyImage ? 'people/' . basename($legacyImage) : null);

            return array_merge($employee->toArray(), [
                'profile' => $profile ? [
                    'people_id' => $profile->people_id,
                    'image_path' => $relativeImage,
                ] : null,
            ]);
        }));
    }
}