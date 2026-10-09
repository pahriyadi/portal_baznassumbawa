<!DOCTYPE HTML>
<html lang="id">

<head>
    <?php
    $_iden_ga = $this->model_utama->view_where('identitas', array('id_identitas' => 1))->row_array();
    if (!empty($_iden_ga['google_analytics'])) {
        echo $_iden_ga['google_analytics'] . "\n";
    }
    if (!empty($_iden_ga['google_adsense'])) {
        echo $_iden_ga['google_adsense'] . "\n";
    }
    ?>
    <title><?php echo $title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
    <meta name="theme-color" content="#006937">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="author" content="BAZNAS Sumbawa">
    <meta http-equiv="Content-Language" content="id-ID">
    <meta name="Distribution" CONTENT="Global">
    <meta name="Rating" CONTENT="General">
    <?php
    $current_uri = $this->uri->uri_string();
    if ($current_uri == "main" || empty($current_uri)) {
        $canonical_url = base_url();
    } else {
        $canonical_url = base_url($current_uri);
    }
    ?>
    <link rel="canonical" href="<?php echo $canonical_url; ?>" />

    <!-- Resource Hints for PageSpeed -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://www.gravatar.com">
    <link rel="dns-prefetch" href="https://connect.facebook.net">

    <?php
    // Ambil identitas website untuk og:site_name
    $_iden_og = $this->model_utama->view_where('identitas', array('id_identitas' => 1))->row_array();
    $_logo_og = $this->model_utama->view_ordering_limit('logo', 'id_logo', 'DESC', 0, 1)->row_array();

    $og_type = 'website';
    if ($this->uri->segment(1) == 'agenda' && $this->uri->segment(2) == 'detail') {
        // Agenda detail
        $rows = $this->model_utama->view_where('agenda', array('tema_seo' => $this->uri->segment(3)))->row_array();
        $gambar_url = 'asset/foto_agenda/' . ($rows['gambar'] ?? '');
        $content_url = 'agenda/detail/' . ($rows['tema_seo'] ?? '');
        $og_type = 'article';
    } else {
        $slug = $this->uri->segment(1);
        // Cek tabel berita dulu
        $rows = $this->model_utama->view_where('berita', array('judul_seo' => $slug))->row_array();
        if (!empty($rows)) {
            $gambar_url = 'asset/foto_berita/' . ($rows['gambar'] ?? '');
            $content_url = $slug;
            $og_type = 'article';
        } else {
            // Fallback: cek halamanstatis (URL bersih domain.id/judul)
            $rows = $this->model_utama->view_where('halamanstatis', array('judul_seo' => $slug))->row_array();
            $gambar_url = !empty($rows['gambar']) ? 'asset/foto_statis/' . $rows['gambar'] : '';
            $content_url = $slug;
        }
    }

    // Fallback logo jika tidak ada gambar
    if (empty($gambar_url) && !empty($_logo_og['gambar'])) {
        $gambar_url = 'asset/logo/' . $_logo_og['gambar'];
    }

    $_og_site_name = htmlspecialchars($_iden_og['nama_website'] ?? 'BAZNAS Sumbawa');
    $_og_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    ?>
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>" />
    <meta property="og:type" content="<?php echo $og_type; ?>" />
    <meta property="og:url" content="<?php echo $_og_url; ?>" />
    <meta property="og:image" content="<?php echo base_url($gambar_url); ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="<?php echo htmlspecialchars($title); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>" />
    <meta property="og:site_name" content="<?php echo $_og_site_name; ?>" />
    <meta property="og:locale" content="id_ID" />

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($title); ?>" />
    <meta name="twitter:description" content="<?php echo htmlspecialchars($description); ?>" />
    <meta name="twitter:image" content="<?php echo base_url($gambar_url); ?>" />
    <meta name="twitter:site" content="<?php echo $_og_site_name; ?>" />

    <link rel="shortcut icon" href="<?php echo base_url(); ?>asset/images/<?php echo favicon(); ?>" />
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url(); ?>asset/images/pwa-icon-192.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>asset/images/pwa-icon-192.png" />
    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>asset/images/apple-touch-icon.png" />
    <link rel="manifest" href="<?php echo base_url(); ?>manifest.json" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="BAZNAS Sumbawa" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="rss.xml" />
    <link type="text/css" rel="stylesheet"
        href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/reset.css" />
    <link type="text/css" rel="stylesheet"
        href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/main-stylesheet.css" />
    <link type="text/css" rel="stylesheet"
        href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/shortcode.css" />
    <link type="text/css" rel="stylesheet"
        href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/fonts.css" />
    <link type="text/css" rel="stylesheet"
        href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/responsive.css" />
    <link type="text/css" rel="stylesheet"
        href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/style.css">
    <link type="text/css" rel="stylesheet"
        href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/ideaboxWeather.css">
    <link type="text/css" rel="stylesheet"
        href="<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/slide.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/lightbox/lightbox.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript"
        src="<?php echo base_url(); ?>template/<?php echo template(); ?>/jscript/jquery-3.2.1.min.js"></script>
    <script type="text/javascript"
        src="<?php echo base_url(); ?>template/<?php echo template(); ?>/jscript/jquery-latest.min.js"></script>
    <script type="text/javascript"
        src="<?php echo base_url(); ?>template/<?php echo template(); ?>/jscript/theme-scripts.js"></script>
    <?php if ($this->uri->segment(1) == 'main' OR $this->uri->segment(1) == '') { ?>
        <script type="text/javascript"
            src="<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/js/jssor.slider-23.1.0.mini.js"></script>
        <script type="text/javascript"
            src="<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/js/slide.js"></script>
        <script type="text/javascript"
            src="<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/js/slide_baznas.js"></script>
    <?php } ?>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --baznas-green: #006937;
            --baznas-yellow: #F4C10F;
            --baznas-dark: #333333;
            --baznas-light: #fcfcfc;
            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.05);
            --radius-lg: 0px;
        }

        body,
        .boxed,
        .active.boxed {
            font-family: 'Inter', sans-serif;
            color: var(--baznas-dark);
            background-color: #ffffff !important;
            background-image: none !important;
            overflow-x: clip;
            /* Prevent horizontal scroll while supporting position: sticky */
        }

        @media (min-width: 992px) {
            .wrapper,
            .main-content,
            .main-page,
            .double-block,
            .content-block,
            .main-sidebar {
                overflow: visible !important;
            }

            .sticky-sidebar-desktop {
                position: -webkit-sticky !important;
                position: sticky !important;
                top: 90px !important;
                z-index: 10;
            }

            .slider-outer-wrap {
                margin-bottom: 15px !important;
            }
        }

        @media (max-width: 991px) {
            .slider-outer-wrap {
                margin-bottom: 2px !important;
            }
        }

        /* Essential Responsive Fixes for Legacy Template */
        @media (max-width: 1200px) {

            .wrapper,
            .boxed,
            .active.boxed,
            #header,
            .main-menu {
                width: 100% !important;
                min-width: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .boxed {
                overflow-x: hidden;
            }
        }

        .modern-widget {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 30px;
            margin-bottom: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
            position: relative;
        }

        /* Content structure classes */
        .widget-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--baznas-yellow);
            padding-bottom: 15px;
        }

        .widget-footer-modern {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .widget-footer-modern.center {
            justify-content: center;
        }

        .widget-header-modern h3 {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            font-size: 20px;
            font-weight: 800;
            color: var(--baznas-green);
        }

        .widget-header-modern .more-pill {
            margin: 0 !important;
        }

        .modern-widget h3 {
            font-size: 20px;
            font-weight: 800;
            color: var(--baznas-green);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid var(--baznas-yellow);
            display: inline-block;
        }

        .modern-article-list {
            display: flex;
            flex-direction: column;
            gap: 0px;
            /* Use padding only to avoid double spacing */
        }

        /* Handler for Word-Limited Titles */
        .judul-mobile {
            display: none;
        }

        @media (max-width: 991px) {

            .judul-desktop {
                display: none !important;
            }

            .judul-mobile {
                display: inline !important;
            }
        }

        .modern-article-item {
            display: flex;
            gap: 12px;
            padding: 12px 10px;
            /* Dikurangi secara drastis */
            border-radius: 0px;
            border-bottom: 1px dashed #eee;
            /* Pembatas titik-titik yang efisien */
            transition: all 0.2s ease;
            text-decoration: none;
            margin-bottom: 0px;
            /* Dibuang untuk hindari margin gabungan */
        }

        .modern-article-item:hover {
            background: #f9f9f9;
            border-color: #eee;
        }

        .modern-article-item:hover .ma-content h4 {
            color: var(--baznas-green);
        }

        .modern-article-item.mini {
            padding: 10px;
            gap: 15px;
            align-items: center;
        }

        .ma-thumb-lg {
            width: 220px;
            height: 140px;
            border-radius: 0;
            /* Menghilangkan rounded */
            object-fit: cover;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .ma-thumb-sm {
            width: 90px;
            height: 70px;
            border-radius: 0;
            /* Menghilangkan rounded */
            object-fit: cover;
        }

        /* .ma-thumb = ukuran standar sidebar (kiri, kanan, halaman) */
        .ma-thumb {
            width: 90px;
            height: 70px;
            border-radius: 0;
            /* Menghilangkan rounded */
            object-fit: cover;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        /* Kontainer teks di sebelah thumbnail */
        .ma-content {
            flex: 1;
            min-width: 0;
        }

        .ma-content h4 {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;
            margin: 0 0 4px 0;
        }

        .ma-content h4 a {
            text-decoration: none;
            color: #333;
            transition: color 0.2s;
        }

        .ma-content h4 a:hover {
            color: var(--baznas-green);
        }

        /* Meta info: tanggal, view count */
        .ma-meta {
            font-size: 10px;
            color: #999;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 4px;
        }

        .ma-meta span {
            white-space: nowrap;
        }

        .modern-article-item:hover .ma-thumb-lg {
            transform: scale(1.03);
        }

        .ma-img-wrapper {
            flex-shrink: 0;
            overflow: hidden;
            border-radius: 0px;
        }

        .cat-pill {
            font-size: 11px;
            color: var(--baznas-green);
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .ma-excerpt {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Grid Card Layouts */
        .modern-grid-card {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .vid-card-item,
        .cat-card-modern {
            text-decoration: none;
            display: block;
        }

        .vid-thumb-wrapper,
        .cat-card-img {
            position: relative;
            height: 140px;
            border-radius: 0px;
            overflow: hidden;
            margin-bottom: 12px;
            background: #000;
        }

        .vid-thumb-wrapper img,
        .cat-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.4s ease;
        }

        .vid-card-item:hover img,
        .cat-card-modern:hover img {
            transform: scale(1.1);
            opacity: 0.8;
        }

        .play-btn-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 45px;
            height: 45px;
            background: rgba(227, 46, 46, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            pointer-events: none;
        }

        .play-btn-overlay svg {
            fill: white;
            width: 24px;
            height: 24px;
            margin-left: 3px;
        }

        .vid-title,
        .cat-card-title {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
            color: #333;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .vid-title:hover,
        .cat-card-title a:hover {
            color: var(--baznas-green);
        }

        /* Real Video Spotlight Cinema Box */
        .spotlight-video-box {
            background: #0d1b14;
            border-radius: 0px !important;
            margin-bottom: 24px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(0, 105, 55, 0.2);
            overflow: hidden;
        }

        .spotlight-player-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #000;
        }

        .spotlight-player-wrapper iframe,
        .spotlight-player-wrapper video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
        }

        .spotlight-info-bar {
            background: var(--baznas-green);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            border-left: 5px solid var(--baznas-yellow);
        }

        .spotlight-title {
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            line-height: 1.4;
        }

        .spotlight-action-btn {
            background: var(--baznas-yellow);
            color: var(--baznas-green);
            font-size: 12px;
            font-weight: 700;
            padding: 8px 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 0px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }

        .spotlight-action-btn:hover {
            background: #ffffff;
            color: var(--baznas-green);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        /* Active state for playlist cards */
        .vid-card-item {
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .vid-card-item.active-playing {
            outline: 3px solid var(--baznas-green);
            outline-offset: -3px;
            background: rgba(0, 105, 55, 0.05);
        }

        .vid-card-item.active-playing .play-btn-overlay {
            background: var(--baznas-green) !important;
        }

        .playing-badge {
            display: none;
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--baznas-yellow);
            color: var(--baznas-green);
            font-size: 10px;
            font-weight: 800;
            padding: 3px 8px;
            text-transform: uppercase;
            z-index: 5;
            letter-spacing: 0.5px;
        }

        .vid-card-item.active-playing .playing-badge {
            display: inline-block;
        }

        /* BAZNAS Shorts / Reels Section (Horizontal Scrollable Track / Tab Menyamping) */
        .baznas-shorts-container {
            margin-bottom: 30px;
        }

        .shorts-track-controls {
            display: flex;
            gap: 6px;
        }

        .shorts-nav-pill {
            background: var(--baznas-green);
            color: #fff;
            border: 1px solid var(--baznas-yellow);
            width: 32px;
            height: 32px;
            border-radius: 0px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .shorts-nav-pill:hover {
            background: var(--baznas-yellow);
            color: var(--baznas-green);
        }

        .baznas-shorts-track {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            padding-bottom: 12px;
            -webkit-overflow-scrolling: touch;
        }

        .baznas-shorts-track::-webkit-scrollbar {
            height: 6px;
        }

        .baznas-shorts-track::-webkit-scrollbar-track {
            background: rgba(0, 105, 55, 0.1);
        }

        .baznas-shorts-track::-webkit-scrollbar-thumb {
            background: var(--baznas-green);
            border-radius: 0px !important;
        }

        .shorts-card-item {
            position: relative;
            flex: 0 0 165px;
            width: 165px;
            aspect-ratio: 9 / 16;
            background: #0d1b14;
            border-radius: 0px !important;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid rgba(0, 105, 55, 0.3);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            scroll-snap-align: start;
            display: block;
        }

        @media (max-width: 600px) {
            .shorts-card-item {
                flex: 0 0 140px;
                width: 140px;
            }
        }

        .shorts-card-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 105, 55, 0.25);
            border-color: var(--baznas-yellow);
        }

        .shorts-card-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .shorts-card-item:hover .shorts-card-thumb {
            transform: scale(1.08);
        }

        .shorts-top-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.75);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 0px !important;
            border-left: 3px solid #ff0050;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.5px;
        }

        .shorts-overlay-gradient {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 40px 12px 12px 12px;
            background: linear-gradient(to top, rgba(0,0,0,0.92) 10%, rgba(0,0,0,0.5) 60%, transparent 100%);
            z-index: 2;
            color: #fff;
        }

        .shorts-views-count {
            font-size: 11px;
            color: var(--baznas-yellow);
            font-weight: 700;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .shorts-card-title {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
            color: #fff;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-shadow: 0 1px 3px rgba(0,0,0,0.8);
        }

        .shorts-play-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 48px;
            height: 48px;
            background: rgba(0, 105, 55, 0.85);
            border: 2px solid var(--baznas-yellow);
            border-radius: 0px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        .shorts-card-item:hover .shorts-play-center {
            background: #ff0050;
            border-color: #fff;
            transform: translate(-50%, -50%) scale(1.1);
        }

        .shorts-play-center svg {
            fill: #fff;
            width: 22px;
            height: 22px;
            margin-left: 2px;
        }

        /* TikTok / YouTube Shorts Fullscreen Modal Viewer */
        .shorts-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.94);
            backdrop-filter: blur(10px);
            z-index: 9999999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .shorts-modal-overlay.active {
            display: flex !important;
        }

        .shorts-phone-frame {
            position: relative;
            width: 380px;
            max-width: 92vw;
            height: 82vh;
            max-height: 680px;
            background: #000;
            border: 2px solid var(--baznas-green);
            box-shadow: 0 0 50px rgba(0, 105, 55, 0.4), 0 0 100px rgba(0,0,0,0.8);
            border-radius: 0px !important;
            display: flex;
            flex-direction: column;
            overflow: visible;
        }

        .shorts-player-container {
            width: 100%;
            height: 100%;
            position: relative;
            background: #050505;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .shorts-player-container iframe,
        .shorts-player-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: none;
        }

        /* TikTok style overlay info inside phone frame */
        .shorts-modal-bottom-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 65px;
            padding: 30px 16px 16px 16px;
            background: linear-gradient(to top, rgba(0,0,0,0.95) 10%, rgba(0,0,0,0.6) 60%, transparent 100%);
            z-index: 10;
            color: #fff;
            pointer-events: none;
        }

        .shorts-author-tag {
            font-size: 13px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .verified-badge-icon {
            color: #20d5ec;
            font-size: 14px;
        }

        .shorts-modal-title {
            font-size: 14px;
            line-height: 1.4;
            margin-bottom: 10px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.8);
        }

        .shorts-music-ticker {
            font-size: 12px;
            color: #ddd;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Side Action Bar inside phone frame */
        .shorts-side-actions {
            position: absolute;
            bottom: 25px;
            right: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
            z-index: 15;
        }

        .shorts-action-btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 0px !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0;
        }

        .shorts-action-btn:hover {
            background: var(--baznas-green);
            border-color: var(--baznas-yellow);
            transform: scale(1.1);
        }

        .shorts-action-btn.liked {
            background: #ff0050 !important;
            border-color: #ff0050 !important;
        }

        .shorts-action-label {
            font-size: 10px;
            font-weight: 700;
            margin-top: 2px;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.8);
        }

        /* Top Bar inside phone frame */
        .shorts-modal-topbar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 14px 16px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.85) 0%, transparent 100%);
            z-index: 20;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .shorts-topbar-title {
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .shorts-close-btn {
            background: rgba(227, 46, 46, 0.9);
            color: #fff;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 0px !important;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .shorts-close-btn:hover {
            background: #ff0000;
            transform: scale(1.1);
        }

        /* Nav Arrows outside/inside frame */
        .shorts-nav-arrows {
            position: absolute;
            right: -65px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 30;
        }

        @media (max-width: 650px) {
            .shorts-nav-arrows {
                right: 12px;
                top: 65px;
                transform: none;
                flex-direction: row;
                gap: 10px;
            }
        }

        .nav-arrow-btn {
            width: 44px;
            height: 44px;
            background: var(--baznas-green);
            border: 2px solid var(--baznas-yellow);
            color: #fff;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 0px !important;
            transition: all 0.2s ease;
        }

        .nav-arrow-btn:hover {
            background: var(--baznas-yellow);
            color: var(--baznas-green);
        }

        /* Gallery Cards */
        .gallery-item-card {
            position: relative;
            height: 160px;
            border-radius: 0px;
            overflow: hidden;
            display: block;
        }

        .gallery-item-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .gallery-item-card:hover img {
            transform: scale(1.15);
        }

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.85));
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }


        /* Ad Grid Styles */
        .ad-card-item {
            background: #fff;
            padding: 10px;
            border-radius: 0px;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .ad-card-item:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .ad-img-modern {
            width: 100%;
            height: auto;
            border-radius: 0px;
            display: block;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .ad-row {
            margin: 30px auto !important;
            max-width: 1200px;
            clear: both;
        }


        @media (max-width: 600px) {

            .modern-grid-card,
            .modern-category-grid {
                grid-template-columns: 1fr;
            }

            .ma-thumb-lg {
                width: 120px;
                height: 90px;
            }

            .modern-article-item {
                gap: 15px;
                padding: 12px;
                background: #fff;
                margin-bottom: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            }

        }

        /* Global Responsive Archive Grid */
        .archive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .archive-item {
            background: #fff;
            border-radius: 0px;
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

        .archive-meta {
            font-size: 11px;
            color: #999;
            margin-top: auto;
        }

        .more-pill {
            display: inline-block;
            background: var(--baznas-yellow);
            color: var(--baznas-dark) !important;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.2s;
            margin-top: 15px;
            box-shadow: 0 4px 10px rgba(244, 193, 15, 0.3);
        }

        .more-pill:hover {
            background: #d4a70c;
            color: #000 !important;
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(244, 193, 15, 0.5);
        }

        .modern-photo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .photo-grid-item {
            position: relative;
            border-radius: 0px;
            overflow: hidden;
            aspect-ratio: 1;
            background: #000;
            display: block;
        }

        .photo-grid-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.85;
            transition: all 0.4s;
        }

        .photo-grid-item:hover img {
            transform: scale(1.15);
            opacity: 1;
        }

        .photo-grid-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: #fff;
            font-size: 11px;
            font-weight: 600;
        }

        .modern-tag-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .modern-tag-badge {
            background: #fff;
            border: 1px solid #eee;
            color: #555;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }

        .modern-tag-badge:hover {
            background: var(--baznas-green);
            color: #fff !important;
            border-color: var(--baznas-green);
        }

        /* Gambar Responsif dalam Artikel Berita */
        .article-body-modern img {
            max-width: 100% !important;
            height: auto !important;
        }

        /* Sticky Payment Button */
        .sticky-payment-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            display: flex;
            align-items: center;
            background: var(--baznas-green);
            color: white !important;
            padding: 14px 28px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(0, 105, 55, 0.4);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-weight: 700;
            border: 2px solid var(--baznas-yellow);
        }

        .sticky-payment-button:hover {
            transform: scale(1.1) translateY(-5px);
            background: #00562d;
            box-shadow: 0 15px 35px rgba(0, 105, 55, 0.6);
        }

        .sticky-payment-button i {
            font-size: 20px;
            margin-right: 12px;
            color: var(--baznas-yellow);
        }

        .pulse-animation-green::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 50px;
            border: 2px solid var(--baznas-green);
            opacity: 0.8;
            pointer-events: none;
            z-index: -1;
            animation: pulse-green-glow-opt 2s infinite;
        }

        @keyframes pulse-green-glow-opt {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }
            70% {
                transform: scale(1.12, 1.28);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .sticky-payment-button {
                bottom: 20px;
                right: 20px;
                padding: 10px 20px;
                font-size: 13px;
            }

            .sticky-payment-button i {
                font-size: 16px;
                margin-right: 8px;
            }
        }

        /* --- RESPONSIVE OPTIMIZATION --- */

        /* Layar Tablet & HP Umum */
        @media (max-width: 991px) {
            .double-block {
                display: flex;
                flex-direction: column;
            }

            .double-block .main.right,
            .double-block .left {
                width: 100% !important;
                float: none !important;
                margin-left: 0;
                margin-right: 0;
                padding: 0;
            }

            .main-sidebar.right {
                display: none;
            }

            /* Sidebar kanan bawah biasanya memenuhi layar di Tablet */
        }

        /* Layar HP (Fokus Utama Perbaikan USER) */
        @media (max-width: 767px) {
            .header {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 9999;
                background: #fff;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);

            /* --- FINAL UNIVERSAL MOBILE THUMBNAIL FIX (V4 - Aggressive) --- */
                /* Memastikan semua gambar di daftar berita (Halaman Beranda, Indeks, Kategori, Rekomendasi) seragam di HP */
                .modern-article-item img,
                .archive-item img,
                .rec-item-modern img,
                .vid-card-item img,
                .ma-thumb,
                .ma-thumb-sm,
                .ma-thumb-lg,
                .ma-img-wrapper img,
                .archive-thumb img,
                .rec-thumb-modern,
                .vid-thumb-wrapper img,
                .modern-article-item>a>img {
                    width: 100% !important;
                    height: 135px !important;
                    max-height: 135px !important;
                    min-height: 135px !important;
                    object-fit: cover !important;
                    display: block !important;
                    flex-shrink: 0 !important;
                    border-radius: 0 !important;
                }

                .ma-img-wrapper,
                .archive-thumb,
                .rec-thumb-modern,
                .vid-thumb-wrapper,
                .rec-item-modern picture,
                .modern-article-item>a {
                    width: 100% !important;
                    height: 135px !important;
                    display: block !important;
                    overflow: hidden !important;
                    background: #eeeeee !important;
                    border-radius: 0 !important;
                    flex-shrink: 0 !important;
                }

                .ma-img-wrapper,
                .archive-thumb,
                .rec-thumb-modern,
                .vid-thumb-wrapper,
                .rec-item-modern picture {
                    width: 100% !important;
                    height: 135px !important;
                    display: block !important;
                    overflow: hidden !important;
                    background: #eeeeee !important;
                    border-radius: 0 !important;
                }

                .ma-img-wrapper,
                .archive-thumb {
                    width: 100% !important;
                    height: 120px !important;
                    display: block !important;
                    background: #f5f5f5 !important;
                }
            }

            .wrapper {
                width: 100% !important;
                padding: 0 !important;
                overflow-x: hidden;
            }

            .content {
                padding: 0 !important;
                background: #fff;
                margin-top: 80px;
                /* Jarak aman slider dari fixed header */
            }

            /* Gap antara slider dan konten di bawahnya */
            .slider-outer-wrap {
                margin-bottom: 30px;
            }

            .header-logo {
                text-align: center;
                width: 100%;
                margin: 15px 0;
            }

            .header-logo img {
                max-height: 45px;
            }

            .header-menu,
            .header-addons,
            .secondary-menu {
                display: none !important;
            }

            .main-menu.sticky {
                display: none !important;
            }

            /* Tema Warna untuk Menu Mobile (.themenumobile) */
            .themenumobile {
                background-color: var(--baznas-green) !important;
                color: #ffffff !important;
            }

            .themenumobile strong {
                background-color: #00562d !important;
                /* Warna lebih gelap untuk header menu */
                color: #ffffff !important;
                padding: 15px;
                font-size: 16px;
            }

            .themenumobile ul li a {
                color: #ffffff !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            }

            .themenumobile ul li a:hover {
                background-color: rgba(255, 255, 255, 0.1) !important;
            }



            /* Optimasi Navigasi Mobile */

            /* --- OPTIMASI KHUSUS LAYAR HP (Mobile Only) --- */
            @media (max-width: 991px) {

                #MOBILE_NAV_UI,
                .mobile-ui-wrapper {
                    display: block !important;
                }

                /* Kontainer 480px di HP */
                .boxed {
                    max-width: 480px !important;
                    margin: 0 auto !important;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                    min-height: 100vh;
                    background: #fff;
                    position: relative;
                    padding-top: 0 !important;
                    /* Menghilangkan gap di atas header */
                }

                /* Paksa elemen legacy mengikuti kontainer dan TIDAK STICKY */
                .wrapper,
                #header,
                .header,
                .main-menu,
                .sticky,
                .content {
                    width: 100% !important;
                    min-width: 0 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    float: none !important;
                    position: relative !important;
                    /* Paksa agar ikut tergulir */
                    top: auto !important;
                    transform: none !important;
                }

                /* Sembunyikan elemen desktop yang tidak perlu di HP */
                .main-sidebar,
                .secondary-menu,
                .header-addons,
                #sidebar,
                .sidebar {
                    display: none !important;
                }

                #MOBILE_NAV_UI {
                    display: block !important;
                }

                /* Tampilkan Sticky Button Hanya di Desktop */
                .desktop-only-btn {
                    display: flex !important;
                    position: fixed !important;
                    bottom: 40px !important;
                    right: 40px !important;
                    background: var(--baznas-green) !important;
                    color: #fff !important;
                    padding: 15px 25px !important;
                    border-radius: 50px !important;
                    z-index: 99999 !important;
                    text-decoration: none !important;
                    font-weight: 800 !important;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
                    align-items: center !important;
                    gap: 12px !important;
                    transition: all 0.3s ease !important;
                }

                .desktop-only-btn:hover {
                    transform: translateY(-5px) !important;
                    background: #00562d !important;
                }

                .desktop-only-btn i {
                    font-size: 20px !important;
                }

                /* Bottom Navigation Bar Modern */
                .bottom-nav {
                    display: flex !important;
                    position: fixed;
                    bottom: 0;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 100%;
                    max-width: 480px;
                    height: 65px;
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(10px);
                    justify-content: space-around;
                    align-items: center;
                    border-top: 1px solid #eee;
                    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
                    z-index: 99999;
                }

                .nav-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-decoration: none;
                    color: #888;
                    flex: 1;
                }

                .nav-item i {
                    font-size: 20px;
                    margin-bottom: 4px;
                }

                .nav-item span {
                    font-size: 10px;
                    font-weight: 600;
                }

                .nav-item.active {
                    color: var(--baznas-green);
                }

                /* Tombol Bayar Zakat Baru (Teks Luar) */
                .nav-special-cta {
                    display: flex !important;
                    flex-direction: column !important;
                    align-items: center !important;
                    justify-content: flex-end !important;
                    text-decoration: none !important;
                    flex: none !important;
                    width: 90px !important;
                    position: relative !important;
                    margin: 0 5px !important;
                }

                .nav-circle-red {
                    background: #ff3b30 !important;
                    border-radius: 50% !important;
                    width: 65px !important;
                    height: 65px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    position: absolute !important;
                    top: -20px !important;
                    /* Melayang sedikit */
                    box-shadow: 0 6px 15px rgba(255, 59, 48, 0.4) !important;
                    border: 4px solid #ffffff !important;
                    z-index: 2 !important;
                    box-sizing: border-box !important;
                }

                .icon-hand {
                    font-size: 28px !important;
                    color: rgba(255, 255, 255, 0.9) !important;
                    position: relative !important;
                }

                .icon-heart {
                    font-size: 14px !important;
                    color: #fff !important;
                    position: absolute !important;
                    top: 22px !important;
                    left: 50% !important;
                    transform: translateX(-50%) !important;
                    text-shadow: 0 0 5px rgba(0, 0, 0, 0.2) !important;
                }

                .nav-label-outside {
                    font-size: 10px !important;
                    font-weight: 800 !important;
                    color: #e74c3c !important;
                    /* Warna merah agar senada */
                    text-transform: uppercase !important;
                    margin-top: 45px !important;
                    /* Jarak agar teks berada di bawah lingkaran */
                    white-space: nowrap !important;
                    line-height: 1 !important;
                }

                .nav-special-cta.active .nav-label-outside {
                    color: #c0392b !important;
                }

                .nav-special-cta:active .nav-circle-red {
                    transform: scale(0.9) !important;
                }

                .modern-widget {
                    padding: 15px !important;
                    margin-bottom: 20px !important;
                    border-radius: 0 !important;
                    /* Menghilangkan rounded */
                    width: 100% !important;
                    box-sizing: border-box !important;
                }

                .widget-header-modern {
                    margin-bottom: 15px !important;
                    padding-bottom: 10px !important;
                }

                /* Standarisasi Padding Kontainer Utama - Mentok Pinggir HP */
                .main-page,
                .content-block,
                .block,
                .block-content {
                    padding: 0 !important;
                    margin: 0 !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                    border: none !important;
                }

                /* Daftar Berita Gaya Grid 2 Kolom di HP */
                .modern-article-list {
                    display: grid !important;
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 12px !important;
                    padding: 0 !important;
                    margin: 0 !important;
                }

                .modern-article-item {
                    display: flex !important;
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0 !important;
                    padding: 0 !important;
                    border-radius: 0 !important;
                    /* Menghilangkan rounded */
                    background: #ffffff !important;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
                    overflow: hidden !important;
                    border: 1px solid #f0f0f0 !important;
                    height: 100% !important;
                }

                .ma-img-wrapper {
                    width: 100% !important;
                    height: 110px !important;
                    flex-shrink: 0 !important;
                    border-radius: 0 !important;
                    overflow: hidden !important;
                }

                .ma-img-wrapper img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                }

                .ma-content {
                    padding: 12px !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                }

                .ma-content h4 {
                    font-size: 13px !important;
                    font-weight: 700 !important;
                    line-height: 1.4 !important;
                    margin-bottom: 8px !important;
                    display: -webkit-box !important;
                    -webkit-line-clamp: 3 !important;
                    -webkit-box-orient: vertical !important;
                    overflow: hidden !important;
                    color: #1a1a1a !important;
                }

                .ma-meta {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    gap: 8px !important;
                    font-size: 10px !important;
                    color: #888 !important;
                }

                /* Berita pertama (Hero) tetap buat full width */
                .modern-article-list .modern-article-item:first-child {
                    grid-column: span 2 !important;
                    border-radius: 0 !important;
                    /* Menghilangkan rounded */
                }

                .modern-article-list .modern-article-item:first-child .ma-img-wrapper {
                    height: 180px !important;
                }

                .modern-article-list .modern-article-item:first-child .ma-content {
                    padding: 15px !important;
                }

                .modern-article-list .modern-article-item:first-child .ma-content h4 {
                    font-size: 16px !important;
                }

                /* Penyesuaian Footer & Sticky Button */
                footer,
                .footer {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                    padding-bottom: 0 !important;
                    /* Hapus padding di sini karena sudah ada di footer.php */
                    width: 100% !important;
                    max-width: 480px !important;
                    margin: 0 auto !important;
                    background: var(--baznas-green) !important;
                }

                /* Sembunyikan Sticky Button di HP karena sudah ada di Nav Bar */
                .sticky-payment-button {
                    display: none !important;
                }

                /* WhatsApp Floating dengan Teks Dinamis - New Stable Version */
                .mobile-wa-btn {
                    display: flex !important;
                    position: fixed !important;
                    bottom: 85px !important;
                    right: 12px !important;
                    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%) !important;
                    color: #ffffff !important;
                    padding: 0 12px !important;
                    border-radius: 50px !important;
                    z-index: 999999 !important;
                    text-decoration: none !important;
                    box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4) !important;
                    align-items: center !important;
                    gap: 8px !important;
                    font-weight: 700 !important;
                    border: 1px solid rgba(255, 255, 255, 0.3) !important;
                    width: 150px !important;
                    height: 42px !important;
                }

                .wa-text-slider {
                    height: 20px !important;
                    overflow: hidden !important;
                    flex: 1 !important;
                    position: relative !important;
                }

                .wa-text-slider-inner {
                    display: flex !important;
                    flex-direction: column !important;
                    -webkit-animation: waVerticalScroll 9s infinite !important;
                    animation: waVerticalScroll 9s infinite !important;
                }

                .slide-item {
                    height: 20px !important;
                    line-height: 20px !important;
                    font-size: 11px !important;
                    text-transform: uppercase !important;
                    white-space: nowrap !important;
                    display: block !important;
                    color: #ffffff !important;
                    opacity: 1 !important;
                }

                @-webkit-keyframes waVerticalScroll {

                    0%,
                    25% {
                        -webkit-transform: translateY(0);
                    }

                    30%,
                    55% {
                        -webkit-transform: translateY(-20px);
                    }

                    60%,
                    85% {
                        -webkit-transform: translateY(-40px);
                    }

                    90%,
                    100% {
                        -webkit-transform: translateY(-60px);
                    }
                }

                @keyframes waVerticalScroll {

                    0%,
                    25% {
                        transform: translateY(0);
                    }

                    30%,
                    55% {
                        transform: translateY(-20px);
                    }

                    60%,
                    85% {
                        transform: translateY(-40px);
                    }

                    90%,
                    100% {
                        transform: translateY(-60px);
                    }
                }

                .mobile-wa-btn i {
                    font-size: 24px !important;
                    color: #fff !important;
                }

                /* Grid Video 2 Kolom di HP */
                .modern-grid-card {
                    display: grid !important;
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 15px !important;
                }

                .vid-card-item {
                    margin-bottom: 0 !important;
                }

                .vid-thumb-wrapper {
                    height: 100px !important;
                    overflow: hidden;
                    border-radius: 0px !important;
                }

                .vid-thumb-wrapper img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .vid-title {
                    font-size: 12px !important;
                    line-height: 1.3 !important;
                    margin-top: 8px !important;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }

                /* Perombakan Menu Mobile - Edisi Final & Rapih (Kotak / Sharp Edge / Clean Vertical List) */
                .modern-mobile-menu {
                    background: #ffffff !important;
                    width: 310px !important;
                    max-width: 85vw !important;
                    height: 100% !important;
                    left: -100% !important;
                    top: 0 !important;
                    transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
                    padding: 0 !important;
                    box-sizing: border-box !important;
                    display: block !important;
                    z-index: 999999 !important;
                    overflow-y: auto !important;
                    position: fixed !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                    box-shadow: 5px 0 30px rgba(0, 0, 0, 0.25) !important;
                    border-radius: 0px !important;
                }

                body.menu-active .modern-mobile-menu {
                    left: 0 !important;
                }

                /* Header Menu yang Elegan & Kotak */
                .mobile-menu-header {
                    display: flex !important;
                    align-items: center;
                    justify-content: space-between;
                    height: 65px;
                    background: var(--baznas-green);
                    padding: 0 20px;
                    color: #fff;
                    border-bottom: 3px solid var(--baznas-yellow);
                    position: sticky;
                    top: 0;
                    z-index: 10;
                    border-radius: 0px !important;
                }

                .mm-brand {
                    font-size: 16px;
                    font-weight: 800;
                    letter-spacing: 1px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    text-transform: uppercase;
                }

                .mm-close-btn {
                    width: 36px;
                    height: 36px;
                    background: rgba(255, 255, 255, 0.15);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff !important;
                    text-decoration: none !important;
                    font-size: 18px;
                    border-radius: 0px !important;
                    transition: all 0.2s;
                }

                .mm-close-btn:hover {
                    background: var(--baznas-yellow);
                    color: var(--baznas-dark) !important;
                }

                .modern-mobile-menu ul,
                .mobile-menu-container ul {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 0 !important;
                    padding: 0 0 100px 0 !important;
                    margin: 0 !important;
                    list-style: none !important;
                }

                .modern-mobile-menu ul li,
                .mobile-menu-container ul li {
                    margin: 0 !important;
                    padding: 0 !important;
                    display: block !important;
                    width: 100% !important;
                    border-bottom: 1px solid #f0f0f0 !important;
                }

                .modern-mobile-menu ul li a,
                .mobile-menu-container ul li a {
                    display: flex !important;
                    flex-direction: row !important;
                    align-items: center !important;
                    justify-content: flex-start !important;
                    background: #ffffff !important;
                    padding: 15px 20px !important;
                    border-radius: 0px !important;
                    border: none !important;
                    text-align: left !important;
                    font-size: 14px !important;
                    font-weight: 600 !important;
                    color: #222222 !important;
                    min-height: auto !important;
                    text-decoration: none !important;
                    transition: all 0.2s ease !important;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                .modern-mobile-menu ul li a:hover,
                .modern-mobile-menu ul li a:active {
                    background: #f8f9fa !important;
                    color: var(--baznas-green) !important;
                    padding-left: 26px !important;
                    border-left: 4px solid var(--baznas-green) !important;
                }

                /* Ikon Pintar di Kiri Teks */
                .modern-mobile-menu ul li a i.menu-icon {
                    display: inline-block;
                    width: 28px !important;
                    font-size: 18px !important;
                    margin-right: 14px !important;
                    margin-bottom: 0 !important;
                    text-align: center !important;
                    color: var(--baznas-green) !important;
                    flex-shrink: 0 !important;
                    opacity: 1;
                }

                /* Sub-menu: Tertata rapi di bawah menu utama */
                .modern-mobile-menu ul li ul,
                .mobile-menu-container ul li ul {
                    display: block !important;
                    flex-direction: column !important;
                    gap: 0 !important;
                    padding: 0 !important;
                    border: none !important;
                    border-left: 3px solid var(--baznas-yellow) !important;
                    margin: 0 !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                    position: static !important;
                    width: 100% !important;
                    background: #f9fbf9 !important;
                }

                .modern-mobile-menu ul li ul li,
                .mobile-menu-container ul li ul li {
                    display: block !important;
                    width: 100% !important;
                    border-bottom: 1px dashed #eaeaea !important;
                }

                .modern-mobile-menu ul li ul li:last-child,
                .mobile-menu-container ul li ul li:last-child {
                    border-bottom: none !important;
                }

                .modern-mobile-menu ul li ul li a,
                .mobile-menu-container ul li ul li a {
                    background: transparent !important;
                    border: none !important;
                    min-height: auto !important;
                    padding: 11px 18px 11px 20px !important;
                    font-size: 13px !important;
                    font-weight: 500 !important;
                    color: #444 !important;
                    text-align: left !important;
                    display: flex !important;
                    align-items: center !important;
                    flex-direction: row !important;
                    border-radius: 0px !important;
                    text-transform: none !important;
                    letter-spacing: normal !important;
                    transition: all 0.2s ease !important;
                }

                .modern-mobile-menu ul li ul li a:hover {
                    background: #f0f5f1 !important;
                    color: var(--baznas-green) !important;
                    padding-left: 25px !important;
                }

                /* Hapus pseudo before lawas agar tidak bertumpuk */
                .modern-mobile-menu ul li ul li a:before,
                .mobile-menu-container ul li ul li a:before {
                    display: none !important;
                }

                /* Desain Kotak Ikon Sub-Menu (Sleek Badge) */
                .modern-mobile-menu ul li ul li a i.submenu-icon {
                    display: inline-flex !important;
                    align-items: center;
                    justify-content: center;
                    width: 26px !important;
                    height: 26px !important;
                    background: rgba(0, 105, 55, 0.08) !important;
                    color: var(--baznas-green) !important;
                    margin-right: 12px !important;
                    margin-bottom: 0 !important;
                    font-size: 12px !important;
                    border-radius: 0px !important;
                    flex-shrink: 0 !important;
                    transition: all 0.2s ease !important;
                }

                .modern-mobile-menu ul li ul li a:hover i.submenu-icon {
                    background: var(--baznas-green) !important;
                    color: #ffffff !important;
                    transform: scale(1.08);
                }

                /* Tombol Close Overlay gelap */
                .escape-mobile-menu {
                    display: none;
                }

                body.menu-active .escape-mobile-menu {
                    display: block !important;
                    background: rgba(0, 0, 0, 0.7) !important;
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100% !important;
                    height: 100% !important;
                    z-index: 999998 !important;
                }

                /* Matikan efek geser layout lama agar tidak bentrok */
                body.menu-active .boxed {
                    margin: 0 auto !important;
                    transform: none !important;
                    left: 0 !important;
                }

                /* Sembunyikan toggle hamburger lama di header dengan sangat agresif */
                .mobile-menu,
                .mobile-menu.icon-text,
                a.mobile-menu,
                .header .mobile-menu {
                    display: none !important;
                    visibility: hidden !important;
                    opacity: 0 !important;
                    pointer-events: none !important;
                }
            }

            /* FINAL KILL SWITCH: Hanya sembunyikan di layar lebar (PC/Desktop) */
            @media (min-width: 992px) {

                #MOBILE_NAV_UI,
                .mobile-ui-wrapper,
                .bottom-nav,
                .modern-mobile-menu,
                .escape-mobile-menu,
                .themenumobile,
                .mobile-menu {
                    display: none !important;
                    visibility: hidden !important;
                    height: 0 !important;
                    max-height: 0 !important;
                    overflow: hidden !important;
                    opacity: 0 !important;
                    pointer-events: none !important;
                }
            }

            /* EXTREME MOBILE THUMBNAIL FIX (V5 - FINAL) */
            @media (max-width: 991px) {

                .modern-article-item img,
                .ma-thumb,
                .ma-thumb-lg,
                .modern-article-item>a img,
                .ma-img-wrapper img,
                .archive-thumb img,
                .rec-thumb-modern,
                .vid-thumb-wrapper img {
                    width: 100% !important;
                    height: 140px !important;
                    min-height: 140px !important;
                    max-height: 140px !important;
                    object-fit: cover !important;
                    display: block !important;
                    float: none !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    border-radius: 0 !important;
                }

                .modern-article-item>a,
                .ma-img-wrapper,
                .archive-thumb,
                .vid-thumb-wrapper {
                    width: 100% !important;
                    height: 140px !important;
                    display: block !important;
                    float: none !important;
                    background: #eee !important;
                }
            }

            /* --- PWA FORCED INSTALL OVERLAY --- */
            .pwa-forced-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                z-index: 99999999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
                box-sizing: border-box;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.4s ease, visibility 0.4s ease;
            }

            .pwa-forced-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .pwa-forced-content {
                background: #ffffff;
                border-radius: 24px;
                padding: 25px;
                width: 100%;
                max-width: 360px;
                text-align: center;
                box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45);
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 18px;
                box-sizing: border-box;
                transform: scale(0.9);
                transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .pwa-forced-overlay.show .pwa-forced-content {
                transform: scale(1);
            }

            .pwa-forced-banner {
                width: 100%;
                height: 160px;
                object-fit: cover;
                border-radius: 16px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
                background: #ffffff;
            }

            .pwa-forced-title {
                font-size: 22px;
                font-weight: 800;
                margin: 0;
                color: var(--baznas-green);
                letter-spacing: -0.5px;
            }

            .pwa-forced-desc {
                font-size: 13px;
                line-height: 1.6;
                color: #555555;
                margin: 0;
            }

            .pwa-forced-btn-install {
                width: 100%;
                background: #F4C10F;
                color: #333333;
                border: none;
                padding: 14px 20px;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 700;
                cursor: pointer;
                box-shadow: 0 4px 15px rgba(244, 193, 15, 0.3);
                transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            }

            .pwa-forced-btn-install:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(244, 193, 15, 0.4);
            }

            .pwa-forced-btn-browser {
                background: transparent;
                color: #777777;
                border: none;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: color 0.2s ease;
                margin-top: 5px;
                text-decoration: underline;
            }

            .pwa-forced-btn-browser:hover {
                color: var(--baznas-green);
            }

            .pwa-forced-ios-guide {
                background: #f9f9f9;
                border-radius: 12px;
                padding: 15px;
                font-size: 12.5px;
                line-height: 1.6;
                text-align: left;
                border-left: 4px solid var(--baznas-green);
                color: #333333;
                margin-top: 5px;
                width: 100%;
                box-sizing: border-box;
                box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
            }

            .pwa-forced-ios-guide i {
                display: inline-block;
                margin: 0 3px;
            }

            /* --- GENERAL PREMIUM HOVER EFFECTS --- */
            .the-menu li a, .header-menu ul li a {
                transition: all 0.25s ease;
            }
            .the-menu li a:hover, .header-menu ul li a:hover {
                color: var(--baznas-yellow) !important;
            }
            .vid-card-item img, .ma-img-wrapper img {
                transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            }
            .vid-card-item:hover img, .modern-article-item:hover img {
                transform: scale(1.05) !important;
            }
            .search-button, .more-pill, .btn-copy {
                transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            }
            .search-button:hover, .more-pill:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
    </style>
    <!-- OneSignal Web Push Notification -->
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function (OneSignal) {
            await OneSignal.init({
                appId: "41162a8f-87b7-4fcf-906e-48ecbf6f64e6",
            });
        });
    </script>

    <script>
        // Global Image Error Handler (Fallback to default image)
        document.addEventListener('error', function (event) {
            var fallback = '<?php echo base_url(); ?>asset/foto_berita/no-image.jpg';
            if (event.target.tagName.toLowerCase() === 'img' && event.target.src !== fallback) {
                event.target.src = fallback;
            }
        }, true);
    </script>
