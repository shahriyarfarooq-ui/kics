<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    // Show all about sections
    public function index()
    {
        $abouts = About::all();

        return view('admin.about', compact('abouts'));
    }

    // Fetch single record for edit (AJAX)
    public function edit($id)
    {
        $about = About::findOrFail($id);

        return response()->json($about);
    }

    // Store new about record (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'section_1' => 'required|string',
            'section_2' => 'required|string',
        ]);

        $about = About::create([
            'section_1' => $request->section_1,
            'section_2' => $request->section_2,
        ]);

        return response()->json(['success' => 'About section added successfully!', 'data' => $about]);
    }

    // Update about record (AJAX)
    public function update(Request $request, $id)
    {
        $request->validate([
            'section_1' => 'required|string',
            'section_2' => 'required|string',
        ]);

        $about = About::findOrFail($id);
        $about->update([
            'section_1' => $request->section_1,
            'section_2' => $request->section_2,
        ]);

        return response()->json(['success' => 'About section updated successfully!', 'data' => $about]);
    }

    // Delete about record (AJAX)
    public function destroy($id)
    {
        $about = About::findOrFail($id);
        $about->delete();

        return response()->json(['success' => 'About section deleted successfully!']);
    }

    // ========================
    // 🧩 API (for React Frontend)
    // ========================

    // Get all about records
    public function apiIndex()
    {
        $abouts = About::all();

        return response()->json($abouts);
    }

    // Get latest about record
    public function apiLatest()
    {
        $about = About::latest()->first();

        return response()->json($about);
    }

    // Get single about record
    public function apiShow($id)
    {
        $about = About::findOrFail($id);

        return response()->json($about);
    }

    // Create new about record (API)
    public function apiStore(Request $request)
    {
        $request->validate([
            'section_1' => 'required|string',
            'section_2' => 'required|string',
        ]);

        $about = About::create($request->only('section_1', 'section_2'));

        return response()->json(['message' => 'About created successfully', 'data' => $about]);
    }

    // Update about record (API)
    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'section_1' => 'required|string',
            'section_2' => 'required|string',
        ]);

        $about = About::findOrFail($id);
        $about->update($request->only('section_1', 'section_2'));

        return response()->json(['message' => 'About updated successfully', 'data' => $about]);
    }

    // Delete about record (API)
    public function apiDestroy($id)
    {
        $about = About::findOrFail($id);
        $about->delete();

        return response()->json(['message' => 'About deleted successfully']);
    }
}
