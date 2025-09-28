<?php

namespace App\Http\Controllers\Events;
// Edited on 2025

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Str;

class EventsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Events::with('user', 'tags')
            ->published()
            ->where('visibility', 'public')
            ->upcoming()
            ->latest('start_datetime');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->paginate(12);
        $categories = Events::getCategories();
        
        $featuredEvents = Events::with('user')
            ->published()
            ->where('visibility', 'public')
            ->upcoming()
            ->whereNotNull('image_url')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $todayEvents = Events::published()
            ->where('visibility', 'public')
            ->upcoming()
            ->whereDate('start_datetime', today())
            ->count();

        $thisWeekEvents = Events::published()
            ->where('visibility', 'public')
            ->upcoming()
            ->thisWeek()
            ->count();

        $upcomingEvents = Events::published()
            ->where('visibility', 'public')
            ->upcoming()
            ->count();

        return view('home.events.index', compact(
            'events',
            'categories', 
            'featuredEvents',
            'todayEvents',
            'thisWeekEvents',
            'upcomingEvents'
        ));
    }

    public function show(Events $event): View
    {
        // Load relationships
        $event->load('user', 'tags');
        
        // Only show public published events or user's own events
        if ($event->visibility !== 'public' || $event->status !== 'published') {
            if (!auth()->check() || auth()->id() !== $event->user_id) {
                abort(404);
            }
        }

        $relatedEvents = Events::with('user')
            ->published()
            ->where('visibility', 'public')
            ->upcoming()
            ->where('id', '!=', $event->id)
            ->where('category', $event->category)
            ->limit(4)
            ->get();

        return view('home.events.show', compact('event', 'relatedEvents'));
    }

    public function searchTags(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $tags = Tag::where('name', 'like', "%{$query}%")
            ->orderBy('usage_count', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'usage_count']);
        
        return response()->json($tags);
    }

    public function create(): View
    {
        return view('home.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'visibility' => 'required|in:public,connections,private',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'timezone' => 'required|string',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'is_free' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'max_attendees' => 'nullable|integer|min:1',
            'image_url' => 'nullable|url',
            'tags' => 'nullable|json'
        ]);

        $event = Events::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category' => $request->category,
            'visibility' => $request->visibility,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'timezone' => $request->timezone,
            'location' => $request->location,
            'venue' => $request->venue,
            'is_free' => $request->boolean('is_free'),
            'price' => $request->is_free ? null : $request->price,
            'max_attendees' => $request->max_attendees,
            'image_url' => $request->image_url,
            'status' => $request->status ?? 'published'
        ]);

        // Handle tags
        if ($request->filled('tags')) {
            $tags = json_decode($request->tags, true);
            if ($tags) {
                $event->attachTags($tags);
            }
        }

        return redirect()->route('events.show', $event->slug)
            ->with('success', 'Event created successfully!');
    }
}