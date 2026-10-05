<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::with('group', 'tags')->orderBy('id', 'DESC')->get();
        $groups = Group::orderBy('group_name', 'ASC')->get();
        $tags = Tag::orderBy('name')->get(); // fetch all tags

        return view('admin.career', compact('careers', 'groups', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_title' => 'required',
            'group_id'  => 'required|exists:kic_group,group_id',
        ]);

        $career = Career::create($request->all());

        // Attach selected tags
        $career->tags()->sync($request->tags ?? []);

        return back()->with('success', 'Career Created Successfully');
    }

    public function edit($id)
    {
        $career = Career::with('tags')->findOrFail($id);
        $groups = Group::orderBy('group_name')->get();
        $tags = Tag::orderBy('name')->get();

        return response()->json([
            'career' => $career,
            'groups' => $groups,
            'tags'   => $tags,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'job_title' => 'required',
            'group_id'  => 'required|exists:kic_group,group_id',
        ]);

        $career = Career::findOrFail($id);
        $career->update($request->all());

        // Sync selected tags
        $career->tags()->sync($request->tags ?? []);

        return back()->with('success', 'Career Updated Successfully');
    }

    public function destroy($id)
    {
        Career::findOrFail($id)->delete();
        return back()->with('success', 'Career Deleted Successfully');
    }
}
