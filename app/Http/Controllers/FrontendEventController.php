<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FrontendEventController extends Controller
{
    public function checkMobile(Request $request)
    {
        $event = Event::find($request->event_id);
        $isRegistrationOver = !$event || today()->gt($event->end_date);

        if ($isRegistrationOver) {
            return response()->json([
                'exists' => false,
                'registration_over' => true,
            ]);
        }

        $exists = EventDetail::where('event_id', $request->event_id)
            ->where('mobile', $request->mobile)
            ->exists();

        return response()->json([
            'exists' => $exists,
            'registration_over' => false,
        ]);
    }

    public function register(Request $request)
    {
        // Frontend sends guests as JSON string in FormData; normalize to array for validation.
        if (is_string($request->input('guests'))) {
            $decodedGuests = json_decode($request->input('guests'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid guest data format.',
                ], 422);
            }
            $request->merge(['guests' => $decodedGuests]);
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::find($request->event_id);

        if (today()->gt($event->end_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Registration is over for this event.',
            ], 422);
        }

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
            'size' => 'required|in:SM,M,L,XL,XXL,XXXL,custom',
            'custom_width' => 'nullable|required_if:size,custom|string',
            'custom_height' => 'nullable|required_if:size,custom|string',
            'sleeve_type' => 'required|in:half_sleeve,full_sleeve',
            'is_guest_jersey' => 'required|boolean',
            'play_status' => 'required|boolean',

            'guests' => 'nullable|array',
            'guests.*.name' => 'required_with:guests|string|max:255',
            'guests.*.jersey_name' => 'required_with:guests|string|max:255',
            'guests.*.jersey_number' => 'required_with:guests|string|max:50',
            'guests.*.size' => 'required_with:guests|in:SM,M,L,XL,XXL,XXXL,custom',
            'guests.*.custom_width' => 'nullable|required_if:guests.*.size,custom|string',
            'guests.*.custom_height' => 'nullable|required_if:guests.*.size,custom|string',
            'guests.*.sleeve_type' => 'required_with:guests|in:half_sleeve,full_sleeve',
        ]);

        $eventDetail = EventDetail::create($validated);

        if ($request->play_status == 1 ) {
            $message ="Registration done.\nYour captain will contact you regarding the entry fee and jersey.";

            if ($request->filled('guests') && !empty($request->guests)) {

                foreach ($request->guests as $guestData) {
                    $eventDetail->guests()->create($guestData);
                }
                $money = $eventDetail?->event?->event_money * count($request->guests);
                // Update main participant's guest jersey status if any guest has a jersey
                $message .= "\nPayable: {$money} Tk to committee for your guest.";

            }
        }



        else {

            if ($request->has('guests'))
                {
                    foreach ($request->guests as $guestData) {
                        $eventDetail->guests()->create($guestData);
                    }
                    $money = $eventDetail?->event?->event_money * count($request->guests);
                    $totalmoney = $eventDetail?->event?->event_money + $money;
                    // Update main participant's guest jersey status if any guest has a jersey
                    $message = "Registration done.\nPayable: {$totalmoney}Tk to committee.";

                }

                else
                {
                    $jerseyMoney = $eventDetail?->event?->event_money;
                    $message ="Registration done.\nPayable: {$jerseyMoney}Tk to committee.";
                }

        }

        // Store guests if provided

        // Send SMS notification (non-blocking)
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



        return response()->json([
            'success' => true,
            'message' => 'Registration successful!'
        ]);
    }

    public function viewRegistrations($eventId)
    {
        $registrations = EventDetail::with('guests')
            ->where('event_id', $eventId)
            ->latest()
            ->get();

        return response()->json([
            'registrations' => $registrations
        ]);
    }
}
