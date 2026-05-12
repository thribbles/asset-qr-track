@extends('layouts.app')

@section('title', 'เพิ่มครุภัณฑ์')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-plus-lg me-2"></i>เพิ่มครุภัณฑ์</h4>
    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>กลับ
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="asset_code" class="form-label">รหัสครุภัณฑ์ *</label>
                    <input type="text" class="form-control @error('asset_code') is-invalid @enderror"
                           id="asset_code" name="asset_code" value="{{ old('asset_code') }}" required>
                    @error('asset_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="asset_name" class="form-label">ชื่อครุภัณฑ์ *</label>
                    <input type="text" class="form-control @error('asset_name') is-invalid @enderror"
                           id="asset_name" name="asset_name" value="{{ old('asset_name') }}" required>
                    @error('asset_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="asset_type" class="form-label">ประเภท *</label>
                    <select class="form-select @error('asset_type') is-invalid @enderror"
                            id="asset_type" name="asset_type" required>
                        <option value="">เลือกประเภท</option>
                        <option value="material" {{ old('asset_type') === 'material' ? 'selected' : '' }}>วัสดุ</option>
                        <option value="durable" {{ old('asset_type') === 'durable' ? 'selected' : '' }}>ครุภัณฑ์</option>
                    </select>
                    @error('asset_type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">สถานะ *</label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>ใช้งานได้</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                        <option value="damaged" {{ old('status') === 'damaged' ? 'selected' : '' }}>เสียหาย</option>
                        <option value="disposed" {{ old('status') === 'disposed' ? 'selected' : '' }}>จำหน่ายแล้ว</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="purchase_date" class="form-label">วันที่ซื้อ</label>
                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                           id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                    @error('purchase_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3" id="disposal_date_container" style="display: {{ old('status') === 'disposed' ? 'block' : 'none' }};">
                    <label for="disposal_date" class="form-label">วันที่จำหน่าย *</label>
                    <input type="date" class="form-control @error('disposal_date') is-invalid @enderror"
                           id="disposal_date" name="disposal_date" value="{{ old('disposal_date') }}">
                    @error('disposal_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="location_id" class="form-label">สถานที่</label>
                    <select class="form-select @error('location_id') is-invalid @enderror"
                            id="location_id" name="location_id">
                        <option value="">เลือกสถานที่</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->building }} {{ $location->floor }} {{ $location->room }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="department" class="form-label">ฝ่าย/กอง</label>
                    <input type="text" class="form-control @error('department') is-invalid @enderror"
                           id="department" name="department" value="{{ old('department') }}">
                    @error('department')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="responsible_person" class="form-label">ผู้รับผิดชอบ</label>
                    <input type="text" class="form-control @error('responsible_person') is-invalid @enderror"
                           id="responsible_person" name="responsible_person" value="{{ old('responsible_person') }}">
                    @error('responsible_person')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">หมายเหตุ</label>
                <textarea class="form-control @error('notes') is-invalid @enderror"
                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">รูปภาพครุภัณฑ์ (สูงสุด 4 รูป)</label>
                <div class="row g-2">
                    @for($i = 0; $i < 4; $i++)
                        <div class="col-md-6">
                            <input type="file" class="form-control @error('images.' . $i) is-invalid @enderror"
                                   name="images[]" accept="image/*" capture="environment">
                            @error('images.' . $i)
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    @endfor
                </div>
                <small class="text-muted">สามารถอัปโหลดได้สูงสุด 4 รูปภาพ (แต่ละรูปไม่เกิน 5MB)</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>บันทึกครุภัณฑ์
                </button>
                <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('status').addEventListener('change', function() {
        const disposalDateContainer = document.getElementById('disposal_date_container');
        const disposalDateInput = document.getElementById('disposal_date');

        if (this.value === 'disposed') {
            disposalDateContainer.style.display = 'block';
            disposalDateInput.required = true;
        } else {
            disposalDateContainer.style.display = 'none';
            disposalDateInput.required = false;
            disposalDateInput.value = '';
        }
    });
</script>
@endpush
@endsection
