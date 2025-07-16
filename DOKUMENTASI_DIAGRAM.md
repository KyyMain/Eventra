# Dokumentasi Use Case Diagram dan Flowchart Sistem Eventra

## 1. Use Case Diagram

### Deskripsi Umum
Use Case Diagram menggambarkan interaksi antara aktor (pengguna) dengan sistem Eventra untuk manajemen event. Diagram ini menunjukkan fungsionalitas utama yang tersedia untuk setiap jenis pengguna.

### Aktor dalam Sistem

#### 1. User (Pengguna Umum)
**Deskripsi**: Pengguna yang ingin mendaftar dan mengikuti event

**Use Cases**:
- **Login/Register**: Masuk atau mendaftar akun baru
- **Kelola Profil**: Mengubah informasi profil pengguna
- **Lihat Daftar Event**: Melihat semua event yang tersedia
- **Lihat Detail Event**: Melihat informasi lengkap event tertentu
- **Daftar Event**: Mendaftarkan diri untuk mengikuti event
- **Batalkan Pendaftaran**: Membatalkan pendaftaran event
- **Pilih Metode Pembayaran**: Memilih cara pembayaran untuk event berbayar
- **Proses Pembayaran**: Melakukan pembayaran event
- **Lihat Status Pembayaran**: Memeriksa status pembayaran
- **Lihat Dashboard**: Melihat ringkasan aktivitas pengguna
- **Lihat Event Saya**: Melihat daftar event yang telah didaftar
- **Lihat Sertifikat**: Mengakses sertifikat event yang telah diikuti

#### 2. Admin (Administrator)
**Deskripsi**: Pengelola sistem yang bertanggung jawab atas manajemen event dan pengguna

**Use Cases**:
- **Kelola Event**: Mengelola semua aspek event
  - **Buat Event**: Membuat event baru
  - **Edit Event**: Mengubah informasi event
  - **Hapus Event**: Menghapus event
- **Kelola User**: Mengelola data pengguna
  - **Lihat Data User**: Melihat informasi pengguna
  - **Export Data User**: Mengekspor data pengguna
- **Kelola Pendaftaran**: Mengelola pendaftaran event
  - **Verifikasi Kehadiran**: Memverifikasi kehadiran peserta
  - **Terbitkan Sertifikat**: Menerbitkan sertifikat untuk peserta
- **Lihat Laporan**: Mengakses berbagai laporan sistem
- **Dashboard Admin**: Melihat statistik dan ringkasan sistem
- **Statistik Event**: Melihat analitik event

#### 3. Payment Gateway
**Deskripsi**: Sistem eksternal untuk memproses pembayaran

**Use Cases**:
- **Validasi Pembayaran**: Memvalidasi transaksi pembayaran
- **Kirim Notifikasi**: Mengirim notifikasi status pembayaran

### Relasi dalam Use Case

#### Include Relationships
- **Daftar Event** include **Pilih Metode Pembayaran** (untuk event berbayar)
- **Pilih Metode Pembayaran** include **Proses Pembayaran**
- **Kelola Event** include **Buat Event**, **Edit Event**, **Hapus Event**
- **Kelola User** include **Lihat Data User**, **Export Data User**
- **Kelola Pendaftaran** include **Verifikasi Kehadiran**, **Terbitkan Sertifikat**

#### Extend Relationships
- **Proses Pembayaran** extend **Lihat Status Pembayaran**

## 2. Flowchart Sistem

### Deskripsi Umum
Flowchart menggambarkan alur proses utama dalam sistem Eventra, khususnya proses pendaftaran event dan pembayaran. Diagram ini menunjukkan langkah-langkah yang harus dilalui pengguna dari awal hingga berhasil mendaftar event.

### Alur Proses Utama (User Flow)

#### 1. Tahap Autentikasi
```
START → Cek Login User → [Jika belum login] → Login/Register → Lihat Daftar Event
                      → [Jika sudah login] → Lihat Daftar Event
```

#### 2. Tahap Pemilihan Event
```
Lihat Daftar Event → Pilih Event → Cek Ketersediaan Event
```

**Decision Point**: Event Tersedia & Kuota?
- **Ya**: Lanjut ke pengecekan pendaftaran
- **Tidak**: Tampilkan pesan "Event Tidak Tersedia" → Kembali ke Daftar Event

