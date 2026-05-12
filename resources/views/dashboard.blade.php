@extends('layouts.app')

@section('title', 'แดชบอร์ด')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</h4>
    <div>
        <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>เพิ่มครุภัณฑ์
        </a>
        <a href="{{ route('inspections.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-clipboard-check me-1"></i>ตรวจสอบใหม่
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">ครุภัณฑ์ทั้งหมด</h6>
                        <h3 class="mb-0">{{ $stats['total_assets'] }}</h3>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">ใช้งานได้ (ว่าง)</h6>
                        <h3 class="mb-0">{{ $stats['active_assets'] }}</h3>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <a href="{{ route('loans.index') }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">กำลังถูกยืม</h6>
                            <h3 class="mb-0">{{ $stats['borrowed_assets'] ?? 0 }}</h3>
                        </div>
                        <i class="bi bi-person-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('repairs.index') }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">เสียหาย / รอซ่อม</h6>
                            <h3 class="mb-0">{{ $stats['damaged_assets'] ?? 0 }}</h3>
                        </div>
                        <i class="bi bi-tools fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0 shadow-sm bg-secondary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">จำหน่ายแล้ว</h6>
                        <h3 class="mb-0">{{ $stats['disposed_assets'] }}</h3>
                    </div>
                    <i class="bi bi-trash fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Inspections -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>การตรวจสอบล่าสุด</h6>
                <a href="{{ route('inspections.index') }}" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
            </div>
            <div class="card-body p-0">
                @if($recent_inspections->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recent_inspections as $inspection)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $inspection->asset->asset_name ?? 'ครุภัณฑ์ถูกลบแล้ว' }}</h6>
                                        <small class="text-muted">
                                            โดย {{ $inspection->inspector->name ?? 'ไม่ระบุ' }} 
                                            • {{ $inspection->inspected_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $inspection->condition_status === 'good' ? 'success' : ($inspection->condition_status === 'damaged' ? 'danger' : 'warning') }}">
                                        @if($inspection->condition_status === 'good') ดี
                                        @elseif($inspection->condition_status === 'fair') พอใช้
                                        @elseif($inspection->condition_status === 'poor') แย่
                                        @else เสียหาย
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 text-center text-muted">ไม่มีการตรวจสอบล่าสุด</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transfers -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>การโยกย้ายล่าสุด</h6>
                <a href="{{ route('transfers.index') }}" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
            </div>
            <div class="card-body p-0">
                @if($recent_transfers->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recent_transfers as $transfer)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $transfer->asset->asset_name ?? 'ครุภัณฑ์ถูกลบแล้ว' }}</h6>
                                        <small class="text-muted">
                                            {{ $transfer->fromLocation->building ?? 'ไม่ระบุ' }} 
                                            <i class="bi bi-arrow-right mx-1"></i>
                                            {{ $transfer->toLocation->building ?? 'ไม่ระบุ' }}
                                        </small>
                                    </div>
                                    <small class="text-muted">{{ $transfer->transferred_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 text-center text-muted">ไม่มีการโยกย้ายล่าสุด</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
