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
        $profileColumns = ['people_id', 'fname', 'lname', 'email'];
        foreach (['profile_visible', 'profile_photo_path', 'image_name'] as $column) {
            if (Schema::hasColumn('people', $column)) {
                $profileColumns[] = $column;
            }
        }

        $profiles = People::query()
            ->when(Schema::hasColumn('people', 'profile_visible'), fn ($profiles) => $profiles->where('profile_visible', true))
            ->whereNotNull('email')
            ->get($profileColumns + ['biography', 'research_interest', 'about_me', 'education', 'achievements', 'certifications', 'publications', 'work_experience', 'projects'])
            ->filter(fn ($person) => !empty(trim((string) $person->email)));

        $profilesByEmail = $profiles->keyBy(fn ($person) => mb_strtolower(trim((string) $person->email)));
        $profilesByName = $profiles->keyBy(fn ($person) => $this->normalizeName((string) ($person->fname ?? '') . ' ' . (string) ($person->lname ?? '')));

        return response()->json($employees->map(function ($employee) use ($profilesByEmail, $profilesByName) {
            $email = mb_strtolower(trim((string) ($employee->work_email ?? $employee->email ?? '')));
            $profile = $email !== '' ? ($profilesByEmail->get($email) ?? $this->matchProfileByEmployeeName($employee, $profilesByName)) : $this->matchProfileByEmployeeName($employee, $profilesByName);
            $photoPath = $profile?->profile_photo_path;
            $legacyImage = $profile?->image_name;
            $relativeImage = $photoPath
                ? (str_starts_with($photoPath, 'staff-profiles/') ? $photoPath : 'staff-profiles/' . basename($photoPath))
                : ($legacyImage ? 'people/' . basename($legacyImage) : null);

            $profilePayload = $profile ? [
                'people_id' => $profile->people_id,
                'image_path' => $relativeImage,
                'bio' => $profile->biography ? strip_tags($profile->biography) : null,
                'research_interest' => $profile->research_interest ? strip_tags($profile->research_interest) : null,
                'about_me' => $profile->about_me ? strip_tags($profile->about_me) : null,
                'education' => $profile->education ? strip_tags($profile->education) : null,
                'achievements' => $profile->achievements ? strip_tags($profile->achievements) : null,
                'certifications' => $profile->certifications ? strip_tags($profile->certifications) : null,
                'publications' => $profile->publications ? strip_tags($profile->publications) : null,
                'work_experience' => $profile->work_experience ? strip_tags($profile->work_experience) : null,
                'projects' => $profile->projects ? strip_tags($profile->projects) : null,
            ] : null;

            return array_merge($employee->toArray(), [
                'bio' => $profilePayload['bio'] ?? $employee->bio ?? null,
                'research_interest' => $profilePayload['research_interest'] ?? $employee->research_interest ?? null,
                'about_me' => $profilePayload['about_me'] ?? $employee->about_me ?? null,
                'education' => $profilePayload['education'] ?? $employee->education ?? null,
                'achievements' => $profilePayload['achievements'] ?? $employee->achievements ?? null,
                'certifications' => $profilePayload['certifications'] ?? $employee->certifications ?? null,
                'publications' => $profilePayload['publications'] ?? $employee->publications ?? null,
                'work_experience' => $profilePayload['work_experience'] ?? $employee->work_experience ?? null,
                'projects' => $profilePayload['projects'] ?? $employee->projects ?? null,
                'profile' => $profilePayload,
            ]);
        }));
    }

    protected function normalizeName(string $value): string
    {
        $value = preg_replace('/^(mr|mrs|ms|miss|dr|prof)\.?\s+/i', '', trim($value));
        $value = preg_replace('/[^a-z0-9\s]/i', ' ', mb_strtolower((string) $value));
        $value = preg_replace('/\s+/', ' ', trim((string) $value));

        return $value;
    }

    protected function matchProfileByEmployeeName($employee, $profilesByName)
    {
        $nameCandidates = [];

        foreach ([$employee->complete_name ?? null, $employee->name ?? null] as $candidate) {
            if (empty(trim((string) $candidate))) {
                continue;
            }

            $nameCandidates[] = $this->normalizeName((string) $candidate);
        }

        foreach ($nameCandidates as $candidate) {
            if ($profilesByName->has($candidate)) {
                return $profilesByName->get($candidate);
            }
        }

        return null;
    }
}