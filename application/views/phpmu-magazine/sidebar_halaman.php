<?php /* Styles sudah didefinisikan secara global di template.php */ ?>

<div class="modern-widget">
    <h3>Informasi Terbaru</h3>
    <div class="modern-article-list">
        <?php
        $terbaru = $this->model_utama->view_join_two('berita', 'users', 'kategori', 'username', 'id_kategori', array('berita.status' => 'Y'), 'id_berita', 'DESC', 0, 5);
        foreach ($terbaru->result_array() as $r2x):
            $total_komentar = $this->model_utama->view_where('komentar', array('id_berita' => $r2x['id_berita']))->num_rows();
            $thumb = (!empty($r2x['gambar'])) ? base_url() . "asset/foto_berita/$r2x[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";
            
            // Logika Pembatasan 4 Kata
            $words = explode(" ", $r2x['judul']);
            $judul_desktop = count($words) > 4 ? implode(" ", array_slice($words, 0, 4)) . "...." : $r2x['judul'];
            ?>
            <div class="modern-article-item">
                <a href="<?php echo base_url() . $r2x['judul_seo']; ?>">
                    <img src="<?php echo $thumb; ?>" class="ma-thumb" alt="<?php echo $r2x['judul']; ?>">
                </a>
                <div class="ma-content">
                    <h4><a title="<?php echo $r2x['judul']; ?>" href="<?php echo base_url() . $r2x['judul_seo']; ?>">
                        <span class="judul-desktop"><?php echo $judul_desktop; ?></span>
                        <span class="judul-mobile"><?php echo $r2x['judul']; ?></span>
                    </a></h4>
                    <div class="ma-meta">
                        <span>&#128197; <?php echo tgl_indo($r2x['tanggal']); ?></span>
                        <span style="margin-left:8px;">&#128172; <?php echo $total_komentar; ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modern-widget">
    <h3>Informasi Populer</h3>
    <div class="modern-article-list">
        <?php
        $populer = $this->model_utama->view_join_two('berita', 'users', 'kategori', 'username', 'id_kategori', array('berita.status' => 'Y'), 'dibaca', 'DESC', 0, 5);
        foreach ($populer->result_array() as $r2x):
            $total_komentar = $this->model_utama->view_where('komentar', array('id_berita' => $r2x['id_berita']))->num_rows();
            $thumb = (!empty($r2x['gambar'])) ? base_url() . "asset/foto_berita/$r2x[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";
            
            // Logika Pembatasan 4 Kata
            $words = explode(" ", $r2x['judul']);
            $judul_desktop = count($words) > 4 ? implode(" ", array_slice($words, 0, 4)) . "...." : $r2x['judul'];
            ?>
            <div class="modern-article-item">
                <a href="<?php echo base_url() . $r2x['judul_seo']; ?>">
                    <img src="<?php echo $thumb; ?>" class="ma-thumb" alt="<?php echo $r2x['judul']; ?>">
                </a>
                <div class="ma-content">
                    <h4><a title="<?php echo $r2x['judul']; ?>" href="<?php echo base_url() . $r2x['judul_seo']; ?>">
                        <span class="judul-desktop"><?php echo $judul_desktop; ?></span>
                        <span class="judul-mobile"><?php echo $r2x['judul']; ?></span>
                    </a></h4>
                    <div class="ma-meta">
                        <span>&#128065; <?php echo $r2x['dibaca']; ?> views</span>
                        <span style="margin-left:8px;">&#128172; <?php echo $total_komentar; ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>