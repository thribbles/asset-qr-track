@extends('layouts.app')

@section('title', 'ครุภัณฑ์')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-box me-2"></i>ครุภัณฑ์</h4>
    <a href="{{ route('assets.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>เพิ่มครุภัณฑ์
    </a>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('assets.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="ค้นหาด้วยรหัสหรือชื่อ..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">ทุกประเภท</option>
                    <option value="material" {{ request('type') === 'material' ? 'selected' : '' }}>วัสดุ</option>
                    <option value="durable" {{ request('type') === 'durable' ? 'selected' : '' }}>ครุภัณฑ์</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">ทุกสถานะ</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>ใช้งานได้</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                    <option value="damaged" {{ request('status') === 'damaged' ? 'selected' : '' }}>เสียหาย</option>
                    <option value="disposed" {{ request('status') === 'disposed' ? 'selected' : '' }}>จำหน่ายแล้ว</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="location" class="form-select">
                    <option value="">ทุกสถานที่</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                            {{ $location->building }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-funnel me-1"></i>กรอง
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Assets Table -->
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>รหัสครุภัณฑ์</th>
                    <th>ชื่อ</th>
                    <th>ประเภท</th>
                    <th>สถานที่</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $asset)
                    <tr>
                        <td><code>{{ $asset->asset_code }}</code></td>
                        <td>{{ $asset->asset_name }}</td>
                        <td>{{ $asset->asset_type === 'material' ? 'วัสดุ' : 'ครุภัณฑ์' }}</td>
                        <td>{{ $asset->location->building ?? 'ไม่ระบุ' }}</td>
                        <td>
                            <span class="badge bg-{{ $asset->status === 'active' ? 'success' : ($asset->status === 'damaged' ? 'danger' : 'secondary') }}">
                                @if($asset->status === 'active') ใช้งานได้
                                @elseif($asset->status === 'inactive') ไม่ใช้งาน
                                @elseif($asset->status === 'damaged') เสียหาย
                                @else จำหน่ายแล้ว
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-primary" title="ดู">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-outline-secondary" title="แก้ไข">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('assets.qr', $asset) }}" class="btn btn-outline-info" title="ดาวน์โหลด QR">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">ไม่พบครุภัณฑ์</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assets->hasPages())
        <div class="card-footer">
            {{ $assets->links() }}
        </div>
    @endif
</div>
@endsection
