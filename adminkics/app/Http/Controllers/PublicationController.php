<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Group;
use App\Models\People;

class PublicationController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        $people = People::all();
        $publications = Publication::orderBy('publication_id', 'asc')->paginate(10, ['*'], 'page', (int) request()->query('page', 1));
        return view('admin.publications', compact('publications', 'groups', 'people'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'publication_title' => 'required',
            'author' => 'required',
            'journal' => 'required',
            'volume' => 'required',
            'publication_seqno' => 'required|integer',
            'publication_year' => 'required|integer',
            'group_id' => 'nullable|integer',
            'people_id' => 'nullable|integer',
            'category' => 'required|string',
        ]);

        Publication::create([
            'publication_title' => $request->publication_title,
            'author' => $request->author,
            'journal' => $request->journal,
            'volume' => $request->volume,
            'publication_seqno' => $request->publication_seqno,
            'publication_abstract' => $request->publication_abstract,
            'publication_iscompleted' => $request->has('publication_iscompleted') ? 1 : 0,
            'publication_year' => $request->publication_year,
            'group_id' => $request->group_id,
            'people_id' => $request->people_id,
            'category' => $request->category,
        ]);

        return redirect()->back()->with('success', 'Publication added successfully.');
    }

    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);

        $publication->update([
            'publication_title' => $request->publication_title,
            'author' => $request->author,
            'journal' => $request->journal,
            'volume' => $request->volume,
            'publication_seqno' => $request->publication_seqno,
            'publication_abstract' => $request->publication_abstract,
            'publication_iscompleted' => $request->has('publication_iscompleted') ? 1 : 0,
            'publication_year' => $request->publication_year,
            'group_id' => $request->group_id,
            'people_id' => $request->people_id,
            'category' => $request->category,
        ]);

        return redirect()->back()->with('success', 'Publication updated successfully.');
    }

    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);
        $publication->delete();

        return redirect()->back()->with('success', 'Publication deleted successfully.');
    }
}
