# 📦 IndexedDB + MySQL Sync

Aplikasi web **offline-first** menggunakan **IndexedDB** dengan sinkronisasi otomatis ke **MySQL** melalui REST API (PHP).

Project ini mendemonstrasikan arsitektur hybrid local-first, di mana data selalu disimpan ke database lokal terlebih dahulu, lalu disinkronkan ke server ketika online.

---

## 🚀 Fitur

- ✅ Arsitektur Offline-First
- ✅ Penyimpanan lokal menggunakan IndexedDB
- ✅ Sinkronisasi otomatis ke MySQL
- ✅ Tombol Force Sync
- ✅ REST API berbasis PHP (PDO)
- ✅ Indikator status sinkronisasi
- ✅ Backup database (JSON)
- ✅ Restore database
- ✅ Export / Import data
- ✅ Ambil data langsung dari MySQL
- ✅ Retry jika gagal sync

---

## 🏗 Arsitektur Sistem


Aksi User
↓
IndexedDB (Database Lokal)
↓
Pending Changes Queue
↓
Sync Manager
↓
MySQL (Server)


### Cara Kerja:

1. Data selalu disimpan ke IndexedDB terlebih dahulu.
2. Perubahan dimasukkan ke tabel `pending_changes`.
3. Jika online, sistem otomatis melakukan sinkronisasi.
4. Jika offline, perubahan akan disimpan dan dikirim saat online kembali.

---

## 🛠 Teknologi yang Digunakan

- HTML5
- CSS3
- JavaScript (Vanilla)
- IndexedDB API
- PHP (PDO)
- MySQL

---

## 📂 Struktur Project


/project-folder
│
├── index.html # Aplikasi utama
├── api.php # REST API untuk sinkronisasi MySQL
└── README.md


---

## ⚙️ Cara Instalasi

### 1️⃣ Clone Repository

```bash
git clone https://github.com/epsantoso/indexeddb-mysql-sync.git
2️⃣ Buat Database MySQL
CREATE DATABASE hybrid_db;
Tabel Users
CREATE TABLE users (
    id VARCHAR(50) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);
Tabel Posts
CREATE TABLE posts (
    id VARCHAR(50) PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);
3️⃣ Konfigurasi API

Edit file api.php:

$host = 'localhost';
$user = 'root';
$pass = 'password_kamu';
$db   = 'hybrid_db';
4️⃣ Jalankan Menggunakan XAMPP

Simpan project di folder:

xampp/htdocs/nama-folder-project

Lalu buka di browser:

http://localhost/nama-folder-project/index.html
🔄 Mekanisme Sinkronisasi

Setiap tambah / edit / hapus data:

Data disimpan ke IndexedDB

Masuk ke antrian pending_changes

Sync Manager mengirim data ke MySQL

Jika sukses:

Status berubah menjadi Tersimpan

Data pending dihapus

📊 Status Sinkronisasi
Status	Arti
Tersimpan	Data sudah berhasil dikirim ke MySQL
Menunggu Sync	Data masih antri untuk dikirim
Gagal Sync	Sinkronisasi gagal setelah beberapa percobaan
💾 Backup & Restore

Backup menghasilkan file JSON

Restore bisa mengganti atau menggabungkan data

Export & Import tersedia

🌐 Mode Offline

Aplikasi tetap bisa digunakan tanpa internet

Semua perubahan akan otomatis dikirim saat koneksi kembali

📌 Pengembangan Selanjutnya

Sistem autentikasi login

Conflict resolution lebih kompleks

WebSocket real-time sync

Docker deployment

Paginasi data

👨‍💻 Pengembang

Project ini dibuat sebagai eksperimen arsitektur database hybrid (local-first + server sync).

📄 Lisensi

MIT License
