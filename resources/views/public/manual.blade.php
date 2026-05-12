@extends('layouts.app')

@section('title', 'คู่มือการใช้งาน')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white p-4">
                <h3 class="mb-0"><i class="bi bi-book me-2"></i>คู่มือการใช้งานระบบจัดการครุภัณฑ์</h3>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active text-start" id="v-pills-start-tab" data-bs-toggle="pill" data-bs-target="#v-pills-start" type="button" role="tab">1. เริ่มต้นใช้งาน</button>
                            <button class="nav-link text-start" id="v-pills-scan-tab" data-bs-toggle="pill" data-bs-target="#v-pills-scan" type="button" role="tab">2. การสแกน QR Code</button>
                            <button class="nav-link text-start" id="v-pills-admin-tab" data-bs-toggle="pill" data-bs-target="#v-pills-admin" type="button" role="tab">3. การจัดการข้อมูล (Admin)</button>
                            <button class="nav-link text-start" id="v-pills-lan-tab" data-bs-toggle="pill" data-bs-target="#v-pills-lan" type="button" role="tab">4. การใช้งานผ่าน LAN/HTTPS</button>
                        </div>
                    </div>
                    <div class="col-md-9 border-start">
                        <div class="tab-content" id="v-pills-tabContent">
                            {{-- Section 1 --}}
                            <div class="tab-pane fade show active px-3" id="v-pills-start" role="tabpanel">
                                <h4 class="text-primary mb-3">1. เริ่มต้นใช้งาน</h4>
                                <p>ระบบจัดการครุภัณฑ์ถูกออกแบบมาเพื่อช่วยในการตรวจสอบและติดตามทรัพย์สินของหน่วยงานผ่านระบบ QR Code โดยมีฟีเจอร์หลักคือ:</p>
                                <ul>
                                    <li><strong>บุคคลทั่วไป:</strong> สามารถสแกนดูข้อมูลครุภัณฑ์ได้ทันที</li>
                                    <li><strong>เจ้าหน้าที่:</strong> สามารถบันทึกผลการตรวจสอบสภาพครุภัณฑ์ได้</li>
                                    <li><strong>ผู้ดูแลระบบ:</strong> สามารถจัดการข้อมูลครุภัณฑ์ สถานที่ และผู้ใช้งาน</li>
                                </ul>
                            </div>

                            {{-- Section 2 --}}
                            <div class="tab-pane fade px-3" id="v-pills-scan" role="tabpanel">
                                <h4 class="text-primary mb-3">2. การสแกน QR Code</h4>
                                <div class="mb-4">
                                    <h5>ขั้นตอนการสแกน:</h5>
                                    <ol>
                                        <li>ใช้มือถือสแกนที่ติดอยู่กับตัวครุภัณฑ์ หรือกดเมนู <strong>"สแกน QR Code"</strong> ในระบบ</li>
                                        <li>หากระบบขอสิทธิ์เข้าถึงกล้อง ให้กด <strong>"Allow"</strong> หรือ <strong>"อนุญาต"</strong></li>
                                        <li>หากเปิดกล้องไม่ได้ (กรณี HTTP) ให้กดปุ่ม <strong>"Scan Image File"</strong> แล้วเลือกถ่ายภาพ QR Code แทน</li>
                                    </ol>
                                </div>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    เมื่อมีการสแกน ระบบจะบันทึกวันที่ "ตรวจสอบล่าสุด" ให้อัตโนมัติทันที
                                </div>
                            </div>

                            {{-- Section 3 --}}
                            <div class="tab-pane fade px-3" id="v-pills-admin" role="tabpanel">
                                <h4 class="text-primary mb-3">3. การจัดการข้อมูล (สำหรับ Admin)</h4>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <h6><i class="bi bi-plus-circle me-2"></i>เพิ่มครุภัณฑ์</h6>
                                            <p class="small text-muted mb-0">ไปที่เมนู ครุภัณฑ์ > เพิ่มข้อมูล กรอกรายละเอียดและเลือกสถานที่</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <h6><i class="bi bi-printer me-2"></i>พิมพ์ฉลาก QR Code</h6>
                                            <p class="small text-muted mb-0">ในหน้าครุภัณฑ์แต่ละชิ้น จะมีปุ่ม "พิมพ์ฉลาก" เพื่อนำไปติดที่ตัวสินค้า</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Section 4 --}}
                            <div class="tab-pane fade px-3" id="v-pills-lan" role="tabpanel">
                                <h4 class="text-primary mb-3">4. การใช้งานผ่าน LAN และปัญหาเรื่องกล้อง</h4>
                                <p>เพื่อให้ระบบใช้งานได้อย่างสมบูรณ์ โดยเฉพาะการเปิดกล้องผ่านเบราว์เซอร์:</p>
                                <ul>
                                    <li>ควรใช้งานผ่าน <strong>HTTPS</strong> เท่านั้น</li>
                                    <li>หากใช้ Laragon ให้เปิด SSL (Menu > Apache > SSL > Enabled)</li>
                                    <li>หากเป็น HTTP ธรรมดา ให้ใช้ฟีเจอร์ <strong>"Scan Image File"</strong> แทนการเปิดกล้องสด</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
