<?php

namespace App\Http\Controllers;

use App\Models\MenuLink;
use App\Models\MenuSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NavbarController extends Controller
{
    // Show admin blade page (returns blade)
    public function index()
    {
        return view('navbar.admin'); // we'll create resources/views/navbar/admin.blade.php
    }

    // API: Get all sections with links
    public function all()
    {
        $sections = MenuSection::with('links')->orderBy('order_index')->get();

        return response()->json($sections);
    }

    // SECTION CRUD
    public function storeSection(Request $request)
    {
        $v = Validator::make($request->all(), [
            'title' => 'required|string|max:191',
            'order_index' => 'nullable|integer',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $section = MenuSection::create([
            'title' => $request->title,
            'order_index' => $request->order_index ?? 0,
        ]);

        return response()->json($section);
    }

    public function updateSection(Request $request, $id)
    {
        $section = MenuSection::findOrFail($id);
        $v = Validator::make($request->all(), [
            'title' => 'required|string|max:191',
            'order_index' => 'nullable|integer',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $section->update([
            'title' => $request->title,
            'order_index' => $request->order_index ?? $section->order_index,
        ]);

        return response()->json($section);
    }

    public function destroySection($id)
    {
        $section = MenuSection::findOrFail($id);
        $section->delete();

        return response()->json(['message' => 'Section deleted']);
    }

    // LINK CRUD
    public function storeLink(Request $request)
    {
        $v = Validator::make($request->all(), [
            'menu_section_id' => 'required|exists:menu_sections,id',
            'label' => 'required|string|max:191',
            'url' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order_index' => 'nullable|integer',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $link = MenuLink::create($request->only(['menu_section_id', 'label', 'url', 'description', 'order_index']));

        return response()->json($link);
    }

    public function updateLink(Request $request, $id)
    {
        $link = MenuLink::findOrFail($id);
        $v = Validator::make($request->all(), [
            'menu_section_id' => 'required|exists:menu_sections,id',
            'label' => 'required|string|max:191',
            'url' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order_index' => 'nullable|integer',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $link->update($request->only(['menu_section_id', 'label', 'url', 'description', 'order_index']));

        return response()->json($link);
    }

    public function destroyLink($id)
    {
        $link = MenuLink::findOrFail($id);
        $link->delete();

        return response()->json(['message' => 'Link deleted']);
    }
}
