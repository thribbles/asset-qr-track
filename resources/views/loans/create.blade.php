@extends('layouts.app')

@section('title', 'บันทึกการยืมครุภัณฑ์')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-plus-lg me-2"></i>บันทึกการยืมครุภัณฑ์</h4>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>ย้อนกลับ
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="asset_id" class="form-label">เลือกครุภัณฑ์ *</label>
                    <select name="asset_id" id="asset_id" class="form-select @error('asset_id') is-invalid @enderror" required>
                        <option value="">-- เลือกครุภัณฑ์ --</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ (isset($selectedAsset) && $selectedAsset->id == $asset->id) || old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_code }} - {{ $asset->asset_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="borrower_name" class="form-label">ชื่อผู้ยืม *</label>
                    <input type="text" class="form-control @error('borrower_name') is-invalid @enderror" 
                           id="borrower_name" name="borrower_name" value="{{ old('borrower_name') }}" required>
                    @error('borrower_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="borrower_department" class="form-label">แผนก/หน่วยงาน ผู้ยืม</label>
                    <input type="text" class="form-control @error('borrower_department') is-invalid @enderror" 
                           id="borrower_department" name="borrower_department" value="{{ old('borrower_department') }}">
                    @error('borrower_department')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="borrowed_at" class="form-label">วันที่ยืม *</label>
                    <input type="datetime-local" class="form-control @error('borrowed_at') is-invalid @enderror" 
                           id="borrowed_at" name="borrowed_at" value="{{ old('borrowed_at', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('borrowed_at')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="due_at" class="form-label">กำหนดคืน</label>
                    <input type="date" class="form-control @error('due_at') is-invalid @enderror" 
                           id="due_at" name="due_at" value="{{ old('due_at') }}">
                    @error('due_at')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="purpose" class="form-label">วัตถุประสงค์การยืม</label>
                <textarea class="form-control @error('purpose') is-invalid @enderror" 
                          id="purpose" name="purpose" rows="2">{{ old('purpose') }}</textarea>
                @error('purpose')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="borrow_remarks" class="form-label">หมายเหตุการยืม</label>
                <textarea class="form-control @error('borrow_remarks') is-invalid @enderror" 
                          id="borrow_remarks" name="borrow_remarks" rows="2">{{ old('borrow_remarks') }}</textarea>
                @error('borrow_remarks')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>บันทึกการยืม
                </button>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
