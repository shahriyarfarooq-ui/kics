<?php

// namespace App\Http\Controllers;

// use App\Models\People;
// use App\Models\User;
// use App\Models\Designation;
// use App\Models\Group;
// use App\Models\KicPost;
// use Illuminate\Http\Request;

// class PeopleController extends Controller
// {
//     // List all staff
//    public function index()
// {
//     $people = People::with(['user', 'designation', 'group', 'post'])->get();
//     $designations = Designation::all();
//     $groups = Group::all();
//     $posts = KicPost::all();

//     return view('admin.staff', compact('people', 'designations', 'groups', 'posts')); 
//     // <-- note 'admin.staff' matches resources/views/admin/staff.blade.php
// }


//     // Store new staff
//     public function store(Request $request)
//     {
//         $data = $request->validate([
//             'url_name' => 'nullable|string|max:225',
//             'user_id' => 'nullable|integer',
//             'designation_id' => 'nullable|integer',
//             'group_id' => 'nullable|integer',
//             'post_id' => 'nullable|integer',
//             'title' => 'nullable|string',
//             'fname' => 'required|string',
//             'lname' => 'required|string',
//             'url' => 'nullable|string',
//             'email' => 'required|email',
//             'off_no' => 'nullable|string',
//             'ext' => 'nullable|string|max:11',
//             'cell_no' => 'required|string',
//             'fax_no' => 'nullable|string',
//             'seqno' => 'nullable|integer',
//             'biography' => 'nullable|string',
//             'research_interest' => 'nullable|string',
//             'status' => 'nullable|boolean',
//             'is_core_team' => 'nullable|integer',
//             'image_name' => 'nullable|image|max:2048'
//         ]);

//         if ($request->hasFile('image_name')) {
//             $data['image_name'] = $request->file('image_name')->store('people', 'public');
//         }

//         People::create($data);

//         return response()->json([
//             'success' => true,
//             'message' => 'Staff added successfully!'
//         ]);
//     }

//     // Fetch staff data for edit modal
//     public function edit($id)
//     {
//         $people = People::with(['designation', 'group', 'post'])->findOrFail($id);
//         return response()->json($people);
//     }

//     // Update staff
//     public function update(Request $request, $id)
//     {
//         $people = People::findOrFail($id);

//         $data = $request->validate([
//             'url_name' => 'nullable|string|max:225',
//             'user_id' => 'nullable|integer',
//             'designation_id' => 'nullable|integer',
//             'group_id' => 'nullable|integer',
//             'post_id' => 'nullable|integer',
//             'title' => 'nullable|string',
//             'fname' => 'required|string',
//             'lname' => 'required|string',
//             'url' => 'nullable|string',
//             'email' => 'required|email',
//             'off_no' => 'nullable|string',
//             'ext' => 'nullable|string|max:11',
//             'cell_no' => 'required|string',
//             'fax_no' => 'nullable|string',
//             'seqno' => 'nullable|integer',
//             'biography' => 'nullable|string',
//             'research_interest' => 'nullable|string',
//             'status' => 'nullable|boolean',
//             'is_core_team' => 'nullable|integer',
//             'image_name' => 'nullable|image|max:2048'
//         ]);

//        if ($request->hasFile('image_name'))
//             {
//              $data['image_name'] = $request->file('image_name')->store('people', 'public');
//             } else if ($request->has('id'))
//              {
//                 // retain old image on update if no new image is uploaded
//                $data['image_name'] = $people->image_name;
//              }

//         $people->update($data);

//         return response()->json([
//             'success' => true,
//             'message' => 'Staff updated successfully!'
//         ]);
//     }

//     // Delete staff
//     public function destroy($id)
//     {
//         $people = People::findOrFail($id);
//         $people->delete();

//         return response()->json([
//             'success' => true,
//             'message' => 'Staff deleted successfully!'
//         ]);
//     }
// public function apiStaff()
// {
//     $staff = People::with(['designation', 'group', 'post'])
//         ->where('status', 0) // only include staff with status 0
//         ->get()
//         ->map(function ($person) {
//             // Check if the image file actually exists
//             $imagePath = $person->image_name 
//                 ? storage_path('app/public/people/' . $person->image_name) 
//                 : null;

