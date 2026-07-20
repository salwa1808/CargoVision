<div align="center">

# 🌐 SupplyGuard
### Global Supply Chain Risk Intelligence Platform

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**Platform intelijen risiko rantai pasok global berbasis web yang memantau kerentanan logistik, geopolitik, cuaca, dan ekonomi secara real-time.**

</div>

---

## 📖 Tentang Proyek

**SupplyGuard** adalah aplikasi web enterprise yang dibangun dengan **Laravel 13** untuk membantu organisasi memantau dan menganalisis risiko rantai pasok global. Platform ini menggabungkan data cuaca, ekonomi, geopolitik, dan maritim ke dalam sebuah dashboard terpadu dengan antarmuka **Dark Glassmorphism** yang modern.

---

## ✨ Fitur Utama

### 🖥️ Dashboard & Control Panel
| Fitur | Deskripsi |
|---|---|
| **Executive Dashboard** | Pusat kontrol terpadu — tingkat ancaman global, peringatan aktif, bagan risiko, dan berita real-time |
| **Country Intelligence** | Profil per negara dengan skor risiko, cuaca lokal, dan indikator PDB |
| **Weather Threat Center** | Pemantauan risiko cuaca real-time di seluruh koridor logistik |
| **Economic Watch** | Pelacakan inflasi, kesehatan keuangan, dan pertumbuhan PDB |
| **Currency Intelligence** | Nilai tukar mata uang terkini untuk mitigasi risiko transaksi |
| **Ports & Maritime Traffic** | Kepadatan pelabuhan, pemantauan kapal aktif, dan penilaian risiko maritim |
| **News & Geopolitical Alerts** | Analisis sentimen berita algoritmik untuk peristiwa yang berdampak pada rute pasokan |

### 📊 Risk Analytics
- **Risk Score Engine** — Indeks risiko komposit berbasis bobot cuaca, sentimen berita, inflasi, dan stabilitas mata uang
- **Watchlist Manager** — Pantau titik-titik kritis rantai pasok secara khusus
- **Side-by-Side Comparison** — Bandingkan skor risiko antar rute pasokan secara berdampingan
- **Interactive Global Map** — Peta Leaflet.js interaktif dengan:
  - Penanda ancaman berkode warna: 🔴 Tinggi · 🟡 Sedang · 🟢 Rendah
  - Perencana Rute Dinamis (Maritim, Udara, Darat) dengan kalkulasi jarak
  - Tooltip nama negara persisten

### 🔔 Notification Center
- Mesin notifikasi polling real-time dengan Toast & push notifikasi Desktop
- Panel dropdown dengan pencarian, pemfilteran kata kunci, dan klasifikasi kategori (Risiko, Cuaca, Berita, Ekonomi)
- Fitur Tandai Dibaca, Baca Semua, dan hapus tunggal/massal

### 👤 User Profile & Preferences
- Upload avatar, statistik konten, dan lini masa aktivitas
- Edit profil inline: Nama, Username, Telepon, Lokasi, Zona Waktu, Bio
- Keamanan akun: update password dengan indikator kekuatan, preferensi notifikasi
- **Activity Log**: Log audit lokal dengan pencarian, paginasi, dan ekspor CSV

### 🛡️ Admin Panel
- **User Management**: CRUD lengkap akun sistem (nama, username, peran Admin/User, status aktif)
- **Article Management**:
  - Editor teks kaya Quill.js
  - Auto-generate slug URL real-time
  - Meta SEO (deskripsi & kata kunci)
  - Status Draf / Diterbitkan dan tag Unggulan

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend** | Laravel 13.x (PHP 8.3+) |
| **ORM** | Eloquent ORM |
| **Database** | MySQL / MariaDB |
| **Frontend** | Bootstrap 5, Vanilla CSS, Custom Dark Glassmorphism UI |
| **Map** | Leaflet.js + OpenStreetMap |
| **Charts** | Chart.js |
| **Rich Text** | Quill.js |
| **PDF Export** | barryvdh/laravel-dompdf |
| **Auth** | Laravel Sanctum |

---

## 📋 Instalasi & Setup

### Prasyarat

Pastikan sistem Anda sudah terinstal:
- **PHP** >= 8.3
- **Composer**
- **XAMPP** / WampServer (MySQL/MariaDB)
- **Node.js & NPM** (opsional, untuk kompilasi aset frontend)

