@extends('layouts.app')

@section('title', 'ส่งซ่อมครุภัณฑ์')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-tools me-2"></i>ส่งซ่อมครุภัณฑ์</h4>
    <a href="{{ route('assets.show', $asset ?? $assets->first()) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>กลับ
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('repairs.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="asset_id" class="form-label">ครุภัณฑ์ *</label>
                <select class="form-select @error('asset_id') is-invalid @enderror"
                        id="asset_id" name="asset_id" required>
                    @if(isset($asset))
                        <option value="{{ $asset->id }}" selected>
                            {{ $asset->asset_code }} - {{ $asset->asset_name }}
                        </option>
                    @else
                        <option value="">เลือกครุภัณฑ์</option>
                        @foreach($assets as $a)
                            <option value="{{ $a->id }}" {{ old('asset_id') == $a->id ? 'selected' : '' }}>
                                {{ $a->asset_code }} - {{ $a->asset_name }} ({{ $a->location->building ?? 'ไม่ระบุ' }})
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('asset_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="requested_at" class="form-label">วันที่ส่งซ่อม *</label>
                <input type="date" class="form-control @error('requested_at') is-invalid @enderror"
                       id="requested_at" name="requested_at" value="{{ old('requested_at', date('Y-m-d')) }}" required>
                @error('requested_at')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="issue_description" class="form-label">รายละเอียดปัญหา *</label>
                <textarea class="form-control @error('issue_description') is-invalid @enderror"
                          id="issue_description" name="issue_description" rows="4" required>{{ old('issue_description') }}</textarea>
                <small class="text-muted">อธิบายอาการเสีย หรือส่วนที่ชำรุดที่ต้องซ่อม</small>
                @error('issue_description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-tools me-1"></i>ส่งซ่อม
                </button>
                <a href="{{ route('assets.show', $asset ?? ($assets->first() ?? 1)) }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
