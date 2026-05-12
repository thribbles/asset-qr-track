@extends('layouts.app')

@section('title', 'จัดการการยืม-คืน')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil me-2"></i>จัดการการยืม-คืน: {{ $loan->asset->asset_code }}</h4>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>ย้อนกลับ
    </a>
</div>

<div class="row">
    {{-- Return Form --}}
    @if($loan->status === 'borrowed')
    <div class="col-md-5">
        <div class="card shadow-sm border-success mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-arrow-return-left me-2"></i>บันทึกการคืนครุภัณฑ์</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('loans.update', $loan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="return">

                    <div class="mb-3">
                        <label for="returned_at" class="form-label">วันที่คืนจริง *</label>
                        <input type="datetime-local" class="form-control" id="returned_at" name="returned_at" 
                               value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">สภาพเมื่อคืน *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="returned">คืนปกติ (สภาพดี)</option>
                            <option value="damaged">คืน (ชำรุด/เสียหาย)</option>
                            <option value="lost">สูญหาย</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="return_remarks" class="form-label">หมายเหตุการคืน</label>
                        <textarea class="form-control" id="return_remarks" name="return_remarks" rows="3" placeholder="ระบุรายละเอียดสภาพเมื่อคืน..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-1"></i>ยืนยันการคืน
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Edit Info Form --}}
    <div class="{{ $loan->status === 'borrowed' ? 'col-md-7' : 'col-md-12' }}">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">แก้ไขข้อมูลการยืม</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('loans.update', $loan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label text-muted">ครุภัณฑ์</label>
                        <input type="text" class="form-control bg-light" value="{{ $loan->asset->asset_code }} - {{ $loan->asset->asset_name }}" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="borrower_name" class="form-label">ชื่อผู้ยืม *</label>
                            <input type="text" class="form-control" id="borrower_name" name="borrower_name" value="{{ $loan->borrower_name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="borrower_department" class="form-label">แผนก/หน่วยงาน</label>
                            <input type="text" class="form-control" id="borrower_department" name="borrower_department" value="{{ $loan->borrower_department }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">วันที่ยืม</label>
                            <input type="text" class="form-control bg-light" value="{{ $loan->borrowed_at->format('d/m/Y H:i') }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="due_at" class="form-label">กำหนดคืน</label>
                            <input type="date" class="form-control" id="due_at" name="due_at" value="{{ $loan->due_at ? $loan->due_at->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="purpose" class="form-label">วัตถุประสงค์การยืม</label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="2">{{ $loan->purpose }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="borrow_remarks" class="form-label">หมายเหตุการยืม</label>
                        <textarea class="form-control" id="borrow_remarks" name="borrow_remarks" rows="2">{{ $loan->borrow_remarks }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>บันทึกการแก้ไข
                        </button>
                        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
