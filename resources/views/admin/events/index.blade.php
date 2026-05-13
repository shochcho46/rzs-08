@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Events</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Events</li>
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
                        <h3 class="card-title">Event List</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Create Event
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Logo</th>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Registrations</th>
                                    <th>Event Money</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                <tr>
                                    <td>{{ $event->id }}</td>
                                    <td>
                                        @if($event->logo)
                                        <img src="{{ asset('storage/'.$event->logo) }}" alt="Logo" style="max-width: 50px; height: auto;">
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->start_date->format('d M, Y') }}</td>
                                    <td>{{ $event->end_date->format('d M, Y') }}</td>
                                    <td>{{ $event->event_details_count }}</td>
                                    <td>{{ $event->event_money }}</td>
                                    <td>
                                        <span class="badge {{ $event->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $event->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
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
                                    <td colspan="8" class="text-center">No events found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
