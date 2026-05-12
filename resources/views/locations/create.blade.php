@extends('layouts.app')

@section('title', 'เพิ่มสถานที่')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-plus-lg me-2"></i>เพิ่มสถานที่</h4>
    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('locations.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="building" class="form-label">อาคาร/สถานที่ *</label>
                    <input type="text" class="form-control @error('building') is-invalid @enderror"
                           id="building" name="building" value="{{ old('building') }}" required>
                    @error('building')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="floor" class="form-label">ชั้น</label>
                    <input type="text" class="form-control @error('floor') is-invalid @enderror"
                           id="floor" name="floor" value="{{ old('floor') }}">
                    @error('floor')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="room" class="form-label">ห้อง</label>
                    <input type="text" class="form-control @error('room') is-invalid @enderror"
                           id="room" name="room" value="{{ old('room') }}">
                    @error('room')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="detail" class="form-label">รายละเอียด</label>
                <textarea class="form-control @error('detail') is-invalid @enderror"
                          id="detail" name="detail" rows="3">{{ old('detail') }}</textarea>
                @error('detail')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>บันทึกสถานที่
                </button>
                <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
