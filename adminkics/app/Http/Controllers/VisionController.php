<?php

namespace App\Http\Controllers;

use App\Models\Vision;
use Illuminate\Http\Request;

class VisionController extends Controller
{
    // Show all Vision sections
    public function index()
    {
        $visions = Vision::all();

        return view('admin.vision', compact('visions'));
    }

    // Fetch single record for edit (AJAX)
    public function edit($id)
    {
        $vision = Vision::findOrFail($id);

        return response()->json($vision);
    }

    // Store new Vision record (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'section_vision' => 'required|string',
            
        ]);

        $vision = Vision::create([
            'section_vision' => $request->section_vision,
            
        ]);

        return response()->json(['success' => 'Vision section added successfully!', 'data' => $vision]);
    }

    // Update Vision record (AJAX)
    public function update(Request $request, $id)
    {
        $request->validate([
            'section_vision' => 'required|string',
            
        ]);

        $vision = Vision::findOrFail($id);
        $vision->update([
            'section_vision' => $request->section_vision,
            
        ]);

        return response()->json(['success' => 'Vision section updated successfully!', 'data' => $vision]);
    }

    // Delete Vision record (AJAX)
    public function destroy($id)
    {
        $vision = Vision::findOrFail($id);
        $vision->delete();

        return response()->json(['success' => 'Vision section deleted successfully!']);
    }

    // ========================
    // 🧩 API (for React Frontend)
    // ========================

    // Get all Vision records
    public function apiIndex()
    {
        $visions = Vision::all();

        return response()->json($visions);
    }

    // Get latest Vision record
    public function apiLatest()
    {
        $vision = Vision::latest()->first();

        return response()->json($vision);
    }

    // Get single Vision record
    public function apiShow($id)
    {
        $vision = Vision::findOrFail($id);

        return response()->json($vision);
    }

    // Create new Vision record (API)
    public function apiStore(Request $request)
    {
        $request->validate([
            'section_vision' => 'required|string',
           
        ]);

        $vision = Vision::create($request->only('section_vision'));

        return response()->json(['message' => 'Vision created successfully', 'data' => $vision]);
    }

    // Update Vision record (API)
    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'section_vision' => 'required|string',
            
        ]);

        $vision = Vision::findOrFail($id);
        $vision->update($request->only('section_vision'));

        return response()->json(['message' => 'Vision updated successfully', 'data' => $vision]);
    }

    // Delete Vision record (API)
    public function apiDestroy($id)
    {
        $vision = Vision::findOrFail($id);
        $vision->delete();

        return response()->json(['message' => 'Vision deleted successfully']);
    }
}
