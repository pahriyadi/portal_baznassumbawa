<style>
    /* Styles khusus Sidebar Kanan - tidak ada di template.php */
    .modern-social-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .modern-social-item {
        width: 38px;
        height: 38px;
        border-radius: 0px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 16px;
    }

    .modern-social-item:hover {
        transform: scale(1.1);
        filter: brightness(1.1);
    }

    .ms-fb {
        background: #1877F2;
    }

    .ms-tw {
        background: #000;
    }

    .ms-ig {
        background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    }

    .ms-yt {
        background: #FF0000;
    }

    .social-desc {
        font-size: 12px;
        color: #777;
        line-height: 1.5;
    }

    .modern-adv-box img {
        border-radius: 0px;
        width: 100%;
        height: auto;
        transition: opacity 0.3s;
    }

    .modern-adv-box img:hover {
        opacity: 0.95;
    }

    .cta-calc-box {
        background: linear-gradient(135deg, #006937 0%, #0b7c44 100%);
        padding: 20px;
        border-radius: 0px;
        color: white;
        text-align: center;
        margin-bottom: 25px;
        box-shadow: 0 10px 20px rgba(0, 105, 55, 0.2);
    }

    .cta-calc-box h4 {
        margin: 0 0 10px 0;
        font-size: 16px;
        font-weight: 800;
        color: #F4C10F;
    }

    .cta-calc-box p {
        font-size: 12px;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    .btn-calc-white {
        display: inline-block;
        background: white;
        color: #006937;
        padding: 8px 20px;
        border-radius: 0px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        transition: transform 0.2s;
    }

    .btn-calc-white:hover {
        transform: scale(1.05);
        color: #006937;
    }
</style>

<!-- Widget Kalkulator CTA -->
<div class="cta-calc-box">
    <h4>Sudahkah Anda Berzakat?</h4>
    <p>Hitung kewajiban Zakat Maal, Profesi, hingga Pertanian Anda di sini.</p>
    <a href="<?php echo base_url(); ?>kalkulator-zakat" class="btn-calc-white">Hitung Zakat Sekarang</a>
</div>

<!-- Widget Sosial -->
<div class="modern-widget">
    <h3>Media Sosial Kami</h3>
    <div class="modern-social-list">
        <?php
        $sosmed = $this->model_utama->view('identitas')->row_array();
        $pecahd = explode(",", $sosmed['facebook']);
        ?>
        <a title="Facebook" target="_BLANK" href="<?php echo trim($pecahd[0]); ?>" class="modern-social-item ms-fb"><i
                class="fa fa-facebook"></i> F</a>
        <a title="X / Twitter" target="_BLANK" href="<?php echo trim($pecahd[1]); ?>" class="modern-social-item ms-tw"><i
                class="fa fa-twitter"></i> X</a>
        <a title="Instagram" target="_BLANK" href="<?php echo trim($pecahd[2]); ?>" class="modern-social-item ms-ig"><i
                class="fa fa-instagram"></i> I</a>
        <a title="Youtube" target="_BLANK" href="<?php echo trim($pecahd[3]); ?>" class="modern-social-item ms-yt"><i
                class="fa fa-youtube"></i> Y</a>
    </div>
    <p class="social-desc">Ikuti media sosial kami untuk mendapatkan update berita dan informasi terbaru seputar BAZNAS.
    </p>
</div>

<!-- Widget Berita Terbaru (Informasi Utama) -->
<div class="modern-widget">
    <h3>Informasi Utama</h3>
    <div class="modern-article-list">
        <?php
        $terbaru = $this->model_utama->view_join_two('berita', 'users', 'kategori', 'username', 'id_kategori', array('berita.status' => 'Y', 'berita.utama' => 'Y', ), 'tanggal', 'DESC', 0, 5);
        foreach ($terbaru->result_array() as $r2x):
            $thumb = (!empty($r2x['gambar'])) ? base_url() . "asset/foto_berita/$r2x[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";

            // Logika Pembatasan 4 Kata
            $words = explode(" ", $r2x['judul']);
            $judul_desktop = count($words) > 4 ? implode(" ", array_slice($words, 0, 4)) . "...." : $r2x['judul'];
            ?>
            <div class="modern-article-item">
                <a href="<?php echo base_url() . $r2x['judul_seo']; ?>">
                    <img src="<?php echo $thumb; ?>" class="ma-thumb" alt="<?php echo $r2x['judul']; ?>" title="<?php echo $r2x['judul']; ?>">
                </a>
                <div class="ma-content">
                    <h4><a title="<?php echo $r2x['judul']; ?>" href="<?php echo base_url() . $r2x['judul_seo']; ?>">
                            <span class="judul-desktop"><?php echo $judul_desktop; ?></span>
                            <span class="judul-mobile"><?php echo $r2x['judul']; ?></span>
                        </a></h4>
                    <div class="ma-meta">
                        <span>&#128197; <?php echo tgl_indo($r2x['tanggal']); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Widget Iklan / Banner -->
<?php
$pasangiklan2 = $this->db->query("SELECT * FROM pasangiklan where judul LIKE '%kanan%'");
if ($pasangiklan2->num_rows() > 0):
    ?>
    <div class="modern-widget" style="padding: 10px;">
        <div class="modern-adv-box">
            <?php
            foreach ($pasangiklan2->result_array() as $b) {
                if ($b['gambar'] != '') {
                    echo "<a href='$b[url]' target='_blank'><img src='" . base_url() . "asset/foto_pasangiklan/$b[gambar]' alt='$b[judul]' title='$b[judul]' /></a>";
                }
                if (trim($b['source']) != '') {
                    echo "$b[source]";
                }
            }
            ?>
        </div>
    </div>
<?php endif; ?>

<!-- Widget Berita Populer -->
<div class="modern-widget">
    <h3>Paling Banyak Dibaca</h3>
    <div class="modern-article-list">
        <?php
        $populer = $this->model_utama->view_join_two('berita', 'users', 'kategori', 'username', 'id_kategori', array('berita.status' => 'Y'), 'dibaca', 'DESC', 0, 5);
        foreach ($populer->result_array() as $r2x):
            $thumb = (!empty($r2x['gambar'])) ? base_url() . "asset/foto_berita/$r2x[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";

            // Logika Pembatasan 4 Kata
            $words = explode(" ", $r2x['judul']);
            $judul_desktop = count($words) > 4 ? implode(" ", array_slice($words, 0, 4)) . "...." : $r2x['judul'];
            ?>
            <div class="modern-article-item">
                <a href="<?php echo base_url() . $r2x['judul_seo']; ?>">
                    <img src="<?php echo $thumb; ?>" class="ma-thumb" alt="<?php echo $r2x['judul']; ?>" title="<?php echo $r2x['judul']; ?>">
                </a>
                <div class="ma-content">
                    <h4><a title="<?php echo $r2x['judul']; ?>" href="<?php echo base_url() . $r2x['judul_seo']; ?>">
                            <span class="judul-desktop"><?php echo $judul_desktop; ?></span>
                            <span class="judul-mobile"><?php echo $r2x['judul']; ?></span>
                        </a></h4>
                    <div class="ma-meta">
                        <span>&#128065; <?php echo $r2x['dibaca']; ?> views</span>
                        <span>&#128197; <?php echo tgl_indo($r2x['tanggal']); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Widget Tag Cloud -->
<div class="modern-widget sticky-sidebar-desktop">
    <h3>Tag Populer</h3>
    <div class="modern-tag-cloud">
        <?php
        $tag = $this->model_utama->view_ordering_limit('tag', 'id_tag', 'RANDOM', 0, 25);
        foreach ($tag->result_array() as $row) {
            echo "<a href='" . base_url() . "tag/detail/$row[tag_seo]' class='modern-tag-badge'>#$row[nama_tag]</a>";
        }
        ?>
    </div>
</div>