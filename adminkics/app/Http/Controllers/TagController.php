<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name')->get();
        return view('admin.tags', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:tags,name']);
        Tag::create($request->all());
        return back()->with('success', 'Tag Created Successfully');
    }

    public function edit($id)
    {
        return Tag::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:tags,name,' . $id]);
        $tag = Tag::findOrFail($id);
        $tag->update($request->all());
        return back()->with('success', 'Tag Updated Successfully');
    }

    public function destroy($id)
    {
        Tag::findOrFail($id)->delete();
        return back()->with('success', 'Tag Deleted Successfully');
    }
}
