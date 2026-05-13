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
                            <input type="hidden" name="is_guest_jersey" value="0">
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
                                    <input type="number" name="jersey_number" id="jersey_number" class="form-control @error('jersey_number') is-invalid @enderror"
                                           value="{{ old('jersey_number', $eventDetail->jersey_number ?? '') }}" step="0.01" required>
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

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Guest Registrations (Optional)</h5>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="addGuest()">
                                            <i class="bi bi-plus-circle"></i> Add Guest
                                        </button>
                                    </div>
                                    <div id="guestContainer"></div>
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
let guestCount = 0;

function toggleCustom() {
    const size = document.getElementById('size').value;
    document.getElementById('custom_fields').style.display = size === 'custom' ? 'block' : 'none';
}

function toggleGuestCustom(selectElement, guestIndex) {
    const customDiv = document.getElementById(`guest_custom_${guestIndex}`);
    customDiv.style.display = selectElement.value === 'custom' ? 'block' : 'none';
}

function addGuest() {
    guestCount++;
    const container = document.getElementById('guestContainer');
    const guestHtml = `
        <div class="card mb-3" id="guest_${guestCount}">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Guest #${guestCount}</h6>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeGuest(${guestCount})">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="guests[${guestCount}][name]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jersey Name *</label>
                        <input type="text" name="guests[${guestCount}][jersey_name]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jersey Number *</label>
                        <input type="number" name="guests[${guestCount}][jersey_number]" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Size *</label>
                        <select name="guests[${guestCount}][size]" class="form-control" onchange="toggleGuestCustom(this, ${guestCount})" required>
                            <option value="">Select Size</option>
                            <option value="SM">SM</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="guest_custom_${guestCount}" style="display:none;">
                        <label class="form-label">Custom Size</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" name="guests[${guestCount}][custom_width]" placeholder="Width" class="form-control">
                            </div>
                            <div class="col-6">
                                <input type="text" name="guests[${guestCount}][custom_height]" placeholder="Height" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sleeve Type *</label>
                        <select name="guests[${guestCount}][sleeve_type]" class="form-control" required>
                            <option value="half_sleeve">Half Sleeve</option>
                            <option value="full_sleeve">Full Sleeve</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', guestHtml);
}

function removeGuest(index) {
    const guestDiv = document.getElementById(`guest_${index}`);
    if (guestDiv) {
        guestDiv.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleCustom();

    @if(isset($eventDetail) && $eventDetail->guests && $eventDetail->guests->count() > 0)
        @foreach($eventDetail->guests as $index => $guest)
            guestCount++;
            const container{{ $loop->iteration }} = document.getElementById('guestContainer');
            const guestHtml{{ $loop->iteration }} = `
                <div class="card mb-3" id="guest_${guestCount}">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Guest #${guestCount}</h6>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeGuest(${guestCount})">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="guests[${guestCount}][id]" value="{{ $guest->id }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="guests[${guestCount}][name]" class="form-control" value="{{ $guest->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jersey Name *</label>
                                <input type="text" name="guests[${guestCount}][jersey_name]" class="form-control" value="{{ $guest->jersey_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jersey Number *</label>
                                <input type="number" name="guests[${guestCount}][jersey_number]" class="form-control" value="{{ $guest->jersey_number }}" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Size *</label>
                                <select name="guests[${guestCount}][size]" class="form-control" onchange="toggleGuestCustom(this, ${guestCount})" required>
                                    <option value="">Select Size</option>
                                    <option value="SM" {{ $guest->size == 'SM' ? 'selected' : '' }}>SM</option>
                                    <option value="M" {{ $guest->size == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="L" {{ $guest->size == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="XL" {{ $guest->size == 'XL' ? 'selected' : '' }}>XL</option>
                                    <option value="XXL" {{ $guest->size == 'XXL' ? 'selected' : '' }}>XXL</option>
                                    <option value="custom" {{ $guest->size == 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="guest_custom_${guestCount}" style="display:{{ $guest->size == 'custom' ? 'block' : 'none' }};">
                                <label class="form-label">Custom Size</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" name="guests[${guestCount}][custom_width]" placeholder="Width" class="form-control" value="{{ $guest->custom_width ?? '' }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="guests[${guestCount}][custom_height]" placeholder="Height" class="form-control" value="{{ $guest->custom_height ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sleeve Type *</label>
                                <select name="guests[${guestCount}][sleeve_type]" class="form-control" required>
                                    <option value="half_sleeve" {{ $guest->sleeve_type == 'half_sleeve' ? 'selected' : '' }}>Half Sleeve</option>
                                    <option value="full_sleeve" {{ $guest->sleeve_type == 'full_sleeve' ? 'selected' : '' }}>Full Sleeve</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container{{ $loop->iteration }}.insertAdjacentHTML('beforeend', guestHtml{{ $loop->iteration }});
        @endforeach
    @endif
});
</script>
@endsection
