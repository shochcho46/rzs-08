<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDetail;
use App\Models\EventDetailGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EventDetailController extends Controller
{
    public function index(Request $request)
    {
        $query = EventDetail::with(['event', 'guests']);

        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        $eventDetails = $query->latest()->paginate(50);
        $events = Event::where('status', 1)->get();

        return view('admin.event-details.index', compact('eventDetails', 'events'));
    }

    public function create()
    {
        $events = Event::where('status', 1)->get();
        return view('admin.event-details.create', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'mobile' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = EventDetail::where('event_id', $request->event_id)
                        ->where('mobile', $value)
                        ->exists();
                    if ($exists) {
                        $fail('This mobile number is already registered for this event.');
                    }
                },
            ],
            'jersey_name' => 'required|string|max:255',
            'jersey_number' => 'required|string|max:50',
            'size' => 'required|in:SM,M,L,XL,XXL,custom',
            'custom_width' => 'nullable|required_if:size,custom|string',
            'custom_height' => 'nullable|required_if:size,custom|string',
            'sleeve_type' => 'required|in:half_sleeve,full_sleeve',
            'is_guest_jersey' => 'required|boolean',
            'play_status' => 'required|boolean',
            'guests' => 'nullable|array',
            'guests.*.name' => 'required_with:guests|string|max:255',
            'guests.*.jersey_name' => 'required_with:guests|string|max:255',
            'guests.*.jersey_number' => 'required_with:guests|string|max:50',
            'guests.*.size' => 'required_with:guests|in:SM,M,L,XL,XXL,custom',
            'guests.*.custom_width' => 'nullable|required_if:guests.*.size,custom|string',
            'guests.*.custom_height' => 'nullable|required_if:guests.*.size,custom|string',
            'guests.*.sleeve_type' => 'required_with:guests|in:half_sleeve,full_sleeve',
        ]);

        $eventDetail = EventDetail::create($validated);

        if ($request->play_status == 1 ) {
            $message ="Registration done. Your captain will contact you regarding the entry fee and jersey.";
        }
        else {

            $jerseyMoney = $eventDetail?->event?->event_money;
            $message ="Registration done.\nPayable: {$jerseyMoney}Tk to committee.";
        }

        // Store guests if provided
        if ($request->has('guests')) {
            foreach ($request->guests as $guestData) {
                $eventDetail->guests()->create($guestData);
            }
            $money = $eventDetail?->event?->event_money * count($request->guests);
            // Update main participant's guest jersey status if any guest has a jersey
            $money = $eventDetail?->event?->event_money * count($request->guests);
            $message .= "\nPayable: {$money}Tk to committee for your guests.";

        }

        // // Send SMS notification (non-blocking)
        // try {
        //     Http::asForm()->post('https://api.bdbulksms.net/api.php?json', [
        //         'to' => $eventDetail->mobile,
        //         'message' => $message,
        //         'token' => 'c3253885b10f98c971b719b5372a4b34'
        //     ]);
        // } catch (\Exception $e) {
        //     // Log error but don't halt registration process
        //     Log::error('SMS sending failed: ' . $e->getMessage());
        // }

        return redirect()->route('admin.event-details.index')->with([
            'message' => 'Registration created successfully',
            'alert-type' => 'success'
        ]);
    }

    public function edit($id)
    {
        $eventDetail = EventDetail::with('guests')->findOrFail($id);
        $events = Event::where('status', 1)->get();
        return view('admin.event-details.create', compact('eventDetail', 'events'));
    }

    public function update(Request $request, $id)
    {
        $eventDetail = EventDetail::findOrFail($id);

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'mobile' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request, $id) {
                    $exists = EventDetail::where('event_id', $request->event_id)
                        ->where('mobile', $value)
                        ->where('id', '!=', $id)
                        ->exists();
                    if ($exists) {
                        $fail('This mobile number is already registered for this event.');
                    }
                },
            ],
            'jersey_name' => 'required|string|max:255',
            'jersey_number' => 'required|string|max:50',
            'size' => 'required|in:SM,M,L,XL,XXL,custom',
            'custom_width' => 'nullable|required_if:size,custom|string',
            'custom_height' => 'nullable|required_if:size,custom|string',
            'sleeve_type' => 'required|in:half_sleeve,full_sleeve',
            'is_guest_jersey' => 'required|boolean',
            'play_status' => 'required|boolean',
            'guests' => 'nullable|array',
            'guests.*.id' => 'nullable|exists:event_detail_guests,id',
            'guests.*.name' => 'required_with:guests|string|max:255',
            'guests.*.jersey_name' => 'required_with:guests|string|max:255',
            'guests.*.jersey_number' => 'required_with:guests|string|max:50',
            'guests.*.size' => 'required_with:guests|in:SM,M,L,XL,XXL,custom',
            'guests.*.custom_width' => 'nullable|required_if:guests.*.size,custom|string',
            'guests.*.custom_height' => 'nullable|required_if:guests.*.size,custom|string',
            'guests.*.sleeve_type' => 'required_with:guests|in:half_sleeve,full_sleeve',
        ]);

        $eventDetail->update($validated);

        // Update guests
        if ($request->has('guests')) {
            $existingGuestIds = [];

            foreach ($request->guests as $guestData) {
                if (isset($guestData['id'])) {
                    // Update existing guest
                    $guest = EventDetailGuest::find($guestData['id']);
                    if ($guest) {
                        $guest->update($guestData);
                        $existingGuestIds[] = $guest->id;
                    }
                } else {
                    // Create new guest
                    $newGuest = $eventDetail->guests()->create($guestData);
                    $existingGuestIds[] = $newGuest->id;
                }
            }

            // Delete guests that were removed
            $eventDetail->guests()->whereNotIn('id', $existingGuestIds)->delete();
        } else {
            // Delete all guests if none provided
            $eventDetail->guests()->delete();
        }

        return redirect()->route('admin.event-details.index')->with([
            'message' => 'Registration updated successfully',
            'alert-type' => 'success'
        ]);
    }

    public function destroy($id)
    {
        $eventDetail = EventDetail::findOrFail($id);
        $eventDetail->delete();

        return redirect()->route('admin.event-details.index')->with([
            'message' => 'Registration deleted successfully',
            'alert-type' => 'success'
        ]);
    }

    public function export(Request $request)
    {
        $query = EventDetail::with(['event', 'guests']);

        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        $eventDetails = $query->get();

        // Get event title
        $eventTitle = 'All Events';
        if ($request->event_id && $eventDetails->isNotEmpty()) {
            $eventTitle = $eventDetails->first()->event->title;
        }

        $filename = 'event_registrations_' . date('Y-m-d_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\EventRegistrationsExport($eventDetails, $eventTitle),
            $filename
        );
    }
}
