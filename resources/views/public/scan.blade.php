@extends('layouts.app')

@section('title', 'สแกน QR Code')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-qr-code-scan me-2"></i>สแกน QR Code ครุภัณฑ์</h5>
            </div>
            <div class="card-body">
    <style>
        #reader { border: none !important; }
        #reader img { display: none; }
        #reader__dashboard_section_csr button {
            background-color: var(--bs-primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            margin-top: 10px;
        }
        #reader__status_span { display: none; }
        .html5-qrcode-element { border-radius: 8px !important; }
    </style>
    <div id="reader" class="mb-3" style="width: 100%;"></div>
    <p class="text-muted small">จัดวาง QR Code ให้อยู่ในกรอบเพื่อสแกน</p>

                <hr class="my-4">

                <h6>หรือป้อนรหัสครุภัณฑ์ด้วยตนเอง:</h6>
                <form action="{{ route('search') }}" method="GET" class="mt-3">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="ป้อนรหัสครุภัณฑ์..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>ค้นหา
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        if (decodedText.startsWith('http')) {
            window.location.href = decodedText;
        } else {
            window.location.href = '/assets/public/' + decodedText;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const isSecure = window.isSecureContext;
        const readerDiv = document.getElementById('reader');
        
        if (!isSecure && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
            readerDiv.innerHTML = `
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>คำแนะนำสำหรับมือถือ:</strong> เนื่องจากคุณเข้าใช้งานผ่าน HTTP (ไม่ปลอดภัย) 
                    เบราว์เซอร์จะไม่อนุญาตให้เปิดกล้องโดยตรง <br>
                    <strong>วิธีแก้:</strong> กรุณากดปุ่ม <strong>"Scan Image File"</strong> ด้านล่าง 
                    แล้วเลือก <strong>"กล้อง"</strong> เพื่อถ่ายรูป QR Code แทนครับ
                </div>
            `;
        }

        const scanner = new Html5QrcodeScanner("reader", { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            rememberLastUsedCamera: true,
            supportedScanTypes: [
                Html5QrcodeScanType.SCAN_TYPE_CAMERA,
                Html5QrcodeScanType.SCAN_TYPE_FILE
            ]
        });
        
        scanner.render(onScanSuccess);
    });
</script>
@endpush
