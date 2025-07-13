# 🎉 Eventra - Event Management System

<div align="center">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/CodeIgniter-4.x-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white" alt="CodeIgniter">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</div>

<div align="center">
  <h3>🚀 Modern Event Management Platform Built with CodeIgniter 4</h3>
  <p>Kelola event Anda dengan mudah, aman, dan efisien!</p>
</div>

---

## 📋 Daftar Isi

- [✨ Fitur Utama](#-fitur-utama)
- [🛠️ Teknologi](#️-teknologi)
- [📦 Instalasi](#-instalasi)
- [🔧 Konfigurasi](#-konfigurasi)
- [🚀 Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [👥 Akun Default](#-akun-default)
- [📱 Fitur Aplikasi](#-fitur-aplikasi)
- [🔒 Keamanan](#-keamanan)
- [📊 Struktur Database](#-struktur-database)
- [🤝 Kontribusi](#-kontribusi)
- [📄 Lisensi](#-lisensi)

---

## ✨ Fitur Utama

### 🎯 **Untuk Organizer Event**
- 📅 **Manajemen Event Lengkap** - Buat, edit, dan kelola event dengan mudah
- 👥 **Manajemen Peserta** - Lihat dan kelola pendaftaran peserta
- 📊 **Dashboard Analytics** - Statistik event dan peserta real-time
- 🎫 **Sistem Tiket** - Generate dan kelola tiket event
- 📧 **Notifikasi Email** - Kirim konfirmasi dan reminder otomatis

### 🎪 **Untuk Peserta**
- 🔍 **Pencarian Event** - Temukan event menarik dengan mudah
- 📝 **Pendaftaran Online** - Daftar event dengan proses yang simpel
- 👤 **Profil Pengguna** - Kelola informasi pribadi dan riwayat event
- 🎟️ **E-Ticket** - Dapatkan tiket digital untuk event
- ⭐ **Rating & Review** - Berikan feedback untuk event yang diikuti

### 🛡️ **Untuk Administrator**
- 🔐 **Manajemen User** - Kelola akun pengguna dan hak akses
- 📈 **Monitoring Sistem** - Pantau performa dan aktivitas aplikasi
- 🔧 **Konfigurasi Sistem** - Atur pengaturan aplikasi
- 📋 **Laporan Lengkap** - Generate laporan event dan keuangan

---

## 🛠️ Teknologi

### **Backend**
- **PHP 8.1+** - Bahasa pemrograman utama
- **CodeIgniter 4.x** - Framework PHP modern dan ringan
- **MySQL 8.0+** - Database management system
- **Composer** - Dependency manager untuk PHP

### **Frontend**
- **Bootstrap 5.x** - CSS framework untuk UI responsif
- **jQuery** - JavaScript library untuk interaktivitas
- **Font Awesome** - Icon library
- **Chart.js** - Library untuk visualisasi data

### **Security & Performance**
- **CSRF Protection** - Perlindungan dari serangan CSRF
- **Rate Limiting** - Pembatasan request untuk mencegah spam
- **Password Hashing** - Enkripsi password dengan bcrypt
- **Session Management** - Manajemen sesi yang aman
- **Input Validation** - Validasi input yang ketat

---

## 📦 Instalasi

### **Prasyarat**
Pastikan sistem Anda memiliki:
- PHP 8.1 atau lebih tinggi
- MySQL 8.0 atau lebih tinggi
- Composer
- Web server (Apache/Nginx) atau XAMPP/WAMP

### **Langkah Instalasi**

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/eventra.git
   cd eventra
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   ```

4. **Konfigurasi Database**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   database.default.hostname = localhost
   database.default.database = eventra_db
   database.default.username = your_username
   database.default.password = your_password
   database.default.DBDriver = MySQLi
   ```

5. **Buat Database**
   ```sql
   CREATE DATABASE eventra_db;
   ```

6. **Jalankan Migration**
   ```bash
   php spark migrate
   ```

7. **Jalankan Seeder (Opsional)**
   ```bash
   php spark db:seed
   ```

---

## 🔧 Konfigurasi

### **Environment Variables**
Sesuaikan konfigurasi di file `.env`:

```env
# App Configuration
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8000'
app.appTimezone = 'Asia/Jakarta'

# Database Configuration
database.default.hostname = localhost
database.default.database = eventra_db
database.default.username = root
database.default.password = 

# Security Configuration
security.csrfProtection = 'cookie'
security.tokenRandomize = true
security.tokenName = 'csrf_token_name'
security.cookieName = 'csrf_cookie_name'

# Session Configuration
session.driver = 'CodeIgniter\Session\Handlers\FileHandler'
session.cookieName = 'ci_session'
session.expiration = 7200
```

---

## 🚀 Menjalankan Aplikasi

### **Development Server**
```bash
php spark serve
```
Aplikasi akan berjalan di `http://localhost:8080`

### **Production Server**
1. Upload semua file ke web server
2. Arahkan document root ke folder `public/`
3. Pastikan folder `writable/` memiliki permission 755
4. Set environment ke `production` di file `.env`

---

## 👥 Akun Default

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

### **Administrator**
- **Email:** `admin@example.com`
- **Password:** `admin123`
- **Role:** Admin

### **User Demo**
- **Email:** `user@example.com`
- **Password:** `user123`
- **Role:** User

---

## 📱 Fitur Aplikasi

### **Dashboard Admin**
- 📊 Statistik event dan pengguna
- 📈 Grafik pendaftaran event
- 🔔 Notifikasi sistem
- 📋 Manajemen konten

### **Dashboard User**
- 🎪 Event yang diikuti
- 📅 Kalender event
- 🎫 Koleksi tiket
- ⭐ Riwayat rating

### **Manajemen Event**
- ➕ Buat event baru
- ✏️ Edit informasi event
- 👥 Kelola peserta
- 📊 Laporan event

### **Sistem Pendaftaran**
- 📝 Form pendaftaran dinamis
- 💳 Integrasi payment gateway
- 📧 Konfirmasi email otomatis
- 🎟️ Generate e-ticket

---

## 🔒 Keamanan

Eventra dilengkapi dengan fitur keamanan tingkat enterprise:

- **🛡️ CSRF Protection** - Mencegah serangan Cross-Site Request Forgery
- **🔐 Password Hashing** - Menggunakan algoritma bcrypt yang aman
- **🚦 Rate Limiting** - Membatasi request untuk mencegah abuse
- **✅ Input Validation** - Validasi ketat pada semua input pengguna
- **🔒 Session Security** - Manajemen sesi yang aman dengan regenerasi token
- **🚫 SQL Injection Prevention** - Menggunakan prepared statements
- **🔍 XSS Protection** - Filter output untuk mencegah script injection

---

## 📊 Struktur Database

### **Tabel Utama**

#### **users**
```sql
- id (Primary Key)
- username (Unique)
- email (Unique)
- password (Hashed)
- full_name
- phone
- role (admin/user)
- is_active
- created_at
- updated_at
```

#### **events**
```sql
- id (Primary Key)
- user_id (Foreign Key)
- title
- description
- start_date
- end_date
- location
- max_participants
- price
- status
- created_at
- updated_at
```

#### **event_registrations**
```sql
- id (Primary Key)
- event_id (Foreign Key)
- user_id (Foreign Key)
- registration_date
- status
- payment_status
- created_at
- updated_at
```

---

## 🎨 Screenshots

### Dashboard Admin
![Admin Dashboard](docs/images/admin-dashboard.png)

### Event Management
![Event Management](docs/images/event-management.png)

### User Registration
![User Registration](docs/images/user-registration.png)

---

## 🚀 Roadmap

### **Version 2.0** (Q2 2024)
- [ ] 📱 Mobile App (React Native)
- [ ] 💳 Multiple Payment Gateways
- [ ] 🌐 Multi-language Support
- [ ] 📊 Advanced Analytics
- [ ] 🔔 Push Notifications

### **Version 2.1** (Q3 2024)
- [ ] 🎥 Live Streaming Integration
- [ ] 🤖 AI-powered Event Recommendations
- [ ] 📱 QR Code Check-in
- [ ] 💬 Real-time Chat
- [ ] 🎯 Marketing Automation

---

## 🤝 Kontribusi

Kami sangat menghargai kontribusi dari komunitas! Berikut cara berkontribusi:

1. **Fork** repository ini
2. **Buat branch** untuk fitur baru (`git checkout -b feature/AmazingFeature`)
3. **Commit** perubahan Anda (`git commit -m 'Add some AmazingFeature'`)
4. **Push** ke branch (`git push origin feature/AmazingFeature`)
5. **Buat Pull Request**

### **Guidelines Kontribusi**
- Ikuti coding standards PSR-12
- Tulis unit tests untuk fitur baru
- Update dokumentasi jika diperlukan
- Pastikan semua tests pass

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE) - lihat file LICENSE untuk detail lengkap.

---

## 🙏 Acknowledgments

- **CodeIgniter Team** - Framework yang luar biasa
- **Bootstrap Team** - UI framework yang powerful
- **Font Awesome** - Icon library yang comprehensive
- **Chart.js** - Library visualisasi data yang elegant

---

<div align="center">
  <h3>⭐ Jika proyek ini membantu Anda, berikan star di GitHub! ⭐</h3>
  <p>Made with ❤️ by Kyy</p>
  
  <a href="https://github.com/username/eventra">
    <img src="https://img.shields.io/github/stars/username/eventra?style=social" alt="GitHub stars">
  </a>
  <a href="https://github.com/username/eventra/fork">
    <img src="https://img.shields.io/github/forks/username/eventra?style=social" alt="GitHub forks">
  </a>
</div>

---

**© 2024 Eventra. All rights reserved.**
