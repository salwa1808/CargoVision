<div align="center">

# 🚢 CargoVision
### Smart Maritime Cargo Tracking & Shipment Management System

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**CargoVision** adalah aplikasi berbasis Laravel yang digunakan untuk memonitor pengiriman kargo laut secara real-time, mengelola data kapal, pelabuhan, negara, dan shipment dalam satu dashboard modern.

</div>

---

# 📖 About

CargoVision merupakan sistem manajemen logistik maritim yang memudahkan perusahaan dalam mengelola data pelabuhan, kapal, negara, serta proses pengiriman barang (shipment).

Sistem dibangun menggunakan Laravel 13 dengan database MySQL dan tampilan dashboard modern yang responsif.

---

# ✨ Features

## 🚢 Vessel Management

- CRUD Data Kapal
- Status Kapal
- Informasi IMO
- MMSI
- Call Sign
- Tipe Kapal
- Posisi Pelabuhan

---

## 🌍 Country Management

- Data Negara
- ISO Code
- Bendera Negara
- Statistik

---

## ⚓ Port Management

- Import otomatis 11.500+ pelabuhan dunia dari UN/LOCODE
- Latitude & Longitude
- Relasi ke Negara
- Pencarian Pelabuhan

---

## 📦 Shipment Management

- Membuat Shipment Baru
- Origin Port
- Destination Port
- Vessel Assignment
- Tracking Status
- ETA & ETD
- Cargo Information

---

## 📊 Dashboard

Dashboard menampilkan:

- Total Countries
- Total Ports
- Total Vessels
- Total Shipments
- Statistik Pengiriman
- Statistik Kapal

---

## 👤 Authentication

- Login
- Register
- Logout
- Profile

---

# 🛠 Tech Stack

| Layer | Technology |
|--------|------------|
| Backend | Laravel 13 |
| Language | PHP 8.3 |
| Database | MySQL |
| Frontend | Bootstrap 5 |
| ORM | Eloquent |
| Authentication | Laravel Auth |
| Server | Apache (XAMPP) |

---

# 📂 Project Structure

```
app/
├── Models
├── Http
│   ├── Controllers
│   └── Middleware
├── Console
│   └── Commands
```

```
database/
├── migrations
├── seeders
```

```
resources/
├── views
```

```
routes/
├── web.php
```

---

# 🚀 Installation

Clone repository

```bash
git clone https://github.com/username/CargoVision.git
```

Masuk ke project

```bash
cd CargoVision
```

Install dependency

```bash
composer install
```

Copy environment

```bash
cp .env.example .env
```

Generate Key

```bash
php artisan key:generate
```

Setting database pada `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cargovision
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration

```bash
php artisan migrate
```

Jalankan Seeder (Opsional)

```bash
php artisan db:seed
```

Import seluruh pelabuhan dunia

```bash
php artisan fetch:ports
```

Jalankan server

```bash
php artisan serve
```

Buka browser

```
http://127.0.0.1:8000
```

---

# 🗄 Database

Model utama:

- User
- Country
- Port
- Vessel
- Shipment *(Coming Soon)*
- ShipmentTracking *(Coming Soon)*

---

# 📌 Artisan Commands

Import seluruh pelabuhan dunia

```bash
php artisan fetch:ports
```

Membersihkan cache

```bash
php artisan optimize:clear
```

---

# 📈 Current Data

Saat ini sistem telah memiliki:

- 🌍 250 Countries
- ⚓ 11.594 Sea Ports
- 🚢 Vessel Management
- 🔐 Authentication

---

# 🔮 Roadmap

- ✅ Authentication
- ✅ Countries
- ✅ Ports
- ✅ Vessel Management
- 🔄 Shipment Module
- 🔄 Shipment Tracking
- 🔄 Cargo Management
- 🔄 Live Vessel Map
- 🔄 Dashboard Analytics
- 🔄 Export PDF
- 🔄 REST API

---

# 📄 License

MIT License

---

<div align="center">

Made with ❤️ using Laravel

</div>