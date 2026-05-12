@extends('layouts.app')

@section('title', 'New Inspection')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-clipboard-check me-2"></i>New Inspection</h4>
    <a href="{{ route('inspections.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('inspections.store') }}" method="POST" enctype="multipart/form-data">
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

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="inspected_at" class="form-label">Inspection Date *</label>
                    <input type="datetime-local" class="form-control @error('inspected_at') is-invalid @enderror"
                           id="inspected_at" name="inspected_at" 
                           value="{{ old('inspected_at', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('inspected_at')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="condition_status" class="form-label">Condition *</label>
                    <select class="form-select @error('condition_status') is-invalid @enderror"
                            id="condition_status" name="condition_status" required>
                        <option value="good" {{ old('condition_status') === 'good' ? 'selected' : '' }}>Good</option>
                        <option value="fair" {{ old('condition_status') === 'fair' ? 'selected' : '' }}>Fair</option>
                        <option value="poor" {{ old('condition_status') === 'poor' ? 'selected' : '' }}>Poor</option>
                        <option value="damaged" {{ old('condition_status') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                    </select>
                    @error('condition_status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control @error('remarks') is-invalid @enderror"
                          id="remarks" name="remarks" rows="3">{{ old('remarks') }}</textarea>
                @error('remarks')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Photo</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror"
                       id="image" name="image" accept="image/*">
                @error('image')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i>Save Inspection
                </button>
                <a href="{{ route('inspections.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
