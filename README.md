Tech Stack

Backend : Laravel 11
Bahasa  : PHP 8.2
Database : MySQL 8.0
Frontend : Blade + Jquery
Ui : Bootsrap 5

Langkah Instalasi

# 1. Clone repository
git clone https://github.com/AdytDwiPutra/TesAptavis.git
cd TesAptavis

# 2. Install dependencies PHP
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Download vendor
npm install

# 6. Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_tracker
DB_USERNAME=root
DB_PASSWORD=

# 7. Jalankan migrasi
php artisan migrate

# 8. Jalankan server
php artisan serve

Menambah Project :
 - Klik tombol "Add Project"
 - Panel slide dari kanan akan muncul
 - Isi nama project, start_date, end_date
 - Klik "Simpan" — project langsung muncul di daftar tanpa reload

Menambah Task :
 - Klik tombol "+" di samping nama project
 - Panel slide dari kanan akan muncul
 - Isi nama task, bobot, status, dan (opsional) dependency
 - Klik "Simpan"