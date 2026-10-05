<?php

namespace App\Http\Controllers;

use App\Models\Publication;

class PublicationApiController extends Controller
{
    public function index()
    {
        $publications = Publication::with(['group', 'person'])
            ->orderByDesc('publication_year')
            ->orderBy('publication_seqno')
            ->get()
            ->map(fn (Publication $publication) => $this->formatPublication($publication));

        return response()->json($publications);
    }

    public function show($id)
    {
        $publication = Publication::with(['group', 'person'])->findOrFail($id);

        return response()->json($this->formatPublication($publication));
    }

    private function formatPublication(Publication $publication): array
    {
        return [
            'id' => $publication->publication_id,
            'publication_id' => $publication->publication_id,
            'publication_title' => $publication->publication_title,
            'title' => $publication->publication_title,
            'author' => $publication->author,
            'journal' => $publication->journal,
            'volume' => $publication->volume,
            'publication_seqno' => $publication->publication_seqno,
            'publication_abstract' => $publication->publication_abstract,
            'abstract' => $publication->publication_abstract,
            'publication_iscompleted' => (bool) $publication->publication_iscompleted,
            'publication_year' => $publication->publication_year,
            'year' => $publication->publication_year,
            'category' => $publication->category,
            'group_id' => $publication->group_id,
            'group' => $publication->group ? [
                'id' => $publication->group->group_id,
                'name' => $publication->group->group_name,
                'code' => $publication->group->code,
            ] : null,
            'people_id' => $publication->people_id,
            'person' => $publication->person ? [
                'id' => $publication->person->people_id,
                'name' => $publication->person->full_name,
                'email' => $publication->person->email,
            ] : null,
        ];
    }
}
