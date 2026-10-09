<?php
$iden = $this->model_utama->view_where('identitas', array('id_identitas' => 1))->row_array();
$logo = $this->model_utama->view_ordering_limit('logo', 'id_logo', 'DESC', 0, 1)->row_array();
?>

<div class="footer-modern-wrap">
    <div class="footer-info-content">
        <!-- 1. ABOUT -->
        <div class="footer-col-item">
            <img src="<?php echo base_url(); ?>asset/logo/<?php echo $logo['gambar']; ?>" alt="Logo" class="f-logo">
            <p class="f-text">BAZNAS Kabupaten Sumbawa mengelola Zakat, Infak, dan Sedekah secara profesional dan amanah.</p>
            <div class="f-social">
                <?php
                $sosmed_f = explode(",", $iden['facebook']);
                $fb = isset($sosmed_f[0]) ? trim(preg_replace('/\s+/', '', $sosmed_f[0])) : '#';
                $tw = isset($sosmed_f[1]) ? trim(preg_replace('/\s+/', '', $sosmed_f[1])) : '#';
                $ig = isset($sosmed_f[2]) ? trim(preg_replace('/\s+/', '', $sosmed_f[2])) : '#';
                $yt = isset($sosmed_f[3]) ? trim(preg_replace('/\s+/', '', $sosmed_f[3])) : '#';
                
                // Final sanitize to remove potential hidden characters/newlines
                $fb = str_replace(array("\r", "\n"), '', $fb);
                $tw = str_replace(array("\r", "\n"), '', $tw);
                $ig = str_replace(array("\r", "\n"), '', $ig);
                $yt = str_replace(array("\r", "\n"), '', $yt);
                ?>
                <a href="<?php echo $fb; ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                <a href="<?php echo $ig; ?>" target="_blank"><i class="fa fa-instagram"></i></a>
                <a href="<?php echo $yt; ?>" target="_blank"><i class="fa fa-youtube-play"></i></a>
                <a href="<?php echo $tw; ?>" target="_blank"><i class="fa fa-twitter"></i></a>
            </div>
        </div>

        <!-- 2. TAUTAN PENTING -->
        <div class="footer-col-item" style="display: block !important; visibility: visible !important;">
            <h4 class="f-title">Tautan Penting</h4>
            <ul class="f-links" style="display: block !important; visibility: visible !important;">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-angle-right"></i> Beranda</a></li>
                <li><a href="<?php echo base_url(); ?>halaman/detail/profil-baznas-kabupaten-sumbawa"><i class="fa fa-angle-right"></i> Profil</a></li>
                <li><a href="<?php echo base_url(); ?>kalkulator-zakat"><i class="fa fa-angle-right"></i> Kalkulator Zakat</a></li>
                <li><a href="<?php echo base_url(); ?>berita"><i class="fa fa-angle-right"></i> Berita</a></li>
                <li><a href="<?php echo base_url(); ?>halaman/detail/kebijakan-privasi"><i class="fa fa-angle-right"></i> Kebijakan Privasi</a></li>
                <li><a href="<?php echo base_url(); ?>halaman/detail/syarat-dan-ketentuan"><i class="fa fa-angle-right"></i> Syarat & Ketentuan</a></li>
                <li><a href="<?php echo base_url(); ?>halaman/detail/disclaimer"><i class="fa fa-angle-right"></i> Disclaimer</a></li>
                <li><a href="<?php echo base_url(); ?>hubungi"><i class="fa fa-angle-right"></i> Hubungi Kami</a></li>
            </ul>
        </div>

        <!-- 3. KONTAK -->
        <div class="footer-col-item">
            <h4 class="f-title">Kontak Kami</h4>
            <div class="f-contact-item">
                <i class="fa fa-map-marker"></i>
                <p><?php echo $iden['alamat']; ?></p>
            </div>
            <div class="f-contact-item">
                <i class="fa fa-phone"></i>
                <p><?php echo $iden['no_telp']; ?></p>
            </div>
            <div class="f-contact-item">
                <i class="fa fa-envelope"></i>
                <p><?php echo $iden['email']; ?></p>
            </div>
        </div>
    </div>
    
    <div class="footer-copy-bar">
        <p>&copy; <?php echo date('Y'); ?> <?php echo $iden['nama_website']; ?> &bull; <a href="<?php echo base_url(); ?>halaman/detail/kebijakan-privasi" style="color: #fff; text-decoration: underline;">Privacy Policy</a> &bull; <a href="<?php echo base_url(); ?>halaman/detail/syarat-dan-ketentuan" style="color: #fff; text-decoration: underline;">Terms of Service</a> &bull; <a href="<?php echo base_url(); ?>halaman/detail/disclaimer" style="color: #fff; text-decoration: underline;">Disclaimer</a></p>
    </div>
</div>

