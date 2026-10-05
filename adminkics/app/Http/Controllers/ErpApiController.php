<?php

namespace App\Http\Controllers;

use App\Models\ErpDepartment;
use App\Models\ErpProject;
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

        return response()->json(
            $query->orderBy('name')->get()
        );
    }
}