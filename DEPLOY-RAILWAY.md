# Deployment CargoVision ke Railway

CargoVision dideploy dari repository:
`https://github.com/salwa1808/CargoVision`.

## Arsitektur awal

- Satu App Service untuk Laravel.
- Satu MySQL Service untuk database.
- Sinkronisasi data dijalankan manual oleh admin.

Cron dan worker tidak dibuat pada tahap awal agar penggunaan kredit Railway
lebih hemat.

## 1. Buat project

1. Login ke Railway menggunakan GitHub.
2. Pilih **New Project > Deploy from GitHub repo**.
3. Pilih repository `salwa1808/CargoVision`.
4. Jangan membuat domain publik sebelum variabel dan database selesai.

## 2. Tambahkan MySQL

1. Pada Project Canvas klik **+ New**.
2. Pilih **Database > Add MySQL**.
3. Tunggu sampai service MySQL aktif.

## 3. Isi Variables pada App Service

Buka App Service > **Variables > Raw Editor**, kemudian isi:

```dotenv
APP_NAME=CargoVision
APP_ENV=production
APP_KEY=base64:HASIL_PERINTAH_KEY_GENERATE
APP_DEBUG=false
APP_URL=https://DOMAIN-RAILWAY
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
LOG_CHANNEL=stderr
LOG_LEVEL=warning
DB_CONNECTION=mysql
DB_URL=${{MySQL.MYSQL_URL}}
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
ADMIN_NAME=System Administrator
ADMIN_EMAIL=EMAIL-ADMIN
ADMIN_PASSWORD=PASSWORD-ADMIN-YANG-KUAT
```

Buat `APP_KEY` di komputer lokal:

```powershell
php artisan key:generate --show
```

Jangan memasukkan password atau `APP_KEY` ke GitHub.

## 4. Deploy dan buat domain

1. Railway akan membaca `railway.toml`.
2. Pre-deploy menjalankan migration dan cache Laravel secara otomatis.
3. Setelah deployment berstatus **Success**, buka
   **Settings > Networking > Generate Domain**.
4. Salin domain tersebut ke variabel `APP_URL`, lalu redeploy.

## 5. Isi data awal sekali saja

Pada App Service, jalankan command berikut satu kali menggunakan Railway CLI
atau command shell:

```bash
php artisan db:seed --force
php artisan fetch:countries
php artisan fetch:economic
php artisan fetch:exchangerates
php artisan fetch:weather
php artisan fetch:news
php artisan fetch:ports
php artisan calculate:risk
php artisan risk:history
```

Seeder menggunakan `ADMIN_EMAIL` dan `ADMIN_PASSWORD` dari Variables. Jangan
memakai kredensial contoh pada production.

## 6. Pemeriksaan

```bash
php artisan about
php artisan migrate:status
php artisan route:list
```

Jika deployment gagal, periksa tab **Deployments > View Logs**. Log Laravel
ditulis ke `stderr`, sehingga error tampil langsung di log Railway.