//             return [
//                 'id' => $person->people_id, // table PK
//                 'name' => trim($person->fname . ' ' . $person->lname),
//                 'designation' => $person->designation->name ?? null,
//                 'department' => $person->group->name ?? null,
//                 'image' => $imagePath && file_exists($imagePath)
//                     ? asset('storage/people/' . $person->image_name)
//                     : asset('images/default-placeholder.png'), // fallback
//                 'bioLink' => '/biographies/' . $person->people_id,
//             ];
//         });

//     return response()->json($staff);
// }


// }


namespace App\Http\Controllers;

use App\Models\People;
use App\Models\User;
use App\Models\Designation;
use App\Models\Group;
use App\Models\KicPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $people = People::with(['user', 'designation', 'group', 'post', 'labs'])
            // ->orderBy('seqno', 'asc')
            ->get();
        
        // Also fetch dropdown data for the modal
        $users = User::all();
        $designations = Designation::all();
        $groups = Group::all();
        $posts = KicPost::all();
        
        return view('admin.staff', compact('people', 'users', 'designations', 'groups', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $designations = Designation::all();
        $groups = Group::all();
        $posts = KicPost::all();
        
        return view('admin.staff', compact('users', 'designations', 'groups', 'posts'));
    }

    /**
     * Generate URL name from title, first name, and last name
     */
    private function generateUrlName($title, $fname, $lname)
    {
        // Remove special characters and create slug
        $fullName = trim(implode(' ', array_filter([$title, $fname, $lname])));
        $urlName = Str::slug($fullName, '-');
        
        // Check if URL name already exists and append number if needed
        $originalUrlName = $urlName;
        $counter = 1;
        
        while (People::where('url_name', $urlName)->exists()) {
            $urlName = $originalUrlName . '-' . $counter;
            $counter++;
        }
        
        return $urlName;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // REMOVED: 'url_name' => 'required|string|max:255|unique:people',
            'user_id' => 'nullable|exists:users,id',
            'designation_id' => 'nullable|exists:designation,designation_id',
            'group_id' => 'nullable|exists:kic_group,group_id',
            'post_id' => 'nullable|exists:kic_post,post_id',
            'title' => 'nullable|string|max:50',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'email' => 'required|email|max:255',
            'off_no' => 'nullable|string|max:50',
            'ext' => 'nullable|string|max:20',
            'cell_no' => 'nullable|string|max:20',
            'fax_no' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'seqno' => 'nullable|integer',
            'biography' => 'nullable|string',
            'research_interest' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'is_core_team' => 'boolean',
            'labs' => 'nullable|array',
            'labs.*' => 'nullable|exists:kic_group,group_id',
            'lab_roles' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Generate URL name automatically
            $urlName = $this->generateUrlName(
                $request->title,
                $request->fname,
                $request->lname
            );
            
            // Prepare data including generated URL name
            $data = $request->only([
                'user_id', 'designation_id', 'group_id', 'post_id',
                'title', 'fname', 'lname', 'url', 'email', 'off_no', 'ext', 
                'cell_no', 'fax_no', 'seqno', 'biography', 
                'research_interest'
            ]);

            $data['url'] = $data['url'] ?? '';
            $data['off_no'] = $data['off_no'] ?? '';
            $data['ext'] = $data['ext'] ?? '';
            $data['cell_no'] = $data['cell_no'] ?? '';
            $data['fax_no'] = $data['fax_no'] ?? '';
            $data['image_name'] = '';
            $data['status'] = $request->status === 'active' ? 0 : 1;
            
            // Handle checkbox
            $data['is_core_team'] = $request->has('is_core_team') ? 1 : 0;
            
            // Add generated URL name
            $data['url_name'] = $urlName;
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $urlName . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/people', $imageName);
                $data['image_name'] = $imageName;
            }
            
            // Create person
            $people = People::create($data);

            // Sync labs if provided
            if ($request->has('labs')) {
                $labData = [];
                foreach ($request->labs as $index => $labId) {
                    if (empty($labId)) {
                        continue;
                    }

                    $role = $request->lab_roles[$index] ?? null;
                    $labData[$labId] = ['role' => is_numeric($role) ? (int) $role : null];
                }
                $people->labs()->sync($labData);
            }

            DB::commit();
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Person created successfully.'
                ]);
            }
            
            return redirect()->route('admin.staff.index')
                ->with('success', 'Person created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating person: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error creating person: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(People $person)
    {
        $person->load(['user', 'designation', 'group', 'post', 'labs']);
        return redirect()->route('admin.staff.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(People $person)
    {
        $users = User::all();
        $designations = Designation::all();
        $groups = Group::all();
        $posts = KicPost::all();
        $person->load('labs');
        
        // Check if request is AJAX (for modal)
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($person);
        }
        
        // Return to index with person data for edit
        $people = People::with(['user', 'designation', 'group', 'post', 'labs'])
            ->orderBy('seqno', 'asc')
            ->get();
            
        return view('admin.staff', compact('person', 'people', 'users', 'designations', 'groups', 'posts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, People $person)
    {
        $validator = Validator::make($request->all(), [
            // REMOVED: 'url_name' validation - will be auto-generated
            'user_id' => 'nullable|exists:users,id',
            'designation_id' => 'nullable|exists:designation,designation_id',
            'group_id' => 'nullable|exists:kic_group,group_id',
            'post_id' => 'nullable|exists:kic_post,post_id',
            'title' => 'nullable|string|max:50',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'email' => 'required|email|max:255',
            'off_no' => 'nullable|string|max:50',
            'ext' => 'nullable|string|max:20',
            'cell_no' => 'nullable|string|max:20',
            'fax_no' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'seqno' => 'nullable|integer',
            'biography' => 'nullable|string',
            'research_interest' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'is_core_team' => 'boolean',
            'labs' => 'nullable|array',
            'labs.*' => 'nullable|exists:kic_group,group_id',
            'lab_roles' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Regenerate URL name if name fields changed
            if ($person->title != $request->title || 
                $person->fname != $request->fname || 
                $person->lname != $request->lname) {
                
                $urlName = $this->generateUrlName(
                    $request->title,
                    $request->fname,
                    $request->lname
                );
                
                // Ensure we don't use the current person's own URL name for uniqueness check
                while (People::where('url_name', $urlName)
                       ->where('people_id', '!=', $person->people_id)
                       ->exists()) {
                    // Append timestamp or random string if needed
                    $urlName = $urlName . '-' . Str::random(3);
                }
                
                $person->url_name = $urlName;
            }
            
            // Prepare update data
            $updateData = $request->only([
                'user_id', 'designation_id', 'group_id', 'post_id',
                'title', 'fname', 'lname', 'url', 'email', 'off_no', 'ext', 
                'cell_no', 'fax_no', 'seqno', 'biography', 
                'research_interest'
            ]);

            $updateData['url'] = $updateData['url'] ?? '';
            $updateData['off_no'] = $updateData['off_no'] ?? '';
            $updateData['ext'] = $updateData['ext'] ?? '';
            $updateData['cell_no'] = $updateData['cell_no'] ?? '';
            $updateData['fax_no'] = $updateData['fax_no'] ?? '';
            $updateData['status'] = $request->status === 'active' ? 0 : 1;
            
            // Handle checkbox
            $updateData['is_core_team'] = $request->has('is_core_team') ? 1 : 0;
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($person->image_name) {
                    $oldImagePath = storage_path('app/public/people/' . $person->image_name);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . ($person->url_name ?? 'staff') . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/people', $imageName);
                $updateData['image_name'] = $imageName;
            }
            
            // Update person
            $person->update($updateData);

            // Sync labs
            if ($request->has('labs')) {
                $labData = [];
                foreach ($request->labs as $index => $labId) {
                    if (empty($labId)) {
                        continue;
                    }

                    $role = $request->lab_roles[$index] ?? null;
                    $labData[$labId] = ['role' => is_numeric($role) ? (int) $role : null];
                }
                $person->labs()->sync($labData);
            } else {
                $person->labs()->detach();
            }

            DB::commit();
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Person updated successfully.'
                ]);
            }
            
            return redirect()->route('admin.staff.index')
                ->with('success', 'Person updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating person: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error updating person: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(People $person)
    {
        DB::beginTransaction();

        try {
            // Delete image if exists
            if ($person->image_name) {
                $imagePath = storage_path('app/public/people/' . $person->image_name);
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }

            // Detach labs first
            $person->labs()->detach();

            // Delete the person
            $person->delete();

            DB::commit();

            // Check if request is AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Person deleted successfully.'
                ]);
            }

            return redirect()->route('admin.staff.index')
                ->with('success', 'Person deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Check if request is AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting person: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.staff.index')
                ->with('error', 'Error deleting person: ' . $e->getMessage());
        }
    }

    public function apiStaff()
{
    $staff = People::with(['designation', 'group', 'post'])
        ->where('status', 0)
        ->where('profile_visible', true)
        ->get()
        ->map(function ($person) {
            $imagePath = $this->staffImagePath($person);

            return [
                'id' => $person->people_id,
                'name' => trim($person->fname . ' ' . $person->lname),
                'email' => $person->email,
                'bio' => strip_tags($person->biography),
                'research_interest' => strip_tags($person->research_interest),
                'about_me' => filled($person->about_me) ? strip_tags($person->about_me) : null,
                'education' => filled($person->education) ? strip_tags($person->education) : null,
                'achievements' => filled($person->achievements) ? strip_tags($person->achievements) : null,
                'certifications' => filled($person->certifications) ? strip_tags($person->certifications) : null,
                'publications' => filled($person->publications) ? strip_tags($person->publications) : null,
                'work_experience' => filled($person->work_experience) ? strip_tags($person->work_experience) : null,
                'projects' => filled($person->projects) ? strip_tags($person->projects) : null,
                'designation' => $person->designation->designation_name ?? null,
                'department' => $person->group->name ?? null,
                'image' => $imagePath ? asset('public/storage/' . $imagePath) : null,
                'image_path' => $imagePath,
                'social_links' => array_filter([
                    'linkedin' => $person->linkedin_url,
                    'github' => $person->github_url,
                    'website' => $person->website_url,
                ], fn ($value) => filled($value)),
                'bioLink' => '/biographies/' . $person->people_id,
            ];
        });

    return response()->json($staff);
}
public function apiStaffById($id)
{
    $person = People::with(['designation', 'group', 'post'])
        ->where('status', 0)
        ->where('profile_visible', true)
        ->where('people_id', $id)
        ->first();

    if (!$person) {
        return response()->json(['message' => 'Staff not found'], 404);
    }

    $imagePath = $this->staffImagePath($person);

    return response()->json([
        'id' => $person->people_id,
        'name' => trim($person->fname . ' ' . $person->lname),
        'email' => $person->email,
        'bio' => strip_tags($person->biography),
        'research_interest' => strip_tags($person->research_interest),
        'about_me' => filled($person->about_me) ? strip_tags($person->about_me) : null,
        'education' => filled($person->education) ? strip_tags($person->education) : null,
        'achievements' => filled($person->achievements) ? strip_tags($person->achievements) : null,
        'certifications' => filled($person->certifications) ? strip_tags($person->certifications) : null,
        'publications' => filled($person->publications) ? strip_tags($person->publications) : null,
        'work_experience' => filled($person->work_experience) ? strip_tags($person->work_experience) : null,
        'projects' => filled($person->projects) ? strip_tags($person->projects) : null,
        'designation' => $person->designation->designation_name ?? null,
        'department' => $person->group->name ?? null,
        'image' => $imagePath ? asset('public/storage/' . $imagePath) : null,
        'image_path' => $imagePath,
        'social_links' => array_filter([
            'linkedin' => $person->linkedin_url,
            'github' => $person->github_url,
            'website' => $person->website_url,
        ], fn ($value) => filled($value)),
        'bioLink' => '/biographies/' . $person->people_id,
    ]);
}

private function staffImagePath(People $person): ?string
{
    $profilePhoto = $person->profile_photo_path;
    if ($profilePhoto) {
        $relative = str_starts_with($profilePhoto, 'staff-profiles/')
            ? $profilePhoto
            : 'staff-profiles/' . basename($profilePhoto);

        if (is_file(public_path('storage/' . $relative))) {
            return $relative;
        }
    }

    if ($person->image_name) {
        $filename = basename($person->image_name);
        foreach ([public_path('storage/people/' . $filename), storage_path('app/public/people/' . $filename)] as $path) {
            if (is_file($path)) {
                return 'people/' . $filename;
            }
        }
    }

    return null;
}


}
