<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    // 🔹 Show all groups
    public function index()
    {
        $groups = Group::paginate(7, ['*'], 'page', (int) request()->query('page', 1));
        return view('admin.group', compact('groups'));
    }

    // 🔹 Store a new group
    public function store(Request $request)
    {
        try {
            $request->validate([
                'group_name' => 'required|string|max:255',
                'group_description' => 'required|string',
                'contact_us' => 'required|string',
                'code' => 'required|string|max:50',
            ]);

            $input = $request->all();

            // ✅ Checkbox handling
            $input['is_center'] = $request->has('is_center') ? 1 : 0;
            $input['group_projectlist_check'] = $request->has('group_projectlist_check') ? 1 : 0;
            $input['group_services_check'] = $request->has('group_services_check') ? 1 : 0;
            $input['group_rdproject_check'] = $request->has('group_rdproject_check') ? 1 : 0;
            $input['group_subgroup_check'] = $request->has('group_subgroup_check') ? 1 : 0;

            // ✅ File uploads
            if ($request->hasFile('img_path')) {
                $file = $request->file('img_path');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $input['img_path'] = $filename;
            }

            if ($request->hasFile('group_banner')) {
                $file = $request->file('group_banner');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $input['group_banner'] = $filename;
            }

            Group::create($input);

            return response()->json(['success' => true, 'message' => 'Group added successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // 🔹 Edit (AJAX)
    public function edit($id)
    {
        $group = Group::findOrFail($id);
        return response()->json($group);
    }

    // 🔹 Update an existing group
   public function update(Request $request, Group $group)
{
    try {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'group_description' => 'required|string',
            'contact_us' => 'required|string',
            'code' => 'required|string|max:50',
        ]);

        $input = $request->all();

        // ✅ Checkbox handling
        $input['is_center'] = $request->has('is_center') ? 1 : 0;
        $input['group_projectlist_check'] = $request->has('group_projectlist_check') ? 1 : 0;
        $input['group_services_check'] = $request->has('group_services_check') ? 1 : 0;
        $input['group_rdproject_check'] = $request->has('group_rdproject_check') ? 1 : 0;
        $input['group_subgroup_check'] = $request->has('group_subgroup_check') ? 1 : 0;

        // ✅ Handle file uploads
        if ($request->hasFile('img_path')) {
            $file = $request->file('img_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $input['img_path'] = $filename;
        } else {
            unset($input['img_path']);
        }

        if ($request->hasFile('group_banner')) {
            $file = $request->file('group_banner');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $input['group_banner'] = $filename;
        } else {
            unset($input['group_banner']);
        }

        $group->update($input);

        return response()->json(['success' => true, 'message' => 'Group updated successfully.']);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}


    // 🔹 Delete a group
    public function destroy(Group $group)
    {
        if ($group->img_path && file_exists(public_path('uploads/' . $group->img_path))) {
            unlink(public_path('uploads/' . $group->img_path));
        }

        if ($group->group_banner && file_exists(public_path('uploads/' . $group->group_banner))) {
            unlink(public_path('uploads/' . $group->group_banner));
        }

        $group->delete();

        return response()->json(['success' => true, 'message' => 'Group deleted successfully.']);
    }

    // 🔹 View a group with related data
    public function view($id)
    {
        $group = Group::with([
            'projects',
            'publications',
            'staff' => function ($q) {
                $q->where('status', 0);
            }
        ])->where('group_id', $id)->firstOrFail();

        return view('admin.group_view', compact('group'));
    }

    // 🔹 Staff tab
    public function groupStaff($group_id)
    {
        $group = Group::findOrFail($group_id);
        $staff = $group->people()->where('status', 0)->get();

        return view('admin.groups.staff', compact('group', 'staff'));
    }
    
    //--------------------
    // 🔹 API: Get groups for frontend (React)
public function apiGroups()
{
    $groups = Group::select(
        'group_name',
        'group_briefdescription',
        'code'
    )->get();

    return response()->json([
        'success' => true,
        'data' => $groups
    ])
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
}

public function apiGroupDetail($code)
{
    $group = Group::with([
        'projects',
        'publications',
        'staff' => function ($q) {
            $q->where('status', 0);
        }
    ])->where('code', $code)->firstOrFail();

    return response()->json([
        'success' => true,
        'data' => $group
    ])
     ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');;
}



}
