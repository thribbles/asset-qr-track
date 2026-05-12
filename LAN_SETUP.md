# การตั้งค่า LAN สำหรับ QR Code

## ระบบตรวจจับอัตโนมัติ
ระบบจะพยายามตรวจจับ IP ของเครื่อง Server ให้โดยอัตโนมัติ เพื่อให้ QR Code ใช้งานได้ทันทีหลังการติดตั้ง (Deploy)

## ขั้นตอนการตั้งค่าแบบ Manual
หากต้องการระบุ URL ให้แน่นอน:

1. **หา IP Address**
   ใช้คำสั่ง `ipconfig` ใน Command Prompt

2. **แก้ไข `.env`**
   ```env
   APP_URL=http://192.168.1.100/assect/public
   APP_PUBLIC_URL=http://192.168.1.100/assect/public
   ```

3. **Clear Cache**
   ```cmd
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

## หมายเหตุ
- QR Code จะใช้ `APP_PUBLIC_URL` หากมีการตั้งค่าไว้
- หากไม่ได้ตั้งค่า ระบบจะใช้ URL ปัจจุบันที่เข้าใช้งาน (Auto-detect)
- มือถือต้องอยู่ในเครือข่ายเดียวกับ Server (WiFi/LAN)
