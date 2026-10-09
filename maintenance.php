<?php
/**
 * Halaman Pemeliharaan Sistem & Peningkatan Keamanan BAZNAS Kabupaten Sumbawa
 * Mengirimkan status HTTP 503 Service Unavailable untuk menjaga reputasi SEO
 * Menampilkan data dinamis yang diambil langsung dari database sistem:
 * - Tabel `identitas` (nama_website, no_telp, email, rekening)
 * - Tabel `logo` (gambar logo resmi)
 * - Tabel `mod_alamat` (alamat kantor resmi)
 * - Tabel `rekening_zakat` (daftar rekening transfer)
 * Dilengkapi dengan safe-fallback jika database sedang offline/maintenance.
 */

http_response_code(503);
header('HTTP/1.1 503 Service Unavailable');
header('Status: 503 Service Unavailable');
header('Retry-After: 3600');

// Nilai bawaan (safe fallback)
$nama_website = "BAZNAS Kabupaten Sumbawa";
$logo = "asset/logo/logo_baznas6.png";
$no_telp = "081936955747";
$email = "baznaskab.sumbawa@baznas.go.id";
$alamat = "Jl. Hasanuddin No. 01 Kelurahan Bugis Kecamatan Sumbawa (Eks kantor DPRD Lama)";
$rekening = "0042100208006";
$rekening_list = array();

// Ambil konfigurasi database secara aman
$db_file = __DIR__ . '/application/config/database.php';
if (file_exists($db_file)) {
    if (!defined('BASEPATH')) define('BASEPATH', '1');
    if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'production');
    @include $db_file;
    if (isset($db['default'])) {
        $db_conf = $db['default'];
        try {
            $mysqli = @new mysqli($db_conf['hostname'], $db_conf['username'], $db_conf['password'], $db_conf['database']);
            if (!$mysqli->connect_error) {
                // 1. Data Identitas
                $res_id = $mysqli->query("SELECT * FROM identitas WHERE id_identitas = 1 LIMIT 1");
                if ($res_id && $row_id = $res_id->fetch_assoc()) {
                    if (!empty($row_id['nama_website'])) $nama_website = htmlspecialchars($row_id['nama_website']);
                    if (!empty($row_id['email'])) $email = htmlspecialchars($row_id['email']);
                    if (!empty($row_id['no_telp'])) $no_telp = htmlspecialchars($row_id['no_telp']);
                    if (!empty($row_id['rekening'])) $rekening = htmlspecialchars($row_id['rekening']);
                }

                // 2. Data Logo Resmi
                $res_logo = $mysqli->query("SELECT gambar FROM logo ORDER BY id_logo DESC LIMIT 1");
                if ($res_logo && $row_logo = $res_logo->fetch_assoc()) {
                    if (!empty($row_logo['gambar'])) $logo = "asset/logo/" . htmlspecialchars($row_logo['gambar']);
                }

                // 3. Data Alamat Resmi
                $res_alamat = $mysqli->query("SELECT alamat FROM mod_alamat LIMIT 1");
                if ($res_alamat && $row_alamat = $res_alamat->fetch_assoc()) {
                    $raw_alamat = $row_alamat['alamat'];
                    if (preg_match('/(Jl\.[^<&]+(?:\([^)]+\))?)/i', $raw_alamat, $m_addr)) {
                        $alamat = htmlspecialchars(rtrim(trim($m_addr[1]), '. '));
                    } else {
                        $cleaned = strip_tags($raw_alamat);
                        if (!empty($cleaned)) {
                            $alamat = htmlspecialchars(trim(preg_replace('/\s+/', ' ', $cleaned)));
                        }
                    }
                }

                // 4. Data Rekening Zakat
                $res_rek = $mysqli->query("SELECT * FROM rekening_zakat LIMIT 5");
                if ($res_rek && $res_rek->num_rows > 0) {
                    while ($r_rek = $res_rek->fetch_assoc()) {
                        $rekening_list[] = $r_rek;
                    }
                }
                $mysqli->close();
            }
        } catch (Exception $e) {
            // Gunakan safe fallback
        }
    }
}

