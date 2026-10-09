<style>
    /* Responsive Archive Styles */
    .archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .archive-item {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eee;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .archive-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    .archive-thumb {
        height: 140px;
        overflow: hidden;
        background: #f5f5f5;
    }

    .archive-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .archive-content {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .archive-content h4 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .archive-content h4 a {
        color: #333;
        text-decoration: none;
    }

    .archive-content h4 a:hover {
        color: #006937;
    }

    .archive-meta {
        font-size: 11px;
        color: #999;
        margin-top: auto;
    }

    /* Category Blocks */
    .category-block {
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 15px;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .category-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .category-article-list {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
    }

    .category-article-list li {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #f0f0f0;
    }

    .category-article-list li:last-child {
        border-bottom: none;
    }

    /* Filter Form Responsive */
    .index-filter-form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .index-filter-form select,
    .index-filter-form input[type="submit"] {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .index-filter-form input[type="submit"] {
        background: #006937;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .index-filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .block-title h2 {
            font-size: 16px;
        }

        .archive-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 15px;
        }

        /* Precision fixes to fit screen */
        .main-page.full-width,
        .content-block.main,
        .block {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            border: none !important;
        }

        .block-content.archive {
            padding: 15px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
    }
</style>

<div class="main-page full-width">
    <div class="content-block main">
        <div class="block">
            <div class="block-title">
                <a href="<?php echo base_url(); ?>" class="right">Kembali ke Beranda</a>
                <h2>Indeks Berita</h2>
            </div>

            <div class="block-content archive" style="width: 100%; padding: 10px;">
                <form action="<?php echo base_url(); ?>berita/indeks_berita" method="POST" class="index-filter-form">
                    <span>Lihat Indeks Tanggal:</span>
                    <div style="display: flex; gap: 5px; flex-wrap: wrap; width: 100%;">
                        <select name="tanggal" class="select" style="flex: 1; min-width: 60px;">
                            <?php
                            for ($n = 1; $n <= 31; $n++) {
                                $tgls = isset($_POST['filter']) ? $_POST['tanggal'] : date("d");
                                echo "<option value='$n' " . ($tgls == $n ? "selected" : "") . ">$n</option>";
                            }
                            ?>
                        </select>

                        <select name="bulan" class="select" style="flex: 2; min-width: 120px;">
                            <?php
                            $bln = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                            for ($n = 1; $n <= 12; $n++) {
                                $blns = isset($_POST['filter']) ? $_POST['bulan'] : date("n");
                                echo "<option value='$n' " . ($blns == $n ? "selected" : "") . ">$bln[$n]</option>";
                            }
                            ?>
                        </select>

                        <select name="tahun" class="select" style="flex: 1; min-width: 80px;">
                            <?php
                            for ($n = 2008; $n <= date('Y'); $n++) {
                                $year = isset($_POST['filter']) ? $_POST['tahun'] : date("Y");
                                echo "<option value='$n' " . ($year == $n ? "selected" : "") . ">$n</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <input type="submit" name="filter" value="Lihat Indeks" style="width: 100%;">
                </form>

                <?php
                $is_filtered = isset($_POST['filter']);
                if ($is_filtered) {
                    $bulann = str_pad($_POST['bulan'], 2, "0", STR_PAD_LEFT);
                    $tanggall = str_pad($_POST['tanggal'], 2, "0", STR_PAD_LEFT);
                    $fil = $_POST['tahun'] . '-' . $bulann . '-' . $tanggall;
                } else {
                    $fil = date("Y-m-d");
                }

                $warna = array("#e74c3c", "#3498db", "#27ae60", "#9b59b6", "#f39c12", "#2c3e50", "#2980b9");

                if (!function_exists('display_latest_news_grid')) {
                    function display_latest_news_grid($berita_terbaru)
                    {
                        if (is_object($berita_terbaru) && $berita_terbaru->num_rows() > 0) {
                            echo '<div class="archive-grid">';
                            foreach ($berita_terbaru->result_array() as $r) {
                                $img_src = (!empty($r['gambar'])) ? base_url() . "asset/foto_berita/$r[gambar]" : base_url() . "asset/foto_berita/no-image.jpg";
                                ?>
                                <article class="archive-item">
                                    <div class="archive-thumb">
                                        <a href="<?php echo base_url() . $r['judul_seo']; ?>">
                                            <img src="<?php echo $img_src; ?>" alt="<?php echo $r['judul']; ?>" title="<?php echo $r['judul']; ?>" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="archive-content">
                                        <h4><a href="<?php echo base_url() . $r['judul_seo']; ?>"><?php echo $r['judul']; ?></a></h4>
                                        <div class="archive-meta">
                                            <span>📅 <?php echo tgl_indo($r['tanggal']); ?></span>
                                        </div>
                                    </div>
                                </article>
                                <?php
                            }
                            echo '</div>';
                        }
                    }
                }

                if ($is_filtered) {
                    echo "<div class='block-title' style='margin-top:30px'><h2>Hasil Indeks Tanggal: " . tgl_indo($fil) . "</h2></div>";
                    if (is_object($record) && $record->num_rows() > 0) {
                        echo '<div class="archive-grid">';
                        $idx = 0;
                        foreach ($record->result_array() as $t) {
                            $total = $this->model_utama->view_where('berita', array('id_kategori' => $t['id_kategori'], 'tanggal' => $fil, 'status' => 'Y'))->num_rows();
                            if ($total >= 1) {
                                $color = $warna[$idx % count($warna)];
                                ?>
                                <div class="category-block">
                                    <h3 class="category-title" style="color: <?php echo $color; ?>"><?php echo $t['nama_kategori']; ?>
                                    </h3>
                                    <ul class="category-article-list">
                                        <?php
                                        $sql = $this->model_utama->view_where_ordering_limit('berita', array('id_kategori' => $t['id_kategori'], 'tanggal' => $fil, 'status' => 'Y'), 'id_berita', 'DESC', 0, 5);
                                        foreach ($sql->result_array() as $r) {
                                            $total_komentar = $this->model_utama->view_where('komentar', array('id_berita' => $r['id_berita']))->num_rows();
                                            echo "<li>
                                                    <a href='" . base_url() . "$r[judul_seo]' style='text-decoration:none; color:#333; font-weight:600; font-size:13px;'>$r[judul]</a>
                                                    <div class='archive-meta' style='margin-top:5px;'>
                                                        <span>💬 $total_komentar</span> • <span>" . tgl_indo($r['tanggal']) . "</span>
                                                    </div>
                                                  </li>";
                                        }
                                        ?>
                                    </ul>
                                    <a href="<?php echo base_url() . "kategori/detail/$t[kategori_seo]"; ?>" class="more-pill"
                                        style="margin-top:auto; font-size:11px; align-self: center;">Selengkapnya</a>
                                </div>
                                <?php
                                $idx++;
                            }
                        }
                        echo '</div>';
                    }

                    if (!is_object($hitung) || $hitung->num_rows() < 1) {
                        echo "<div class='alert alert-info' style='margin-top:20px; text-align:center'>Maaf, belum ada artikel pada tanggal " . tgl_indo($fil) . ". Berikut berita terbaru lainnya:</div>";
                        display_latest_news_grid($berita_terbaru);
                    }
                } else {
                    // Default View
                    echo "<div class='block-title' style='margin-top:30px'><h2>Berita Terbaru</h2></div>";
                    display_latest_news_grid($berita_terbaru);

                    echo "<div style='margin: 50px 0; border-top: 1px solid #eee;'></div>";

                    echo "<div class='block-title'><h2>Jelajahi Berita per Kategori</h2></div>";
                    if (is_object($record) && $record->num_rows() > 0) {
                        echo '<div class="archive-grid">';
                        foreach ($record->result_array() as $t) {
                            ?>
                            <div class="category-block">
                                <h3 class="category-title" style="color: #666;"><?php echo $t['nama_kategori']; ?></h3>
                                <ul class="category-article-list">
                                    <?php
                                    $sql = $this->model_utama->view_where_ordering_limit('berita', array('id_kategori' => $t['id_kategori'], 'status' => 'Y'), 'id_berita', 'DESC', 0, 3);
                                    foreach ($sql->result_array() as $r) {
                                        echo "<li><a href='" . base_url() . "$r[judul_seo]' style='text-decoration:none; color:#444; font-size:13px;'>$r[judul]</a></li>";
                                    }
                                    ?>
                                </ul>
                                <a href="<?php echo base_url() . "kategori/detail/$t[kategori_seo]"; ?>" class="more-pill"
                                    style="margin-top:auto; font-size:11px; align-self: center;">Lihat Semua</a>
                            </div>
                            <?php
                        }
                        echo '</div>';
                    }

                    echo "<div style='margin: 50px 0; border-top: 1px solid #eee;'></div>";

                    echo "<div class='block-title'><h2>Informasi & Halaman Penting</h2></div>";
                    if (is_object($halamanstatis) && $halamanstatis->num_rows() > 0) {
                        echo "<div class='archive-grid' style='grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));'>";
                        foreach ($halamanstatis->result_array() as $h) {
                            echo "<div style='background:#f9f9f9; padding:15px; border-left:4px solid #006937; border-radius:8px;'>
                                    <a style='font-weight:bold; color:#333; text-decoration:none;' href='" . base_url() . "$h[judul_seo]'>$h[judul]</a>
                                  </div>";
                        }
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>