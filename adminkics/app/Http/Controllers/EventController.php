<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('event_status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(20, ['*'], 'page', (int) $request->query('page', 1));

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'), 'events/featured');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $this->uploadImage($image, 'events/gallery');
            }
            $data['gallery_images'] = $gallery;
        }

        // Convert checkbox values
        $data['is_featured'] = $request->has('is_featured');
        $data['is_visible'] = $request->has('is_visible');

        // Generate slug
        $data['slug'] = Str::slug($request->title);

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            if ($event->featured_image) {
                Storage::disk('public')->delete($event->featured_image);
            }
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'), 'events/featured');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            if ($event->gallery_images) {
                foreach ($event->gallery_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $this->uploadImage($image, 'events/gallery');
            }
            $data['gallery_images'] = $gallery;
        }

        // Convert checkbox values
        $data['is_featured'] = $request->has('is_featured');
        $data['is_visible'] = $request->has('is_visible');

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Delete images
        if ($event->featured_image) {
            Storage::disk('public')->delete($event->featured_image);
        }
        if ($event->gallery_images) {
            foreach ($event->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    // Upload helper
    protected function uploadImage($file, $path)
    {
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $filename, 'public');
    }
}