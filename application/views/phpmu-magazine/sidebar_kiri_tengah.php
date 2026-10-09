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
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px dashed #eee;
    }
    .modern-article-item:last-child { border-bottom: none; margin-bottom: 0; }
    .ma-thumb { width: 65px; height: 50px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
    .ma-content h4 { font-size: 13px; font-weight: 700; line-height: 1.4; margin-bottom: 4px; }
    .ma-content h4 a { text-decoration: none; color: #333; }
    .ma-meta { font-size: 10px; color: #999; }
    .more-pill {
        display: inline-block;
        background: #f1f3f5;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        color: #006937;
        text-decoration: none;
        margin-top: 10px;
    }
    .more-pill:hover { background: #006937; color: white; }
</style>

<!-- Widget Iklan Kiri -->
<?php
    $pasangiklan = $this->db->query("SELECT * FROM pasangiklan where judul LIKE '%kiri%'");
    if($pasangiklan->num_rows() > 0):
?>
    <div class="modern-widget" style="padding:10px;">
        <?php foreach ($pasangiklan->result_array() as $b): ?>
            <div style="margin-bottom:10px;">
                <a href="<?php echo $b['url']; ?>" target="_blank">
                    <img src="<?php echo base_url()."asset/foto_pasangiklan/$b[gambar]"; ?>" style="width:100%; border-radius:12px;" alt="<?php echo $b['judul']; ?>" />
                </a>
                <?php if (trim($b['source']) != ''){ echo "$b[source]"; } ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Widget Kategori Info (ID 8 & 9) -->
<?php 
    $cat_ids = array(8, 9);
    foreach ($cat_ids as $sid):
        $r = $this->model_utama->view_where('kategori',array('sidebar' => $sid))->row_array();
        if ($r):
?>
    <div class="modern-widget">
        <h3>Informasi <?php echo $r['nama_kategori']; ?></h3>
        <div class="modern-article-list">
            <?php 
                $kategori5 = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $r['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',0,3);			
                foreach ($kategori5->result_array() as $r2x):
                    $thumb = (!empty($r2x['gambar'])) ? base_url()."asset/foto_berita/$r2x[gambar]" : base_url()."asset/foto_berita/small_no-image.jpg";
            ?>
                <div class="modern-article-item">
                    <a href="<?php echo base_url().$r2x['judul_seo']; ?>">
                        <img src="<?php echo $thumb; ?>" class="ma-thumb" alt="news">
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
        <center><a href="<?php echo base_url()."kategori/detail/$r[kategori_seo]"; ?>" class="more-pill">Read More</a></center>
    </div>
<?php 
        endif;
    endforeach; 
?>