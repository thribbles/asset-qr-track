@extends('layouts.app')

@section('title', 'อัปเดตสถานะการซ่อม')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-tools me-2"></i>อัปเดตสถานะการซ่อม</h4>
    <a href="{{ route('assets.show', $repair->asset_id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>กลับ
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0">ข้อมูลการส่งซ่อม</h6>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td class="text-muted" style="width: 25%">ครุภัณฑ์:</td>
                <td>{{ $repair->asset->asset_code }} - {{ $repair->asset->asset_name }}</td>
            </tr>
            <tr>
                <td class="text-muted">วันที่ส่งซ่อม:</td>
                <td>{{ $repair->requested_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <td class="text-muted">ผู้ส่งซ่อม:</td>
                <td>{{ $repair->requester->name }}</td>
            </tr>
            <tr>
                <td class="text-muted">ปัญหา:</td>
                <td>{{ $repair->issue_description }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('repairs.update', $repair) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">สถานะการซ่อม *</label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="pending" {{ old('status', $repair->status) === 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                        <option value="in_progress" {{ old('status', $repair->status) === 'in_progress' ? 'selected' : '' }}>กำลังซ่อม</option>
                        <option value="completed" {{ old('status', $repair->status) === 'completed' ? 'selected' : '' }}>ซ่อมเสร็จ</option>
                        <option value="cancelled" {{ old('status', $repair->status) === 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="completed_at" class="form-label">วันที่ซ่อมเสร็จ</label>
                    <input type="date" class="form-control @error('completed_at') is-invalid @enderror"
                           id="completed_at" name="completed_at"
                           value="{{ old('completed_at', $repair->completed_at?->format('Y-m-d')) }}">
                    @error('completed_at')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="repair_details" class="form-label">รายละเอียดการซ่อม</label>
                    <textarea class="form-control @error('repair_details') is-invalid @enderror"
                              id="repair_details" name="repair_details" rows="3">{{ old('repair_details', $repair->repair_details) }}</textarea>
                    @error('repair_details')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="repaired_by" class="form-label">ผู้ซ่อม/ร้านซ่อม</label>
                    <input type="text" class="form-control @error('repaired_by') is-invalid @enderror"
                           id="repaired_by" name="repaired_by"
                           value="{{ old('repaired_by', $repair->repaired_by) }}">
                    @error('repaired_by')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cost" class="form-label">ค่าใช้จ่าย (บาท)</label>
                    <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror"
                           id="cost" name="cost"
                           value="{{ old('cost', $repair->cost) }}">
                    @error('cost')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>บันทึก
                </button>
                <a href="{{ route('assets.show', $repair->asset_id) }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
