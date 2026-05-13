@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">{{ isset($eventDetail) ? 'Edit' : 'Add' }} Registration</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.event-details.index') }}">Registrations</a></li>
                    <li class="breadcrumb-item active">{{ isset($eventDetail) ? 'Edit' : 'Add' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <form action="{{ isset($eventDetail) ? route('admin.event-details.update', $eventDetail->id) : route('admin.event-details.store') }}" method="POST">
                        @csrf
                        @if(isset($eventDetail))
                            @method('PUT')
                        @endif

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="event_id" class="form-label">Event *</label>
                                    <select name="event_id" id="event_id" class="form-control @error('event_id') is-invalid @enderror" required>
                                        <option value="">Select Event</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ old('event_id', $eventDetail->event_id ?? '') == $event->id ? 'selected' : '' }}>
                                                {{ $event->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $eventDetail->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="mobile" class="form-label">Mobile Number *</label>
                                    <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                           value="{{ old('mobile', $eventDetail->mobile ?? '') }}" required>
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="jersey_name" class="form-label">Jersey Name *</label>
                                    <input type="text" name="jersey_name" id="jersey_name" class="form-control @error('jersey_name') is-invalid @enderror"
                                           value="{{ old('jersey_name', $eventDetail->jersey_name ?? '') }}" required>
                                    @error('jersey_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="jersey_number" class="form-label">Jersey Number *</label>
                                    <input type="text" name="jersey_number" id="jersey_number" class="form-control @error('jersey_number') is-invalid @enderror"
                                           value="{{ old('jersey_number', $eventDetail->jersey_number ?? '') }}" required>
                                    @error('jersey_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="size" class="form-label">Size *</label>
                                    <select name="size" id="size" class="form-control @error('size') is-invalid @enderror" onchange="toggleCustom()" required>
                                        <option value="">Select Size</option>
                                        <option value="SM" {{ old('size', $eventDetail->size ?? '') == 'SM' ? 'selected' : '' }}>SM</option>
                                        <option value="M" {{ old('size', $eventDetail->size ?? '') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('size', $eventDetail->size ?? '') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('size', $eventDetail->size ?? '') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ old('size', $eventDetail->size ?? '') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        <option value="custom" {{ old('size', $eventDetail->size ?? '') == 'custom' ? 'selected' : '' }}>Custom</option>
                                    </select>
                                    @error('size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3" id="custom_fields" style="display:none;">
                                    <label class="form-label">Custom Size</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" name="custom_width" placeholder="Width" class="form-control" value="{{ old('custom_width', $eventDetail->custom_width ?? '') }}">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="custom_height" placeholder="Height" class="form-control" value="{{ old('custom_height', $eventDetail->custom_height ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="sleeve_type" class="form-label">Sleeve Type *</label>
                                    <select name="sleeve_type" id="sleeve_type" class="form-control @error('sleeve_type') is-invalid @enderror" required>
                                        <option value="half_sleeve" {{ old('sleeve_type', $eventDetail->sleeve_type ?? '') == 'half_sleeve' ? 'selected' : '' }}>Half Sleeve</option>
                                        <option value="full_sleeve" {{ old('sleeve_type', $eventDetail->sleeve_type ?? '') == 'full_sleeve' ? 'selected' : '' }}>Full Sleeve</option>
                                    </select>
                                    @error('sleeve_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="is_guest_jersey" class="form-label">Jersey Type *</label>
                                    <select name="is_guest_jersey" id="is_guest_jersey" class="form-control @error('is_guest_jersey') is-invalid @enderror" required>
                                        <option value="0" {{ old('is_guest_jersey', $eventDetail->is_guest_jersey ?? 0) == 0 ? 'selected' : '' }}>Own Jersey</option>
                                        <option value="1" {{ old('is_guest_jersey', $eventDetail->is_guest_jersey ?? 0) == 1 ? 'selected' : '' }}>Guest Jersey</option>
                                    </select>
                                    @error('is_guest_jersey')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="play_status" class="form-label">Play Status *</label>
                                    <select name="play_status" id="play_status" class="form-control @error('play_status') is-invalid @enderror" required>
                                        <option value="1" {{ old('play_status', $eventDetail->play_status ?? 1) == 1 ? 'selected' : '' }}>Player</option>
                                        <option value="0" {{ old('play_status', $eventDetail->play_status ?? 1) == 0 ? 'selected' : '' }}>Only Jersey</option>
                                    </select>
                                    @error('play_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{ isset($eventDetail) ? 'Update' : 'Add' }} Registration
                            </button>
                            <a href="{{ route('admin.event-details.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustom() {
    const size = document.getElementById('size').value;
    document.getElementById('custom_fields').style.display = size === 'custom' ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleCustom);
</script>
@endsection
