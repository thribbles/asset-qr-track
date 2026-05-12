@extends('layouts.app')

@section('title', 'รายละเอียดการยืม-คืน')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-info-circle me-2"></i>รายละเอียดการยืม-คืน</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>ย้อนกลับ
        </a>
        @if($loan->status === 'borrowed')
            <a href="{{ route('loans.edit', $loan) }}" class="btn btn-success">
                <i class="bi bi-arrow-return-left me-1"></i>คืนครุภัณฑ์
            </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">ข้อมูลครุภัณฑ์</h6>
            </div>
            <div class="card-body text-center">
                @if($loan->asset->image_path)
                    <img src="{{ asset('storage/' . $loan->asset->image_path) }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                @endif
                <h5 class="text-primary">{{ $loan->asset->asset_code }}</h5>
                <p class="fw-bold mb-1">{{ $loan->asset->asset_name }}</p>
                <p class="text-muted small">{{ $loan->asset->location->building }}</p>
                <hr>
                <a href="{{ route('assets.show', $loan->asset) }}" class="btn btn-sm btn-outline-primary">
                    ดูรายละเอียดครุภัณฑ์
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">ข้อมูลการยืม-คืน</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="30%" class="bg-light">สถานะ</th>
                        <td>
                            @if($loan->status === 'borrowed')
                                <span class="badge bg-warning text-dark">กำลังยืม</span>
                            @elseif($loan->status === 'returned')
                                <span class="badge bg-success">คืนแล้ว</span>
                            @elseif($loan->status === 'overdue')
                                <span class="badge bg-danger">เกินกำหนด</span>
                            @else
                                <span class="badge bg-secondary">{{ $loan->status }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">ผู้ยืม</th>
                        <td>
                            <strong>{{ $loan->borrower_name }}</strong>
                            @if($loan->borrower_department)
                                <br><small class="text-muted">แผนก: {{ $loan->borrower_department }}</small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">วันที่ยืม</th>
                        <td>{{ $loan->borrowed_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">กำหนดคืน</th>
                        <td>{{ $loan->due_at ? $loan->due_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">ผู้ทำรายการยืม</th>
                        <td>{{ $loan->issuer->name ?? 'System' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">วัตถุประสงค์</th>
                        <td>{{ $loan->purpose ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">หมายเหตุการยืม</th>
                        <td>{{ $loan->borrow_remarks ?: '-' }}</td>
                    </tr>

                    @if($loan->returned_at)
                    <tr class="table-success">
                        <th class="bg-light">วันที่คืนจริง</th>
                        <td>{{ $loan->returned_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr class="table-success">
                        <th class="bg-light">ผู้รับคืน</th>
                        <td>{{ $loan->receiver->name ?? '-' }}</td>
                    </tr>
                    <tr class="table-success">
                        <th class="bg-light">หมายเหตุการคืน</th>
                        <td>{{ $loan->return_remarks ?: '-' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