#### 3. Tahap Verifikasi Pendaftaran
```
Cek Ketersediaan → Cek Status Pendaftaran
```

**Decision Point**: Sudah Terdaftar?
- **Ya**: Tampilkan pesan "Sudah Terdaftar" → Kembali ke Daftar Event
- **Tidak**: Lanjut ke proses pendaftaran

#### 4. Tahap Pendaftaran
```
Daftar Event → Cek Harga Event
```

**Decision Point**: Event Berbayar?
- **Tidak**: Pendaftaran Berhasil (Gratis) → END (Success)
- **Ya**: Lanjut ke proses pembayaran

#### 5. Tahap Pembayaran
```
Pilih Metode Pembayaran → Buat Pembayaran → Tampilkan Instruksi → 
Proses Pembayaran (Payment Gateway) → Cek Status Pembayaran
```

**Decision Point**: Pembayaran Berhasil?
- **Ya**: Update Status Terbayar → Kirim Email Konfirmasi → END (Success)
- **Tidak**: Update Status Gagal/Expired → END (Error)

### Alur Proses Admin (Admin Flow)

```
Admin Login → Dashboard Admin → [Pilihan Menu]:
├── Kelola Event
├── Kelola User  
├── Kelola Pendaftaran
└── Lihat Laporan
```

### Decision Points Penting

1. **User Login Check**: Memastikan pengguna telah login sebelum mengakses fitur
2. **Event Availability**: Memverifikasi event masih tersedia dan ada kuota
3. **Registration Status**: Mencegah pendaftaran ganda
4. **Event Price**: Menentukan alur pembayaran atau langsung berhasil
5. **Payment Status**: Memvalidasi hasil pembayaran

### Error Handling

Sistem memiliki beberapa jalur error handling:
- Event tidak tersedia atau penuh
- User sudah terdaftar untuk event
- Pembayaran gagal atau expired
- Setiap error akan mengarahkan user kembali ke langkah yang sesuai

## 3. Fitur Utama Sistem

### Untuk User:
1. **Manajemen Akun**: Registrasi, login, update profil
2. **Pencarian Event**: Browse dan filter event berdasarkan kategori
3. **Pendaftaran Event**: Daftar event gratis atau berbayar
4. **Pembayaran**: Integrasi dengan payment gateway
5. **Dashboard**: Tracking event yang diikuti dan status pembayaran
6. **Sertifikat**: Download sertifikat event yang telah diselesaikan

### Untuk Admin:
1. **Manajemen Event**: CRUD operations untuk event
2. **Manajemen User**: Monitoring dan pengelolaan pengguna
3. **Manajemen Pendaftaran**: Verifikasi kehadiran dan penerbitan sertifikat
4. **Reporting**: Dashboard analytics dan export data
5. **Sistem Pembayaran**: Monitoring transaksi pembayaran

## 4. Teknologi dan Arsitektur

### Backend:
- **Framework**: CodeIgniter 4
- **Database**: MySQL
- **Architecture Pattern**: MVC dengan Service Layer, Repository Pattern, dan DTO

### Fitur Teknis:
- **Payment Integration**: Support multiple payment gateways
- **Event System**: Event-driven architecture untuk audit trails
- **Validation**: Custom validation rules
- **Security**: CSRF protection, input validation, authentication
- **Performance**: Caching service, database optimization

### Database Entities:
- **Users**: Data pengguna dan admin
- **Events**: Informasi event dan metadata
- **Event_Registrations**: Data pendaftaran peserta
- **Payments**: Transaksi pembayaran
- **Payment_Methods**: Metode pembayaran yang tersedia

## 5. Keamanan dan Validasi

### Validasi Input:
- Email format validation
- Password strength requirements
- Event capacity limits
- Payment amount validation

### Keamanan:
- Password hashing
- Session management
- CSRF protection
- SQL injection prevention
- XSS protection

### Audit Trail:
- Payment logging
- User activity tracking
- Event creation/modification logs

Dokumentasi ini memberikan gambaran lengkap tentang fungsionalitas dan alur kerja sistem Eventra berdasarkan use case diagram dan flowchart yang telah dibuat.