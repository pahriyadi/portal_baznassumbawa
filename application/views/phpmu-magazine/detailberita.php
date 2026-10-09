<?php
$baca = $rows['dibaca'] + 1;
$total_komentar = $this->model_utama->view_where('komentar', array('id_berita' => $rows['id_berita']))->num_rows();
$logo_schema = $this->db->query("SELECT gambar FROM logo ORDER BY id_logo DESC LIMIT 1")->row_array();
$url_logo = (!empty($logo_schema['gambar'])) ? base_url() . "asset/logo/" . $logo_schema['gambar'] : base_url() . "asset/logo/logo.png";

// SEO Tool: Calculation Reading Time
$words = str_word_count(strip_tags($rows['isi_berita']));
$readTime = ceil($words / 200); // Average 200 words per minute
?>

<style>
    /* Modern News Detail Styles */
    :root {
        --baznas-green: #006937;
        --baznas-green-light: #0b7c44;
        --baznas-accent: #F4C10F;
        --glass-white: rgba(255, 255, 255, 0.98);
        --soft-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        --hover-shadow: 0 15px 45px rgba(0, 0, 0, 0.08);
        --text-dark: #1a1a1a;
        --text-grey: #444444;
    }

    /* Reading Progress Bar */
    #reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 4px;
        background: var(--baznas-accent);
        z-index: 10001;
        transition: width 0.1s ease-out;
    }

    .modern-news-wrapper {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #333;
        line-height: 1.7;
        /* Optimized for readability */
        margin-bottom: 60px;
        letter-spacing: -0.01em;
    }

    .news-hero {
        position: relative;
        padding: 40px 0 50px 0;
        background: linear-gradient(135deg, var(--baznas-green) 0%, var(--baznas-green-light) 100%);
        border-radius: 30px;
        margin-bottom: -40px;
        z-index: 1;
        overflow: hidden;
    }

    .news-hero-content {
        max-width: 900px;
        margin: 0 auto;
        text-align: center;
        padding: 0 20px;
    }

    .news-badge {
        display: inline-block;
        background: var(--baznas-accent);
        color: var(--baznas-green);
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .news-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.15;
        letter-spacing: -0.03em;
        color: #ffffff !important;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .news-hero .sub-title {
        font-size: 1.2rem;
        opacity: 0.9;
        font-weight: 500;
        margin-bottom: 30px;
        display: block;
        color: #ffffff !important;
    }

    .news-meta-modern {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        font-size: 14px;
        flex-wrap: wrap;
    }

    .meta-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        /* Slightly brighter */
        padding: 6px 14px;
        border-radius: 30px;
        backdrop-filter: blur(5px);
        color: #ffffff;
        /* Warna putih agar kontras */
    }

    .author-link {
        color: white;
        text-decoration: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .news-main-container {
        display: flex;
        gap: 25px;
        position: relative;
        z-index: 2;
        padding: 0 15px;
    }

    .news-content-card {
        background: var(--glass-white);
        border-radius: 30px;
        box-shadow: var(--soft-shadow);
        padding: 35px 45px;
        /* Increased for desktop premium feel */
        flex: 1;
        min-width: 0;
    }

    .featured-img-box {
        margin: -35px -35px 35px -35px;
        border-radius: 30px 30px 0 0;
        overflow: hidden;
        position: relative;
    }

    .featured-img-box img {
        width: 100%;
        height: auto;
        display: block;
    }

    .img-caption {
        padding: 12px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        font-style: italic;
        color: #666;
        font-size: 13px;
        text-align: center;
    }

    .article-body-modern {
        font-size: 1.12rem;
        color: var(--text-dark);
        text-align: justify;
        /* Kembali ke rata kiri-kanan sesuai permintaan */
        text-justify: inter-word;
        hyphens: auto;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
        -ms-hyphens: auto;
    }

    .article-body-modern p {
        margin-bottom: 1.5rem;
        /* Standardized spacing */
        color: var(--text-dark);
    }

    /* Responsive Video/iFrame */
    .article-body-modern iframe,
    .article-body-modern object,
    .article-body-modern embed,
    .article-body-modern video {
        max-width: 100% !important;
        width: 100% !important;
        aspect-ratio: 16 / 9;
        height: auto !important;
        border-radius: 12px;
        margin: 20px 0;
    }

    /* Baca Lainnya Styling */
    .baca-lainnya-modern {
        background: #f1f8f4;
        border-left: 4px solid var(--baznas-green);
        padding: 25px;
        border-radius: 0 16px 16px 0;
        margin: 30px 0;
    }

    .bl-header {
        font-weight: 800;
        font-size: 15px;
        color: var(--baznas-green);
        text-transform: uppercase;
        margin-bottom: 15px;
        display: block;
    }

    .bl-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .bl-item {
        margin-bottom: 12px;
    }

    .bl-link {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        padding: 8px;
        border-radius: 8px;
        transition: 0.2s;
    }

    .bl-link:hover {
        background: white;
        color: var(--baznas-green);
    }

    .bl-dot {
        width: 6px;
        height: 6px;
        background: var(--baznas-green);
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Tags */
    .modern-tags {
        margin-top: 40px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .tag-item {
        background: #f0f2f5;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-grey);
        text-decoration: none;
        transition: all 0.2s;
    }

    .tag-item:hover {
        background: var(--baznas-green);
        color: white;
    }

    /* Share Block */
    .news-share-modern {
        margin: 10px 0 25px 0;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 20px;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
    }

    .share-title-text {
        font-weight: 700;
        color: var(--baznas-green);
    }

    /* Comments Section */
    .comment-card {
        margin-top: 50px;
        padding: 40px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
    }

    .comment-title {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--baznas-green);
        display: inline-block;
    }

    .comment-list-modern {
        list-style: none;
        padding: 0;
    }

    .comment-item {
        display: flex;
        gap: 15px;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 15px;
        transition: transform 0.2s;
    }

    .comment-item:hover {
        transform: scale(1.01);
    }

    .comment-form-modern {
        margin-top: 40px;
    }

    .comment-input-group {
        margin-bottom: 20px;
    }

    .comment-input-group label {
        display: block;
        font-weight: 700;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .comment-input-group input,
    .comment-input-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 12px;
        font-family: inherit;
        font-size: 15px;
        transition: border-color 0.2s;
    }

    .comment-input-group input:focus,
    .comment-input-group textarea:focus {
        border-color: var(--baznas-green);
        outline: none;
    }

    .submit-btn-modern {
        background: var(--baznas-green);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }

    .submit-btn-modern:hover {
        background: var(--baznas-green-light);
        transform: translateY(-2px);
    }

    /* Recommendations Grid */
    .rec-grid-modern {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 30px;
    }

    .rec-item-modern {
        display: flex;
        gap: 15px;
        text-decoration: none;
        color: inherit;
        padding: 12px;
        border-radius: 16px;
        background: white;
        border: 1px solid #eee;
        transition: all 0.3s;
    }

    .rec-item-modern:hover {
        border-color: var(--baznas-green);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .rec-thumb-modern {
        width: 100px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
    }

    .modern-sidebar {
        width: 280px;
        flex-shrink: 0;
    }
    @media (max-width: 992px) {
        .news-main-container {
            flex-direction: column;
            padding: 0;
        }

        .news-content-card {
            border-radius: 0;
            padding: 20px 15px;
            box-shadow: none;
            margin-top: -20px;
        }

        .modern-sidebar {
            width: 100%;
            padding: 15px;
        }

        .news-hero {
            border-radius: 0 !important;
            padding: 20px 15px 40px 15px !important;
            text-align: left;
        }

        .news-hero h1 {
            font-size: 1.5rem !important;
            line-height: 1.3;
            margin-bottom: 15px;
            text-align: left;
            color: #ffffff !important;
        }

        .news-hero .sub-title {
            font-size: 1rem;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.9) !important;
        }

        .news-hero-content {
            padding: 0;
            text-align: left;
        }

        .news-meta-modern {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            width: 100%;
        }

        .meta-pill {
            font-size: 10px !important;
            padding: 5px 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            justify-content: flex-start;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #ffffff !important;
        }

        .featured-img-box {
            margin: -20px -15px 20px -15px;
            border-radius: 0;
            max-height: 250px;
            overflow: hidden;
            background: #f0f0f0;
        }

        .featured-img-box img {
            width: 100%;
            height: 200px !important;
            object-fit: contain;
            background: #eeeeee;
        }

        .news-share-modern {
            margin: 15px 0;
            padding: 10px 15px;
            justify-content: space-between;
            background: rgba(0,0,0,0.2) !important;
            border: none;
        }

        .share-title-text {
            font-size: 12px;
            color: #ffffff !important;
        }

        .rec-grid-modern {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .rec-item-modern {
            flex-direction: column;
            padding: 8px;
            align-items: flex-start;
        }

        .rec-thumb-modern {
            width: 100%;
            height: 135px;
            margin-bottom: 8px;
            object-fit: cover;
        }

        .rec-title {
            font-size: 12px !important;
            line-height: 1.2;
        }
    }

    @media (max-width: 600px) {
        .news-hero h1 {
            font-size: 1.6rem;
        }

        .news-share-modern {
            flex-direction: row;
            justify-content: center;
            gap: 15px;
            padding: 10px;
        }
    }
</style>

<!-- SEO Schema.org v2.0 (Expert Optimized for AI & Google) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "mainEntityOfPage": { "@type": "WebPage", "@id": "<?php echo current_url(); ?>" },
    "headline": "<?php echo htmlspecialchars(strip_tags($rows['judul'])); ?>",
    "description": "<?php echo htmlspecialchars(substr(strip_tags($rows['isi_berita']), 0, 160)); ?>",
    "image": {
        "@type": "ImageObject",
        "url": "<?php echo base_url(); ?>asset/foto_berita/<?php echo $rows['gambar']; ?>",
        "width": 1200,
        "height": 675
    },
    "datePublished": "<?php echo date('c', strtotime($rows['tanggal'] . ' ' . $rows['jam'])); ?>",
    "dateModified": "<?php echo date('c', strtotime($rows['tanggal'] . ' ' . $rows['jam'])); ?>",
    "author": { 
        "@type": "Person", 
        "name": "<?php echo $rows['nama_lengkap']; ?>",
        "url": "<?php echo base_url(); ?>berita/indeks_berita"
    },
    "publisher": {
        "@type": "Organization",
        "name": "BAZNAS Kabupaten Sumbawa",
        "logo": { 
            "@type": "ImageObject", 
            "url": "<?php echo $url_logo; ?>" 
        }
    },
    "keywords": "<?php echo $rows['tag']; ?>"
}
</script>

<!-- Breadcrumb Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Beranda",
    "item": "<?php echo base_url(); ?>"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "<?php echo $rows['nama_kategori']; ?>",
    "item": "<?php echo base_url() . 'kategori/detail/' . $rows['kategori_seo']; ?>"
  },{
    "@type": "ListItem",
    "position": 3,
    "name": "<?php echo htmlspecialchars(strip_tags($rows['judul'])); ?>",
    "item": "<?php echo current_url(); ?>"
  }]
}
</script>

