<style>
    /* Shared Modern Styles */
    .modern-widget {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 25px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .modern-widget h3 {
        font-size: 15px;
        font-weight: 800;
        color: #006937;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #F4C10F;
        display: inline-block;
        text-transform: uppercase;
    }
    .modern-article-list { list-style: none; padding: 0; margin: 0; }
    .modern-article-item {
        display: flex;
        gap: 12px;
        margin-bottom: 18px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #eee;
    }
    .modern-article-item:last-child { border-bottom: none; margin-bottom: 0; }
    .ma-thumb { width: 70px; height: 55px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
    .ma-content h4 { font-size: 13px; font-weight: 700; line-height: 1.4; margin-bottom: 5px; }
    .ma-content h4 a { text-decoration: none; color: #333; }
    .ma-meta { font-size: 10px; color: #999; }
    .more-pill {
        display: inline-block;
        background: #f1f3f5;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        color: #006937;
        text-decoration: none;
        margin-top: 5px;
        transition: all 0.2s;
    }
    .more-pill:hover { background: #006937; color: white; }
</style>

<?php 
    $sections = array(12, 13); // ID sidebar kategori yang ingin ditampilkan
    foreach ($sections as $sidebar_id):
        $r = $this->model_utama->view_where('kategori', array('sidebar' => $sidebar_id))->row_array();
        if ($r):
?>
    <div class="modern-widget">
        <h3>Informasi <?php echo $r['nama_kategori']; ?></h3>
        <div class="modern-article-list">
            <?php 
                $kategori5 = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $r['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',0,5);			
                foreach ($kategori5->result_array() as $r2x):
                    $thumb = (!empty($r2x['gambar'])) ? base_url()."asset/foto_berita/$r2x[gambar]" : base_url()."asset/foto_berita/small_no-image.jpg";
            ?>
                <div class="modern-article-item">
                    <a href="<?php echo base_url().$r2x['judul_seo']; ?>">
                        <img src="<?php echo $thumb; ?>" class="ma-thumb" alt="<?php echo $r2x['judul']; ?>">
                    </a>
                    <div class="ma-content">
                        <h4><a href="<?php echo base_url().$r2x['judul_seo']; ?>"><?php echo $r2x['judul']; ?></a></h4>
                        <div class="ma-meta">
                            <span>&#128197; <?php echo tgl_indo($r2x['tanggal']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <center><a href="<?php echo base_url()."kategori/detail/$r[kategori_seo]"; ?>" class="more-pill">Baca Selengkapnya</a></center>
    </div>
<?php 
        endif;
    endforeach; 
?>