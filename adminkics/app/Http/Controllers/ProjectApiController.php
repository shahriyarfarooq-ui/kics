<?php

namespace App\Http\Controllers;

use App\Models\KicGroupProjectlist;
use Illuminate\Support\Str;

class ProjectApiController extends Controller
{
    public function index()
    {
        $projects = KicGroupProjectlist::with('group')
            ->where(fn ($query) => $query->whereNull('inactive')->orWhere('inactive', 0))
            ->orderBy('projectlist_seqno')
            ->orderByDesc('projectlist_id')
            ->get()
            ->map(fn (KicGroupProjectlist $project) => $this->formatProject($project));

        return response()->json($projects);
    }

    public function show($id)
    {
        $project = KicGroupProjectlist::with('group')
            ->where(fn ($query) => $query->whereNull('inactive')->orWhere('inactive', 0))
            ->findOrFail($id);

        return response()->json($this->formatProject($project));
    }

    public function full($id)
    {
        $project = KicGroupProjectlist::with([
            'group',
            'activities',
            'content',
            'downloads',
            'teamMembers.member.designation',
            'teamMembers.member.group',
            'phases',
            'publicationLinks.publication.group',
            'publicationLinks.publication.person',
        ])
            ->where(fn ($query) => $query->whereNull('inactive')->orWhere('inactive', 0))
            ->findOrFail($id);

        return response()->json([
            'project' => $this->formatProject($project),
            'content' => $project->content->map(fn ($content) => [
                'id' => $content->id,
                'project_id' => $content->project_id,
                'tpr' => $content->tpr,
                'team' => $content->team,
                'publications' => $content->publications,
                'aad' => $content->aad,
                'usecase' => $content->usecase,
                'downloads' => $content->downloads,
            ])->values(),
            'activities' => $project->activities->map(fn ($activity) => [
                'id' => $activity->id,
                'project_id' => $activity->project_id,
                'task' => $activity->task,
                'description' => $activity->description,
                'start_date' => $this->formatDate($activity->start_date),
                'expected_date' => $this->formatDate($activity->expected_date),
            ])->values(),
            'downloads' => $project->downloads->map(fn ($download) => [
                'id' => $download->id,
                'project_id' => $download->project_id,
                'title' => $download->title,
                'description' => $download->description,
                'file' => $download->file,
                'file_url' => $this->projectManagementFileUrl($download->file),
                'url' => $download->url,
            ])->values(),
            'team' => $project->teamMembers->map(fn ($teamMember) => [
                'id' => $teamMember->id,
                'project_id' => $teamMember->project_id,
                'member_id' => $teamMember->member_id,
                'role' => $teamMember->role,
                'member' => $teamMember->member ? [
                    'id' => $teamMember->member->people_id,
                    'name' => $teamMember->member->full_name,
                    'title' => $teamMember->member->title,
                    'fname' => $teamMember->member->fname,
                    'lname' => $teamMember->member->lname,
                    'email' => $teamMember->member->email,
                    'image' => $this->personImageUrl($teamMember->member->image_name),
                    'designation' => $teamMember->member->designation?->designation_name,
                    'group' => $teamMember->member->group ? [
                        'id' => $teamMember->member->group->group_id,
                        'name' => $teamMember->member->group->group_name,
                        'code' => $teamMember->member->group->code,
                    ] : null,
                ] : null,
            ])->values(),
            'phases' => $project->phases->map(fn ($phase) => [
                'id' => $phase->id,
                'projectlist_id' => $phase->projectlist_id,
                'phase' => $phase->phase,
                'start_date' => $this->formatDate($phase->start_date),
                'end_date' => $this->formatDate($phase->end_date),
                'extended_date' => $this->formatDate($phase->extended_date),
                'is_complete' => (bool) $phase->is_complete,
            ])->values(),
            'publications' => $project->publicationLinks->map(fn ($link) => [
                'id' => $link->id,
                'project_id' => $link->project_id,
                'publication_id' => $link->publication_id,
                'publication' => $link->publication ? [
                    'id' => $link->publication->publication_id,
                    'publication_id' => $link->publication->publication_id,
                    'publication_title' => $link->publication->publication_title,
                    'title' => $link->publication->publication_title,
                    'author' => $link->publication->author,
                    'journal' => $link->publication->journal,
                    'volume' => $link->publication->volume,
                    'publication_abstract' => $link->publication->publication_abstract,
                    'abstract' => $link->publication->publication_abstract,
                    'publication_year' => $link->publication->publication_year,
                    'year' => $link->publication->publication_year,
                    'category' => $link->publication->category,
                    'group' => $link->publication->group ? [
                        'id' => $link->publication->group->group_id,
                        'name' => $link->publication->group->group_name,
                        'code' => $link->publication->group->code,
                    ] : null,
                    'person' => $link->publication->person ? [
                        'id' => $link->publication->person->people_id,
                        'name' => $link->publication->person->full_name,
                        'email' => $link->publication->person->email,
                    ] : null,
                ] : null,
            ])->values(),
        ]);
    }

    private function formatProject(KicGroupProjectlist $project): array
    {
        return [
            'id' => $project->projectlist_id,
            'projectlist_id' => $project->projectlist_id,
            'projectlist_Name' => $project->projectlist_Name,
            'title' => $project->projectlist_Name,
            'projectlist_seqno' => $project->projectlist_seqno,
            'projectlist_description' => $project->projectlist_description,
            'description' => $project->projectlist_description,
            'projectlist_small_picture' => $project->projectlist_small_picture,
            'image' => $this->projectImageUrl($project->projectlist_small_picture),
            'group_id' => $project->group_id,
            'group' => $project->group ? [
                'id' => $project->group->group_id,
                'name' => $project->group->group_name,
                'code' => $project->group->code,
            ] : null,
            'subgroup_id' => $project->subgroup_id,
            'sub_site_id' => $project->sub_site_id,
            'code' => $project->code,
            'project_category' => $project->project_category,
            'category' => $project->project_category,
            'is_completed' => (bool) $project->is_completed,
            'fundedby' => $project->fundedby,
            'inactive' => (bool) $project->inactive,
        ];
    }

    private function projectImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['storage/', 'uploads/'])) {
            return asset($path);
        }

        return asset('uploads/projects/' . ltrim($path, '/'));
    }

    private function projectManagementFileUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', 'storage/', 'uploads/'])) {
            return $this->projectImageUrl($path);
        }

        return asset('uploads/project-management/' . ltrim($path, '/'));
    }

    private function personImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['storage/', 'uploads/'])) {
            return asset($path);
        }

        return asset('storage/people/' . ltrim($path, '/'));
    }

    private function formatDate($date): ?string
    {
        if (!$date) {
            return null;
        }

        return is_string($date) ? $date : $date->format('Y-m-d');
    }
}
