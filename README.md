# 🏫 SiBookan - Sistem Booking Ruangan UNESA

<div align="center">
  <img src="https://img.shields.io/badge/Status-Live%20Production-brightgreen" alt="Status">
  <img src="https://img.shields.io/badge/Platform-Web-blue" alt="Platform">
  <img src="https://img.shields.io/badge/Framework-PHP%20%7C%20MySQL-orange" alt="Framework">
  <img src="https://img.shields.io/badge/Deployment-Localhost%20%2F%20XAMPP-purple" alt="Deployment">
</div>

## 🎯 Overview
<div style="display: flex; gap: 30px; flex-wrap: wrap;">
  <!-- Replace with your own screenshots or illustrations -->
  <img width="400" alt="home" src="https://github.com/yourusername/sibookan/assets/home_screenshot.png" />
  <img width="400" alt="booking" src="https://github.com/yourusername/sibookan/assets/booking_screenshot.png" />
  <img width="400" alt="responsive" src="https://github.com/yourusername/sibookan/assets/responsive_screenshot.png" />
</div>

**SiBookan** adalah sistem booking ruangan online untuk Gedung A10 UNESA Ketintang. Platform ini memudahkan mahasiswa dan dosen dalam melakukan pemesanan ruangan secara cepat, efisien, dan transparan.

### ✨ Key Features

- 🗓️ **Booking Ruangan Online** - Pesan ruangan dengan mudah dan real-time
- 👨‍🏫 **Role Dosen & PJ** - Hak akses berbeda untuk dosen dan penanggung jawab
- 📅 **Filter & Cek Ketersediaan** - Cek ketersediaan ruangan berdasarkan tanggal dan jam
- 📱 **Responsive Design** - Tampilan optimal di desktop & mobile
- 🔒 **Autentikasi & Hak Akses** - Sistem login dan validasi user
- 📊 **Daftar Booking & Riwayat** - Lihat daftar booking dan statusnya

## 🚀 Live Demo

> **Note:** SiBookan berjalan di lingkungan lokal (localhost/XAMPP). Untuk demo online, silakan deploy ke server pilihan Anda.

## 🛠️ Technology Stack

- **Backend:** PHP 7+, MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Styling:** Custom CSS (responsive, modern look)
- **Icons:** Google Material Icons
- **Deployment:** XAMPP / Localhost

## 📋 Features Breakdown

### 🗓️ Booking Ruangan
- Pilih tanggal, jam, ruangan, mata kuliah, kelas, dan jumlah SKS
- Validasi bentrok jadwal otomatis
- Tidak bisa booking di tanggal lampau atau weekend (opsional)

### 👨‍🏫 Role Management
- **Dosen:** Booking ruangan untuk kelas sendiri
- **PJ:** Mengelola booking dan melihat semua jadwal

### 📱 Responsive Design
- Tabel booking bisa scroll horizontal di mobile
- Form dan tampilan menyesuaikan layar

### 📊 Riwayat & Status
- Lihat daftar booking, status, dan detail dosen/matkul
- Filter berdasarkan tanggal dan jam

## ⚡ Installation

1. **Clone repository**
   ```bash
   git clone https://github.com/yourusername/sibookan.git
   cd sibookan
   ```
2. **Setup Database**
   - Import file `sibookan.sql` ke MySQL Anda (bisa lewat phpMyAdmin)
   - Edit file `database.php` sesuai konfigurasi MySQL lokal Anda
3. **Jalankan di XAMPP**
   - Pindahkan folder ke `htdocs` (misal: `C:/xampp/htdocs/sibookan`)
   - Start Apache & MySQL dari XAMPP
   - Akses di browser: `http://localhost/sibookan/home.php`

## 🎯 Usage Examples

### Booking Ruangan
```
1. Login sebagai dosen atau PJ
2. Pilih tanggal, jam, ruangan, matkul, kelas, dan SKS
3. Klik "Booking Sekarang"
4. Sistem akan menampilkan notifikasi sukses/gagal
```

### Filter Jadwal
```
1. Gunakan form filter di halaman utama
2. Pilih tanggal dan jam
3. Tabel akan menampilkan ketersediaan ruangan
```

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **UNESA** untuk inspirasi sistem booking
- **Google Material Icons** untuk ikon
- **XAMPP** untuk environment development 