// Format nomor WhatsApp untuk tautan
$clean_wa = preg_replace('/[^0-9]/', '', $no_telp);
if (substr($clean_wa, 0, 1) === '0') {
    $clean_wa = '62' . substr($clean_wa, 1);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemeliharaan Sistem &amp; Peningkatan Keamanan | <?php echo $nama_website; ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#006937">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="asset/images/logobaznas.png" type="image/x-icon">
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --baznas-green: #006937;
            --baznas-green-dark: #004d25;
            --baznas-green-light: #0b7c44;
            --baznas-yellow: #F4C10F;
            --baznas-dark: #222222;
            --baznas-gray: #666666;
            --border-color: #b8b8b8;
            --bg-body: #f8faf9;
            --bg-card: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            border-radius: 0px !important; /* Standar Desain BAZNAS Sumbawa: Sudut Kotak Biasa */
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--baznas-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-image: radial-gradient(rgba(0, 105, 55, 0.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .top-accent-bar {
            height: 6px;
            background: linear-gradient(90deg, var(--baznas-green) 0%, var(--baznas-green-light) 65%, var(--baznas-yellow) 100%);
            width: 100%;
        }

        .container {
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 30px;
        }

        .header-logo {
            max-height: 75px;
            width: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));
        }

        .brand-subtitle {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--baznas-green);
        }

        .maintenance-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 45px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .status-badge-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eef2ef;
            margin-bottom: 30px;
        }

        .badge-live-pulse {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #f0f7f3;
            border: 1px solid var(--baznas-green);
            color: var(--baznas-green-dark);
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            background: #e74c3c;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7);
            animation: pulse-ring 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(231, 76, 60, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(231, 76, 60, 0);
            }
        }

        .time-badge-wita {
            font-size: 12px;
            font-weight: 600;
            color: var(--baznas-gray);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f9f9f9;
            padding: 6px 12px;
            border: 1px solid #e5e5e5;
        }

        .hero-notice {
            text-align: center;
            max-width: 780px;
            margin: 0 auto 35px auto;
        }

        .hero-icon-wrap {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px auto;
            background: #f0f7f3;
            border: 2px solid var(--baznas-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--baznas-green);
            font-size: 36px;
            position: relative;
        }

        .hero-icon-wrap .badge-security {
            position: absolute;
            bottom: -5px;
            right: -5px;
            background: var(--baznas-yellow);
            color: var(--baznas-dark);
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            border: 2px solid #fff;
        }

        .hero-title {
            font-size: 28px;
            font-weight: 900;
            color: var(--baznas-green);
            line-height: 1.3;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .hero-desc {
            font-size: 15px;
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .points-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .point-item {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-left: 4px solid var(--baznas-green);
            padding: 20px;
            transition: all 0.2s ease;
        }

        .point-item:hover {
            border-left-color: var(--baznas-yellow);
            background: #fcfdfc;
        }

        .point-icon {
            font-size: 20px;
            color: var(--baznas-green);
            margin-bottom: 10px;
            display: inline-block;
        }

        .point-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--baznas-dark);
            margin-bottom: 6px;
        }

        .point-text {
            font-size: 12.5px;
            color: #6b7280;
            line-height: 1.5;
        }

        .emergency-contact-box {
            background: #fdfdfd;
            border: 1px solid #e2e8f0;
            border-top: 3px solid var(--baznas-yellow);
            padding: 25px;
            margin-bottom: 30px;
        }

        .emergency-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .emergency-header i {
            font-size: 22px;
            color: var(--baznas-green);
        }

        .emergency-header h3 {
            font-size: 16px;
            font-weight: 800;
            color: var(--baznas-green-dark);
        }

        .emergency-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
        }

        .contact-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .contact-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 800;
            color: #9ca3af;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .contact-val {
            font-size: 13.5px;
            font-weight: 700;
            color: #1f2937;
            text-decoration: none;
            word-break: break-word;
        }

        .contact-val:hover {
            color: var(--baznas-green);
        }

        .action-row {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }

        .btn-official {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 28px;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-wa {
            background: #25D366;
            color: #ffffff;
            border-color: #1ebe5b;
        }

        .btn-wa:hover {
            background: #1eb355;
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        }

        .btn-refresh {
            background: #ffffff;
            color: var(--baznas-green);
            border: 1px solid var(--baznas-green);
        }

        .btn-refresh:hover {
            background: var(--baznas-green);
            color: #ffffff;
        }

        footer {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        footer p {
            margin: 4px 0;
        }

        @media (max-width: 768px) {
            .maintenance-card {
                padding: 25px 20px;
            }
            .hero-title {
                font-size: 22px;
            }
            .hero-desc {
                font-size: 13.5px;
            }
            .action-row {
                flex-direction: column;
                width: 100%;
            }
            .btn-official {
                width: 100%;
            }
            .status-badge-container {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Aksen Hijau & Emas BAZNAS -->
    <div class="top-accent-bar"></div>

    <div class="container">
        
        <!-- Header Logo BAZNAS Dinamis -->
        <header class="header-brand">
            <img src="<?php echo $logo; ?>" onerror="this.onerror=null; this.src='asset/images/logobaznas.png';" alt="Logo <?php echo $nama_website; ?>" class="header-logo">
            <div class="brand-subtitle"><?php echo $nama_website; ?></div>
        </header>

        <!-- Main Card Putih di Atas Kertas -->
        <main class="maintenance-card">
            
            <!-- Status Badge & WITA Clock -->
            <div class="status-badge-container">
                <div class="badge-live-pulse">
                    <span class="pulse-dot"></span>
                    <span>Proses Pemeliharaan &amp; Peningkatan Keamanan Sedang Berlangsung</span>
                </div>
                <div class="time-badge-wita">
                    <i class="fa-regular fa-clock" style="color: var(--baznas-green);"></i>
                    <span id="wita-clock">Zona Waktu WITA (Sumbawa Besar): Memuat...</span>
                </div>
            </div>

            <!-- Hero Notice -->
            <div class="hero-notice">
                <div class="hero-icon-wrap">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div class="badge-security"><i class="fa-solid fa-lock"></i></div>
                </div>

                <h1 class="hero-title">Situs Web Sedang Dalam Pemeliharaan Sistem &amp; Peningkatan Protokol Keamanan</h1>
                
                <p class="hero-desc">
                    <em>Assalamu’alaikum Warahmatullahi Wabarakatuh.</em><br>
                    Dalam rangka meningkatkan keandalan sistem, perlindungan data muzaki dan mustahik, serta penguatan infrastruktur keamanan digital dari potensi akses tidak sah, portal resmi <strong>baznassumbawa.id</strong> saat ini sedang menjalani proses <strong>Pemeliharaan Berkala &amp; Peningkatan Protokol Keamanan Sistem</strong>.
                </p>
            </div>

            <!-- 3 Poin Pembaruan Keamanan -->
            <div class="points-grid">
                <div class="point-item">
                    <div class="point-icon"><i class="fa-solid fa-user-shield"></i></div>
                    <div class="point-title">Penguatan Akses &amp; Otentikasi</div>
                    <div class="point-text">Pembaruan gerbang verifikasi multi-faktor, proteksi anti brute-force, dan penutupan seluruh celah akses tidak resmi.</div>
                </div>

                <div class="point-item">
                    <div class="point-icon"><i class="fa-solid fa-database"></i></div>
                    <div class="point-title">Optimalisasi &amp; Audit Data</div>
                    <div class="point-text">Pembersihan basis data dari anomali, penataan struktur transaksi, dan peningkatan kecepatan akses server.</div>
                </div>

                <div class="point-item">
                    <div class="point-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                    <div class="point-title">Integritas Layanan ZIS Umat</div>
                    <div class="point-text">Memastikan transparansi penuh dan keamanan pelaporan dana Zakat, Infak, dan Sedekah amanah umat.</div>
                </div>
            </div>

            <!-- Seksi Layanan Darurat ZIS (Dinamis dari Tabel Database) -->
            <section class="emergency-contact-box">
                <div class="emergency-header">
                    <i class="fa-solid fa-headset"></i>
                    <h3>Layanan Konsultasi &amp; Konfirmasi ZIS Tetap Beroperasi</h3>
                </div>
                <p style="font-size: 13px; color: #555; margin-bottom: 15px;">
                    Masyarakat dan para Muzaki tetap dapat menunaikan Zakat, Infak, Sedekah, serta melakukan konfirmasi dan konsultasi secara langsung melalui kanal resmi <?php echo $nama_website; ?>:
                </p>

                <div class="emergency-grid">
                    <div class="contact-card">
                        <div class="contact-label"><i class="fa-brands fa-whatsapp text-success"></i> WhatsApp Layanan ZIS</div>
                        <a href="https://wa.me/<?php echo $clean_wa; ?>?text=Assalamu%27alaikum%20BAZNAS%20Sumbawa%2C%20saya%20ingin%20konsultasi%2Fkonfirmasi%20ZIS" target="_blank" rel="noopener" class="contact-val"><?php echo $no_telp; ?></a>
                    </div>

                    <div class="contact-card">
                        <div class="contact-label"><i class="fa-solid fa-envelope" style="color: var(--baznas-green);"></i> Email Resmi</div>
                        <a href="mailto:<?php echo $email; ?>" class="contact-val"><?php echo $email; ?></a>
                    </div>

                    <div class="contact-card">
                        <div class="contact-label"><i class="fa-solid fa-building-columns" style="color: var(--baznas-yellow);"></i> Rekening ZIS Resmi</div>
                        <div class="contact-val" style="font-size: 12.5px;">
                            <?php if (!empty($rekening_list)): ?>
                                <?php foreach($rekening_list as $rk): 
                                    $b_name = isset($rk['nama_bank']) ? htmlspecialchars($rk['nama_bank']) : 'Bank';
                                    $rek_z  = isset($rk['rek_zakat']) && !empty($rk['rek_zakat']) ? htmlspecialchars($rk['rek_zakat']) : '';
                                    $rek_i  = isset($rk['rek_infaq']) && !empty($rk['rek_infaq']) ? htmlspecialchars($rk['rek_infaq']) : '';
                                    $rek_n  = isset($rk['no_rekening']) && !empty($rk['no_rekening']) ? htmlspecialchars($rk['no_rekening']) : '';
                                ?>
                                    <div style="margin-bottom: 5px; padding-bottom: 4px; border-bottom: 1px dashed #eee;">
                                        <strong><?php echo $b_name; ?></strong>
                                        <?php if (!empty($rek_z) && !empty($rek_i) && $rek_z === $rek_i): ?>
                                            <div><?php echo $rek_z; ?></div>
                                        <?php elseif (!empty($rek_z) && !empty($rek_i)): ?>
                                            <div style="font-size: 11.5px; color: #444;">Zakat: <strong><?php echo $rek_z; ?></strong></div>
                                            <div style="font-size: 11.5px; color: #444;">Infaq: <strong><?php echo $rek_i; ?></strong></div>
                                        <?php elseif (!empty($rek_z)): ?>
                                            <div><?php echo $rek_z; ?></div>
                                        <?php elseif (!empty($rek_i)): ?>
                                            <div><?php echo $rek_i; ?> (Infaq)</div>
                                        <?php elseif (!empty($rek_n)): ?>
                                            <div><?php echo $rek_n; ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div>No. Rek: <strong><?php echo $rekening; ?></strong></div>
                                <div style="font-size: 11px; color: #666;">a.n <?php echo $nama_website; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-label"><i class="fa-solid fa-map-location-dot" style="color: #dc2626;"></i> Kantor BAZNAS</div>
                        <div class="contact-val" style="font-size: 12px; color: #444;"><?php echo $alamat; ?></div>
                    </div>
                </div>
            </section>

            <!-- Action Buttons -->
            <div class="action-row">
                <a href="https://wa.me/<?php echo $clean_wa; ?>?text=Assalamu%27alaikum%20BAZNAS%20Sumbawa%2C%20saya%20ingin%20konsultasi%2Fkonfirmasi%20ZIS" target="_blank" rel="noopener" class="btn-official btn-wa">
                    <i class="fa-brands fa-whatsapp" style="font-size: 18px;"></i>
                    <span>Hubungi Layanan WhatsApp Resmi</span>
                </a>

                <button onclick="checkSystemStatus()" class="btn-official btn-refresh">
                    <i class="fa-solid fa-rotate-right" id="refresh-icon"></i>
                    <span>Periksa Kembali Status Website</span>
                </button>
            </div>

        </main>
    </div>

    <!-- Footer Resmi -->
    <footer>
        <p><strong><?php echo $nama_website; ?></strong></p>
        <p>Lembaga Pemerintah Nonstruktural Pengelola Zakat Resmi Kabupaten Sumbawa, Nusa Tenggara Barat</p>
        <p style="margin-top: 6px; font-size: 11px; color: #9ca3af;">&copy; <?php echo date('Y'); ?> <?php echo $nama_website; ?>. Hak Cipta Dilindungi Undang-Undang.</p>
    </footer>

    <!-- Script Realtime Clock WITA & Auto-Status -->
    <script>
        function updateWitaClock() {
            // Zona Waktu WITA (UTC+8)
            const now = new Date();
            const utcTime = now.getTime() + (now.getTimezoneOffset() * 60000);
            const witaTime = new Date(utcTime + (3600000 * 8));

            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            const dayName = days[witaTime.getDay()];
            const date = String(witaTime.getDate()).padStart(2, '0');
            const monthName = months[witaTime.getMonth()];
            const year = witaTime.getFullYear();

            const hours = String(witaTime.getHours()).padStart(2, '0');
            const minutes = String(witaTime.getMinutes()).padStart(2, '0');
            const seconds = String(witaTime.getSeconds()).padStart(2, '0');

            const clockElem = document.getElementById('wita-clock');
            if (clockElem) {
                clockElem.innerHTML = `WITA: <strong>${dayName}, ${date} ${monthName} ${year} — ${hours}:${minutes}:${seconds}</strong>`;
            }
        }

        setInterval(updateWitaClock, 1000);
        updateWitaClock();

        function checkSystemStatus() {
            const icon = document.getElementById('refresh-icon');
            if (icon) icon.classList.add('fa-spin');
            
            setTimeout(() => {
                window.location.reload();
            }, 600);
        }
    </script>
</body>
</html>
