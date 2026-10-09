<style>
    /* Styles khusus Sidebar Kiri Kategori - tidak ada di template.php */

    /* Photo Grid */
    .modern-photo-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .photo-grid-item { position: relative; border-radius: 8px; overflow: hidden; height: 100px; display: block; }
    .photo-grid-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
    .photo-grid-item:hover img { transform: scale(1.1); }
    .photo-grid-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 5px; background: rgba(0,0,0,0.6); color: white; font-size: 9px; text-align: center; }

    /* Polling */
    .modern-poll-question { font-size: 14px; font-weight: 700; color: #333; margin-bottom: 15px; line-height: 1.4; }
    .modern-poll-options { margin-bottom: 20px; }
    .poll-opt { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; font-size: 13px; color: #555; cursor: pointer; }
    .poll-opt input { margin-top: 3px; }
    .poll-btn { width: 100%; padding: 10px; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; transition: all 0.2s; margin-bottom: 8px; font-size: 12px; }
    .btn-vote { background: #006937; color: white; }
    .btn-vote:hover { background: #00522c; }
    .btn-result { background: #f1f3f5; color: #666; }
    .btn-result:hover { background: #e9ecef; }

    /* Video Embed */
    .modern-video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; margin-bottom: 15px; }
    .modern-video-wrapper iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
</style>


<!-- Berita Foto / Album -->
<div class="modern-widget">
	<h3>Berita Foto</h3>
	<div class="modern-photo-grid">
        <?php
            $album = $this->model_utama->view_where_ordering_limit('album',array('aktif' => 'Y'),'id_album','RANDOM',0,6);
            foreach ($album->result_array() as $row):
                $jumlah = $this->model_utama->view_where('gallery',array('id_album' => $row['id_album']))->num_rows();
        ?>
            <a href="<?php echo base_url()."albums/detail/$row[album_seo]"; ?>" class="photo-grid-item" title="<?php echo $row['jdl_album']; ?>">
                <img src="<?php echo base_url()."asset/img_album/$row[gbr_album]"; ?>" alt="album">
                <div class="photo-grid-overlay"><?php echo $jumlah; ?> Foto</div>
            </a>
        <?php endforeach; ?>
	</div>
</div>

<!-- Jejak Pendapat (Poling) -->
<div class="modern-widget">
	<h3>Jejak Pendapat</h3>
    <?php $t = $this->model_utama->view_where('poling',array('aktif' => 'Y','status' => 'Pertanyaan'))->row_array(); ?>
    <div class="modern-poll-question"><?php echo $t['pilihan']; ?></div>
    <form method="POST" action="<?php echo base_url(); ?>polling/hasil_poling">
        <div class="modern-poll-options">
            <?php
                $pilih = $this->model_utama->view_where('poling',array('aktif' => 'Y','status' => 'Jawaban'));
                foreach ($pilih->result_array() as $p):
            ?>
                <label class="poll-opt">
                    <input type="radio" name="pilihan" value="<?php echo $p['id_poling']; ?>" required>
                    <span><?php echo $p['pilihan']; ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="poll-btn btn-vote">KIRIM PILIHAN</button>
        <a href="<?php echo base_url(); ?>polling/lihat_poling" style="text-decoration:none;">
            <button type="button" class="poll-btn btn-result">LIHAT HASIL</button>
        </a>
    </form>
</div>

<!-- Dynamic Popular Section (URI Segment Logic) -->
<div class="modern-widget">
	<?php
	if ($this->uri->segment(1)=='video'):
		echo "<h3>Video Terpopuler</h3>";					  
		$video = $this->model_utama->view_ordering_limit('video','dilihat','DESC',0,2);
		foreach ($video->result_array() as $d):
            $tgl = tgl_indo($d['tanggal']);
    ?>
        <div style="margin-bottom:20px; border-bottom:1px solid #eee; padding-bottom:15px;">
            <div class="modern-video-wrapper">
                <?php 
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $d['youtube'], $match)) {
                    echo "<iframe src='https://www.youtube.com/embed/".$match[1]."' allowfullscreen></iframe>";
                } 
                ?>
            </div>
            <?php 
                // Logika Pembatasan 4 Kata
                $words = explode(" ", $d['jdl_video']);
                $judul_desktop = count($words) > 4 ? implode(" ", array_slice($words, 0, 4)) . "...." : $d['jdl_video'];
            ?>
            <h4 style="font-size:14px; margin-bottom:5px;"><a title="<?php echo $d['jdl_video']; ?>" href="<?php echo base_url()."video/play/$d[video_seo]"; ?>" style="text-decoration:none; color:#333; font-weight:700;">
                <span class="judul-desktop"><?php echo $judul_desktop; ?></span>
                <span class="judul-mobile"><?php echo $d['jdl_video']; ?></span>
            </a></h4>
            <div style="font-size:11px; color:#999;"><?php echo "$d[hari], $tgl"; ?> • <?php echo $d['dilihat']; ?> views</div>
        </div>
    <?php 
        endforeach;
		  
	elseif ($this->uri->segment(1)=='kategori'):
		$r = $this->model_utama->view_where('kategori',array('kategori_seo' => $this->uri->segment(3)))->row_array();
		echo "<h3>Berita $r[nama_kategori]</h3>";
    ?>
		<div class="modern-article-list">
            <?php
			$kategori1 = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $r['id_kategori'],'berita.status' => 'Y'),'dibaca','DESC',0,5);			
			foreach ($kategori1->result_array() as $r2x):
                $thumb = (!empty($r2x['gambar'])) ? base_url()."asset/foto_berita/$r2x[gambar]" : base_url()."asset/foto_berita/small_no-image.jpg";
                
                // Logika Pembatasan 4 Kata
                $words = explode(" ", $r2x['judul']);
                $judul_desktop = count($words) > 4 ? implode(" ", array_slice($words, 0, 4)) . "...." : $r2x['judul'];
            ?>
                <div class="modern-article-item">
                    <img src="<?php echo $thumb; ?>" class="ma-thumb">
                    <div class="ma-content">
                        <h4 style="font-size:13px; line-height:1.3;"><a title="<?php echo $r2x['judul']; ?>" href="<?php echo base_url().$r2x['judul_seo']; ?>" style="text-decoration:none; color:#333; font-weight:700;">
                            <span class="judul-desktop"><?php echo $judul_desktop; ?></span>
                            <span class="judul-mobile"><?php echo $r2x['judul']; ?></span>
                        </a></h4>
                        <div style="font-size:10px; color:#999; margin-top:3px;"><?php echo $r2x['dibaca']; ?> views</div>
                    </div>
                </div>
            <?php endforeach; ?>
		</div>
        <div class="widget-footer-modern center">
            <a href="<?php echo base_url()."kategori/detail/$r[kategori_seo]"; ?>" class="more-pill">Lihat Semua Berita</a>
        </div>
    <?php 
	else:
		echo "<h3>Informasi Populer</h3>";
    ?>
		<div class="modern-article-list">
            <?php
            $populer = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.status' => 'Y'),'dibaca','DESC',0,5);
            foreach ($populer->result_array() as $r2x):
                $thumb = (!empty($r2x['gambar'])) ? base_url()."asset/foto_berita/$r2x[gambar]" : base_url()."asset/foto_berita/small_no-image.jpg";
                
                // Logika Pembatasan 4 Kata
                $words = explode(" ", $r2x['judul']);
                $judul_desktop = count($words) > 4 ? implode(" ", array_slice($words, 0, 4)) . "...." : $r2x['judul'];
            ?>
                <div class="modern-article-item">
                    <img src="<?php echo $thumb; ?>" class="ma-thumb">
                    <div class="ma-content">
                        <h4 style="font-size:13px; line-height:1.3;"><a title="<?php echo $r2x['judul']; ?>" href="<?php echo base_url().$r2x['judul_seo']; ?>" style="text-decoration:none; color:#333; font-weight:700;">
                            <span class="judul-desktop"><?php echo $judul_desktop; ?></span>
                            <span class="judul-mobile"><?php echo $r2x['judul']; ?></span>
                        </a></h4>
                        <div style="font-size:10px; color:#999; margin-top:3px;"><?php echo $r2x['dibaca']; ?> views</div>
                    </div>
                </div>
            <?php endforeach; ?>
		</div>
        <div class="widget-footer-modern center">
            <a href="#" class="more-pill">Lihat Berita Lainnya</a>
        </div>
	<?php endif; ?>
</div>

<!-- Widget Iklan -->
<?php
    $pasangiklan1 = $this->model_utama->view_ordering_limit('pasangiklan','id_pasangiklan','ASC',0,1);
    foreach ($pasangiklan1->result_array() as $b):
?>
    <div class="modern-widget sticky-sidebar-desktop" style="padding:10px;">
        <a href="<?php echo $b['url']; ?>" target="_blank">
            <img src="<?php echo base_url()."asset/foto_pasangiklan/$b[gambar]"; ?>" style="width:100%; border-radius:12px;" alt="<?php echo $b['judul']; ?>" />
        </a>
    </div>
<?php endforeach; ?>