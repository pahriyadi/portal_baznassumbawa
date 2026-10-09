<style>
    /* Modern Detail Halaman Styles */
    :root {
        --baznas-green: #006937;
        --baznas-green-light: #0b7c44;
        --baznas-accent: #F4C10F;
        --glass-white: rgba(255, 255, 255, 0.95);
        --soft-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        --hover-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .modern-post-wrapper {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        color: #333;
        line-height: 1.7;
        margin-bottom: 50px;
    }

    .post-hero {
        position: relative;
        padding: 60px 0;
        background: linear-gradient(135deg, var(--baznas-green) 0%, var(--baznas-green-light) 100%);
        color: white;
        border-radius: 24px;
        margin-bottom: -60px;
        z-index: 1;
        overflow: hidden;
    }

    .post-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('<?php echo base_url(); ?>asset/images/pattern.png') repeat;
        opacity: 0.1;
        pointer-events: none;
    }

    .post-hero-content {
        max-width: 90%;
        margin: 0 auto;
        text-align: center;
        padding-bottom: 60px;
    }

    .post-category-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .post-hero h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .post-meta-modern {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        font-size: 14px;
        opacity: 0.9;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .author-pill {
        display: flex;
        align-items: center;
        background: rgba(0,0,0,0.1);
        padding: 4px 12px 4px 4px;
        border-radius: 30px;
        gap: 10px;
    }

    .author-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid white;
        object-fit: cover;
    }

    .post-main-container {
        display: flex;
        gap: 25px;
        position: relative;
        z-index: 2;
        padding: 0 15px;
    }

    .post-content-card {
        background: var(--glass-white);
        border-radius: 24px;
        box-shadow: var(--soft-shadow);
        padding: 35px;
        flex: 1;
        min-width: 0; /* Clearfix for flex items */
    }

    .featured-image-wrapper {
        margin: -35px -35px 35px -35px;
        border-radius: 24px 24px 0 0;
        overflow: hidden;
    }

    .featured-image-wrapper img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }

    .featured-image-wrapper:hover img {
        transform: scale(1.02);
    }

    .article-body {
        font-size: 1.1rem;
        color: #444;
        margin: 30px 0;
    }

    .article-body p {
        margin-bottom: 20px;
    }

    .article-body img {
        max-width: 100%;
        height: auto !important;
        border-radius: 12px;
        margin: 20px 0;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    /* Responsive Video/iFrame */
    .article-body iframe,
    .article-body object,
    .article-body embed,
    .article-body video {
        max-width: 100% !important;
        width: 100% !important;
        aspect-ratio: 16 / 9;
        height: auto !important;
        border-radius: 12px;
        margin: 20px 0;
    }

    .modern-share-block {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .share-title {
        font-weight: 700;
        font-size: 16px;
        color: var(--baznas-green);
    }

    .share-buttons {
        display: flex;
        gap: 10px;
    }

    .share-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-weight: bold;
        font-size: 18px;
    }

    .share-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .btn-fb { background: #1877F2; }
    .btn-tw { background: #1DA1F2; }
    .btn-wa { background: #25D366; }
    .btn-tg { background: #0088cc; }
    .btn-print { background: #636e72; }

    .modern-sidebar {
        width: 280px;
        flex-shrink: 0;
    }

    @media (max-width: 992px) {
        .post-main-container {
            flex-direction: column;
            padding: 0; /* Full bleed layout */
        }
        .modern-sidebar {
            width: 100%;
            padding: 15px;
        }
        .post-content-card {
            border-radius: 0; /* Full edge feel */
            padding: 20px 15px;
            box-shadow: none;
            background: #fff;
        }
        .post-hero {
            border-radius: 0;
            padding: 25px 15px 35px 15px;
        }
        .post-hero-content {
            text-align: left;
            padding-bottom: 0px;
        }
        .post-hero h1 {
            font-size: 1.6rem;
            line-height: 1.3;
            margin-bottom: 15px;
        }
        .post-meta-modern {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 8px;
            width: 100%;
            justify-content: start;
        }
        .meta-item, .author-pill {
            background: rgba(0,0,0,0.15);
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 11px;
            white-space: nowrap;
        }
        .author-avatar {
            width: 20px;
            height: 20px;
        }
        .featured-image-wrapper {
            margin: -20px -15px 25px -15px;
            border-radius: 0;
        }
        .post-category-badge {
            margin-bottom: 15px;
            padding: 4px 12px;
            font-size: 11px;
        }
    }

</style>

<div class="modern-post-wrapper">
    <!-- Hero Section -->
    <div class="post-hero">
        <div class="post-hero-content">
            <span class="post-category-badge">Informasi</span>
            <h1><?php echo $rows['judul']; ?></h1>
            
            <div class="post-meta-modern">
                <div class="author-pill">
                    <?php 
                        $email_hash = md5(strtolower(trim($rows['email']))); 
                        echo "<img class='author-avatar' src='http://www.gravatar.com/avatar/$email_hash.jpg?s=100' alt='$rows[nama_lengkap]'/>";
                    ?>
                    <span><b><?php echo $rows['nama_lengkap']; ?></b></span>
                </div>
                <div class="meta-item">
                    <span class="icon">&#128197;</span>
                    <?php echo tgl_indo($rows['tgl_posting']); ?>
                </div>
                <div class="meta-item">
                    <span class="icon">&#9200;</span>
                    <?php echo $rows['jam']; ?> WITA
                </div>
                <div class="meta-item">
                    <span class="icon">&#128065;</span>
                    <?php echo $rows['dibaca']; ?> kali
                </div>
            </div>

            <nav class="news-share-modern" style="margin-top: 25px; margin-bottom: 0; display: flex; align-items: center; justify-content: center; gap: 20px; padding: 15px 20px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; backdrop-filter: blur(5px); max-width: max-content; margin-inline: auto;">
                <span class="share-title-text" style="color:white; font-size:13px; font-weight:700;">Bagikan:</span>
                <div style="display: flex; gap: 10px;">
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($rows['judul']) . " " . current_url(); ?>"
                        target="_blank"
                        style="background:#25D366; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:10px; font-weight:bold; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"
                        aria-label="Share WhatsApp">WA</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo current_url(); ?>" target="_blank"
                        style="background:#1877F2; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:10px; font-weight:bold; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"
                        aria-label="Share Facebook">FB</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo current_url(); ?>" target="_blank"
                        style="background:black; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:10px; font-weight:bold; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"
                        aria-label="Share Twitter">TW</a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="post-main-container">
        <div class="post-content-card">
            <?php if (trim($rows['gambar']) != ''): ?>
                <div class="featured-image-wrapper">
                    <img src="<?php echo base_url()."asset/foto_statis/$rows[gambar]"; ?>" alt="<?php echo $rows['judul']; ?>" title="<?php echo $rows['judul']; ?>">
                </div>
            <?php endif; ?>

            <div class="article-body">
                <?php 
                    if ($rows['isi_halaman'] == '') {
                        echo "<div style='text-align:center; padding:50px; color:#e74c3c; font-weight:600;'>Maaf, Belum ada Informasi pada Halaman ini.</div>"; 
                    } else {
                        echo $rows['isi_halaman'];
                    } 
                ?>
            </div>

            <!-- Ad Block -->
            <?php
                $ads = $this->model_utama->view_where_ordering_limit('iklantengah', array('posisi'=>'hal_statis'), 'id_iklantengah', 'ASC', 0, 5);
                if ($ads->num_rows() > 0): ?>
                <div class="ad-block" style="margin-top: 30px;">
                    <?php foreach ($ads->result_array() as $ad): ?>
                        <a href="<?php echo $ad['url']; ?>" target="_blank" style="display:block; margin-bottom:15px;">
                            <?php if (preg_match("/swf\z/i", $ad['gambar'])): ?>
                                <embed src="<?php echo base_url()."asset/foto_iklantengah/$ad[gambar]"; ?>" width="100%" height="90" quality="high">
                            <?php else: ?>
                                <img src="<?php echo base_url()."asset/foto_iklantengah/$ad[gambar]"; ?>" alt="<?php echo $ad['judul']; ?>" title="<?php echo $ad['judul']; ?>" style="width:100%; border-radius:12px;">
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


        </div>

        <!-- Sidebar -->
        <aside class="modern-sidebar">
            <?php include "sidebar_halaman.php"; ?>
        </aside>
    </div>
</div>

<script>
    // Print functionality script if needed, though window.print() works fine
    function printArticle() {
        window.print();
    }
</script>