---

### Langkah-Langkah Instalasi

**1. Clone Repository**
```bash
cd C:/xampp/htdocs
git clone <repository-url> global-supply-chain-risk
cd global-supply-chain-risk
```

**2. Install Dependensi PHP**
```bash
composer install
```

**3. Konfigurasi Environment**

Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Sesuaikan konfigurasi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=global_supply_chain_risk
DB_USERNAME=root
DB_PASSWORD=
```

**4. Generate Application Key**
```bash
php artisan key:generate
```

**5. Jalankan Migrasi & Seeder**

Buat tabel dan isi data awal (negara, pelabuhan, kapal, indikator ekonomi):
```bash
php artisan migrate --seed
```

**6. (Opsional) Kompilasi Aset Frontend**
```bash
npm install
npm run dev
```

**7. Jalankan Development Server**
```bash
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser Anda.

---

## 🔑 Akun Default (Seeder)

Setelah menjalankan `php artisan migrate --seed`, dua akun siap digunakan:

| Role | Email | Password |
|---|---|---|
| **Administrator** | admin@example.com | password |
| **User** | user@example.com | password |

> ⚠️ **Penting:** Segera ganti password default setelah login pertama kali di lingkungan produksi.

---

## 🗺️ Struktur Rute

| Route | Method | Deskripsi |
|---|---|---|
| `/login` | GET/POST | Halaman login |
| `/register` | GET/POST | Halaman registrasi |
| `/` | GET | Executive Dashboard |
| `/countries` | GET | Daftar negara & profil risiko |
| `/economy` | GET | Indikator ekonomi |
| `/currency` | GET | Nilai tukar mata uang |
| `/weather` | GET | Pemantauan cuaca |
| `/ports` | GET | Pelabuhan & lalu lintas maritim |
| `/news` | GET | Berita & peringatan geopolitik |
| `/watchlist` | GET | Watchlist kustom |
| `/compare` | GET | Perbandingan risiko |
| `/map` | GET | Peta interaktif global |
| `/profile` | GET | Profil pengguna |
| `/settings` | GET | Pengaturan akun |
| `/admin/users` | CRUD | Manajemen pengguna (Admin) |
| `/admin/articles` | CRUD | Manajemen artikel (Admin) |

---

## 🔒 Keamanan & Middleware

- **`auth` Middleware** — Semua rute kecuali `/login` dan `/register` dilindungi dan memerlukan autentikasi.
- **`guest` Middleware** — Rute login/register hanya dapat diakses oleh pengguna yang belum login.
- **`admin` Middleware** — Rute `/admin/*` hanya dapat diakses oleh pengguna dengan peran `admin`. Akses tidak sah akan menghasilkan respons `403 Forbidden`.

---

## 🗄️ Struktur Database

Model utama yang digunakan dalam aplikasi:

| Model | Deskripsi |
|---|---|
| `User` | Akun pengguna sistem (admin/user) |
| `Country` | Data negara yang dilacak |
| `RiskScore` | Skor risiko per negara |
| `RiskScoreHistory` | Histori perubahan skor risiko |
| `WeatherSnapshot` | Snapshot data cuaca |
| `EconomicIndicator` | Data indikator ekonomi |
| `ExchangeRate` | Data nilai tukar mata uang |
| `Port` | Data pelabuhan global |
| `Vessel` | Data kapal yang dipantau |
| `NewsCache` | Cache berita & sentimen |
| `Watchlist` | Daftar pantau kustom pengguna |
| `Article` | Konten artikel (CMS) |
| `PositiveWord` / `NegativeWord` | Kosakata analisis sentimen berita |

---

## 📁 Struktur Direktori

```
global-supply-chain-risk/
├── app/
│   ├── Http/
│   │   └── Controllers/      # Controller aplikasi
│   ├── Models/               # Eloquent models
│   ├── Services/             # Business logic services
│   └── Providers/
├── database/
│   ├── migrations/           # Skema database
│   └── seeders/              # Data awal
├── resources/
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Rute web
│   └── api.php               # Rute API
├── public/                   # Aset publik
└── .env                      # Konfigurasi environment
```

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

<div align="center">
  Dibangun dengan ❤️ menggunakan <strong>Laravel</strong>
</div>