<style>
    .footer-modern-wrap { background: var(--baznas-green); color: #fff; width: 100%; }
    .footer-info-content { padding: 40px 20px; display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 30px; max-width: 1200px; margin: 0 auto; }
    .f-logo { background: #fff; padding: 10px; border-radius: 8px; max-height: 60px; margin-bottom: 20px; }
    .f-text { font-size: 14px; line-height: 1.6; color: rgba(255,255,255,0.9) !important; margin-bottom: 20px; }
    .f-title { color: var(--baznas-yellow) !important; font-size: 17px; font-weight: 700; margin-bottom: 20px; padding-bottom: 8px; border-bottom: 2px solid var(--baznas-yellow); display: inline-block; border-top:none !important; border-left:none !important; border-right:none !important; }
    .f-contact-item { display: flex; gap: 12px; margin-bottom: 12px; }
    .f-contact-item i { color: var(--baznas-yellow); font-size: 16px; margin-top: 2px; }
    .f-contact-item p { font-size: 13px; color: #fff !important; margin: 0; }
    .f-links { list-style: none; padding: 0; margin: 0; }
    .f-links li { margin-bottom: 8px; }
    .f-links li a { color: #fff !important; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 8px; }
    .f-social { display: flex; gap: 10px; }
    .f-social a { width: 35px; height: 35px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff !important; }
    .footer-copy-bar { background: #004d28; padding: 15px; text-align: center; font-size: 11px; color: rgba(255,255,255,0.5); }

    @media (max-width: 991px) {
        .footer-info-content { grid-template-columns: 1fr !important; text-align: center; padding: 30px 20px 10px 20px !important; gap: 15px !important; }
        .footer-col-item { margin-bottom: 15px !important; display: flex !important; flex-direction: column; align-items: center; }
        .f-title { margin-bottom: 12px !important; font-size: 16px !important; }
        .f-contact-item { flex-direction: column; align-items: center; text-align: center; margin-bottom: 8px !important; }
        .f-links li a { justify-content: center; }
        .footer-modern-wrap { padding-bottom: 70px !important; margin-bottom: 0 !important; }
    }
</style>
<style>
    /* FINAL OVERRIDE - PLACED IN FOOTER FOR MAXIMUM PRIORITY */
    @media (max-width: 991px) {
        .modern-article-item img, 
        .ma-thumb, 
        .ma-thumb-lg,
        .modern-article-item > a img,
        .ma-img-wrapper img,
        .archive-thumb img,
        .rec-thumb-modern,
        .vid-thumb-wrapper img,
        .modern-article-item img.ma-thumb {
            width: 100% !important;
            height: 140px !important;
            min-height: 140px !important;
            max-height: 140px !important;
            object-fit: cover !important;
            display: block !important;
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }
        
        .modern-article-item > a,
        .ma-img-wrapper,
        .archive-thumb,
        .vid-thumb-wrapper {
            width: 100% !important;
            height: 140px !important;
            display: block !important;
            float: none !important;
            background: #eee !important;
            border-radius: 0 !important;
            overflow: hidden !important;
        }

        .modern-article-item {
            flex-direction: column !important;
            gap: 0 !important;
        }

        .ma-content {
            width: 100% !important;
            padding: 12px !important;
            box-sizing: border-box !important;
        }
        }
    }
</style>

<!-- GOOGLE ADSENSE & GDPR COOKIE CONSENT BANNER -->
<style>
    /* DESKTOP MODE (>= 768px): Full width bottom bar */
    #baznasCookieConsent {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #004d28;
        color: #fff;
        padding: 15px 20px;
        z-index: 9999999;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
        font-size: 13px;
        line-height: 1.5;
        border-top: 3px solid #ffc107;
    }

    .cookie-flex-wrap {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .cookie-btn {
        background: #ffc107;
        color: #004d28;
        border: none;
        padding: 10px 24px;
        font-weight: 700;
        cursor: pointer;
        border-radius: 0px !important;
        font-size: 13px;
        text-transform: uppercase;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .cookie-btn:hover {
        background: #e0a800;
        color: #000;
    }

    /* KHUSUS MODE MOBILE (<= 767px): Floating Card di atas Sidebar Bawah */
    @media (max-width: 767px) {
        #baznasCookieConsent {
            bottom: 85px !important; /* Diangkat 85px agar bebas dari sidebar bawah / floating bar mobile */
            left: 15px !important;
            right: 15px !important;
            border: 2px solid #ffc107 !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6) !important;
            padding: 15px !important;
            border-radius: 0px !important;
        }
        .cookie-flex-wrap {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 12px !important;
        }
        .cookie-btn {
            width: 100% !important;
            padding: 12px !important;
            text-align: center !important;
            font-size: 14px !important;
            display: block !important;
        }
    }
</style>

<div id="baznasCookieConsent">
    <div class="cookie-flex-wrap">
        <div style="flex: 1;">
            <strong><i class="fa fa-info-circle" style="color: #ffc107;"></i> Pemberitahuan Privasi &amp; Cookie:</strong><br>
            Situs web ini menggunakan cookie (termasuk cookie dari Google AdSense dan mitra analitik) untuk meningkatkan pengalaman navigasi, menganalisis lalu lintas web, serta menayangkan iklan yang relevan. Dengan melanjutkan navigasi di situs ini, Anda menyetujui penggunaan cookie sesuai dengan <a href="<?php echo base_url(); ?>halaman/detail/kebijakan-privasi" style="color: #ffc107; text-decoration: underline; font-weight: bold;">Kebijakan Privasi</a> kami.
        </div>
        <div>
            <button onclick="acceptBaznasCookie()" class="cookie-btn">Saya Setuju &amp; Paham</button>
        </div>
    </div>
</div>
<script>
    function acceptBaznasCookie() {
        localStorage.setItem('baznas_cookie_consent', 'accepted');
        document.getElementById('baznasCookieConsent').style.display = 'none';
    }
    document.addEventListener("DOMContentLoaded", function() {
        if (!localStorage.getItem('baznas_cookie_consent')) {
            setTimeout(function() {
                var el = document.getElementById('baznasCookieConsent');
                if (el) el.style.display = 'block';
            }, 1000);
        }
    });
</script>
