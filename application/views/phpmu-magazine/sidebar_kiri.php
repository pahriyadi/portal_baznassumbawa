<style>
    /* Gallery & Album Widget - Specific to Sidebar Kiri */
    .gallery-nav {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: -45px;
        margin-bottom: 20px;
    }

    .nav-btn {
        width: 30px;
        height: 30px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #666;
        font-size: 12px;
        transition: all 0.2s;
    }

    .nav-btn:hover {
        background: #006937;
        color: white;
        border-color: #006937;
    }

    .album-cover-modern {
        position: relative;
        border-radius: 0px;
        overflow: hidden;
        display: block;
    }

    .album-cover-modern img {
        width: 100%;
        transition: transform 0.5s;
    }

    .album-cover-modern:hover img {
        transform: scale(1.1);
    }

    .album-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 15px;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        color: white;
    }
</style>


<!-- Galeri Foto -->
<div class="modern-widget">
    <h3 class="modern-list-title">Galeri Foto</h3>
    <div class="gallery-nav">
        <a href="#" class="nav-btn slide-left">❮</a>
        <a href="#" class="nav-btn slide-right">❯</a>
    </div>
    <div class="latest-galleries">
        <?php
        $album = $this->model_utama->view_where_ordering_limit('album', array('aktif' => 'Y'), 'id_album', 'RANDOM', 0, 1);
        foreach ($album->result_array() as $row):
            $jumlah = $this->model_utama->view_where('gallery', array('id_album' => $row['id_album']))->num_rows();
            ?>
            <a href="<?php echo base_url() . "albums/detail/$row[album_seo]"; ?>" class="album-cover-modern">
                <img width="100%" src="<?php echo base_url() . "asset/img_album/$row[gbr_album]"; ?>"
                    alt="<?php echo $row['jdl_album']; ?>" title="<?php echo $row['jdl_album']; ?>">
                <div class="album-overlay">
                    <div style="font-size: 13px; font-weight: 700;"><?php echo $row['jdl_album']; ?></div>
                    <div style="font-size: 10px; opacity: 0.8;"><?php echo $jumlah; ?> Foto</div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="widget-footer-modern center">
        <a href="<?php echo base_url() . "albums"; ?>" class="more-pill">Lihat Semua Galeri</a>
    </div>
</div>

<!-- Kategori Berita Utama Kiri -->
<?php
$kategori_ids = array(5, 6, 7);
$total_sid_kiri = count($kategori_ids);
$idx_sid_kiri = 0;
foreach ($kategori_ids as $sid):
    $rh = $this->model_utama->view_where('kategori', array('sidebar' => $sid))->row_array();
    if ($rh):
        $idx_sid_kiri++;
        $sticky_class = ($idx_sid_kiri == $total_sid_kiri) ? ' sticky-sidebar-desktop' : '';
        ?>
        <div class="modern-widget<?php echo $sticky_class; ?>">
            <h3><?php echo $rh['nama_kategori']; ?></h3>
            <div class="modern-article-list">
                <?php
                $kategori_news = $this->model_utama->view_join_two('berita', 'users', 'kategori', 'username', 'id_kategori', array('berita.id_kategori' => $rh['id_kategori'], 'berita.status' => 'Y'), 'id_berita', 'DESC', 0, 5);
                foreach ($kategori_news->result_array() as $r2z):
                    $thumb = (!empty($r2z['gambar'])) ? base_url() . "asset/foto_berita/$r2z[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";

                    // Logika Pembatasan 4 Kata
                    $words = explode(" ", $r2z['judul']);
                    $judul_desktop = count($words) > 4 ? implode(" ", array_slice($words, 0, 4)) . "...." : $r2z['judul'];
                    ?>
                    <div class="modern-article-item">
                        <a href="<?php echo base_url() . $r2z['judul_seo']; ?>">
                            <img src="<?php echo $thumb; ?>" class="ma-thumb" alt="<?php echo $r2z['judul']; ?>" title="<?php echo $r2z['judul']; ?>">
                        </a>
                        <div class="ma-content">
                            <h4><a title="<?php echo $r2z['judul']; ?>" href="<?php echo base_url() . $r2z['judul_seo']; ?>">
                                    <span class="judul-desktop"><?php echo $judul_desktop; ?></span>
                                    <span class="judul-mobile"><?php echo $r2z['judul']; ?></span>
                                </a></h4>
                            <div class="ma-meta">
                                <span>&#128197; <?php echo tgl_indo($r2z['tanggal']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="widget-footer-modern center">
                <a href="<?php echo base_url() . "kategori/detail/$rh[kategori_seo]"; ?>" class="more-pill">Lihat Lainnya</a>
            </div>
        </div>
        <?php
    endif;
endforeach;
?>