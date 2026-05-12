@extends('layouts.app')

@section('title', 'พิมพ์ฉลาก')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">ฉลากครุภัณฑ์</h5>
            </div>
            <div class="card-body">
                <h4 class="mb-2">{{ $asset->asset_name }}</h4>
                <p class="text-muted mb-3">{{ $asset->asset_code }}</p>

                <div class="mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($asset->public_qr_url) }}" 
                         alt="QR Code" class="img-fluid">
                </div>

                <p class="small text-muted">สแกนเพื่อดูข้อมูลครุภัณฑ์</p>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-center mt-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer me-1"></i>พิมพ์
            </button>
            <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>กลับ
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .card, .card * { visibility: visible; }
        .card { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); }
        .btn { display: none; }
    }
</style>
@endsection
