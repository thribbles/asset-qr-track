# การตั้งค่า QR Code สำหรับ LAN

## ระบบตรวจจับ IP อัตโนมัติ (แนะนำ)

ปัจจุบันระบบมีฟีเจอร์ตรวจจับ IP ของเครื่อง Server โดยอัตโนมัติ:
1. หากคุณเข้าใช้งานผ่าน IP (เช่น `http://192.168.1.100/assect/public`) ระบบจะใช้ IP นั้นใน QR Code ทันที
2. หากคุณเข้าใช้งานผ่าน `localhost` ระบบจะพยายามค้นหา LAN IP ของเครื่องให้โดยอัตโนมัติ

## การตั้งค่าแบบ Manual (กรณีระบบตรวจไม่พบ)

หาก QR Code ยังคงแสดง localhost หรือ URL ที่ไม่ถูกต้อง:

1. **หา IP Address ของเครื่อง Server**
   เปิด Command Prompt แล้วพิมพ์ `ipconfig` ดูที่ `IPv4 Address`

2. **แก้ไขไฟล์ `.env`**
   เพิ่มหรือแก้ไขบรรทัดนี้:
   ```env
   APP_PUBLIC_URL=http://192.168.1.100/assect/public
   ```

3. **ล้าง Cache ของระบบ**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

## การทดสอบ
1. เปิดหน้าพิมพ์ฉลาก (Print Label)
2. สแกน QR Code ด้วยมือถือ (ต้องต่อ WiFi เดียวกัน)
3. ตรวจสอบว่า URL เป็น IP ของ Server
