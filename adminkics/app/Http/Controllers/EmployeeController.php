<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->department, fn($q) => $q->where('department', $request->department))
            ->orderBy('name')
            ->paginate(20, ['*'], 'page', (int) $request->query('page', 1));

        return view('employees.index', compact('employees'));
    }

    public function adminIndex(Request $request)
    {
        $employees = Employee::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->search;
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhere('emp_id', 'like', "%{$term}%")
                        ->orWhere('job_title', 'like', "%{$term}%")
                        ->orWhere('department', 'like', "%{$term}%")
                        ->orWhere('work_email', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('visibility'), fn($query) => $query->where('is_visible', $request->visibility === 'visible'))
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.form', ['employee' => new Employee(), 'editing' => false]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);
        $validated['active'] = $request->boolean('active');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_visible'] = $request->boolean('is_visible');

        Employee::create($validated);

        return redirect()->route('admin.erp.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.form', compact('employee') + ['editing' => true]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateEmployee($request, $employee);
        $validated['active'] = $request->boolean('active');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_visible'] = $request->boolean('is_visible');

        $employee->update($validated);

        return redirect()->route('admin.erp.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('admin.erp.employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function toggleVisibility(Employee $employee)
    {
        $employee->is_visible = !$employee->is_visible;
        $employee->save();

        return redirect()->back()->with('success', 'Employee visibility updated.');
    }

    private function validateEmployee(Request $request, ?Employee $employee = null): array
    {
        return $request->validate([
            'emp_id' => ['required', 'integer', Rule::unique('employees', 'emp_id')->ignore($employee?->id)],
            'name' => ['required', 'string', 'max:255'],
            'complete_name' => ['nullable', 'string', 'max:255'],
            'prefix' => ['nullable', 'string', 'max:50'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'integer'],
            'campus' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['nullable', 'integer'],
            'manager' => ['nullable', 'string', 'max:255'],
            'coach' => ['nullable', 'string', 'max:255'],
            'work_phone' => ['nullable', 'string', 'max:50'],
            'work_email' => ['nullable', 'email', 'max:255'],
            'mobile_phone' => ['nullable', 'string', 'max:50'],
            'state' => ['nullable', 'string', 'max:100'],
            'joining_date' => ['nullable', 'date'],
            'experience' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'type' => ['nullable', 'string', 'max:100'],
            'active' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
        ]);
    }
}
