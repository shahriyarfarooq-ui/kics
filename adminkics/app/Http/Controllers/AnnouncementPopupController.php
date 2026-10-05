<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementPopup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnnouncementPopupController extends Controller
{
    public function edit()
    {
        return view('admin.announcement-popup', [
            'popup' => AnnouncementPopup::first(),
        ]);
    }

    public function update(Request $request)
    {
        $popup = AnnouncementPopup::first();
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'image' => [$popup ? 'nullable' : 'required', 'image', 'max:10240'],
            'link_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $oldImagePath = $popup?->image_path;
        if ($request->hasFile('image')) {
            $directory = public_path('storage/announcements');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $extension = $request->file('image')->guessExtension() ?: 'jpg';
            $filename = Str::random(40) . '.' . $extension;
            $request->file('image')->move($directory, $filename);
            $validated['image_path'] = 'announcements/' . $filename;
        }

        $validated['is_active'] = $request->boolean('is_active');
        unset($validated['image']);

        if ($popup) {
            $popup->update($validated);
        } else {
            $popup = AnnouncementPopup::create($validated);
        }

        if (!empty($validated['image_path']) && $oldImagePath && $oldImagePath !== $validated['image_path']) {
            $this->deleteStoredImage($oldImagePath);
        }

        return redirect()->route('admin.announcement-popup.edit')
            ->with('success', 'Homepage popup settings saved.');
    }

    public function show(Request $request)
    {
        $popup = AnnouncementPopup::where('is_active', true)->first();

        if (!$popup) {
            return response()->json(null);
        }

        return response()->json([
            'title' => $popup->title,
            'image_url' => rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/')
                . '/storage/' . ltrim($popup->image_path, '/'),
            'link_url' => $popup->link_url,
            'is_active' => $popup->is_active,
        ]);
    }

    private function deleteStoredImage(string $path): void
    {
        if (!str_starts_with($path, 'announcements/')) {
            return;
        }

        $file = public_path('storage/announcements/' . basename($path));
        if (is_file($file)) {
            unlink($file);
        }
    }
}
