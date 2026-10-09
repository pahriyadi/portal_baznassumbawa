<style>
    /* Modern Kategori Sidebar Styles */
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

    /* Comment Styling */
    .modern-comment-item {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #eee;
    }
    .modern-comment-item:last-child { border-bottom: none; margin-bottom: 0; }
    .mc-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #f0f0f0; }
    .mc-content h4 { font-size: 14px; font-weight: 800; color: #006937; margin-bottom: 3px; }
    .mc-content p { font-size: 13px; color: #555; line-height: 1.4; margin-bottom: 5px; font-style: italic; }
    .mc-content span { font-size: 11px; color: #999; }
    
    /* Social Block (Reuse) */
    .modern-social-list { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
    .modern-social-item { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; font-size: 14px; }
    .ms-fb { background: #1877F2; } .ms-tw { background: #000; } .ms-ig { background: #E4405F; } .ms-yt { background: #FF0000; }
</style>

<!-- Widget Iklan -->
<?php
    $pasangiklan2 = $this->model_utama->view_ordering_limit('pasangiklan','id_pasangiklan','ASC',1,1);
    foreach ($pasangiklan2->result_array() as $b):
?>
    <div class="modern-widget" style="padding:10px;">
        <a href="<?php echo $b['url']; ?>" target="_blank">
            <img src="<?php echo base_url()."asset/foto_pasangiklan/$b[gambar]"; ?>" style="width:100%; border-radius:12px;" alt="<?php echo $b['judul']; ?>" title="<?php echo $b['judul']; ?>" />
        </a>
    </div>
<?php endforeach; ?>

<!-- Widget Sosial -->
<div class="modern-widget">
    <h3>Media Sosial</h3>
    <div class="modern-social-list">
        <?php
            $sosmed = $this->model_utama->view('identitas')->row_array();
            $pecahd = explode(",", $sosmed['facebook']);
        ?>
        <a title="Facebook" href="<?php echo trim($pecahd[0]); ?>" class="modern-social-item ms-fb">F</a>
        <a title="X / Twitter" href="<?php echo trim($pecahd[1]); ?>" class="modern-social-item ms-tw">X</a>
        <a title="Instagram" href="<?php echo trim($pecahd[2]); ?>" class="modern-social-item ms-ig">I</a>
        <a title="Youtube" href="<?php echo trim($pecahd[3]); ?>" class="modern-social-item ms-yt">Y</a>
    </div>
    <p style="font-size:12px; color:#777;">Ikuti kami untuk informasi terbaru.</p>
</div>

<!-- Widget Informasi Terbaru -->
<div class="modern-widget">
    <h3>Informasi Terbaru</h3>
    <div class="modern-article-list">
        <?php 
            $terbaru = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.status' => 'Y'),'id_berita','DESC',0,5);
            foreach ($terbaru->result_array() as $r2x):
                $thumb = (!empty($r2x['gambar'])) ? base_url()."asset/foto_berita/$r2x[gambar]" : base_url()."asset/foto_berita/small_no-image.jpg";
        ?>
            <div class="modern-article-item">
                <a href="<?php echo base_url().$r2x['judul_seo']; ?>">
                    <img src="<?php echo $thumb; ?>" class="ma-thumb" alt="<?php echo $r2x['judul']; ?>" title="<?php echo $r2x['judul']; ?>">
                </a>
                <div class="ma-content">
                    <h4 style="font-size:13px; line-height:1.3;"><a href="<?php echo base_url().$r2x['judul_seo']; ?>" style="text-decoration:none; color:#333; font-weight:700;"><?php echo $r2x['judul']; ?></a></h4>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Widget Komentar Terakhir -->
<div class="modern-widget sticky-sidebar-desktop">
    <h3>Komentar Terakhir</h3>
    <div class="modern-comment-list">
        <?php
            $komentar = $this->model_utama->view_where_ordering_limit('komentar',array('aktif' => 'Y'),'id_komentar','DESC',0,5);
            foreach ($komentar->result_array() as $r):
                $avatar = md5(strtolower(trim($r['email'])));
                $b = $this->model_utama->view_where('berita',array('id_berita' => $r['id_berita']))->row_array();
                $isi = substr(strip_tags($r['isi_komentar']), 0, 80);
        ?>
            <div class="modern-comment-item">
                <img src="http://www.gravatar.com/avatar/<?php echo $avatar; ?>.jpg?s=100" class="mc-avatar" alt="<?php echo $r['nama_komentar']; ?>" title="<?php echo $r['nama_komentar']; ?>" />
                <div class="mc-content">
                    <h4><?php echo $r['nama_komentar']; ?></h4>
                    <p>"<?php echo $isi; ?>..."</p>
                    <span>Pada: <a href="<?php echo base_url().$b['judul_seo']; ?>" style="color:#006937; text-decoration:none; font-weight:600;">Lihat Berita</a></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</div>