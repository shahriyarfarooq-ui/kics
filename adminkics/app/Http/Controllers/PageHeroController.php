<?php

namespace App\Http\Controllers;

use App\Models\PageHeroBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageHeroController extends Controller
{
    private const PAGES = [
        ['key' => 'home', 'label' => 'Home'],
        ['key' => 'about', 'label' => 'About KICS'],
        ['key' => 'conferences', 'label' => 'Conferences'],
        ['key' => 'contact', 'label' => 'Contact'],
        ['key' => 'director-message', 'label' => "Director's Message"],
        ['key' => 'erp-departments', 'label' => 'ERP Departments'],
        ['key' => 'erp-department-detail', 'label' => 'ERP Department Detail'],
        ['key' => 'erp-employees', 'label' => 'ERP Employees'],
        ['key' => 'events', 'label' => 'Events'],
        ['key' => 'event-detail', 'label' => 'Event Detail'],
        ['key' => 'icosst', 'label' => 'ICOSST'],
        ['key' => 'innovation', 'label' => 'Innovation'],
        ['key' => 'jobs', 'label' => 'Jobs'],
        ['key' => 'job-detail', 'label' => 'Job Detail'],
        ['key' => 'new-page', 'label' => 'New Page'],
        ['key' => 'news', 'label' => 'News'],
        ['key' => 'news-detail', 'label' => 'News Detail'],
        ['key' => 'publications', 'label' => 'Publications'],
        ['key' => 'research', 'label' => 'Research'],
        ['key' => 'research-areas', 'label' => 'Research Areas'],
        ['key' => 'research-area-detail', 'label' => 'Research Area Detail'],
        ['key' => 'services', 'label' => 'Services'],
        ['key' => 'staff', 'label' => 'Staff'],
        ['key' => 'staff-detail', 'label' => 'Staff Detail'],
        ['key' => 'workshops', 'label' => 'Workshops'],
    ];

    public function edit()
    {
        $banners = PageHeroBanner::all()->keyBy('page_key');

        return view('admin.page-heroes', [
            'pages' => self::PAGES,
            'banners' => $banners,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (self::PAGES as $page) {
            $key = $page['key'];
            $rules["images.{$key}"] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'];
            $rules["remove.{$key}"] = ['nullable', 'boolean'];
        }

        $request->validate($rules);

        foreach (self::PAGES as $page) {
            $key = $page['key'];
            $banner = PageHeroBanner::where('page_key', $key)->first();
            $oldPath = $banner?->image_path;

            if ($request->hasFile("images.{$key}")) {
                $directory = public_path('storage/page-heroes');
                if (! is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                $file = $request->file("images.{$key}");
                $extension = $file->guessExtension() ?: 'jpg';
                $filename = Str::random(40) . '.' . $extension;
                $file->move($directory, $filename);
                $newPath = 'page-heroes/' . $filename;
                PageHeroBanner::updateOrCreate(
                    ['page_key' => $key],
                    ['image_path' => $newPath]
                );

                if ($oldPath && $oldPath !== $newPath) {
                    $this->deleteStoredImage($oldPath);
                }

                continue;
            }

            if ($banner && $oldPath && $request->boolean("remove.{$key}")) {
                $banner->update(['image_path' => null]);
                $this->deleteStoredImage($oldPath);
            }
        }

        return redirect()->route('admin.page-heroes.edit')
            ->with('success', 'Page banner images updated.');
    }

    public function apiIndex()
    {
        $banners = PageHeroBanner::whereNotNull('image_path')
            ->pluck('image_path', 'page_key');

        return response()->json($banners);
    }

    private function deleteStoredImage(string $path): void
    {
        if (! str_starts_with($path, 'page-heroes/')) {
            return;
        }

        $file = public_path('storage/page-heroes/' . basename($path));
        if (is_file($file)) {
            unlink($file);
        }
    }
}
