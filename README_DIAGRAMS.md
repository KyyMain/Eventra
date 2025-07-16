# 📊 Use Case Diagram dan Flowchart Sistem Eventra

## 📁 File Diagram yang Tersedia

### 1. Use Case Diagram
**File**: `use_case_diagram.svg`
- **Format**: SVG (Scalable Vector Graphics)
- **Ukuran**: 1200x800 pixels
- **Deskripsi**: Menggambarkan interaksi antara aktor (User, Admin, Payment Gateway) dengan sistem Eventra

### 2. Flowchart Proses Bisnis
**File**: `flowchart_eventra.svg`
- **Format**: SVG (Scalable Vector Graphics) 
- **Ukuran**: 1400x1200 pixels
- **Deskripsi**: Menggambarkan alur proses pendaftaran event dan pembayaran

### 3. Dokumentasi Lengkap
**File**: `DOKUMENTASI_DIAGRAM.md`
- **Format**: Markdown
- **Deskripsi**: Penjelasan detail tentang kedua diagram dan sistem Eventra

## 🔍 Cara Melihat Diagram

### Opsi 1: Browser Web
1. Buka file `.svg` langsung di browser (Chrome, Firefox, Safari, Edge)
2. Klik kanan pada file → "Open with" → Pilih browser
3. Diagram akan ditampilkan dengan kualitas vector yang dapat di-zoom

### Opsi 2: Editor Kode
1. Buka file `.svg` di VS Code, Sublime Text, atau editor lainnya
2. Install extension SVG preview jika tersedia
3. Lihat preview atau edit kode SVG langsung

### Opsi 3: Aplikasi Desain
1. Import file `.svg` ke aplikasi seperti:
   - Adobe Illustrator
   - Inkscape (gratis)
   - Figma
   - Canva

### Opsi 4: Online SVG Viewer
1. Upload file ke online viewer seperti:
   - svgviewer.dev
   - vecta.io/nano
   - boxy-svg.com

## 📋 Ringkasan Konten Diagram

### Use Case Diagram

#### 👤 Aktor Utama:
- **User**: Peserta event
- **Admin**: Pengelola sistem
- **Payment Gateway**: Sistem pembayaran eksternal

#### 🎯 Use Cases User:
- Login/Register
- Kelola Profil
- Lihat & Daftar Event
- Proses Pembayaran
- Lihat Dashboard & Sertifikat

#### ⚙️ Use Cases Admin:
- Kelola Event (CRUD)
- Kelola User
- Kelola Pendaftaran
- Lihat Laporan & Statistik

### Flowchart

#### 🔄 Alur Proses Utama:
1. **Autentikasi** → Login/Register
2. **Pemilihan Event** → Browse & Select
3. **Verifikasi** → Cek ketersediaan & status
4. **Pendaftaran** → Register event
5. **Pembayaran** → Payment process (jika berbayar)
6. **Konfirmasi** → Success notification

#### ❌ Error Handling:
- Event tidak tersedia
- Sudah terdaftar
- Pembayaran gagal
- Redirect ke langkah yang sesuai

## 🛠️ Teknologi yang Digunakan

### Diagram Creation:
- **SVG**: Format vector untuk kualitas tinggi
- **Hand-coded**: Dibuat manual untuk kontrol penuh
- **Responsive**: Dapat di-scale tanpa kehilangan kualitas

### Sistem Eventra:
- **Backend**: CodeIgniter 4
- **Database**: MySQL
- **Architecture**: MVC + Service Layer + Repository Pattern
- **Payment**: Multiple gateway support
- **Security**: CSRF, validation, authentication

## 📖 Cara Membaca Diagram

### Use Case Diagram:
- **Oval**: Use case (fungsi sistem)
- **Stick Figure**: Aktor (pengguna)
- **Rectangle**: Boundary sistem
- **Lines**: Relasi antara aktor dan use case
- **<<include>>**: Relasi include (wajib)
- **<<extend>>**: Relasi extend (opsional)

### Flowchart:
- **Oval**: Start/End point
- **Rectangle**: Process/Action
- **Diamond**: Decision point
- **Parallelogram**: Input/Output
- **Arrows**: Flow direction
- **Yes/No**: Decision outcomes

## 🎨 Kustomisasi Diagram

### Mengubah Warna:
```css
/* Edit bagian <style> dalam file SVG */
.usecase-oval { fill: #your-color; }
.process { fill: #your-color; }
.decision { fill: #your-color; }
```

### Mengubah Teks:
```xml
<!-- Edit elemen <text> dalam file SVG -->
<text x="300" y="125" class="usecase">Your Text</text>
```

### Menambah Elemen:
```xml
<!-- Tambah shape baru -->
<ellipse cx="x" cy="y" rx="width" ry="height" class="usecase-oval"/>
<text x="x" y="y" class="usecase">New Use Case</text>
```

## 📚 Referensi Tambahan

### UML Standards:
- [UML Use Case Diagram Guidelines](https://www.uml-diagrams.org/use-case-diagrams.html)
- [Flowchart Symbols and Meanings](https://www.lucidchart.com/pages/flowchart-symbols-meaning-explained)

### Tools Alternatif:
- **Draw.io**: Free online diagramming
- **Lucidchart**: Professional diagramming
- **PlantUML**: Text-based UML diagrams
- **Mermaid**: Markdown-based diagrams

## 🤝 Kontribusi

Jika Anda ingin mengembangkan atau memperbaiki diagram:

1. **Fork** repository ini
2. **Edit** file SVG sesuai kebutuhan
3. **Test** tampilan di berbagai browser
4. **Submit** pull request dengan deskripsi perubahan

## 📞 Support

Jika ada pertanyaan tentang diagram atau sistem Eventra:
- Buka issue di repository
- Hubungi tim development
- Lihat dokumentasi lengkap di `DOKUMENTASI_DIAGRAM.md`

---

**Catatan**: Diagram ini dibuat berdasarkan analisis kode sistem Eventra yang ada. Pastikan diagram tetap sinkron dengan perkembangan sistem.