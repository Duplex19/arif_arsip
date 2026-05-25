# 📋 Plan & Implementation Report
## Sistem Informasi E-Arsip Dinas Ketahanan Pangan Kabupaten Way Kanan

---

## 🎯 Project Overview

Sistem Informasi E-Arsip adalah aplikasi berbasis web untuk mengelola arsip digital Dinas Ketahanan Pangan Kabupaten Way Kanan dengan fitur role-based access control (Admin, User, Pimpinan).

---

## 🛠️ Technology Stack

| Component | Technology | Version |
|-----------|-----------|---------|
| Backend | Laravel | 13.6.0 |
| PHP | PHP | 8.5 |
| Frontend | Blade Templates | - |
| CSS Framework | Tailwind CSS | 4.2.4 |
| Database | MySQL | - |
| Excel Export | maatwebsite/excel | ^3.1 |
| PDF Export | barryvdh/laravel-dompdf | ^3.1 |
| Charts | ApexCharts | Latest |
| Testing | Pest PHP | 4.6.3 |

---

## 🔐 Akun Default (Seeder)

| Username | Password | Role | Bidang |
|----------|----------|------|--------|
| `admin` | `password` | **Admin** | - |
| `kepala.dinas` | `password` | **Pimpinan** | Sekretariat |
| `staff.sekretariat` | `password` | **User** | Sekretariat |
| `staff.ketersediaan` | `password` | **User** | Bidang Ketersediaan Pangan |
| `staff.distribusi` | `password` | **User** | Bidang Distribusi Pangan |
| `staff.konsumsi` | `password` | **User** | Bidang Konsumsi Pangan |

---

## 📊 Database Schema

### Tabel: `bidang`
```
- id (bigint, PK, AI)
- nama_bidang (string, unique)
- created_at (timestamp)
- updated_at (timestamp)
```

