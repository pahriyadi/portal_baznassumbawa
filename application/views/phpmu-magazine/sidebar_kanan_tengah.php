<style>
    /* Modern Sidebar Styles (Shared Logic) */
    .modern-widget {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 25px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .modern-widget h3, .modern-widget .modern-list-title {
        font-size: 15px;
        font-weight: 800;
        color: #006937;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #F4C10F;
        display: inline-block;
        text-transform: uppercase;
    }
    .polling-question { font-weight: 700; color: #333; margin-bottom: 15px; line-height: 1.4; }
    .polling-option { display: block; margin-bottom: 10px; cursor: pointer; font-size: 14px; position: relative; padding-left: 28px; }
    .polling-option input { position: absolute; left: 0; top: 3px; }
    
    .modern-btn-group { display: flex; gap: 10px; margin-top: 20px; }
    .modern-btn {
        flex: 1;
        padding: 10px;
        border-radius: 10px;
        border: none;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
    }
    .btn-polling-submit { background: #006937; color: white; }
    .btn-polling-result { background: #f1f3f5; color: #495057; }
    .modern-btn:hover { transform: translateY(-2px); opacity: 0.9; }

    .video-container-modern {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        height: 0;
        overflow: hidden;
        border-radius: 12px;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .video-container-modern iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
    .more-pill {
        display: inline-block;
        background: #f1f3f5;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        color: #006937;
        text-decoration: none;
        margin-top: 15px;
        transition: all 0.2s;
    }
    .more-pill:hover { background: #006937; color: white; }
</style>

<!-- Widget Poling -->
<div class="modern-widget">
	<h3 class="modern-list-title">Jajak Pendapat</h3>
    <?php
        $t = $this->model_utama->view_where('poling',array('aktif' => 'Y','status' => 'Pertanyaan'))->row_array();
        echo "<div class='polling-question'>$t[pilihan]</div>";
        echo "<form method='POST' action='".base_url()."polling/hasil'>";
            $pilih = $this->model_utama->view_where('poling',array('aktif' => 'Y','status' => 'Jawaban'));
            foreach ($pilih->result_array() as $p) {
                echo "<label class='polling-option'>
                        <input type='radio' name='pilihan' value='$p[id_poling]' required />
                        $p[pilihan]
                      </label>";
            }
            echo "<div class='modern-btn-group'>
                    <button type='submit' class='modern-btn btn-polling-submit'>PILIH SEKARANG</button>
                    <a href='".base_url()."polling' class='modern-btn btn-polling-result'>LIHAT HASIL</a>
                  </div>";
        echo "</form>";
    ?>
</div>

<!-- Widget Video -->
<div class="modern-widget">
	<h3>Video Terbaru</h3>
    <?php						  
        $video = $this->model_utama->view_ordering_limit('video','id_video','DESC',0,1);
        foreach ($video->result_array() as $d):
            $baca = $d['dilihat']+1;
            $tgl = tgl_indo($d['tanggal']);
            $src = !empty($d['youtube']) ? trim($d['youtube']) : (!empty($d['video']) ? trim($d['video']) : '');
            if (!empty($src)):
    ?>
            <div class="video-container-modern" style="border-radius: 0px !important; overflow: hidden; background: #000;">
                <?php if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $src, $match)): ?>
                    <iframe src="https://www.youtube.com/embed/<?php echo $match[1]; ?>" frameborder="0" allowfullscreen style="width:100%; aspect-ratio:16/9; border:none;"></iframe>
                <?php elseif (preg_match('/\.(mp4|webm|ogg|m3u8|mov)($|\?)/i', $src)): ?>
                    <video controls style="width:100%; aspect-ratio:16/9;">
                        <source src="<?php echo $src; ?>" type="video/mp4">
                    </video>
                <?php else: ?>
                    <iframe src="<?php echo $src; ?>" frameborder="0" allowfullscreen style="width:100%; aspect-ratio:16/9; border:none;"></iframe>
                <?php endif; ?>
            </div>
            <div style="margin-bottom: 15px;">
                <h4 style="font-size:14px; margin-bottom:5px;"><a href="<?php echo base_url()."playlist/watch/$d[video_seo]"; ?>" style="text-decoration:none; color:#333; font-weight:700;"><?php echo $d['jdl_video']; ?></a></h4>
                <div style="font-size:11px; color:#999;">Dilihat <?php echo $baca; ?> kali • <?php echo $tgl; ?></div>
            </div>
    <?php 
            endif;
        endforeach; 
    ?>
    <center><a href="<?php echo base_url()."playlist"; ?>" class="more-pill">Lihat Semua Video</a></center>
</div>