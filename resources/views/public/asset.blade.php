@extends('layouts.app')

@section('title', $asset->asset_name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-center">
                <h5 class="mb-0">{{ $asset->asset_name }}</h5>
            </div>
            <div class="card-body text-center">
                {{-- Image Gallery --}}
                @php $assetImages = $asset->images ?? []; @endphp
                @if(count($assetImages) > 0)
                    <div id="imageCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($assetImages as $index => $imgPath)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $imgPath) }}"
                                         class="img-fluid rounded" style="max-height: 300px;"
                                         alt="รูปที่ {{ $index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        @if(count($assetImages) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
                            </button>
                        @endif
                    </div>
                @elseif($asset->image_path)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $asset->image_path) }}"
                             alt="{{ $asset->asset_name }}"
                             class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                @else
                    <div class="bg-light rounded p-4 text-muted mb-4">
                        <i class="bi bi-image display-4"></i>
                        <p class="mb-0">ไม่มีรูปภาพ</p>
                    </div>
                @endif

                {{-- Asset Info --}}
                <div class="table-responsive">
                    <table class="table table-borderless text-start mb-0">
                        <tr>
                            <th width="35%" class="text-muted fw-normal">รหัส:</th>
                            <td class="fw-bold text-primary">{{ $asset->asset_code }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">ประเภท:</th>
                            <td>{{ $asset->asset_type === 'material' ? 'วัสดุ' : 'ครุภัณฑ์' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">สถานะ:</th>
                            <td>
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
                            <th class="text-muted fw-normal">สถานที่:</th>
                            <td>{{ $asset->location->building ?? 'ไม่ระบุ' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">แผนก:</th>
                            <td>{{ $asset->department ?? 'ไม่ระบุ' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">ชั้น:</th>
                            <td>{{ $asset->location->floor ?? 'ไม่ระบุ' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">ห้อง:</th>
                            <td>{{ $asset->location->room ?? 'ไม่ระบุ' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">วันที่ซื้อ:</th>
                            <td>{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : 'ไม่ระบุ' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">ตรวจสอบล่าสุด:</th>
                            <td>
                                <span class="text-{{ $asset->latest_inspection_date ? 'success' : 'muted' }}">
                                    {{ $asset->latest_inspection_date ? $asset->latest_inspection_date->format('d/m/Y H:i') : 'ยังไม่มีการตรวจสอบ' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Inspection for Authenticated Users --}}
        @auth
            @php
                $latestInspection = $asset->latestInspection;
                $hasInspectionToday = $latestInspection && $latestInspection->inspected_at->isToday();
            @endphp
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>บันทึกการตรวจสอบ</h6>
                </div>
                <div class="card-body">
                    @if($hasInspectionToday)
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-check-circle me-1"></i>
                            ตรวจสอบแล้ววันนี้ ({{ $latestInspection->condition_status === 'good' ? 'สภาพดี' : ($latestInspection->condition_status === 'fair' ? 'สภาพพอใช้' : ($latestInspection->condition_status === 'poor' ? 'สภาพแย่' : 'เสียหาย')) }})
                            โดย {{ $latestInspection->inspector->name }}
                        </div>
                        @if(auth()->user()->role === 'admin' || $latestInspection->inspected_by === auth()->id())
                            <div class="d-flex gap-2">
                                <a href="{{ route('inspections.edit', $latestInspection) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil me-1"></i>แก้ไข
                                </a>
                            </div>
                        @endif
                    @else
                        <form action="{{ route('inspections.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                            <input type="hidden" name="inspected_at" value="{{ now()->format('Y-m-d H:i') }}">

                            <div class="mb-3">
                                <label class="form-label">สภาพครุภัณฑ์</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <input type="radio" class="btn-check" name="condition_status" id="good" value="good" checked>
                                    <label class="btn btn-outline-success" for="good">สภาพดี</label>

                                    <input type="radio" class="btn-check" name="condition_status" id="fair" value="fair">
                                    <label class="btn btn-outline-info" for="fair">พอใช้</label>

                                    <input type="radio" class="btn-check" name="condition_status" id="poor" value="poor">
                                    <label class="btn btn-outline-warning" for="poor">แย่</label>

                                    <input type="radio" class="btn-check" name="condition_status" id="damaged" value="damaged">
                                    <label class="btn btn-outline-danger" for="damaged">เสียหาย</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="remarks" class="form-label">หมายเหตุ</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="2" placeholder="ระบุหมายเหตุหากมี"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">รูปภาพ (ถ้ามี)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" capture="environment">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="accept" class="btn btn-success">
                                    <i class="bi bi-check-lg me-1"></i>ยืนยัน (ไม่มีปัญหา)
                                </button>
                                <button type="submit" name="action" value="edit" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i>แจ้งมีปัญหา
                                </button>
                                <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-eye me-1"></i>ดูรายละเอียด
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        @endauth

        <div class="mt-3 text-center">
            <a href="{{ route('scan') }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-qr-code-scan me-1"></i>สแกนเพิ่ม
            </a>
            @auth
                <a href="{{ route('assets.show', $asset) }}" class="btn btn-primary">
                    <i class="bi bi-eye me-1"></i>ดูทั้งหมด
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
