@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Event Registrations</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Registrations</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @php
                            $displayTitle = 'All Events';
                            if(request('event_id') && $eventDetails->isNotEmpty()) {
                                $displayTitle = $eventDetails->first()->event->title;
                            }
                        @endphp
                        <h3 class="card-title">
                            Registration List - <span class="badge" style="background-color: #7C3AED; color: white;">{{ $displayTitle }}</span>
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.event-details.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Add Registration
                            </a>
                            <a href="{{ route('admin.event-details.export', request()->all()) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-download"></i> Export to Excel
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="event_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Events</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>

                        @php
                            $totalRegistrations = $eventDetails->total();
                            $totalMainPersons = 0;
                            $totalGuests = 0;
                            $totalMainMoney = 0;
                            $totalGuestMoney = 0;
                            foreach($eventDetails as $detail) {
                                $totalMainPersons++;
                                $guestCount = $detail->guests ? $detail->guests->count() : 0;
                                $totalGuests += $guestCount;
                                $eventMoney = $detail->event->event_money ?? 0;
                                $totalMainMoney += $eventMoney;
                                $totalGuestMoney += ($eventMoney * $guestCount);
                            }
                            $grandTotal = $totalMainMoney + $totalGuestMoney;
                        @endphp

                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Total Registrations:</strong> {{ $totalRegistrations }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Main Persons:</strong> {{ $totalMainPersons }} | <strong>Guests:</strong> {{ $totalGuests }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Main Money:</strong> <span class="text-primary">{{ number_format($totalMainMoney, 2) }}</span><br>
                                    <strong>Guest Money:</strong> <span class="text-warning">{{ number_format($totalGuestMoney, 2) }}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Grand Total:</strong> <span class="text-success fs-5">{{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Event</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Jersey Info</th>
                                        <th>Guests</th>
                                        <th>Main Money</th>
                                        <th>Guest Money</th>
                                        <th>Total Money</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($eventDetails as $loop_index => $detail)
                                    <tr>
                                        <td>{{ $loop_index + 1 }}</td>
                                        <td>{{ $detail->event->title }}</td>
                                        <td>{{ $detail->name }}</td>
                                        <td>{{ $detail->mobile }}</td>
                                        <td>
                                            <strong>{{ $detail->jersey_name }} #{{ $detail->jersey_number }}</strong><br>
                                            <small>Size: {{ $detail->size }}
                                            @if($detail->custom_width && $detail->custom_height)
                                                ({{ $detail->custom_width }}x{{ $detail->custom_height }})
                                            @endif<br>
                                            {{ ucfirst(str_replace('_', ' ', $detail->sleeve_type)) }}</small>
                                        </td>
                                        <td>
                                            @if($detail->guests && $detail->guests->count() > 0)
                                                <span class="badge bg-primary">{{ $detail->guests->count() }} Guest(s)</span>
                                                <div class="mt-1">
                                                    @foreach($detail->guests as $guest)
                                                        <small class="d-block text-muted">
                                                            • {{ $guest->name }}<br>
                                                            &nbsp;&nbsp;{{ $guest->jersey_name }} #{{ $guest->jersey_number }} - {{ $guest->size }} - {{ ucfirst(str_replace('_', ' ', $guest->sleeve_type)) }}
                                                        </small>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">No guests</span>
                                            @endif
                                        </td>
                                        @php
                                            $eventMoney = $detail->event->event_money ?? 0;
                                            $guestCount = $detail->guests ? $detail->guests->count() : 0;
                                            $mainMoney = $eventMoney;
                                            $guestMoney = $eventMoney * $guestCount;
                                            $totalMoney = $mainMoney + $guestMoney;
                                        @endphp
                                        <td>
                                            @if($eventMoney > 0)
                                                <strong class="text-primary">{{ number_format($mainMoney, 2) }}</strong>
                                            @else
                                                <span class="badge bg-info">Free</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($eventMoney > 0 && $guestCount > 0)
                                                <strong class="text-warning">{{ number_format($guestMoney, 2) }}</strong><br>
                                                <small class="text-muted">({{ $guestCount }} x {{ number_format($eventMoney, 2) }})</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($eventMoney > 0)
                                                <strong class="text-success">{{ number_format($totalMoney, 2) }}</strong>
                                            @else
                                                <span class="badge bg-info">Free</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $detail->play_status ? 'bg-success' : 'bg-info' }}">
                                                {{ $detail->play_status ? 'Player' : 'Jersey Only' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.event-details.edit', $detail->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.event-details.destroy', $detail->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No registrations found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $eventDetails->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
