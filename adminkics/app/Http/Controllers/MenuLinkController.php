<?php

namespace App\Http\Controllers;

use App\Models\MenuLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuLinkController extends Controller
{
    /**
     * Store a newly created resource in storage (AJAX).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'menu_section_id' => 'required|exists:menu_sections,id',
            'label' => 'required|string|max:191',
            'url' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order_index' => 'required|integer|min:0',
        ]);

        $link = MenuLink::create($validated);

        return response()->json([
            'success' => true,
            'data' => $link,
            'message' => 'Menu link created successfully.',
        ]);
    }

    /**
     * Update the specified resource in storage (AJAX).
     */
    public function update(Request $request, MenuLink $menuLink): JsonResponse
    {
        $validated = $request->validate([
            'menu_section_id' => 'required|exists:menu_sections,id',
            'label' => 'required|string|max:191',
            'url' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order_index' => 'required|integer|min:0',
        ]);

        $menuLink->update($validated);

        return response()->json([
            'success' => true,
            'data' => $menuLink,
            'message' => 'Menu link updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage (AJAX).
     */
    public function destroy(MenuLink $menuLink): JsonResponse
    {
        $menuLink->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu link deleted successfully.',
        ]);
    }
}
