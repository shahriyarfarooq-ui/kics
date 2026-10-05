<?php

namespace App\Http\Controllers;

use App\Models\MenuSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuSectionController extends Controller
{
    /**
     * Store a newly created resource in storage (AJAX).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'order_index' => 'required|integer|min:0',
        ]);

        $section = MenuSection::create($validated);

        return response()->json([
            'success' => true,
            'data' => $section,
            'message' => 'Menu section created successfully.',
        ]);
    }

    /**
     * Update the specified resource in storage (AJAX).
     */
   public function update(Request $request, MenuSection $menuSection): JsonResponse
{
    $validated = $request->validate([
        'title' => 'required|string|max:191',
        'order_index' => 'required|integer|min:0',
    ]);

    $menuSection->update($validated);

    return response()->json([
        'success' => true,
        'data' => $menuSection,
        'message' => 'Menu section updated successfully.',
    ]);
}

    /**
     * Remove the specified resource from storage (AJAX).
     */
    public function destroy(MenuSection $menuSection): JsonResponse
    {
        $menuSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu section deleted successfully.',
        ]);
    }

    public function apiIndex(): JsonResponse
    {
        $menuSections = MenuSection::with(['menuLinks' => function ($query) {
            $query->ordered();
        }])->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $menuSections,
        ]);
    }
}
