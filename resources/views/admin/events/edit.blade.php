@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">{{ isset($event) ? 'Edit' : 'Create' }} Event</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
                    <li class="breadcrumb-item active">{{ isset($event) ? 'Edit' : 'Create' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form action="{{ isset($event) ? route('admin.events.update', $event->id) : route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($event))
                            @method('PUT')
                        @endif

                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Event Title *</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $event->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Event Logo</label>
                                <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                                @if(isset($event) && $event->logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$event->logo) }}" alt="Current Logo" style="max-width: 150px;">
                                    </div>
                                @endif
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date', isset($event) ? $event->start_date->format('Y-m-d') : '') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">End Date *</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date', isset($event) ? $event->end_date->format('Y-m-d') : '') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="1" {{ old('status', $event->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $event->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="event_money" class="form-label">Registration Fee (Optional)</label>
                                <input type="number" name="event_money" id="event_money" class="form-control @error('event_money') is-invalid @enderror"
                                       value="{{ old('event_money', $event->event_money ?? '') }}" step="0.01" min="0" placeholder="Enter fee per person">
                                @error('event_money')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty if the event is free</small>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{ isset($event) ? 'Update' : 'Create' }} Event
                            </button>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
