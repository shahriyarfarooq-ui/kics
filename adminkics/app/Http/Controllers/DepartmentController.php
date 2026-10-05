<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Group;
use App\Models\KicGroupProjectlist;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(30, ['*'], 'page', (int) $request->query('page', 1));

        return view('departments.index', compact('departments'));
    }

    public function projects(Department $department, Request $request)
    {
        // Try to match groups by code or name to the department
        $query = Group::query();
        if ($department->code) {
            $query->orWhere('code', $department->code);
        }
        if ($department->name) {
            $query->orWhere('group_name', 'like', '%' . $department->name . '%');
        }

        $groupIds = $query->pluck('group_id')->all();

        $projects = KicGroupProjectlist::whereIn('group_id', $groupIds)
            ->orderByDesc('projectlist_id')
            ->paginate(10, ['*'], 'page', (int) $request->query('page', 1))
            ->appends($request->query());

        return view('departments.projects', compact('department', 'projects'));
    }
}
