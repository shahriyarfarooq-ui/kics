<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()
            ->visible()
            ->where('event_status', '!=', 'cancelled');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }

        // Upcoming events
        if ($request->boolean('upcoming')) {
            $query->upcoming();
        }

        // Featured events
        if ($request->boolean('featured')) {
            $query->featured();
        }

        $events = $query->orderBy('start_date', 'asc')
            ->paginate((int) ($request->per_page ?? 12), ['*'], 'page', (int) $request->query('page', 1));

        return response()->json([
            'success' => true,
            'data' => $events->items(),
            'meta' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    public function upcoming(Request $request)
    {
        $limit = $request->limit ?? 6;
        $events = Event::visible()
            ->upcoming()
            ->orderBy('start_date', 'asc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    public function calendar(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

        $events = Event::visible()
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->orderBy('start_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
}