<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('eventDetails')->latest()->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
            'event_money' => 'nullable|numeric'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('events', 'public');
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')->with([
            'message' => 'Event created successfully',
            'alert-type' => 'success'
        ]);
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
            'event_money' => 'nullable|numeric'
        ]);

        if ($request->hasFile('logo')) {
            if ($event->logo) {
                Storage::disk('public')->delete($event->logo);
            }
            $validated['logo'] = $request->file('logo')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with([
            'message' => 'Event updated successfully',
            'alert-type' => 'success'
        ]);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->logo) {
            Storage::disk('public')->delete($event->logo);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with([
            'message' => 'Event deleted successfully',
            'alert-type' => 'success'
        ]);
    }
}
