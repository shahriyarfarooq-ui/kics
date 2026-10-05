<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use Illuminate\Support\Facades\File;

class DesignationController extends Controller
{
    // Display all designations
    public function index()
    {
        $designations = Designation::orderBy('designation_seqno', 'ASC')->get();
        return view('admin.designation', compact('designations'));
    }

    // Store new designation
    public function store(Request $request)
    {
        $request->validate([
            'designation_name' => 'required|string|max:255',
            'designation_seqno' => 'required|integer',
            'designation_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('designation_image')) {
            $imageName = time() . '.' . $request->designation_image->extension();
            $request->designation_image->move(public_path('uploads/designations'), $imageName);
        }

        Designation::create([
            'designation_name' => $request->designation_name,
            'designation_seqno' => $request->designation_seqno,
            'designation_image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'Designation added successfully.');
    }

    // Update existing designation
    public function update(Request $request, $id)
    {
        $designation = Designation::findOrFail($id);

        $request->validate([
            'designation_name' => 'required|string|max:255',
            'designation_seqno' => 'required|integer',
            'designation_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imageName = $designation->designation_image;
        if ($request->hasFile('designation_image')) {
            if ($imageName && File::exists(public_path('uploads/designations/' . $imageName))) {
                File::delete(public_path('uploads/designations/' . $imageName));
            }
            $imageName = time() . '.' . $request->designation_image->extension();
            $request->designation_image->move(public_path('uploads/designations'), $imageName);
        }

        $designation->update([
            'designation_name' => $request->designation_name,
            'designation_seqno' => $request->designation_seqno,
            'designation_image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'Designation updated successfully.');
    }

    // Delete designation
    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);

        if ($designation->designation_image && File::exists(public_path('uploads/designations/' . $designation->designation_image))) {
            File::delete(public_path('uploads/designations/' . $designation->designation_image));
        }

        $designation->delete();
        return redirect()->back()->with('success', 'Designation deleted successfully.');
    }
}
