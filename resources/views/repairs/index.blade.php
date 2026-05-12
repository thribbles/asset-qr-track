@extends('layouts.app')

@section('title', 'ประวัติการซ่อม')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-tools me-2"></i>ประวัติการซ่อม</h4>
    <a href="{{ route('repairs.create') }}" class="btn btn-warning">
        <i class="bi bi-plus-lg me-1"></i>ส่งซ่อม
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @if($repairs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ครุภัณฑ์</th>
                            <th>ปัญหา</th>
                            <th>สถานะ</th>
                            <th>วันที่ส่งซ่อม</th>
                            <th>ผู้ส่งซ่อม</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($repairs as $repair)
                            <tr>
                                <td>
                                    <a href="{{ route('assets.show', $repair->asset) }}" class="text-decoration-none">
                                        {{ $repair->asset->asset_code }}
                                    </a>
                                    <br><small class="text-muted">{{ $repair->asset->asset_name }}</small>
                                </td>
                                <td>{{ Str::limit($repair->issue_description, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ $repair->status === 'completed' ? 'success' : ($repair->status === 'in_progress' ? 'info' : ($repair->status === 'cancelled' ? 'secondary' : 'warning')) }}">
                                        @if($repair->status === 'pending') รอดำเนินการ
                                        @elseif($repair->status === 'in_progress') กำลังซ่อม
                                        @elseif($repair->status === 'completed') ซ่อมเสร็จ
                                        @else ยกเลิก
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $repair->requested_at->format('d M Y') }}</td>
                                <td>{{ $repair->requester->name }}</td>
                                <td>
                                    <a href="{{ route('repairs.edit', $repair) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $repairs->links() }}
        @else
            <div class="text-center py-4 text-muted">
                <i class="bi bi-tools display-4"></i>
                <p class="mb-0">ยังไม่มีการส่งซ่อม</p>
            </div>
        @endif
    </div>
</div>
@endsection
