@extends('layouts.app')

@section('title', 'รายการยืม-คืนครุภัณฑ์')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-arrow-left-right me-2"></i>รายการยืม-คืนครุภัณฑ์</h4>
    <a href="{{ route('loans.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>บันทึกการยืมใหม่
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ครุภัณฑ์</th>
                    <th>ผู้ยืม</th>
                    <th>วันที่ยืม</th>
                    <th>กำหนดคืน</th>
                    <th>วันที่คืนจริง</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td>
                            @if($loan->asset)
                                <strong>{{ $loan->asset->asset_name }}</strong>
                                <br><small class="text-muted">{{ $loan->asset->asset_code }}</small>
                            @else
                                <span class="text-danger">ไม่พบข้อมูลครุภัณฑ์</span>
                            @endif
                        </td>
                        <td>
                            {{ $loan->borrower_name }}
                            @if($loan->borrower_department)
                                <br><small class="text-muted">{{ $loan->borrower_department }}</small>
                            @endif
                        </td>
                        <td>{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                        <td>{{ $loan->due_at ? $loan->due_at->format('d/m/Y') : '-' }}</td>
                        <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y') : '-' }}</td>
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
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if($loan->status === 'borrowed')
                                    <a href="{{ route('loans.edit', $loan) }}" class="btn btn-success" title="คืนครุภัณฑ์">
                                        <i class="bi bi-arrow-return-left"></i> คืน
                                    </a>
                                @endif
                                <a href="{{ route('loans.show', $loan) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('loans.edit', $loan) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">ไม่พบข้อมูลการยืม-คืน</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($loans->hasPages())
        <div class="card-footer">
            {{ $loans->links() }}
        </div>
    @endif
</div>
@endsection
