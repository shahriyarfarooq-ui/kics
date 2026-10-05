<?php

namespace App\Http\Controllers;

use App\Models\Career;

class CareerApiController extends Controller
{
    public function index()
    {
        $careers = Career::with(['group', 'tags'])
            ->orderByDesc('id')
            ->get()
            ->map(fn (Career $career) => $this->formatCareer($career));

        return response()->json($careers);
    }

    public function show($id)
    {
        $career = Career::with(['group', 'tags'])->findOrFail($id);

        return response()->json($this->formatCareer($career));
    }

    private function formatCareer(Career $career): array
    {
        return [
            'id' => $career->id,
            'job_title' => $career->job_title,
            'title' => $career->job_title,
            'group_id' => $career->group_id,
            'group' => $career->group ? [
                'id' => $career->group->group_id,
                'name' => $career->group->group_name,
                'code' => $career->group->code,
            ] : null,
            'location' => $career->location,
            'company' => $career->company,
            'job_close_date' => $this->formatDate($career->job_close_date),
            'description' => $career->description,
            'tags' => $career->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])->values(),
            'created_at' => optional($career->created_at)->toISOString(),
            'updated_at' => optional($career->updated_at)->toISOString(),
        ];
    }

    private function formatDate($date): ?string
    {
        if (!$date) {
            return null;
        }

        return is_string($date) ? $date : $date->format('Y-m-d');
    }
}
