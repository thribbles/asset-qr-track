@extends('layouts.app')

@section('title', 'ประวัติการตรวจสอบ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-clipboard-check me-2"></i>ประวัติการตรวจสอบ</h4>
    <a href="{{ route('inspections.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i>บันทึกการตรวจสอบใหม่
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ครุภัณฑ์</th>
                    <th>ผู้ตรวจสอบ</th>
                    <th>วันที่ตรวจสอบ</th>
                    <th>สภาพ</th>
                    <th>หมายเหตุ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inspections as $inspection)
                    <tr>
                        <td>
                            @if($inspection->asset)
                                {{ $inspection->asset->asset_name }}
                                <br><small class="text-muted">{{ $inspection->asset->asset_code }}</small>
                                @if($inspection->asset->trashed())
                                    <span class="badge bg-secondary">จำหน่ายแล้ว/ลบแล้ว</span>
                                @endif
                            @else
                                <span class="text-danger">ไม่พบข้อมูลครุภัณฑ์ (ถูกลบถาวร)</span>
                            @endif
                        </td>
                        <td>{{ $inspection->inspector->name ?? 'System' }}</td>
                        <td>{{ $inspection->inspected_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $inspection->condition_status === 'good' ? 'success' : ($inspection->condition_status === 'damaged' ? 'danger' : 'warning') }}">
                                @if($inspection->condition_status === 'good') ดี
                                @elseif($inspection->condition_status === 'fair') พอใช้
                                @elseif($inspection->condition_status === 'poor') แย่
                                @else เสียหาย
                                @endif
                            </span>
                        </td>
                        <td>{{ Str::limit($inspection->remarks, 50) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">ไม่พบข้อมูลการตรวจสอบ</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inspections->hasPages())
        <div class="card-footer">
            {{ $inspections->links() }}
        </div>
    @endif
</div>
@endsection
