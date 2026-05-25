# 🚀 Instalasi & Panduan Menjalankan E-Arsip DKPP Way Kanan

Dokumen ini berisi panduan lengkap untuk menjalankan aplikasi **Sistem Informasi E-Arsip Dinas Ketahanan Pangan Kabupaten Way Kanan** menggunakan **Docker** dengan **FrankenPHP** + **Laravel Octane**.

---

## 📋 Daftar Isi

- [Persyaratan Sistem](#persyaratan-sistem)
- [Struktur File Docker](#struktur-file-docker)
- [Cara Menjalankan (Docker)](#cara-menjalankan-docker)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Perintah Penting (Makefile)](#perintah-penting-makefile)
- [Akses Aplikasi](#akses-aplikasi)
- [Akun Default (Seeder)](#akun-default-seeder)
- [Troubleshooting](#troubleshooting)
- [Cara Menjalankan Tanpa Docker (Local)](#cara-menjalankan-tanpa-docker-local)

---

## Persyaratan Sistem

### Docker (direkomendasikan)
- [Docker](https://docs.docker.com/get-docker/) versi 24+
- [Docker Compose](https://docs.docker.com/compose/install/) versi 2.20+
- RAM minimal 2GB untuk semua container

### Local (tanpa Docker)
- PHP 8.3+
- Composer 2
- Node.js 20+ & npm
- MySQL 8.0+ atau MariaDB 10+
- Redis 7+ (opsional, untuk cache & queue)

---

## Struktur File Docker

```
📁 arsip_arif/
├── Dockerfile                    # Image FrankenPHP + PHP 8.5 + Octane
├── docker-compose.yml            # 3 services: app, database, cache
├── Makefile                      # 20+ perintah cepat
├── .env.docker                   # Environment variables untuk Docker
├── .env.example                  # Contoh file .env
└── .docker/
    └── frankenphp/
        └── Caddyfile             # Konfigurasi Caddy (FrankenPHP)
```

### Arsitektur Container

```
┌─────────────────────────────────────────────────────┐
│                    docker-compose                    │
│  ┌─────────────┐  ┌──────────┐  ┌───────────────┐  │
│  │    app      │  │ database │  │    cache      │  │
│  │ FrankenPHP  │◀─│  MySQL   │  │   Redis 7     │  │
│  │ Octane      │  │  8.0     │  │   (alpine)    │  │
│  │ Port 80/443 │  │ Port 3307│  │ Port 6380     │  │
│  └─────────────┘  └──────────┘  └───────────────┘  │
└─────────────────────────────────────────────────────┘
```

---

## Cara Menjalankan (Docker)

### 1. Clone atau Copy Project

```bash
cd /path/to/project
```

### 2. Setup Environment File

```bash
cp .env.docker .env
```

> **Catatan:** Jika ingin menggunakan direktori storage lokal (bukan volume), hapus dulu symlink yang sudah ada:
> ```bash
> rm -rf public/storage
> ```

### 3. Build & Jalankan Container

```bash
# Build image (hanya pertama kali atau setelah mengubah Dockerfile)
docker compose build --no-cache

# Jalankan semua container di background
docker compose up -d

# Atau langsung build + up:
docker compose up -d --build
```

### 4. Generate App Key

```bash
docker compose exec app php artisan key:generate
```

### 5. Setup Database

```bash
# Jalankan migrasi + seeder (data contoh)
docker compose exec app php artisan migrate --seed --force

# Buat storage symlink
docker compose exec app php artisan storage:link --force
```

### 6. Selesai! 🎉

Akses aplikasi di: **http://localhost**

---

## Konfigurasi Environment

### File `.env.docker` (default untuk Docker)

| Variable | Default | Keterangan |
|----------|---------|------------|
| `APP_URL` | `http://localhost:80` | URL aplikasi |
| `DB_DATABASE` | `arsip_arif` | Nama database |
| `DB_USERNAME` | `arsip_user` | User database |
| `DB_PASSWORD` | `secret` | Password database |
| `SESSION_DRIVER` | `redis` | Penyimpanan session |
| `CACHE_STORE` | `redis` | Penyimpanan cache |
| `QUEUE_CONNECTION` | `redis` | Driver queue |

### Port Mapping (docker-compose.yml)

| Service | Internal Port | Host Port (default) |
|---------|---------------|---------------------|
| App (HTTP) | 80 | 80 |
| App (HTTPS) | 443 | 443 |
| MySQL | 3306 | 3307 |
| Redis | 6379 | 6380 |

Untuk mengubah port host, set di file `.env`:

```env
APP_PORT=8080
DB_PORT_MAP=3307
REDIS_PORT_MAP=6380
```

---

## Perintah Penting (Makefile)

Semua perintah dijalankan dari direktori project.

```bash
# Menampilkan bantuan
make help

# Build image
make build

# Start semua container
make up

# Stop semua container
make down

# Restart container
make restart

# Masuk ke container app
make shell

# Melihat logs
make logs

# Status container
make ps
```

### Perintah Laravel via Makefile

```bash
# Migrasi database
make migrate

# Seed data contoh
make seed

# Migrate fresh + seed
make migrate-fresh

# Jalankan tests
make test

# Optimasi Laravel (production)
make optimize

# Clear cache
make cache-clear

# Reload Octane workers
make octane-reload

# Artisan custom
make artisan cmd="route:list"
make artisan cmd="make:controller DashboardController"

# Queue worker
make queue

# Build frontend assets
make npm-build
```

### Atau Langsung dengan Docker Compose

```bash
# Melihat logs app
docker compose logs -f app

# Eksekusi perintah
docker compose exec app php artisan route:list

# Restart service tertentu
docker compose restart app

# Hentikan semua
docker compose down

# Hapus volume juga (data database akan hilang!)
docker compose down -v
```

---

## Akses Aplikasi

| Halaman | URL | Keterangan |
|---------|-----|------------|
| Login | `http://localhost/login` | Halaman masuk |
| Dashboard | `http://localhost/dashboard` | Halaman utama |
| Arsip | `http://localhost/arsip` | Daftar arsip |

---

## Akun Default (Seeder)

Setelah menjalankan `make seed`, akun berikut tersedia:

| Username | Password | Role | Bidang |
|----------|----------|------|--------|
| `admin` | `password` | **Admin** | - |
| `kepala.dinas` | `password` | **Pimpinan** | Sekretariat |
| `staff.sekretariat` | `password` | **User** | Sekretariat |
| `staff.ketersediaan` | `password` | **User** | Bidang Ketersediaan Pangan |
| `staff.distribusi` | `password` | **User** | Bidang Distribusi Pangan |
| `staff.konsumsi` | `password` | **User** | Bidang Konsumsi Pangan |

---

## Troubleshooting

### 1. Container tidak bisa start

```bash
# Cek logs detail
docker compose logs

# Cek apakah port sudah digunakan
sudo lsof -i :80
sudo lsof -i :3307
sudo lsof -i :6380

# Ganti port di .env jika bentrok
```

### 2. Database connection refused

```bash
# Pastikan database sudah siap
docker compose ps

# Cek health status
docker compose exec database mysqladmin ping -u root -proot_secret

# Tunggu database siap, lalu jalankan migrasi ulang
make migrate
```

### 3. Storage link error

```bash
# Hapus symlink lama
rm -rf public/storage

# Buat ulang
docker compose exec app php artisan storage:link --force
```

### 4. Permission denied (storage)

```bash
# Perbaiki permission di dalam container
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R appuser:appgroup storage bootstrap/cache
```

### 5. Octane error / 502 Bad Gateway

```bash
# Cek status Octane
make octane-status

# Restart Octane
make octane-reload

# Atau restart container
docker compose restart app
```

### 6. Reset total (data akan hilang)

```bash
# Hentikan + hapus semua volume
docker compose down -v

# Build ulang + start
docker compose up -d --build

# Setup ulang
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed --force
docker compose exec app php artisan storage:link --force
```

---

## Cara Menjalankan Tanpa Docker (Local)

Jika tidak menggunakan Docker, ikuti langkah berikut:

### 1. Persyaratan
- PHP 8.3+ dengan ekstensi: `pdo_mysql`, `mbstring`, `gd`, `zip`, `bcmath`, `intl`
- Composer 2.x
- Node.js 20+ & npm
- MySQL 8.0+ / MariaDB
- Redis 7+ (opsional)

### 2. Langkah Instalasi

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_DATABASE=arsip_arif
# DB_USERNAME=root
# DB_PASSWORD=

# Buat database MySQL
mysql -u root -p -e "CREATE DATABASE arsip_arif CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Migrasi + seed
php artisan migrate --seed

# Storage symlink
php artisan storage:link

# Build assets
npm run build

# Jalankan (pilih salah satu):
php artisan serve
# atau dengan hot reload:
composer run dev
```

Akses: **http://localhost:8000**

---

## 🐳 Tips Production

Untuk deployment production, perhatikan hal berikut:

1. **Gunakan HTTPS** — Aktifkan dengan set `OCTANE_HTTPS=true` dan siapkan certificate
2. **Optimasi Laravel** — Jalankan `make optimize`
3. **Worker scaling** — Atur `OCTANE_WORKERS` sesuai jumlah CPU: `OCTANE_WORKERS=4`
4. **Backup database** — Backup volume `mysql_data` secara periodik
5. **Monitoring** — Gunakan `make logs` atau integrasikan dengan logging eksternal

---

## 📦 Teknologi yang Digunakan

| Teknologi | Fungsi |
|-----------|--------|
| Laravel 13 | Framework PHP |
| FrankenPHP 1.4 | Application server (PHP + Caddy) |
| Laravel Octane 2 | Performance booster |
| MySQL 8.0 | Database |
| Redis 7 | Cache & Session |
| Tailwind CSS 4 | Frontend styling |
| ApexCharts | Grafik dashboard |
| Maatwebsite Excel | Export Excel |
| DomPDF | Export PDF |

---

**Dibuat oleh:** Dinas Ketahanan Pangan Kabupaten Way Kanan  
**Versi:** 1.0  
**Terakhir diperbarui:** 2026-04-28