<div class="modern-news-wrapper">
    <!-- Breadcrumb UI -->
    <nav class="breadcrumb-flat" aria-label="Breadcrumb" style="max-width: 900px; margin: 20px auto; padding: 0 20px;">
        <ol style="list-style: none; padding: 0; margin: 0; display: flex; gap: 10px; font-size: 13px; color: #666;">
            <li><a href="<?php echo base_url(); ?>" style="text-decoration: none; color: inherit;">Beranda</a></li>
            <li style="color: #ccc;">/</li>
            <li><a href="<?php echo base_url() . 'kategori/detail/' . $rows['kategori_seo']; ?>"
                    style="text-decoration: none; color: inherit;"><?php echo $rows['nama_kategori']; ?></a></li>
            <li style="color: #ccc;">/</li>
            <li style="color: var(--baznas-green); font-weight: 700;"><?php echo substr($rows['judul'], 0, 30); ?>...
            </li>
        </ol>
    </nav>

    <!-- Hero Section -->
    <header class="news-hero">
        <div class="news-hero-content">
            <span class="news-badge"><?php echo $rows['nama_kategori']; ?></span>
            <h1><?php echo $rows['judul']; ?></h1>
            <?php if ($rows['sub_judul'] != ''): ?>
                <span class="sub-title"><?php echo $rows['sub_judul']; ?></span>
            <?php endif; ?>

            <div class="news-meta-modern">
                <div class="meta-pill">
                    <a href="#" class="author-link">
                        <?php
                        $email_hash = md5(strtolower(trim($rows['email'])));
                        echo "<img loading='lazy' style='width:24px; height:24px; border-radius:50%;' src='http://www.gravatar.com/avatar/$email_hash.jpg?s=50' alt='$rows[nama_lengkap]' />";
                        ?>
                        <span><?php echo $rows['nama_lengkap']; ?></span>
                    </a>
                </div>
                <div class="meta-pill">
                    <time datetime="<?php echo $rows['tanggal']; ?>">📅 <?php echo tgl_indo($rows['tanggal']); ?></time>
                </div>
                <div class="meta-pill">
                    <span>🕒 <?php echo $rows['jam']; ?> WITA</span>
                </div>
                <div class="meta-pill" title="Estimasi Waktu Baca">
                    <span>⏱️ <?php echo $readTime; ?> menit baca</span>
                </div>
                <div class="meta-pill">
                    <span>👁️ <?php echo $rows['dibaca']; ?> kali dikunjungi</span>
                </div>
            </div>

            <nav class="news-share-modern">
                <span class="share-title-text" style="color:white; font-size:13px;">Bagikan:</span>
                <div style="display: flex; gap: 10px;">
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($rows['judul']) . " " . current_url(); ?>"
                        target="_blank"
                        style="background:#25D366; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:10px; font-weight:bold;"
                        aria-label="Share WhatsApp">WA</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo current_url(); ?>" target="_blank"
                        style="background:#1877F2; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:10px; font-weight:bold;"
                        aria-label="Share Facebook">FB</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo current_url(); ?>" target="_blank"
                        style="background:black; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:10px; font-weight:bold;"
                        aria-label="Share Twitter">TW</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="news-main-container">
        <article class="news-content-card">
            <!-- Featured Image -->
            <?php if ($rows['gambar'] != ''): ?>
                <figure class="featured-img-box">
                    <img src="<?php echo base_url() . "asset/foto_berita/$rows[gambar]"; ?>"
                        alt="<?php echo $rows['judul']; ?>" title="<?php echo $rows['judul']; ?>" loading="lazy" style="width: 100%; height: auto;">
                    <?php if ($rows['keterangan_gambar'] != ''): ?>
                        <figcaption class="img-caption"><?php echo $rows['keterangan_gambar']; ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <section class="article-body-modern">
                <?php
                $paragraph = explode("</p>", $rows['isi_berita']);
                if (count($paragraph) > 3) {
                    // Show first 3 paragraphs
                    for ($i = 0; $i < 3; $i++) {
                        echo $paragraph[$i] . "</p>";
                    }

                    // "Baca Lainnya" section
                    echo "<aside class='baca-lainnya-modern'>
                                <span class='bl-header'>Baca Lainnya :</span>
                                <nav class='bl-list'>";
                    $tags_array = explode(",", $rows['tag']);
                    $tag_query = "";
                    foreach ($tags_array as $t) {
                        if ($t != '')
                            $tag_query .= "tag LIKE '%$t%' OR ";
                    }
                    $tag_query = rtrim($tag_query, " OR ");

                    if ($tag_query != "") {
                        $related = $this->db->query("SELECT * FROM berita WHERE status='Y' AND id_berita != '$rows[id_berita]' AND ($tag_query) ORDER BY id_berita DESC LIMIT 3");
                        foreach ($related->result_array() as $rel) {
                            echo "<li class='bl-item'>
                                                    <a href='" . base_url() . "$rel[judul_seo]' class='bl-link'>
                                                        <span class='bl-dot'></span> $rel[judul]
                                                    </a>
                                                  </li>";
                        }
                    }
                    echo "  </nav>
                              </aside>";

                    // Show remaining paragraphs
                    for ($i = 3; $i < count($paragraph); $i++) {
                        echo $paragraph[$i] . "</p>";
                    }
                } else {
                    echo $rows['isi_berita'];
                }
                ?>
            </section>

            <!-- Tags (Dinonaktifkan atas permintaan user) -->
            <?php /*
       <nav class="modern-tags" aria-label="Tags" style="margin: 20px 0; border: none; padding: 0;">
           <?php
           $tags = explode(",", $rows['tag']);
           foreach ($tags as $tag) {
               if (trim($tag) != '') {
                   echo "<a href='" . base_url() . "tag/detail/" . trim($tag) . "' class='tag-item'>#" . trim($tag) . "</a>";
               }
           }
           ?>
       </nav>
       */ ?>

            <!-- Video Related -->
            <?php if ($rows['youtube'] != ''): ?>
                <section class="video-section-modern" style="margin-top: 40px;">
                    <h4 style="font-weight: 800; color: var(--baznas-green); margin-bottom: 15px;">Video Terkait</h4>
                    <?php
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $rows['youtube'], $match)) {
                        echo "<div style='position:relative; padding-bottom:56.25%; height:0; border-radius:20px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.1);'>
                                    <iframe loading='lazy' style='position:absolute; top:0; left:0; width:100%; height:100%;' src='https://www.youtube.com/embed/" . $match[1] . "' frameborder='0' allowfullscreen></iframe>
                                  </div>";
                    }
                    ?>
                </section>
            <?php endif; ?>


            <!-- Recommendations Grid -->
            <section style="margin-top: 50px;">
                <h3 class="comment-title">Rekomendasi Untuk Anda</h3>
                <div class="rec-grid-modern">
                    <?php
                    $recommendations = $this->db->query("SELECT * FROM berita WHERE status='Y' AND id_berita != '$rows[id_berita]' ORDER BY RAND() LIMIT 6");
                    foreach ($recommendations->result_array() as $rec):
                        $thumb = (!empty($rec['gambar'])) ? base_url() . "asset/foto_berita/$rec[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";
                        ?>
                        <a href="<?php echo base_url() . $rec['judul_seo']; ?>" class="rec-item-modern"
                            aria-label="<?php echo $rec['judul']; ?>">
                            <img src="<?php echo $thumb; ?>" loading="lazy" class="rec-thumb-modern"
                                alt="<?php echo $rec['judul']; ?>" title="<?php echo $rec['judul']; ?>">
                            <div>
                                <div class="rec-title" style="font-weight: 600; font-size: 14px; line-height: 1.3;">
                                    <?php echo $rec['judul']; ?>
                                </div>
                                <time style="font-size: 11px; color:#999; margin-top:5px;"
                                    datetime="<?php echo $rec['tanggal']; ?>"><?php echo tgl_indo($rec['tanggal']); ?></time>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        </article>

        <!-- Sidebar -->
        <aside class="modern-sidebar">
            <?php include "sidebar_kanan.php"; ?>
        </aside>
    </main>
</div>



<div id="reading-progress"></div>
<script>
    window.onscroll = function () {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        document.getElementById("reading-progress").style.width = scrolled + "%";
    };
</script>