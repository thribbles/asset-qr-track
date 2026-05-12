@extends('layouts.app')

@section('title', $asset->asset_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-box me-2"></i>{{ $asset->asset_name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('assets.qr', $asset) }}" class="btn btn-outline-info btn-sm">
            <i class="bi bi-qr-code me-1"></i>ดาวน์โหลด QR
        </a>
        <a href="{{ route('assets.print-label', $asset) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
            <i class="bi bi-printer me-1"></i>พิมพ์ฉลาก
        </a>
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>แก้ไข
        </a>
        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline"
              onsubmit="return confirm('ลบครุภัณฑ์นี้?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash me-1"></i>ลบ
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">รายละเอียดครุภัณฑ์</h6>
            </div>
            <div class="card-body">
                {{-- Image Gallery --}}
                @php $assetImages = $asset->images ?? []; @endphp
                @if(count($assetImages) > 0)
                    <div class="row g-2 mb-3">
                        @foreach($assetImages as $index => $imgPath)
                            <div class="col-6">
                                <a href="{{ asset('storage/' . $imgPath) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $imgPath) }}"
                                         class="img-fluid rounded w-100" style="height: 120px; object-fit: cover;"
                                         alt="{{ $asset->asset_name }} - รูปที่ {{ $index + 1 }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-light rounded p-4 text-center text-muted mb-3">
                        <i class="bi bi-image display-4"></i>
                        <p class="mb-0">ไม่มีรูปภาพ</p>
                    </div>
                @endif

                {{-- QR Code Display --}}
                <div class="text-center mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($asset->public_qr_url) }}"
                         alt="QR Code" class="img-fluid border rounded p-2">
                    <p class="small text-muted mt-2">สแกนเพื่อดูข้อมูล</p>
                </div>

                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">รหัส:</td>
                        <td class="text-end"><code>{{ $asset->asset_code }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">ประเภท:</td>
                        <td class="text-end">{{ $asset->asset_type === 'material' ? 'วัสดุ' : 'ครุภัณฑ์' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">สถานะ:</td>
                        <td class="text-end">
                            <span class="badge bg-{{ $asset->status === 'active' ? 'success' : ($asset->status === 'damaged' ? 'danger' : 'secondary') }}">
                                @if($asset->status === 'active') ใช้งานได้
                                @elseif($asset->status === 'inactive') ไม่ใช้งาน
                                @elseif($asset->status === 'damaged') เสียหาย
                                @else จำหน่ายแล้ว
                                @endif
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">สถานที่:</td>
                        <td class="text-end">{{ $asset->location->building ?? 'ไม่ระบุ' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">แผนก:</td>
                        <td class="text-end">{{ $asset->department ?? 'ไม่ระบุ' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">ชั้น:</td>
                        <td class="text-end">{{ $asset->location->floor ?? 'ไม่ระบุ' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">ห้อง:</td>
                        <td class="text-end">{{ $asset->location->room ?? 'ไม่ระบุ' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">วันที่ซื้อ:</td>
                        <td class="text-end">{{ $asset->purchase_date?->format('d M Y') ?? 'ไม่ระบุ' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">ตรวจสอบล่าสุด:</td>
                        <td class="text-end">{{ $asset->latest_inspection_date?->format('d M Y') ?? 'ไม่มีข้อมูล' }}</td>
                    </tr>
                    @if($asset->status === 'disposed' && $asset->disposal_date)
                        <tr>
                            <td class="text-muted">วันที่จำหน่าย:</td>
                            <td class="text-end text-danger">{{ $asset->disposal_date->format('d M Y') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Quick Inspection Form --}}
        <div class="card shadow-sm mb-4 border-success">
            <div class="card-header bg-success text-white d-flex justify-content-between">
                <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>บันทึกการตรวจสอบ</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('inspections.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <input type="hidden" name="inspected_at" value="{{ now()->format('Y-m-d\TH:i') }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">สถานะการตรวจสอบ</label>
                            <select name="condition_status" class="form-select" required>
                                <option value="good">ดี</option>
                                <option value="fair">พอใช้</option>
                                <option value="poor">แย่</option>
                                <option value="damaged">เสียหาย</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ถ่ายรูป (ถ้ามี)</label>
                            <input type="file" name="image" class="form-control" accept="image/*" capture="environment">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">หมายเหตุ</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="บันทึกรายละเอียดการตรวจสอบ..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>บันทึกการตรวจสอบ
                        </button>
                        <a href="{{ route('inspections.create') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-right me-1"></i>ตรวจสอบแบบเต็ม
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Inspection History --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>ประวัติการตรวจสอบ</h6>
            </div>
            <div class="card-body p-0">
                @if($asset->inspections->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($asset->inspections as $inspection)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="badge bg-{{ $inspection->condition_status === 'good' ? 'success' : ($inspection->condition_status === 'damaged' ? 'danger' : 'warning') }} me-2">
                                            @if($inspection->condition_status === 'good') ดี
                                            @elseif($inspection->condition_status === 'fair') พอใช้
                                            @elseif($inspection->condition_status === 'poor') แย่
                                            @else เสียหาย
                                            @endif
                                        </span>
                                        <small>{{ $inspection->remarks }}</small>
                                        @if($inspection->image_url)
                                            <br><a href="{{ asset('storage/' . $inspection->image_url) }}" target="_blank" class="small">
                                                <i class="bi bi-image me-1"></i>ดูรูปภาพ
                                            </a>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ $inspection->inspected_at->format('d M Y H:i') }}</small>
                                        <br><small class="text-muted">โดย {{ $inspection->inspector->name }}</small>
                                        @if($inspection->inspected_by === auth()->id() || auth()->user()->role === 'admin')
                                            <br><a href="{{ route('inspections.edit', $inspection) }}" class="btn btn-sm btn-outline-primary mt-1">
                                                <i class="bi bi-pencil me-1"></i>แก้ไข
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">ยังไม่มีการตรวจสอบ</p>
                @endif
            </div>
        </div>

        {{-- Transfer History --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between">
                <h6 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>ประวัติการโยกย้าย</h6>
                <a href="{{ route('assets.transfers.create', $asset) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>โยกย้าย
                </a>
            </div>
            <div class="card-body p-0">
                @if($asset->transfers->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($asset->transfers as $transfer)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="bi bi-geo-alt text-muted me-1"></i>
                                        {{ $transfer->fromLocation->building ?? 'ไม่ระบุ' }}
                                        <i class="bi bi-arrow-right mx-2"></i>
                                        <i class="bi bi-geo-alt text-primary me-1"></i>
                                        {{ $transfer->toLocation->building }}
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ $transfer->transferred_at->format('d M Y') }}</small>
                                        <br><small class="text-muted">โดย {{ $transfer->transferredBy->name }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">ยังไม่มีการโยกย้าย</p>
                @endif
            </div>
        </div>

        {{-- Loan History --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between">
                <h6 class="mb-0"><i class="bi bi-person-check me-2"></i>ประวัติการยืม-คืน</h6>
                <a href="{{ route('assets.loans.create', $asset) }}" class="btn btn-sm btn-info text-white">
                    <i class="bi bi-plus-lg me-1"></i>บันทึกการยืม
                </a>
            </div>
            <div class="card-body p-0">
                @if($asset->loans->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($asset->loans as $loan)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $loan->borrower_name }}</strong>
                                        <span class="badge bg-{{ $loan->status === 'borrowed' ? 'warning text-dark' : ($loan->status === 'returned' ? 'success' : 'danger') }} ms-2">
                                            @if($loan->status === 'borrowed') กำลังยืม
                                            @elseif($loan->status === 'returned') คืนแล้ว
                                            @else {{ $loan->status }}
                                            @endif
                                        </span>
                                        <br><small class="text-muted">{{ $loan->purpose }}</small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ $loan->borrowed_at->format('d M Y') }}</small>
                                        @if($loan->returned_at)
                                            <br><small class="text-success">คืนเมื่อ: {{ $loan->returned_at->format('d M Y') }}</small>
                                        @endif
                                        <br><a href="{{ route('loans.show', $loan) }}" class="small">ดูรายละเอียด</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">ยังไม่มีประวัติการยืม</p>
                @endif
            </div>
        </div>

        {{-- Repair History - Only show if status is damaged or has repair history --}}
        @if($asset->status === 'damaged' || $asset->repairs->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white d-flex justify-content-between">
                    <h6 class="mb-0"><i class="bi bi-tools me-2"></i>ประวัติการซ่อม</h6>
                    @if($asset->status === 'damaged')
                        <a href="{{ route('assets.repairs.create', $asset) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-plus-lg me-1"></i>ส่งซ่อม
                        </a>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($asset->repairs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($asset->repairs as $repair)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge bg-{{ $repair->status === 'completed' ? 'success' : ($repair->status === 'in_progress' ? 'info' : ($repair->status === 'cancelled' ? 'secondary' : 'warning')) }} me-2">
                                                @if($repair->status === 'pending') รอดำเนินการ
                                                @elseif($repair->status === 'in_progress') กำลังซ่อม
                                                @elseif($repair->status === 'completed') ซ่อมเสร็จ
                                                @else ยกเลิก
                                                @endif
                                            </span>
                                            <small>{{ $repair->issue_description }}</small>
                                            @if($repair->repair_details)
                                                <br><small class="text-muted">ผลการซ่อม: {{ $repair->repair_details }}</small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">{{ $repair->requested_at->format('d M Y') }}</small>
                                            <br><small class="text-muted">โดย {{ $repair->requester->name }}</small>
                                            @if(auth()->user()->role === 'admin' || $repair->requested_by === auth()->id())
                                                <br><a href="{{ route('repairs.edit', $repair) }}" class="btn btn-sm btn-outline-primary mt-1">
                                                    <i class="bi bi-pencil me-1"></i>แก้ไข
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">ยังไม่มีการส่งซ่อม</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
