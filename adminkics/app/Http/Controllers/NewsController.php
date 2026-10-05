<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('category', 'tags')->orderBy('created_at', 'desc')->get();
        $categories = NewsCategory::all();
        $tags = Tag::all();

        return view('admin.news', compact('news', 'categories', 'tags'));
    }

   private function uploadThumbnail($file)
{
    $destination = public_path('storage/new');

    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    $extension = strtolower($file->getClientOriginalExtension());

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $allowed)) {
        throw new \Exception('Invalid file type.');
    }

    $filename = time() . '_' . uniqid() . '.' . $extension;

    $file->move($destination, $filename);

    return 'new/' . $filename;
}

    private function deleteThumbnail($path)
    {
        if ($path && file_exists(public_path('storage/' . $path))) {
            unlink(public_path('storage/' . $path));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:news_categories,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            Log::info('NewsController.store: thumbnail present');
            $thumbnailPath = $this->uploadThumbnail($request->file('thumbnail'));
            Log::info('NewsController.store: stored thumbnail', ['path' => $thumbnailPath]);
        }

        $news = News::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
        ]);

        if ($request->tags) {
            $news->tags()->attach($request->tags);
        }

        return response()->json([
            'success' => 'News added successfully!',
            'news' => $news->load('category', 'tags')
        ]);
    }

    public function edit($id)
    {
        $news = News::with('tags')->findOrFail($id);
        return response()->json($news);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:news_categories,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($request->hasFile('thumbnail')) {
            Log::info('NewsController.update: thumbnail present', ['id' => $id]);

            $this->deleteThumbnail($news->thumbnail);

            $news->thumbnail = $this->uploadThumbnail($request->file('thumbnail'));

            Log::info('NewsController.update: stored thumbnail', [
                'id' => $id,
                'path' => $news->thumbnail
            ]);
        }

        $news->title = $request->title;
        $news->category_id = $request->category_id;
        $news->description = $request->description;
        $news->save();

        if ($request->tags) {
            $news->tags()->sync($request->tags);
        } else {
            $news->tags()->detach();
        }

        return response()->json([
            'success' => 'News updated successfully!',
            'news' => $news->load('category', 'tags')
        ]);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        $this->deleteThumbnail($news->thumbnail);

        $news->tags()->detach();
        $news->delete();

        return response()->json(['success' => 'News deleted successfully!']);
    }

    public function apiLatest()
    {
        $news = News::latest()->take(5)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'image' => $item->thumbnail
                    ? asset('storage/' . $item->thumbnail)
                    : 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=800&q=80',
            ];
        });

        return response()->json($news);
    }

    public function apiIndex()
    {
        $news = News::with('category', 'tags')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'category' => $item->category ? $item->category->name : null,
                    'image' => $item->thumbnail
                        ? asset('storage/' . $item->thumbnail)
                        : 'https://images.unsplash.com/photo-1516321497487-e288fb19713f',
                    'tags' => $item->tags->pluck('name'),
                    'created_at' => $item->created_at->format('Y-m-d'),
                ];
            });

        return response()->json($news);
    }

    public function apiShow($id)
    {
        $news = News::with('category', 'tags')->findOrFail($id);

        return response()->json([
            'id' => $news->id,
            'title' => $news->title,
            'description' => $news->description,
            'category' => $news->category ? $news->category->name : null,
            'image' => $news->thumbnail
                ? asset('storage/' . $news->thumbnail)
                : 'https://images.unsplash.com/photo-1516321497487-e288fb19713f',
            'tags' => $news->tags->pluck('name'),
            'created_at' => $news->created_at->format('Y-m-d'),
        ]);
    }
}