@extends('layouts.app')

@section('title', 'ค้นหาครุภัณฑ์')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-search me-2"></i>ค้นหาครุภัณฑ์</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text" name="q" class="form-control" 
                               placeholder="ค้นหาด้วยรหัส ชื่อ หรือสถานที่..."
                               value="{{ $query ?? '' }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>ค้นหา
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($assets) && $assets->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">พบ {{ $assets->count() }} รายการ</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($assets as $asset)
                        <a href="{{ route('assets.public', $asset->qr_token) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $asset->asset_name }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-upc-scan me-1"></i>{{ $asset->asset_code }}
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-geo-alt me-1"></i>{{ $asset->location->building ?? 'ไม่ระบุ' }}
                                    <span class="mx-2">|</span>
                                    <span class="badge bg-{{ $asset->status === 'active' ? 'success' : ($asset->status === 'damaged' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($asset->status) }}
                                    </span>
                                </small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @elseif(isset($query))
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>ไม่พบครุภัณฑ์ที่ตรงกับ "{{ $query }}"
            </div>
        @endif
    </div>
</div>
@endsection
