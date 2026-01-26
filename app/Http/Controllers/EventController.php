<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index()
    {
        $upcomingEvents = Event::upcoming()
            ->orderBy('event_date', 'asc')
            ->get();
        
        $pastEvents = Event::where('event_date', '<', now()->toDateString())
            ->orWhere('status', 'completed')
            ->orderBy('event_date', 'desc')
            ->take(10)
            ->get();
        
        return view('events.index', compact('upcomingEvents', 'pastEvents'));
    }

    /**
     * Display the specified event
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        
        // Get related events
        $relatedEvents = Event::upcoming()
            ->where('id', '!=', $event->id)
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();
        
        return view('events.show', compact('event', 'relatedEvents'));
    }
}
