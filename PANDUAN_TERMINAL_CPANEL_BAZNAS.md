# 📘 PANDUAN PRAKTIS TERMINAL CPANEL — BAZNAS KABUPATEN SUMBAWA
Domain: **https://baznassumbawa.id** | Repo: **https://github.com/pahriyadi/portal_baznassumbawa.git**

Dokumen ini adalah ringkasan perintah cepat (*cheatsheet*) Terminal cPanel untuk operasional sehari-hari, pengujian, pembaruan (*update*), dan penanganan kendala (*troubleshooting*).

---

## ⚡ 1. PERINTAH WAJIB SETIAP KALI MASUK TERMINAL

Setiap kali Anda baru membuka Terminal cPanel, Anda selalu berada di *home directory* (`~`). Jalankan perintah ini untuk masuk ke folder website:

```bash
cd public_html
```

Untuk memastikan posisi Anda sudah benar:
```bash
pwd
```
*Output yang benar:* `/home/n1579664/public_html`

---

## 🔄 2. CARA UJI COBA / PEMBUKTIAN UPDATE (SIKLUS KERJA)

### 💻 Tahap 1: Di Laptop / Komputer Lokal (VS Code / PowerShell)
Setelah selesai mengedit file (misal mengubah teks atau fitur):
```bash
git add .
git commit -m "test: uji coba update pertama"
git push origin main
```

### 🌐 Tahap 2: Di Terminal cPanel Hosting (Cukup 1 Baris)
Buka Terminal cPanel, lalu ketik:
```bash
cd public_html && git pull origin main
```
> **Hasil:** Seluruh perubahan yang Anda push dari laptop akan langsung masuk ke website `https://baznassumbawa.id` dalam hitungan detik!

---

## 🛠️ 3. DAFTAR PERINTAH HARIAN TERMINAL CPANEL

| Kebutuhan | Perintah Terminal | Penjelasan |
|---|---|---|
| **Cek Status Git** | `git status` | Melihat apakah ada file yang berubah atau belum ditarik. |
| **Tarik Update Terbaru** | `git pull origin main` | Mengambil commit terbaru dari GitHub ke server. |
| **Lihat Riwayat Update** | `git log -n 5 --oneline` | Melihat 5 riwayat commit/update terakhir. |
| **Lihat Daftar File** | `ls -la` | Menampilkan seluruh file & folder termasuk yang tersembunyi. |
| **Cek Izin Folder Cache** | `chmod -R 775 application/cache` | Memastikan CodeIgniter bisa menulis cache dan session. |

---

## 🚨 4. SOLUSI JIKA TERJADI KENDALA (TROUBLESHOOTING)

### Kasus A: Gagal `git pull` karena file di server pernah diedit manual
Jika muncul pesan *“Your local changes to the following files would be overwritten by merge”*:
```bash
git reset --hard origin/main
git pull origin main
```
*Perintah ini akan menyamakan seluruh file server 100% persis dengan GitHub tanpa error.*

### Kasus B: Ingin membatalkan perubahan sementara di server
```bash
git stash
git pull origin main
```

### Kasus C: Memastikan hak akses folder upload / cache aman
```bash
chmod -R 755 application/cache application/logs asset
```

---

## 🌐 5. DATABASE CONFIGURATION CHECK
Jika database belum terhubung di hosting, periksa file konfigurasi:
```bash
nano application/config/database.php
```
*(Tekan `Ctrl + X` lalu `Y` untuk simpan, atau edit via File Manager cPanel)*
Pastikan username, password, dan nama database sudah sesuai dengan yang dibuat di menu cPanel MySQL Database.
