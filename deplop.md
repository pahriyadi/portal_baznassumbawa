# 🚀 PANDUAN DEPLOYMENT AMIL AI VIRTUAL — BAZNAS KABUPATEN SUMBAWA

Dokumen ini berisi daftar lengkap file baru yang harus di-upload dan file lama yang perlu dimodifikasi ke cPanel Hosting / Server Production.

---

## 📦 1. FILE BARU (Wajib Di-upload ke Hosting)

Cukup salin / upload file-file berikut langsung ke foldernya masing-masing di hosting:

| No | Nama File | Path Tujuan di Hosting | Fungsi Utama |
|---|---|---|---|
| **1** | `Ai_asisten.php` | `application/controllers/Ai_asisten.php` | **Backend Controller:** Integrasi Google Gemini API, live database query (RAG) untuk berita & pimpinan realtime, rate limiter, dan fallback engine. |
| **2** | `widget_ai_chat.php` | `application/views/phpmu-magazine/widget_ai_chat.php` | **Frontend Widget:** Floating launcher button SVG anti-rusak, modal chat, efek typewriter streaming, chip interaktif, dan responsive mobile anti-tumpang tindih. |
| **3** | `asisten ai rancangan.md` | `asisten ai rancangan.md` *(Root Folder)* | **Dokumentasi Lengkap:** Panduan teknis & arsitektur perancangan AI untuk diterapkan pada sistem lain di masa depan. *(Opsional)* |

---

## ✏️ 2. FILE LAMA YANG DIMODIFIKASI

Jika Anda ingin mengedit langsung file di cPanel (File Manager / Edit Code) tanpa menimpa seluruh file, cukup tambahkan baris kode berikut pada masing-masing file:

---

### A. File: `application/config/config.php`
* **Posisi:** Tambahkan di baris paling bawah file (sekitar baris 517–525).
* **Kode yang Ditambahkan:**
```php
/*
|--------------------------------------------------------------------------
| Konfigurasi Agen AI Asisten BAZNAS Sumbawa (Google Gemini API)
|--------------------------------------------------------------------------
| Dapatkan API Key gratis (Free Tier) di https://aistudio.google.com/
| Jika dikosongkan, sistem otomatis menjalankan Intelligent Local Engine (Fallback Cerdas)
*/
$config['gemini_api_key'] = 'AQ.Ab8RN6KnYLlzz3oGxYuK6BlBcch_-4nrps_E82yHUO9W7-GB6w';
$config['ai_assistant_name'] = 'Amil Virtual BAZNAS Sumbawa';
```

---

### B. File: `application/config/routes.php`
* **Posisi:** Tambahkan sebelum baris `$route['(:any)'] = 'news/$1/$2';` (sekitar baris 92–96).
* **Kode yang Ditambahkan:**
```php
// AI Asisten Virtual BAZNAS Sumbawa
$route['ai-asisten'] = 'ai_asisten/chat';
$route['ai-asisten/chat'] = 'ai_asisten/chat';
$route['ai_asisten/chat'] = 'ai_asisten/chat';
```

---

### C. File: `application/views/phpmu-magazine/template.php`
* **Posisi:** Tambahkan tepat sebelum tag penutup `</body>` (sekitar baris 3140–3142).
* **Kode yang Ditambahkan:**
```php
    <!-- Amil Virtual AI BAZNAS Sumbawa -->
    <?php include "widget_ai_chat.php"; ?>
</body>
```

---

## 📋 3. LANGKAH-LANGKAH DEPLOYMENT DI CPANEL

1. **Login ke cPanel Hosting** Anda.
2. Masuk ke menu **File Manager** lalu buka folder direktori website Anda (biasanya `public_html` atau subfolder domain).
3. **Upload File Baru:**
   - Masuk ke folder `application/controllers/` ➔ Klik tombol **Upload** ➔ Pilih file `Ai_asisten.php`.
   - Masuk ke folder `application/views/phpmu-magazine/` ➔ Klik tombol **Upload** ➔ Pilih file `widget_ai_chat.php`.
4. **Edit / Update File Konfigurasi:**
   - Buka `application/config/config.php` ➔ Klik **Edit** ➔ Tempelkan kode konfigurasi API Key di bagian bawah ➔ Klik **Save Changes**.
   - Buka `application/config/routes.php` ➔ Klik **Edit** ➔ Tempelkan rute `ai-asisten` ➔ Klik **Save Changes**.
   - Buka `application/views/phpmu-magazine/template.php` ➔ Klik **Edit** ➔ Tempelkan include `widget_ai_chat.php` sebelum `</body>` ➔ Klik **Save Changes**.
5. **Testing & Verifikasi:**
   - Buka browser dan akses alamat website Anda: `https://baznassumbawa.id/`.
   - Lakukan **Hard Refresh** (`Ctrl + F5` di PC / bersihkan cache di browser HP).
   - Pastikan ikon launcher hijau-emas BAZNAS muncul di pojok kanan bawah.
   - Uji coba percakapan di Desktop dan Mobile untuk memastikan bebas tumpang tindih.

---

## 🛡️ 4. CATATAN KEAMANAN & TEKNIS

* **Zona Waktu:** Sistem telah dikonfigurasi sinkron dengan waktu **WITA (`Asia/Makassar`)** sesuai standar Kabupaten Sumbawa.
* **Database Realtime (RAG):** Kapan pun Anda mengupdate berita atau susunan pimpinan di database hosting, asisten AI langsung mengetahui dan menjawab menggunakan data terkini secara otomatis.
* **Fallback Otomatis:** Jika koneksi API Gemini terganggu atau kuota harian habis, sistem otomatis beralih ke *Intelligent Local Engine* sehingga pengguna tetap terlayani 24 jam.
