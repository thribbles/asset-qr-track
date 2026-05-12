@extends('layouts.app')

@section('title', 'แก้ไขการตรวจสอบ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil me-2"></i>แก้ไขการตรวจสอบ</h4>
    <a href="{{ route('assets.show', $inspection->asset) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>กลับ
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0">{{ $inspection->asset->asset_name }} ({{ $inspection->asset->asset_code }})</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('inspections.update', $inspection) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">สถานะการตรวจสอบ *</label>
                    <select name="condition_status" class="form-select @error('condition_status') is-invalid @enderror" required>
                        <option value="good" {{ old('condition_status', $inspection->condition_status) === 'good' ? 'selected' : '' }}>ดี</option>
                        <option value="fair" {{ old('condition_status', $inspection->condition_status) === 'fair' ? 'selected' : '' }}>พอใช้</option>
                        <option value="poor" {{ old('condition_status', $inspection->condition_status) === 'poor' ? 'selected' : '' }}>แย่</option>
                        <option value="damaged" {{ old('condition_status', $inspection->condition_status) === 'damaged' ? 'selected' : '' }}>เสียหาย</option>
                    </select>
                    @error('condition_status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">วันที่ตรวจสอบ</label>
                    <input type="text" class="form-control" value="{{ $inspection->inspected_at->format('d M Y H:i') }}" disabled>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">หมายเหตุ</label>
                <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks', $inspection->remarks) }}</textarea>
                @error('remarks')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">รูปภาพ</label>
                @if($inspection->image_url)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $inspection->image_url) }}" class="img-thumbnail" style="max-height: 200px;">
                        <p class="small text-muted">รูปปัจจุบัน - อัปโหลดใหม่เพื่อแทนที่</p>
                    </div>
                @endif
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" capture="environment">
                @error('image')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>บันทึกการเปลี่ยนแปลง
                </button>
                <a href="{{ route('assets.show', $inspection->asset) }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
