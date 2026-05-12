# คู่มือการ Deployment ด้วย Docker

คู่มือนี้สำหรับนำระบบไปติดตั้งบน Server จริงโดยใช้ Docker

## สิ่งที่ต้องเตรียม
1. Server (Ubuntu แนะนำ) ที่ติดตั้ง Docker และ Docker Compose เรียบร้อยแล้ว
2. Source Code ของระบบ

## ขั้นตอนการติดตั้ง

### 1. ตั้งค่า Environment
คัดลอกไฟล์ `.env.example` เป็น `.env` และแก้ไขค่าต่างๆ:
```bash
cp .env.example .env
```
แก้ไขไฟล์ `.env`:
- `APP_URL`: ตั้งเป็น Domain หรือ IP ของ Server จริง
- `DB_HOST`: ตั้งเป็น `db` (ชื่อ Service ใน docker-compose)
- `DB_DATABASE`: assect
- `DB_USERNAME`: laravel
- `DB_PASSWORD`: (รหัสผ่านที่คุณต้องการ)

### 2. สร้าง Container
รันคำสั่งเพื่อสร้างและรันระบบ:
```bash
docker-compose up -d --build
```

### 3. ตั้งค่าระบบภายใน Container
รันคำสั่งเหล่านี้เพื่อตั้งค่าเบื้องต้น:
```bash
# ติดตั้ง PHP Dependencies
docker-compose exec app composer install --optimize-autoloader --no-dev

# สร้าง Application Key
docker-compose exec app php artisan key:generate

# รัน Migration และ Seeding (สร้าง Database และ User Admin)
docker-compose exec app php artisan migrate --seed --force

# สร้าง Symbolic Link สำหรับรูปภาพ
docker-compose exec app php artisan storage:link
```

### 4. Build Frontend Assets
หากคุณมีการแก้ไข CSS/JS:
```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

## การเข้าใช้งาน
- ระบบจะเปิดให้บริการที่ Port **8000** (หรือตามที่คุณตั้งค่าใน docker-compose.yml)
- **Admin Login:** `admin@assect.local` / `password`

## การจัดการ Database
หากต้องการสำรองข้อมูล Database:
```bash
docker-compose exec db mysqldump -u laravel -p assect > backup.sql
```

พัฒนาโดยน้าอ๋อง ที่นั่งห้องเซิพ
