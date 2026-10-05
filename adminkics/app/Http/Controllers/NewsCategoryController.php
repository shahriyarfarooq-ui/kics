<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::orderBy('id', 'DESC')->get();
        return view('admin.newscategory', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        NewsCategory::create(['name' => $request->name]);
        return back()->with('success', 'Category Added Successfully');
    }


 public function edit($id)
    {
        $category = NewsCategory::findOrFail($id);
        return response()->json($category); // Return JSON for AJAX modal
    }

    // Update category
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:news_categories,name,' . $id
        ]);

        $category = NewsCategory::findOrFail($id);

        // Only update fillable fields
        $category->update([
            'name' => $request->name
        ]);

        // Return JSON if using AJAX
        if ($request->ajax()) {
            return response()->json(['success' => 'Category updated successfully']);
        }

        // Otherwise redirect back with success
        return back()->with('success', 'Category updated successfully');
    }



    public function delete($id)
    {
        NewsCategory::where('id', $id)->delete();
        return back()->with('success', 'Category Deleted Successfully');
    }
}
