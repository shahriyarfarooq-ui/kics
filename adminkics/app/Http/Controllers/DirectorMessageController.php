<?php

namespace App\Http\Controllers;

use App\Models\Director;
use Illuminate\Http\Request;

class DirectorMessageController extends Controller
{
    // Show all Director messages
    public function index()
    {
        $director = Director::all(); // ✅ variable name now matches the view
        return view('admin.director_message', compact('director'));
    }

    // Fetch single record for edit (AJAX)
    public function edit($id)
    {
        $director = Director::findOrFail($id);
        return response()->json($director);
    }

    // Store new Director message (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'section_message' => 'required|string',
        ]);

        $director = Director::create([
            'section_message' => $request->section_message,
        ]);

        return response()->json(['success' => 'Director message added successfully!', 'data' => $director]);
    }

    // Update Director message (AJAX)
    public function update(Request $request, $id)
    {
        $request->validate([
            'section_message' => 'required|string',
        ]);

        $director = Director::findOrFail($id);
        $director->update([
            'section_message' => $request->section_message,
        ]);

        return response()->json(['success' => 'Director message updated successfully!', 'data' => $director]);
    }

    // Delete Director message (AJAX)
    public function destroy($id)
    {
        $director = Director::findOrFail($id);
        $director->delete();

        return response()->json(['success' => 'Director message deleted successfully!']);
    }

    // ========================
    // 🧩 API (for React Frontend)
    // ========================

    public function apiIndex()
    {
        return response()->json(Director::all());
    }

    public function apiLatest()
    {
        return response()->json(Director::latest()->first());
    }

    public function apiShow($id)
    {
        return response()->json(Director::findOrFail($id));
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'section_message' => 'required|string',
        ]);

        $director = Director::create($request->only('section_message'));

        return response()->json(['message' => 'Message created successfully', 'data' => $director]);
    }

    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'section_message' => 'required|string',
        ]);

        $director = Director::findOrFail($id);
        $director->update($request->only('section_message'));

        return response()->json(['message' => 'Message updated successfully', 'data' => $director]);
    }

    public function apiDestroy($id)
    {
        $director = Director::findOrFail($id);
        $director->delete();

        return response()->json(['message' => 'Director message deleted successfully']);
    }
}