### Tabel: `users` (extend Laravel default)
```
- id (bigint, PK, AI)
- username (string, unique)
- email (string, unique)
- password (string, hashed)
- bidang_id (bigint, FK -> bidang.id, nullable)
- role (enum: admin, user, pimpinan)
- remember_token (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Tabel: `jenis_arsip`
```
- id (bigint, PK, AI)
- nama_jenis (string)
- kode_jenis (string, unique, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Tabel: `arsip`
```
- id (bigint, PK, AI)
- nomor_arsip (string, index)
- tgl_dilegalkan (date, index)
- judul (text)
- jenis_arsip_id (bigint, FK -> jenis_arsip.id)
- bidang_id (bigint, FK -> bidang.id, index)
- file_path (string)
- file_size (int, bytes)
- file_type (string: pdf, jpg, png)
- uploaded_by (bigint, FK -> users.id)
- created_at (timestamp)
- updated_at (timestamp)
```

---

## 👥 User Roles & Permissions

### Admin
- ✅ Dashboard (semua data)
- ✅ Kelola User (CRUD)
- ✅ Kelola Bidang (CRUD)
- ✅ Kelola Jenis Arsip (CRUD)
- ✅ Kelola Arsip (CRUD semua arsip)
- ✅ Lihat semua arsip
- ✅ Export Excel/PDF (semua data)
- ✅ Edit password sendiri

### User (Staf/Operator)
- ✅ Dashboard (data bidang sendiri)
- ❌ Kelola User
- ❌ Kelola Bidang
- ❌ Kelola Jenis Arsip
- ✅ Upload arsip (bidang sendiri, auto-fill)
- ✅ Edit arsip (milik bidang sendiri)
- ✅ Hapus arsip (milik bidang sendiri)
- ✅ Lihat arsip (bidang sendiri saja)
- ✅ Export Excel/PDF (data bidang sendiri)
- ✅ Edit password sendiri

### Pimpinan (Kepala Dinas/Sekretaris)
- ✅ Dashboard (semua data, filter per bidang)
- ❌ Kelola User
- ❌ Kelola Bidang
- ❌ Kelola Jenis Arsip
- ❌ Upload/Edit/Hapus arsip
- ✅ Lihat SEMUA arsip (read-only)
- ✅ Export Excel/PDF (semua data)
- ✅ Edit password sendiri

---

## 📁 File Structure (Actual Implemented)

```
app/
├── Exports/
│   └── ArsipExport.php                 ← Export Excel (Maatwebsite)
├── Models/
│   ├── User.php                         ← Auth + relasi bidang & arsip
│   ├── Bidang.php                       ← $table = 'bidang'
│   ├── JenisArsip.php                   ← $table = 'jenis_arsip'
│   └── Arsip.php                        ← $table = 'arsip'
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── LoginController.php      ← Custom auth (username)
│   │   ├── DashboardController.php      ← Statistik + charts
│   │   ├── UserController.php           ← CRUD user (Admin)
│   │   ├── BidangController.php         ← CRUD bidang (Admin)
│   │   ├── JenisArsipController.php     ← CRUD jenis arsip (Admin)
│   │   ├── ArsipController.php          ← CRUD arsip + export Excel/PDF
│   │   └── ProfileController.php        ← Edit profil & password
│   └── Middleware/
│       ├── AdminMiddleware.php
│       ├── UserMiddleware.php
│       └── PimpinanMiddleware.php
└── Providers/
    └── AppServiceProvider.php

bootstrap/
└── app.php                              ← Register middleware aliases

database/
├── migrations/
│   ├── 0001_01_01_000003_create_bidang_table.php
│   ├── 0001_01_01_000004_add_fields_to_users_table.php
│   ├── 0001_01_01_000005_create_jenis_arsip_table.php
│   └── 0001_01_01_000006_create_arsip_table.php
├── factories/
│   ├── BidangFactory.php
│   ├── UserFactory.php                  ← States: admin, user, pimpinan
│   ├── JenisArsipFactory.php
│   └── ArsipFactory.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── BidangSeeder.php                  ← 4 bidang
    ├── UserSeeder.php                    ← 6 users
    └── JenisArsipSeeder.php             ← 6 jenis arsip

resources/
├── css/
│   └── app.css                          ← Tailwind v4 + glassmorphism
├── js/
│   ├── app.js                           ← ApexCharts + sidebar toggle
│   └── bootstrap.js
└── views/
    ├── layouts/
    │   ├── app.blade.php                 ← Main layout (sidebar + navbar)
    │   └── guest.blade.php              ← Login layout
    ├── components/
    │   ├── glass-card.blade.php
    │   ├── glass-button.blade.php
    │   ├── glass-input.blade.php
    │   ├── glass-table.blade.php
    │   ├── glass-modal.blade.php
    │   ├── sidebar.blade.php             ← Dynamic menu per role
    │   ├── navbar.blade.php
    │   ├── chart-container.blade.php
    │   └── alert.blade.php
    ├── auth/
    │   └── login.blade.php               ← Glassmorphism login page
    ├── dashboard/
    │   └── index.blade.php               ← 3 ApexCharts (donut, bar, area)
    ├── users/
    │   ├── index.blade.php               ← Table + CRUD
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── bidang/
    │   ├── index.blade.php               ← Table with users & arsip count
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── jenis-arsip/
    │   ├── index.blade.php               ← Table with arsip count
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── arsip/
    │   ├── index.blade.php               ← Filter + Pagination + Export
    │   ├── create.blade.php              ← Upload form (auto-fill bidang)
    │   ├── edit.blade.php
    │   └── export-pdf.blade.php          ← PDF template
    └── profile/
        └── edit.blade.php               ← Edit profil + ubah password

routes/
└── web.php                               ← 36 routes with middleware

tests/
└── Feature/
    └── ExampleTest.php                   ← 22 test cases
```

---

## 🚀 Installation Instructions

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & npm
- MySQL / MariaDB

### Steps

```bash
# 1. Clone atau copy project
cd /path/to/project

# 2. Install PHP dependencies
composer install --no-interaction

# 3. Install Node.js dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database di .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=arsip_arif
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Buat database MySQL:
#    mysql -u root -p -e "CREATE DATABASE arsip_arif CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 8. Run migrations + seeders (data contoh)
php artisan migrate --seed

# 9. Create storage symlink (untuk file uploads)
php artisan storage:link

# 10. Build frontend assets
npm run build

# 11. Jalankan development server
php artisan serve
#    atau dengan hot reload:
composer run dev
```

### Akses Aplikasi
Buka browser: `http://localhost:8000`

---

## 📋 Hasil Implementasi (Checklist)

### Phase 1: Setup & Dependencies
- [x] Install maatwebsite/excel
- [x] Install barryvdh/laravel-dompdf
- [x] Setup Tailwind CSS v4 with glassmorphism
- [x] Install ApexCharts

### Phase 2: Database & Models
- [x] Create bidang migration
- [x] Create users migration (add fields)
- [x] Create jenis_arsip migration
- [x] Create arsip migration
- [x] Create all Models with relationships
- [x] Set $table on models (bidang, jenis_arsip, arsip)
- [x] Create all Factories
- [x] Create all Seeders (6 users, 4 bidang, 6 jenis_arsip)

### Phase 3: Authentication & Middleware
- [x] Create LoginController (custom auth with username)
- [x] Create AdminMiddleware
- [x] Create UserMiddleware
- [x] Create PimpinanMiddleware
- [x] Register middleware aliases in bootstrap/app.php
- [x] Setup 36 routes with middleware protection

### Phase 4: UI Components (Glassmorphism)
- [x] Create guest layout
- [x] Create app layout
- [x] Create glass-card, glass-button, glass-input components
- [x] Create glass-table, glass-modal components
- [x] Create sidebar with dynamic menu (per role)
- [x] Create navbar with user info
- [x] Create chart-container component
- [x] Create alert component (auto-dismiss)
- [x] Setup responsive design (mobile collapsible sidebar)

### Phase 5: Controllers & Features
- [x] DashboardController with 3 ApexCharts (donut, bar, area)
- [x] UserController (CRUD) - Admin only
- [x] BidangController (CRUD) - Admin only (prevent delete if has users)
- [x] JenisArsipController (CRUD) - Admin only (prevent delete if has arsip)
- [x] ArsipController (CRUD) - role-based with filters
- [x] ProfileController (edit profile + change password)
- [x] File upload validation (max 5MB, PDF/JPG/PNG)
- [x] Role-based data filtering (user only sees own bidang)

### Phase 6: Export Features
- [x] Create ArsipExport class (Maatwebsite)
- [x] Implement Excel export (role-based filtering)
- [x] Create PDF export view (with header, table, page numbers)
- [x] Implement PDF download (Dompdf, A4 landscape)
- [x] Export filtering based on user role

### Phase 7: Routes
- [x] Guest routes (login)
- [x] Authenticated routes (dashboard, profile, arsip)
- [x] Admin routes (/admin/*) with AdminMiddleware
- [x] Named routes for all endpoints

### Phase 8: Testing & Security
- [x] 22 automated tests passing (Pest PHP)
- [x] Test authentication (login, logout, wrong password)
- [x] Test role permissions (admin/user/pimpinan access)
- [x] Test file upload validation (max size, format)
- [x] Test arsip CRUD with role-based filtering
- [x] Test export functionality (Excel + PDF)
- [x] CSRF protection (Laravel default)
- [x] SQL injection prevention (Eloquent ORM)
- [x] XSS prevention (Blade escaping `{{ }}`)
- [x] Password hashing (bcrypt)
- [x] File upload validation (MIME type + size)

---

## 🎨 Design Guidelines

### Glassmorphism Style
```css
.glass {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.glass-card { ... }
.glass-button { ... }
.glass-input { ... }
.glass-table { ... }
.glass-sidebar { ... }
.glass-navbar { ... }
```

### Color Palette
- Primary: `#6366f1` (Indigo)
- Secondary: `#8b5cf6` (Violet)
- Background: Gradient (slate-900 to slate-800)
- Text: `#f8fafc` (Slate-50)

---

## 📊 Dashboard Charts (ApexCharts)

1. **Statistic Cards** - Total arsip, jenis arsip, bidang, pengguna
2. **Arsip per Jenis** - Donut/Pie chart (group by jenis_arsip)
3. **Arsip per Bidang** - Bar chart (group by bidang)
4. **Arsip per Bulan** - Area chart (group by month)

---

## 📦 Sample Data (Seeders)

### Bidang
1. Sekretariat
2. Bidang Ketersediaan Pangan
3. Bidang Distribusi Pangan
4. Bidang Konsumsi Pangan

### Jenis Arsip
1. Surat Keputusan (SK)
2. Nota Dinas
3. Surat Masuk
4. Surat Keluar
5. Laporan
6. Instruksi Kerja

---

## 🔒 Security Features

- CSRF protection on all POST/PUT/DELETE forms
- SQL injection prevention via Eloquent ORM
- XSS prevention via Blade's automatic `{{ }}` escaping
- File upload validation: MIME type checking + max size (5MB)
- Role-based authorization middleware for all restricted routes
- Password hashing with bcrypt
- Input validation on all forms (server-side)
- Route model binding for authorization checks

---

## 🎯 Success Criteria (All Achieved)

1. ✅ All user roles can access appropriate features
2. ✅ File upload validation works (max 5MB, PDF/JPG/PNG)
3. ✅ Export Excel/PDF generates correct data
4. ✅ Dashboard charts display correct statistics
5. ✅ Glassmorphism design is responsive
6. ✅ Security measures prevent unauthorized access
7. ✅ All CRUD operations work correctly
8. ✅ Seeder provides usable sample data
9. ✅ Application runs on local server (SQLite/MySQL)
10. ✅ Code follows Laravel best practices
11. ✅ 22 automated tests passing

---

## 🔧 Configuration

### storage/app/public
- Symlink: `php artisan storage:link`
- Upload path: `storage/app/public/arsip/`
- Public URL: `/storage/arsip/{filename}`

---

## 📞 Support & Documentation

- Laravel Docs: https://laravel.com/docs
- Maatwebsite Excel: https://docs.laravel-excel.com
- DomPDF: https://github.com/barryvdh/laravel-dompdf
- ApexCharts: https://apexcharts.com/docs
- Tailwind CSS: https://tailwindcss.com/docs

---

**Document Version:** 1.0  
**Last Updated:** 2026-04-28  
**Project Status:** ✅ **IMPLEMENTED** - All 8 phases complete, 22 tests passing