</head>

<body>
    <div id='Back-to-top'>
        <img alt='Scroll to top' src='<?php echo base_url(); ?>asset/css/img/top.png' />
    </div>
    <div class="boxed">
        <div class="header">
            <?php include "header.php"; ?>
        </div>

        <div class="content">
            <div class="wrapper">
                <!-- Breaking News (Dinonaktifkan atas permintaan user) -->
                <?php /*
<div class="breaking-news">
<span class="the-title">Breaking News</span>
<ul>
<?php
$terkini = $this->model_utama->view_where_ordering_limit('berita', array('status' => 'Y'), 'id_berita', 'DESC', 0, 10);
foreach ($terkini->result_array() as $row) {
echo "<li><a href='$row[judul_seo]'>$row[judul]</a></li>";
}
?>
</ul>
</div>
*/ ?>

                <?php
                if ($this->uri->segment(1) == 'main' OR $this->uri->segment(1) == '') {
                    $slider = $this->model_utama->view_ordering_limit('slider', 'urutan', 'ASC', 0, 10);
                    if ($slider->num_rows() > 0) {
                        ?>
                        <!-- Slider wrapper: lebar 100%, overflow hidden agar slider JSSOR terskala pas ke tengah -->
                        <div class="slider-outer-wrap" style="width:100%;overflow:hidden;">
                            <div id="jssor_baznas"
                                style="position:relative;top:0px;left:0px;width:2880px;height:570px;overflow:hidden;visibility:hidden;">
                                <!-- Loading Screen -->
                                <div data-u="loading"
                                    style="position:absolute;top:0px;left:0px;background-color:rgba(0,0,0,0.7);">
                                    <div
                                        style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;">
                                    </div>
                                    <div
                                        style="position:absolute;display:block;background:url('<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;">
                                    </div>
                                </div>
                                <div data-u="slides"
                                    style="cursor:default;position:relative;top:0px;left:0px;width:2880px;height:570px;overflow:hidden;">
                                    <?php
                                    foreach ($slider->result_array() as $sl) {
                                        echo "<div>
									<a href='$sl[url]'><img data-u='image' src='" . base_url() . "asset/foto_slider/$sl[gambar]' /></a>
								  </div>";
                                    }
                                    ?>
                                </div>
                                <!-- Bullet Navigator -->
                                <div data-u="navigator" class="jssorb05" style="bottom:16px;right:16px;" data-autocenter="1">
                                    <!-- bullet navigator item prototype -->
                                    <div data-u="prototype" style="width:16px;height:16px;"></div>
                                </div>
                                <!-- Arrow Navigator -->
                                <span data-u="arrowleft" class="jssora22l" style="top:0px;left:8px;width:40px;height:58px;"
                                    data-autocenter="2"></span>
                                <span data-u="arrowright" class="jssora22r" style="top:0px;right:8px;width:40px;height:58px;"
                                    data-autocenter="2"></span>
                            </div>
                        </div><!-- /.slider-outer-wrap -->
                        <?php
                    }
                }
                ?>

                <center>
                    <?php
                    if ($this->uri->segment(1) == 'main' OR $this->uri->segment(1) == '') {
                        $iklanatas = $this->model_utama->view('iklanatas');
                        foreach ($iklanatas->result_array() as $b) {
                            $string = $b['gambar'];
                            if ($b['gambar'] != '') {
                                if (preg_match("/swf\z/i", $string)) {
                                    echo "<embed width='100%' src='" . base_url() . "asset/foto_iklanatas/$b[gambar]' quality='high' type='application/x-shockwave-flash'>";
                                } else {
                                    echo "<a href='$b[url]' target='_blank'><img style='margin-bottom:5px' width='100%' src='" . base_url() . "asset/foto_iklanatas/$b[gambar]' alt='$b[judul]' /></a>";
                                }
                            }
                            if (trim($b['source']) != '') {
                                echo "$b[source]";
                            }
                        }
                    }
                    ?>
                </center>

                <?php
                if ($this->uri->segment(1) == 'kategori') {
                    $bb = $this->db->query("SELECT * FROM kategori where kategori_seo='" . cetak($this->uri->segment(3)) . "'")->row_array();
                    if (!empty($bb['gambar_utama'])) {
                        // Cek apakah file adalah Flash (.swf) atau gambar biasa
                        if (preg_match("/swf\z/i", $bb['gambar_utama'])) {
                            echo "<embed width='100%' src='" . base_url() . "asset/foto_berita/$bb[gambar_utama]' quality='high' type='application/x-shockwave-flash'>";
                        } else {
                            echo "<a href='#' target='_blank'><img style='margin-bottom:5px' width='100%' src='" . base_url() . "asset/foto_berita/$bb[gambar_utama]' alt='$bb[nama_kategori]' /></a>";
                        }
                    }
                }
                ?>

                <main class="main-content" id="maincontent">
                    <?php echo $contents; ?>
                    <div class="clear-float"></div>
                </main>
            </div>
        </div>

        <footer>
            <div class="footer">
                <?php
                include "footer.php";
                $this->model_utama->kunjungan();
                ?>
            </div>
        </footer>
    </div>
    <!-- Scripts -->
    <script type='text/javascript'>
        $(function () {
            var btnVisible = false;
            $(window).scroll(function () {
                var scrollTop = $(this).scrollTop();
                if (scrollTop > 400) {
                    if (!btnVisible) {
                        $('#Back-to-top').stop(true, true).fadeIn();
                        btnVisible = true;
                    }
                } else {
                    if (btnVisible) {
                        $('#Back-to-top').stop(true, true).fadeOut();
                        btnVisible = false;
                    }
                }
            });
            $('#Back-to-top').click(function () {
                $('body,html')
                    .animate({ scrollTop: 0 }, 300)
                    .animate({ scrollTop: 40 }, 200)
                    .animate({ scrollTop: 0 }, 130)
                    .animate({ scrollTop: 15 }, 100)
                    .animate({ scrollTop: 0 }, 70);
            });
        });

        function jam() {
            var waktu = new Date();
            var jam = waktu.getHours();
            var menit = waktu.getMinutes();
            var detik = waktu.getSeconds();

            if (jam < 10) { jam = "0" + jam; }
            if (menit < 10) { menit = "0" + menit; }
            if (detik < 10) { detik = "0" + detik; }
            var jam_div = document.getElementById('jam');
            jam_div.innerHTML = jam + ":" + menit + ":" + detik;
            setTimeout("jam()", 1000);
        } jam();

    </script>

    <script type="text/javascript">
        (function (jQuery) {
            $.fn.ideaboxWeather = function (settings) {
                var defaults = {
                    modulid: 'Swarakalibata',
                    width: '100%',
                    themecolor: '#2582bd',
                    todaytext: 'Hari Ini',
                    radius: true,
                    location: ' Jakarta',
                    daycount: 7,
                    imgpath: 'img_cuaca/',
                    template: 'vertical',
                    lang: 'id',
                    metric: 'C',
                    days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"],
                    dayssmall: ["Mg", "Sn", "Sl", "Rb", "Km", "Jm", "Sa"]
                };
                var settings = $.extend(defaults, settings);

                return this.each(function () {
                    settings.modulid = "#" + $(this).attr("id");
                    $(settings.modulid).css({ "width": settings.width, "background": settings.themecolor });

                    if (settings.radius)
                        $(settings.modulid).addClass("ow-border");

                    getWeather();
                    resizeEvent();

                    $(window).on("resize", function () {
                        resizeEvent();
                    });

                    function resizeEvent() {
                        var mW = $(settings.modulid).width();

                        if (mW < 200) {
                            $(settings.modulid).addClass("ow-small");
                        }
                        else {
                            $(settings.modulid).removeClass("ow-small");
                        }
                    }

                    function getWeather() {
                        $.get("https://api.openweathermap.org/data/2.5/forecast/daily?q=" + settings.location + "&mode=xml&units=metric&cnt=" + settings.daycount + "&lang=" + settings.lang + "&appid=b318ee3082fcae85097e680e36b9c749", function (data) {
                            var $XML = $(data);
                            var sstr = "";
                            var location = $XML.find("name").text();
                            $XML.find("time").each(function (index, element) {
                                var $this = $(this);
                                var d = new Date($(this).attr("day"));
                                var n = d.getDay();
                                var metrics = "";
                                if (settings.metric == "F") {
                                    metrics = Math.round($this.find("temperature").attr("day") * 1.8 + 32) + "�F";
                                }
                                else {
                                    metrics = Math.round($this.find("temperature").attr("day")) + "�C";
                                }

                                if (index == 0) {
                                    if (settings.template == "vertical") {
                                        sstr = sstr + '<div class="ow-today">' +
                                            '<span><img src="<?php echo base_url(); ?>asset/' + settings.imgpath + $this.find("symbol").attr("var") + '.png"/></span>' +
                                            '<h2>' + metrics + '<span>' + ucFirst($this.find("symbol").attr("name")) + '</span><b>' + location + ' - ' + settings.todaytext + '</b></h2>' +
                                            '</div>';
                                    }
                                    else {
                                        sstr = sstr + '<div class="ow-today">' +
                                            '<span><img src="<?php echo base_url(); ?>asset/' + settings.imgpath + $this.find("symbol").attr("var") + '.png"/></span>' +
                                            '<h2>' + metrics + '<span>' + ucFirst($this.find("symbol").attr("name")) + '</span><b>' + location + ' - ' + settings.todaytext + '</b></h2>' +
                                            '</div>';
                                    }
                                }
                                else {
                                    if (settings.template == "vertical") {
                                        sstr = sstr + '<div class="ow-days">' +
                                            '<span>' + settings.days[n] + '</span>' +
                                            '<p><img src="<?php echo base_url(); ?>asset/' + settings.imgpath + $this.find("symbol").attr("var") + '.png" title="' + ucFirst($this.find("symbol").attr("name")) + '"> <b>' + metrics + '</b></p>' +
                                            '</div>';
                                    }
                                    else {
                                        sstr = sstr + '<div class="ow-dayssmall" style="width:' + 100 / (settings.daycount - 1) + '%">' +
                                            '<span title=' + settings.days[n] + '>' + settings.dayssmall[n] + '</span>' +
                                            '<p><img src="<?php echo base_url(); ?>asset/' + settings.imgpath + $this.find("symbol").attr("var") + '.png" title="' + ucFirst($this.find("symbol").attr("name")) + '"></p>' +
                                            '<b>' + metrics + '</b>' +
                                            '</div>';
                                    }
                                }
                            });

                            $(settings.modulid).html(sstr);
                        });
                    }

                    function ucFirst(string) {
                        return string.substring(0, 1).toUpperCase() + string.substring(1).toLowerCase();
                    }
                });
            };
        })(jQuery);

        $(document).ready(function () {
            $('#example1').ideaboxWeather({
                location: ' Jakarta, ID'
            });
        });
    </script>

    <script>
        $(function () {
            var url = window.location.pathname,
                urlRegExp = new RegExp(url.replace(/\/$/, '') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
            // now grab every link from the navigation
            $('.the-menu a').each(function () {
                // and test its normalized href against the url pathname regexp
                if (urlRegExp.test(this.href.replace(/\/$/, ''))) {
                    $(this).addClass('active');
                }
            });

        });
    </script>
    <!-- Grouping Mobile UI with Inline Style for absolute Desktop hiding -->
    <div id="MOBILE_NAV_UI" class="mobile-ui-wrapper" style="display: none;">
        <!-- Manual Mobile Menu for reliability -->
        <div class="modern-mobile-menu">
            <div class="mobile-menu-header">
                <div class="mm-brand">
                    <i class="fa fa-th-large"></i> <span>MENU UTAMA</span>
                </div>
                <a href="#" class="mm-close-btn" onclick="toggleMobileMenu(event);" title="Tutup Menu">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <div class="mobile-menu-container">
                <?php
                if (function_exists('main_menu')) {
                    echo main_menu();
                }
                ?>
            </div>
        </div>
        <a href="#" class="escape-mobile-menu" onclick="toggleMobileMenu(event);"></a>

        <!-- Bottom Navigation Bar -->
        <nav class="bottom-nav">
            <a href="<?php echo base_url(); ?>"
                class="nav-item <?php echo ($this->uri->segment(1) == '' || $this->uri->segment(1) == 'main') ? 'active' : ''; ?>">
                <i class="fa fa-home"></i>
                <span>Beranda</span>
            </a>
            <a href="<?php echo base_url(); ?>berita"
                class="nav-item <?php echo ($this->uri->segment(1) == 'berita') ? 'active' : ''; ?>">
                <i class="fa fa-newspaper-o"></i>
                <span>Berita</span>
            </a>
            <a href="<?php echo base_url(); ?>bayar-zakat"
                class="nav-item nav-special-cta <?php echo ($this->uri->segment(1) == 'bayar-zakat') ? 'active' : ''; ?>">
                <div class="nav-circle-red">
                    <i class="fa fa-hand-stop-o icon-hand"></i>
                    <i class="fa fa-heart icon-heart"></i>
                </div>
                <span class="nav-label-outside">Bayar Zakat</span>
            </a>
            <a href="<?php echo base_url(); ?>laporan"
                class="nav-item <?php echo ($this->uri->segment(1) == 'laporan') ? 'active' : ''; ?>">
                <i class="fa fa-bar-chart"></i>
                <span>Laporan</span>
            </a>
            <a href="#" class="nav-item" onclick="toggleMobileMenu(event);" id="btn-mobile-menu">
                <i class="fa fa-th-large"></i>
                <span>Menu</span>
            </a>
        </nav>

        <!-- Mobile Only WhatsApp Floating Button with Dynamic Text Slider -->
        <a href="https://api.whatsapp.com/send?phone=<?php echo str_replace([' ', '-', '+'], '', $_iden_og['no_telp']); ?>&text=Assalamu%27alaikum%20BAZNAS%20Sumbawa..."
            class="mobile-wa-btn">
            <i class="fa fa-whatsapp"></i>
            <div class="wa-text-slider">
                <div class="wa-text-slider-inner">
                    <span class="slide-item">Layanan BAZNAS</span>
                    <span class="slide-item">Konfirmasi Zakat</span>
                    <span class="slide-item">Konsultasi ZIS</span>
                    <span class="slide-item">Layanan BAZNAS</span> <!-- Loop back item -->
                </div>
            </div>
        </a>
    </div>

    <!-- Desktop Only Sticky Button -->
    <a href="<?php echo base_url(); ?>bayar-zakat" class="sticky-payment-button pulse-animation-green desktop-only-btn">
        <i class="fa fa-credit-card-alt"></i>
        <span>Bayar Zakat & ZIS</span>
    </a>

    <script>
        function toggleMobileMenu(e) {
            if (e) e.preventDefault();
            document.body.classList.toggle('menu-active');
        }

        // Script Pintar untuk Menambahkan Ikon & Merapikan Menu & Submenu Mobile
        function applyMenuIcons() {
            const allLinks = document.querySelectorAll('.modern-mobile-menu .mobile-menu-container a');
            
            const mainIconMap = {
                'beranda': 'fa-home',
                'home': 'fa-home',
                'profil': 'fa-institution',
                'tentang': 'fa-info-circle',
                'program': 'fa-th-list',
                'pendayagunaan': 'fa-briefcase',
                'layanan': 'fa-handshake-o',
                'zakat': 'fa-money',
                'ziswaf': 'fa-heart',
                'berita': 'fa-newspaper-o',
                'artikel': 'fa-file-text-o',
                'laporan': 'fa-line-chart',
                'galeri': 'fa-image',
                'video': 'fa-play-circle',
                'kontak': 'fa-phone',
                'hubungi': 'fa-envelope'
            };

            const subIconMap = {
                'struktur': 'fa-sitemap',
                'organisasi': 'fa-sitemap',
                'visi': 'fa-bullseye',
                'misi': 'fa-flag',
                'strategis': 'fa-crosshairs',
                'kerja': 'fa-tasks',
                'azaz': 'fa-shield',
                'gambaran': 'fa-map-o',
                'jemput': 'fa-truck',
                'upz': 'fa-users',
                'kalkulator': 'fa-calculator',
                'sehat': 'fa-medkit',
                'cerdas': 'fa-graduation-cap',
                'makmur': 'fa-briefcase',
                'peduli': 'fa-heart',
                'takwa': 'fa-star',
                'rekening': 'fa-credit-card',
                'kinerja': 'fa-bar-chart',
                'penerimaan': 'fa-arrow-circle-down',
                'pendistribusian': 'fa-share-square-o',
                'penyaluran': 'fa-share-square-o',
                'audit': 'fa-check-square-o',
                'peraturan': 'fa-gavel',
                'regulasi': 'fa-gavel',
                'bayar': 'fa-money',
                'pengumpulan': 'fa-inbox',
                'admin': 'fa-user-secret',
                'presensi': 'fa-clock-o',
                'drive': 'fa-cloud-upload'
            };

            allLinks.forEach(link => {
                // Bersihkan inline style bawaan desktop jika ada
                link.removeAttribute('style');

                // Cek apakah ini sub-menu (berada di dalam ul li ul)
                const isSubMenu = (link.closest('ul') && link.closest('ul').parentElement.tagName === 'LI' && link.closest('ul').parentElement.closest('ul'));

                if (!isSubMenu) {
                    // --- PENANGANAN MENU UTAMA ---
                    const existingIcon = link.querySelector('i.menu-icon');
                    if (existingIcon) existingIcon.remove();

                    let text = link.textContent.trim();
                    if (!text || text === '') {
                        link.innerHTML = '<span>BERANDA</span>';
                        text = 'beranda';
                    }

                    const textLower = text.toLowerCase();
                    let iconClass = 'fa-dot-circle-o';

                    for (let key in mainIconMap) {
                        if (textLower.includes(key)) {
                            iconClass = mainIconMap[key];
                            break;
                        }
                    }

                    const icon = document.createElement('i');
                    icon.className = `fa ${iconClass} menu-icon`;
                    link.prepend(icon);
                } else {
                    // --- PENANGANAN SUB-MENU ---
                    const existingSubIcon = link.querySelector('i.submenu-icon');
                    if (existingSubIcon) existingSubIcon.remove();

                    const textLower = link.textContent.toLowerCase().trim();
                    let iconClass = 'fa-angle-right'; // Ikon default sub-menu

                    for (let key in subIconMap) {
                        if (textLower.includes(key)) {
                            iconClass = subIconMap[key];
                            break;
                        }
                    }

                    const icon = document.createElement('i');
                    icon.className = `fa ${iconClass} submenu-icon`;
                    link.prepend(icon);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', applyMenuIcons);
        // Panggil lagi setelah jeda singkat untuk memastikan menu terisi jika ada delay
        setTimeout(applyMenuIcons, 500);

        // Delegasi event untuk tombol close
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('escape-mobile-menu')) {
                e.preventDefault();
                document.body.classList.remove('menu-active');
            }
        });
    </script>
    <script>
        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?php echo base_url(); ?>sw.js')
                    .then(reg => console.log('Service Worker registered successfully!', reg.scope))
                    .catch(err => console.log('Service Worker registration failed:', err));
            });
        }

        // PWA Forced Install Overlay Logic
        document.addEventListener('DOMContentLoaded', () => {
            let deferredPrompt;
            const dismissedSessionKey = 'pwa_forced_dismissed';
            const installedKey = 'pwa_installed';
            const bannerUrl = '<?php echo base_url(); ?>asset/images/pwa-mockup.png';

            // Detect if we are on a mobile device
            const isMobile = window.innerWidth <= 767 || /Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i.test(navigator.userAgent);

            // Detect standalone (installed) mode
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

            // Check if dismissed in this session
            const isDismissedThisSession = sessionStorage.getItem(dismissedSessionKey) === 'true';

            // Handle successful install tracking
            window.addEventListener('appinstalled', () => {
                localStorage.setItem(installedKey, 'true');
                console.log('PWA was installed successfully');
                const overlay = document.getElementById('pwa-forced-overlay');
                if (overlay) {
                    updateOverlayContent(true);
                }
            });

            // Listen for beforeinstallprompt event (Android / Chrome Desktop)
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;

                // If beforeinstallprompt fires, the app is NOT installed.
                // If it was marked as installed, the user must have uninstalled it!
                if (localStorage.getItem(installedKey) === 'true') {
                    localStorage.setItem(installedKey, 'false');
                    console.log('PWA uninstalled (detected via beforeinstallprompt)');
                    
                    // Self-correction: morph overlay dynamically if already visible
                    const overlay = document.getElementById('pwa-forced-overlay');
                    if (overlay) {
                        updateOverlayContent(false);
                    }
                }
            });

            // Run verification after a short delay (300ms) to let events fire
            setTimeout(() => {
                if (isMobile && !isStandalone && !isDismissedThisSession) {
                    checkInstallStatusAndShow();
                }
            }, 300);

            function checkInstallStatusAndShow() {
                let isAppInstalled = localStorage.getItem(installedKey) === 'true';

                if ('getInstalledRelatedApps' in navigator) {
                    navigator.getInstalledRelatedApps().then((relatedApps) => {
                        if (relatedApps.length > 0) {
                            isAppInstalled = true;
                            localStorage.setItem(installedKey, 'true');
                        } else {
                            isAppInstalled = false;
                            localStorage.setItem(installedKey, 'false');
                        }
                        injectForcedOverlay(isAppInstalled);
                    }).catch(() => {
                        injectForcedOverlay(isAppInstalled);
                    });
                } else {
                    injectForcedOverlay(isAppInstalled);
                }
            }

            function getOverlayHTML(alreadyInstalled) {
                if (alreadyInstalled) {
                    return `
                        <div class="pwa-forced-content">
                            <img class="pwa-forced-banner" src="${bannerUrl}" alt="BAZNAS App Mockup">
                            <h3 class="pwa-forced-title">Buka di Aplikasi</h3>
                            <p class="pwa-forced-desc">Buka aplikasi BAZNAS Sumbawa yang sudah terpasang di HP Anda agar lebih optimal, efektif, dan powerful.</p>
                            
                            <div id="pwa-forced-action-area" style="width: 100%;">
                                <button class="pwa-forced-btn-install" id="pwa-forced-btn-open" style="background: #F4C10F; color: #333333;">
                                    <i class="fa fa-external-link" style="margin-right: 8px;"></i> Buka Aplikasi sekarang
                                </button>
                            </div>
                            
                            <button class="pwa-forced-btn-browser" id="pwa-forced-btn-browser">Lanjutkan via Browser</button>
                        </div>
                    `;
                } else {
                    return `
                        <div class="pwa-forced-content">
                            <img class="pwa-forced-banner" src="${bannerUrl}" alt="BAZNAS App Mockup">
                            <h3 class="pwa-forced-title">Pasang Aplikasi</h3>
                            <p class="pwa-forced-desc">Dapatkan kemudahan bayar zakat, kalkulator zakat online, dan berita terbaru langsung dari layar HP Anda dengan memasang aplikasi BAZNAS Sumbawa.</p>
                            
                            <div id="pwa-forced-action-area" style="width: 100%;">
                                <button class="pwa-forced-btn-install" id="pwa-forced-btn-install" style="background: #F4C10F; color: #333333;">
                                    <i class="fa fa-download" style="margin-right: 8px;"></i> Pasang Aplikasi Sekarang
                                </button>
                            </div>
                            
                            <button class="pwa-forced-btn-browser" id="pwa-forced-btn-browser">Lanjutkan via Browser</button>
                        </div>
                    `;
                }
            }

            function injectForcedOverlay(alreadyInstalled) {
                if (document.getElementById('pwa-forced-overlay')) return;

                const overlay = document.createElement('div');
                overlay.id = 'pwa-forced-overlay';
                overlay.className = 'pwa-forced-overlay';
                overlay.innerHTML = getOverlayHTML(alreadyInstalled);
                document.body.appendChild(overlay);

                // Show with transition
                setTimeout(() => {
                    overlay.classList.add('show');
                }, 100);

                bindOverlayEvents(overlay, alreadyInstalled);
            }

            function updateOverlayContent(alreadyInstalled) {
                const overlay = document.getElementById('pwa-forced-overlay');
                if (!overlay) return;
                
                overlay.innerHTML = getOverlayHTML(alreadyInstalled);
                bindOverlayEvents(overlay, alreadyInstalled);
            }

            function bindOverlayEvents(overlay, alreadyInstalled) {
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
                const browserBtn = overlay.querySelector('#pwa-forced-btn-browser');

                if (browserBtn) {
                    browserBtn.addEventListener('click', () => {
                        overlay.classList.remove('show');
                        sessionStorage.setItem(dismissedSessionKey, 'true');
                        setTimeout(() => overlay.remove(), 400);
                    });
                }

                if (alreadyInstalled) {
                    const openBtn = overlay.querySelector('#pwa-forced-btn-open');
                    if (openBtn) {
                        openBtn.addEventListener('click', () => {
                            // Try custom protocol scheme first
                            window.location.href = 'web+baznas://open';
                            
                            // Fallback to normal URL navigation in same tab after short timeout
                            setTimeout(() => {
                                window.location.href = '<?php echo base_url(); ?>?pwa_mode=standalone';
                            }, 1200);

                            overlay.classList.remove('show');
                            sessionStorage.setItem(dismissedSessionKey, 'true');
                            setTimeout(() => overlay.remove(), 400);
                        });
                    }
                } else {
                    const installBtn = overlay.querySelector('#pwa-forced-btn-install');
                    if (installBtn) {
                        installBtn.addEventListener('click', () => {
                            if (isIOS) {
                                showIosGuide();
                            } else if (deferredPrompt) {
                                deferredPrompt.prompt();
                                deferredPrompt.userChoice.then((choiceResult) => {
                                    if (choiceResult.outcome === 'accepted') {
                                        localStorage.setItem(installedKey, 'true');
                                        overlay.classList.remove('show');
                                        setTimeout(() => overlay.remove(), 400);
                                    }
                                    deferredPrompt = null;
                                });
                            } else {
                                showManualGuide();
                            }
                        });
                    }
                }
            }

            function showIosGuide() {
                const actionArea = document.getElementById('pwa-forced-action-area');
                if (actionArea) {
                    actionArea.innerHTML = `
                        <div class="pwa-forced-ios-guide">
                            <strong>Panduan Pemasangan iPhone/iPad:</strong><br>
                            1. Ketuk tombol <strong>Bagi (Share)</strong> <i class="fa fa-share-square-o" style="color:#007aff; font-size:15px;"></i> pada menu bawah Safari.<br>
                            2. Gulir ke bawah lalu pilih menu <strong>'Tambahkan ke Layar Utama'</strong> (Add to Home Screen).<br>
                            3. Ketuk <strong>'Tambah'</strong> di pojok kanan atas.
                        </div>
                    `;
                }
            }
            function showManualGuide() {
                const actionArea = document.getElementById('pwa-forced-action-area');
                if (actionArea) {
                    actionArea.innerHTML = `
                        <div class="pwa-forced-ios-guide" style="border-left-color: #ff9800;">
                            <strong>Cara Pasang Aplikasi:</strong><br>
                            1. Ketuk tombol <strong>menu (tiga titik vertikal)</strong> <i class="fa fa-ellipsis-v"></i> di pojok kanan atas browser Anda.<br>
                            2. Pilih opsi <strong>'Instal aplikasi'</strong> atau <strong>'Tambahkan ke Layar Utama'</strong>.
                        </div>
                    `;
                }
            }
        });

        /* Penyeimbang Tinggi Kolom Sidebar untuk Mendukung Sticky Widget Tanpa Mengubah Layout Floating Asli */
        function matchSidebarHeight() {
            if (window.innerWidth >= 992) {
                var mainPageH = $('.main-page').outerHeight();
                if (mainPageH > $('.main-sidebar.right').outerHeight()) {
                    $('.main-sidebar.right').css('min-height', mainPageH + 'px');
                }
                var contentMainH = $('.double-block .content-block.main').outerHeight();
                if (contentMainH > $('.double-block .content-block.left').outerHeight()) {
                    $('.double-block .content-block.left').css('min-height', contentMainH + 'px');
                }
            } else {
                $('.main-sidebar.right, .double-block .content-block.left').css('min-height', '');
            }
        }
        $(window).on('load resize scroll', matchSidebarHeight);
        setTimeout(matchSidebarHeight, 500);
        setInterval(matchSidebarHeight, 2000);
    </script>
    <!-- Amil Virtual AI BAZNAS Sumbawa -->
    <?php include "widget_ai_chat.php"; ?>
</body>

</html>