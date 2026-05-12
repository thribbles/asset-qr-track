@extends('layouts.app')

@section('title', 'New Transfer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-arrow-left-right me-2"></i>New Transfer</h4>
    <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('transfers.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="asset_id" class="form-label">Asset *</label>
                @if(isset($asset))
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <input type="text" class="form-control" value="{{ $asset->asset_name }} ({{ $asset->asset_code }})" disabled>
                @else
                    <select class="form-select @error('asset_id') is-invalid @enderror"
                            id="asset_id" name="asset_id" required>
                        <option value="">Select Asset</option>
                        @foreach($assets as $a)
                            <option value="{{ $a->id }}" {{ old('asset_id') == $a->id ? 'selected' : '' }}>
                                {{ $a->asset_name }} ({{ $a->asset_code }})
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('asset_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="to_location" class="form-label">Destination Location *</label>
                <select class="form-select @error('to_location') is-invalid @enderror"
                        id="to_location" name="to_location" required>
                    <option value="">Select Location</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ old('to_location') == $location->id ? 'selected' : '' }}>
                            {{ $location->building }} {{ $location->floor }} {{ $location->room }}
                        </option>
                    @endforeach
                </select>
                @error('to_location')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="transferred_at" class="form-label">Transfer Date *</label>
                <input type="datetime-local" class="form-control @error('transferred_at') is-invalid @enderror"
                       id="transferred_at" name="transferred_at" 
                       value="{{ old('transferred_at', now()->format('Y-m-d\TH:i')) }}" required>
                @error('transferred_at')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason</label>
                <textarea class="form-control @error('reason') is-invalid @enderror"
                          id="reason" name="reason" rows="3">{{ old('reason') }}</textarea>
                @error('reason')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Save Transfer
                </button>
                <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
