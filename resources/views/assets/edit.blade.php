@extends('layouts.app')

@section('title', 'แก้ไขครุภัณฑ์')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil me-2"></i>แก้ไขครุภัณฑ์</h4>
    <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>กลับ
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="asset_code" class="form-label">รหัสครุภัณฑ์ *</label>
                    <input type="text" class="form-control @error('asset_code') is-invalid @enderror"
                           id="asset_code" name="asset_code" value="{{ old('asset_code', $asset->asset_code) }}" required>
                    @error('asset_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="asset_name" class="form-label">ชื่อครุภัณฑ์ *</label>
                    <input type="text" class="form-control @error('asset_name') is-invalid @enderror"
                           id="asset_name" name="asset_name" value="{{ old('asset_name', $asset->asset_name) }}" required>
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
                        <option value="material" {{ old('asset_type', $asset->asset_type) === 'material' ? 'selected' : '' }}>วัสดุ</option>
                        <option value="durable" {{ old('asset_type', $asset->asset_type) === 'durable' ? 'selected' : '' }}>ครุภัณฑ์</option>
                    </select>
                    @error('asset_type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">สถานะ *</label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="active" {{ old('status', $asset->status) === 'active' ? 'selected' : '' }}>ใช้งานได้</option>
                        <option value="inactive" {{ old('status', $asset->status) === 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                        <option value="damaged" {{ old('status', $asset->status) === 'damaged' ? 'selected' : '' }}>เสียหาย</option>
                        <option value="disposed" {{ old('status', $asset->status) === 'disposed' ? 'selected' : '' }}>จำหน่ายแล้ว</option>
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
                           id="purchase_date" name="purchase_date" 
                           value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}">
                    @error('purchase_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3" id="disposal_date_container" style="display: {{ (old('status', $asset->status) === 'disposed') ? 'block' : 'none' }};">
                    <label for="disposal_date" class="form-label">วันที่จำหน่าย *</label>
                    <input type="date" class="form-control @error('disposal_date') is-invalid @enderror"
                           id="disposal_date" name="disposal_date" 
                           value="{{ old('disposal_date', $asset->disposal_date?->format('Y-m-d')) }}">
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
                            <option value="{{ $location->id }}" {{ old('location_id', $asset->location_id) == $location->id ? 'selected' : '' }}>
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
                           id="department" name="department" 
                           value="{{ old('department', $asset->department) }}">
                    @error('department')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="responsible_person" class="form-label">ผู้รับผิดชอบ</label>
                    <input type="text" class="form-control @error('responsible_person') is-invalid @enderror"
                           id="responsible_person" name="responsible_person" 
                           value="{{ old('responsible_person', $asset->responsible_person) }}">
                    @error('responsible_person')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">หมายเหตุ</label>
                <textarea class="form-control @error('notes') is-invalid @enderror"
                          id="notes" name="notes" rows="3">{{ old('notes', $asset->notes) }}</textarea>
                @error('notes')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">รูปภาพครุภัณฑ์ (รวมสูงสุด 4 รูป)</label>

                {{-- แสดงรูปที่มีอยู่และตัวเลือกลบ --}}
                @php $existingImages = $asset->images ?? []; @endphp
                @if(count($existingImages) > 0)
                    <div class="row g-2 mb-3">
                        @foreach($existingImages as $index => $imgPath)
                            <div class="col-md-3">
                                <div class="position-relative border rounded p-1 text-center">
                                    <img src="{{ asset('storage/' . $imgPath) }}" class="img-thumbnail w-100 mb-2" style="height: 100px; object-fit: cover;">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="checkbox" name="remove_images[]" value="{{ $imgPath }}" id="remove_image_{{ $index }}">
                                        <label class="form-check-label text-danger" for="remove_image_{{ $index }}">
                                            ลบรูปนี้
                                        </label>
                                    </div>
                                    <input type="hidden" name="keep_images[]" value="{{ $imgPath }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- อัปโหลดรูปใหม่ --}}
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="form-label text-muted small">อัปโหลดรูปเพิ่มเติม (เลือกได้หลายรูป)</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror"
                               name="images[]" accept="image/*" multiple capture="environment">
                        @error('images')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>บันทึกการเปลี่ยนแปลง
                </button>
                <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary">ยกเลิก</a>
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
