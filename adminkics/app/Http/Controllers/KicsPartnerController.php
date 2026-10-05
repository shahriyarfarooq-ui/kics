<?php
// app/Http/Controllers/KicsPartnerController.php

namespace App\Http\Controllers;

use App\Models\KicsPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KicsPartnerController extends Controller
{
    // ============= WEB METHODS (for admin panel) ============= //
    
    // Display partners list in admin panel
    public function index()
    {
        $partners = KicsPartner::all();
        return view('admin.partner', compact('partners'));
    }
    
    // Show create form (if needed)
    public function create()
    {
        return view('admin.partner-create');
    }
    
    // Store partner from admin panel
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url|max:255'
        ]);
        
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partners', 'public');
        }
        
        $partner = KicsPartner::create([
            'title' => $request->title,
            'logo' => $logoPath,
            'link' => $request->link
        ]);
        
        if ($request->ajax()) {
            return response()->json(['success' => 'Partner created successfully!']);
        }
        
        return redirect()->route('partners.index')->with('success', 'Partner created successfully!');
    }
    
    // Show edit form (returns JSON for AJAX)
    public function edit($id)
    {
        $partner = KicsPartner::findOrFail($id);
        return response()->json($partner);
    }
    
    // Update partner from admin panel
    public function update(Request $request, $id)
    {
        $partner = KicsPartner::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url|max:255'
        ]);
        
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $logoPath = $request->file('logo')->store('partners', 'public');
            $partner->logo = $logoPath;
        }
        
        $partner->title = $request->title;
        $partner->link = $request->link;
        $partner->save();
        
        if ($request->ajax()) {
            return response()->json(['success' => 'Partner updated successfully!']);
        }
        
        return redirect()->route('partners.index')->with('success', 'Partner updated successfully!');
    }
    
    // Delete partner from admin panel
    public function destroy($id)
    {
        $partner = KicsPartner::findOrFail($id);
        
        // Delete logo file
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }
        
        $partner->delete();
        
        return response()->json(['success' => 'Partner deleted successfully!']);
    }
    
    // ============= API METHODS (for React) ============= //
    
    public function apiIndex()
    {
        $partners = KicsPartner::all();
        return response()->json([
            'success' => true,
            'data' => $partners
        ]);
    }
    
    public function apiShow($id)
    {
        $partner = KicsPartner::find($id);
        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $partner]);
    }
    
    public function apiStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url|max:255'
        ]);
        
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partners', 'public');
        }
        
        $partner = KicsPartner::create([
            'title' => $request->title,
            'logo' => $logoPath,
            'link' => $request->link
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Partner created successfully',
            'data' => $partner
        ], 201);
    }
    
    public function apiUpdate(Request $request, $id)
    {
        $partner = KicsPartner::find($id);
        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner not found'], 404);
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url|max:255'
        ]);
        
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $logoPath = $request->file('logo')->store('partners', 'public');
            $partner->logo = $logoPath;
        }
        
        $partner->title = $request->title;
        $partner->link = $request->link;
        $partner->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Partner updated successfully',
            'data' => $partner
        ]);
    }
    
    public function apiDestroy($id)
    {
        $partner = KicsPartner::find($id);
        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner not found'], 404);
        }
        
        // Delete logo file
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }
        
        $partner->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Partner deleted successfully'
        ]);
    }
}