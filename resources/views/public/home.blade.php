@extends('layouts.app')

@section('title', 'หน้าหลัก - ระบบจัดการครุภัณฑ์')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <h1 class="display-5 mb-4">
            <i class="bi bi-box-seam text-primary"></i>
            ระบบจัดการครุภัณฑ์และวัสดุราชการ
        </h1>
        <p class="lead text-muted mb-5">
            ติดตามและจัดการครุภัณฑ์และวัสดุได้อย่างง่ายดาย
            สแกน QR Code เพื่อดูข้อมูลครุภัณฑ์ทันที
        </p>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-qr-code-scan display-4 text-primary mb-3"></i>
                        <h5>สแกน QR Code</h5>
                        <p class="text-muted">เข้าถึงข้อมูลครุภัณฑ์ได้อย่างรวดเร็วโดยการสแกน QR Code</p>
                        <a href="{{ route('scan') }}" class="btn btn-primary">
                            <i class="bi bi-camera me-2"></i>สแกนเลย
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-search display-4 text-success mb-3"></i>
                        <h5>ค้นหาครุภัณฑ์</h5>
                        <p class="text-muted">ค้นหาครุภัณฑ์ด้วยรหัส ชื่อ หรือสถานที่</p>
                        <a href="{{ route('search') }}" class="btn btn-success">
                            <i class="bi bi-search me-2"></i>ค้นหา
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-shield-check display-4 text-info mb-3"></i>
                        <h5>เข้าสู่ระบบ</h5>
                        <p class="text-muted">สำหรับผู้ดูแลระบบ เจ้าหน้าที่ และผู้ตรวจสอบ</p>
                        <a href="{{ route('login') }}" class="btn btn-info text-white">
                            <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-light border">
            <i class="bi bi-info-circle me-2"></i>
            <strong>การเข้าถึงสาธารณะ:</strong> การสแกน QR และการค้นหาครุภัณฑ์ใช้งานได้โดยไม่ต้องเข้าสู่ระบบ
            <br>ฟีเจอร์การจัดการต้องเข้าสู่ระบบก่อน
        </div>
    </div>
</div>
@endsection
