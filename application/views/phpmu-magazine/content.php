<div class="main-page left">
	<div class="double-block">
		<div class="content-block main right">
			<div class="block">
				<div class="featured-block">
					<?php
					$cekslide = $this->model_utama->view_single('berita', array('headline' => 'Y', 'status' => 'Y'), 'id_berita', 'DESC');
					if ($cekslide->num_rows() > 0) {
						include "slide.php";
					}
					?>
				</div>
			</div>

			<section class="modern-widget" aria-labelledby="latest-news-title">
				<div class="widget-header-modern">
					<h3 id="latest-news-title">Berita dan Kegiatan Terbaru</h3>
				</div>
				<div class="modern-article-list">
					<?php
					$hot = $this->db->query("SELECT berita.*, users.nama_lengkap, kategori.nama_kategori, kategori.kategori_seo FROM berita 
						LEFT JOIN users ON berita.username=users.username
						LEFT JOIN kategori ON berita.id_kategori=kategori.id_kategori 
						WHERE berita.status='Y' AND (berita.tag NOT LIKE '%artikel%' OR berita.tag IS NULL) AND (kategori.kategori_seo != 'artikel' OR kategori.kategori_seo IS NULL)
						ORDER BY berita.id_berita DESC LIMIT 5");
					foreach ($hot->result_array() as $row):
						$total_komentar = $this->model_utama->view_where('komentar', array('id_berita' => $row['id_berita']))->num_rows();
						$isi_berita = strip_tags($row['isi_berita']);
						$isi = substr($isi_berita, 0, 150) . '...';
						$thumb = (!empty($row['gambar'])) ? base_url() . "asset/foto_berita/$row[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";
						?>
						<article class="modern-article-item">
							<a href="<?php echo base_url() . $row['judul_seo']; ?>" class="ma-img-wrapper"
								aria-label="<?php echo $row['judul']; ?>">
								<img src="<?php echo $thumb; ?>" loading="lazy" class="ma-thumb-lg"
									alt="<?php echo $row['judul']; ?>" title="<?php echo $row['judul']; ?>">
							</a>
							<div class="ma-content">
								<header>
									<a href="<?php echo base_url() . "kategori/detail/$row[kategori_seo]"; ?>"
										class="cat-pill"><?php echo $row['nama_kategori']; ?></a>
									<h4><a
											href="<?php echo base_url() . $row['judul_seo']; ?>"><?php echo $row['judul']; ?></a>
									</h4>
								</header>
								<div class="ma-meta">
									<time datetime="<?php echo $row['tanggal']; ?>">📅
										<?php echo tgl_indo($row['tanggal']); ?></time>
									<span>🕒 <?php echo $row['jam']; ?></span>
									<span>💬 <?php echo $total_komentar; ?></span>
								</div>
								<p class="ma-excerpt"><?php echo $isi; ?></p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<div class="widget-footer-modern">
					<a href="<?php echo base_url(); ?>berita/indeks_berita" class="more-pill">Lihat Lebih Banyak</a>
				</div>
			</section>

			<!-- Widget Unified Video & BAZNAS Shorts -->
			<section class="modern-widget baznas-shorts-container" aria-labelledby="unified-video-title">
				<div class="widget-header-modern" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
					<h3 id="unified-video-title" style="margin: 0;"><i class="fa fa-play-circle" style="color: var(--baznas-yellow);"></i> Video &amp; BAZNAS Shorts</h3>
					<div class="shorts-track-controls">
						<button class="shorts-nav-pill" onclick="slideShortsTrack(-1)" title="Geser Kiri Shorts"><i class="fa fa-chevron-left"></i></button>
						<button class="shorts-nav-pill" onclick="slideShortsTrack(1)" title="Geser Kanan Shorts"><i class="fa fa-chevron-right"></i></button>
					</div>
				</div>

				<?php
				// 1. Cek dan pastikan kolom jenis_video ada
				$fields_check = $this->db->list_fields('video');
				if (!in_array('jenis_video', $fields_check)) {
					$this->db->query("ALTER TABLE video ADD COLUMN jenis_video VARCHAR(20) DEFAULT 'video'");
				}

				// 2. Query untuk Video Singkat / Shorts (9:16 Portrait)
				$q_shorts = $this->db->query("SELECT * FROM video WHERE jenis_video = 'short' ORDER BY id_video DESC LIMIT 8");
				$shorts_list = array();
				foreach ($q_shorts->result_array() as $s) {
					$src = !empty($s['youtube']) ? trim($s['youtube']) : (!empty($s['video']) ? trim($s['video']) : '');
					$yt_id = '';
					$type = 'iframe';
					$embed_url = $src;

					if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $src, $match)) {
						$yt_id = $match[1];
						$type = 'youtube';
						$embed_url = "https://www.youtube.com/embed/" . $yt_id . "?autoplay=1&loop=1&playlist=" . $yt_id;
					} elseif (preg_match('/\.(mp4|webm|ogg|m3u8|mov)($|\?)/i', $src)) {
						$type = 'mp4';
					}

					if (!empty($s['gbr_video']) && file_exists("asset/img_video/" . $s['gbr_video'])) {
						$t_img = base_url() . "asset/img_video/" . $s['gbr_video'];
					} elseif ($yt_id != '') {
						$t_img = "https://img.youtube.com/vi/{$yt_id}/hqdefault.jpg";
					} else {
						$t_img = base_url() . "asset/foto_berita/no-image.jpg";
					}

					$shorts_list[] = array(
						'id' => $s['id_video'],
						'title' => $s['jdl_video'],
						'embed_url' => $embed_url,
						'type' => $type,
						'thumb' => $t_img,
						'views' => $s['dilihat'] + 15
					);
				}

				// 3. Query untuk Video Biasa (16:9 Cinema)
				$q_vid = $this->db->query("SELECT * FROM video WHERE jenis_video != 'short' OR jenis_video IS NULL ORDER BY id_video DESC LIMIT 6");
				$videos_data = array();
				foreach ($q_vid->result_array() as $v) {
					$src = !empty($v['youtube']) ? trim($v['youtube']) : (!empty($v['video']) ? trim($v['video']) : '');
					$yt_id = '';
					$type = 'iframe';
					$embed_url = $src;

					if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $src, $match)) {
						$yt_id = $match[1];
						$type = 'youtube';
						$embed_url = "https://www.youtube.com/embed/" . $yt_id . "?rel=0";
					} elseif (preg_match('/\.(mp4|webm|ogg|m3u8|mov)($|\?)/i', $src)) {
						$type = 'mp4';
					}

					if (!empty($v['gbr_video']) && file_exists("asset/img_video/" . $v['gbr_video'])) {
						$t_img = base_url() . "asset/img_video/" . $v['gbr_video'];
					} elseif ($yt_id != '') {
						$t_img = "https://img.youtube.com/vi/{$yt_id}/mqdefault.jpg";
					} else {
						$t_img = base_url() . "asset/foto_berita/no-image.jpg";
					}

					$videos_data[] = array(
						'id' => $v['id_video'],
						'title' => $v['jdl_video'],
						'seo' => $v['video_seo'],
						'embed_url' => $embed_url,
						'type' => $type,
						'thumb' => $t_img
					);
				}
				?>

				<!-- Bagian Atas: Horizontal Tab Shorts (Portrait 9:16) -->
				<div style="margin-bottom: 25px;">
					<div style="font-size: 13px; font-weight: 700; color: #444; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
						<i class="fa fa-bolt" style="color: #ff0050;"></i> Shorts / Reels <span style="font-size: 11px; font-weight: normal; color: #888;">(Klik untuk memutar layar penuh)</span>
					</div>
					<div class="baznas-shorts-track">
						<?php foreach ($shorts_list as $idx => $sh): ?>
							<div class="shorts-card-item" onclick="openShortsViewer(<?php echo $idx; ?>)">
								<div class="shorts-top-badge">
									<i class="fa fa-play"></i> SHORTS
								</div>
								<img src="<?php echo $sh['thumb']; ?>" class="shorts-card-thumb" loading="lazy" alt="<?php echo $sh['title']; ?>">
								<div class="shorts-play-center">
									<svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
								</div>
								<div class="shorts-overlay-gradient">
									<div class="shorts-views-count"><i class="fa fa-eye"></i> <?php echo $sh['views']; ?> kali</div>
									<h4 class="shorts-card-title"><?php echo $sh['title']; ?></h4>
								</div>
							</div>
						<?php endforeach; 
						if (empty($shorts_list)) {
							echo '<div style="width: 100%; text-align: center; padding: 25px; background: #fafafa; border: 1px dashed #ddd; font-size: 13px; color: #777;">Belum ada video pendek (Shorts). Silakan ubah jenis video menjadi "Video Singkat / Shorts" melalui Administrator.</div>';
						}
						?>
					</div>
				</div>

				<!-- Bagian Bawah: Spotlight Cinema Player & Video Biasa (16:9 Cinema) -->
				<div>
					<div style="font-size: 13px; font-weight: 700; color: #444; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
						<i class="fa fa-film" style="color: var(--baznas-green);"></i> Video Terbaru <span style="font-size: 11px; font-weight: normal; color: #888;">(Format Cinema 16:9)</span>
					</div>
					<?php if (!empty($videos_data)): $spot = $videos_data[0]; ?>
					<!-- Spotlight Cinema Player Area -->
					<div class="spotlight-video-box">
						<div class="spotlight-player-wrapper" id="spotlight-player">
							<?php if ($spot['type'] == 'mp4'): ?>
								<video controls width="100%" height="100%" id="spotlight-video-elem">
									<source src="<?php echo $spot['embed_url']; ?>" type="video/mp4">
									Browser Anda tidak mendukung tag video HTML5.
								</video>
							<?php else: ?>
								<iframe id="spotlight-iframe-elem" src="<?php echo $spot['embed_url']; ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							<?php endif; ?>
						</div>
						<div class="spotlight-info-bar" style="justify-content: flex-end; padding: 10px 20px;">
							<div style="display:none;">
								<span style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--baznas-yellow); font-weight: 800; display: block; margin-bottom: 4px;">Sedang Diputar</span>
								<h4 class="spotlight-title" id="spotlight-title-text"><?php echo $spot['title']; ?></h4>
							</div>
							<a href="<?php echo base_url() . 'playlist/watch/' . $spot['seo']; ?>" id="spotlight-full-link" class="spotlight-action-btn" title="Tonton di Halaman Penuh">
								<i class="fa fa-external-link"></i> Tonton di Halaman Penuh
							</a>
						</div>
					</div>

					<!-- Daftar Playlist Interaktif -->
					<div class="modern-grid-card">
						<?php foreach ($videos_data as $idx => $vd): ?>
							<article class="vid-card-item <?php echo ($idx === 0) ? 'active-playing' : ''; ?>" 
								onclick="playSpotlightVideo(event, '<?php echo addslashes($vd['embed_url']); ?>', '<?php echo $vd['type']; ?>', '<?php echo addslashes($vd['title']); ?>', '<?php echo base_url() . 'playlist/watch/' . $vd['seo']; ?>', this)">
								<div class="playing-badge">Sedang Diputar</div>
								<div class="vid-thumb-wrapper">
									<img src="<?php echo $vd['thumb']; ?>" loading="lazy" alt="<?php echo $vd['title']; ?>" title="<?php echo $vd['title']; ?>">
									<div class="play-btn-overlay">
										<svg viewBox="0 0 24 24">
											<path d="M8 5v14l11-7z" />
										</svg>
									</div>
								</div>
								<h4 class="vid-title"><?php echo $vd['title']; ?></h4>
							</article>
						<?php endforeach; ?>
					</div>

					<script>
					function playSpotlightVideo(e, embedUrl, type, title, fullLink, elem) {
						if (e) e.preventDefault();
						
						document.querySelectorAll('.vid-card-item').forEach(card => card.classList.remove('active-playing'));
						if (elem) elem.classList.add('active-playing');

						const titleElem = document.getElementById('spotlight-title-text');
						if (titleElem) titleElem.textContent = title;
						const linkElem = document.getElementById('spotlight-full-link');
						if (linkElem) linkElem.href = fullLink;

						const playerWrapper = document.getElementById('spotlight-player');
						if (!playerWrapper) return;

						if (type === 'mp4') {
							playerWrapper.innerHTML = `<video controls width="100%" height="100%" autoplay><source src="${embedUrl}" type="video/mp4">Browser Anda tidak mendukung tag video HTML5.</video>`;
						} else {
							let finalUrl = embedUrl;
							if (type === 'youtube') {
								finalUrl += (finalUrl.includes('?') ? '&' : '?') + 'autoplay=1';
							}
							playerWrapper.innerHTML = `<iframe src="${finalUrl}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
						}

						if (window.innerWidth < 768) {
							playerWrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
						}
					}
					</script>
					<?php else: ?>
						<div style="width: 100%; text-align: center; padding: 25px; background: #fafafa; border: 1px dashed #ddd; font-size: 13px; color: #777;">Belum ada video biasa (16:9 Cinema). Silakan tambahkan video melalui Administrator.</div>
					<?php endif; ?>
				</div>

				<div class="widget-footer-modern" style="margin-top: 20px;">
					<a href="<?php echo base_url(); ?>playlist" class="more-pill">Semua Video</a>
				</div>
			</section>

			<!-- TikTok / YouTube Shorts Fullscreen Modal Player -->
			<div id="baznasShortsModal" class="shorts-modal-overlay">
				<div class="shorts-phone-frame">
					<!-- Top Bar -->
					<div class="shorts-modal-topbar">
						<div class="shorts-topbar-title"><i class="fa fa-bolt" style="color: #ff0050;"></i> BAZNAS SHORTS</div>
						<button class="shorts-close-btn" onclick="closeShortsViewer()" title="Tutup">✕</button>
					</div>

					<!-- Video Container -->
					<div class="shorts-player-container" id="shortsModalPlayer"></div>

					<!-- Bottom Info -->
					<div class="shorts-modal-bottom-info">
						<div class="shorts-author-tag">@BAZNASSumbawa <span class="verified-badge-icon">✔</span></div>
						<div class="shorts-modal-title" id="shortsModalTitle">Judul Video Singkat</div>
						<div class="shorts-music-ticker"><i class="fa fa-music"></i> <span>Suara asli - BAZNAS Sumbawa</span></div>
					</div>

					<!-- Side Action Buttons -->
					<div class="shorts-side-actions">
						<button class="shorts-action-btn" id="shortsLikeBtn" onclick="toggleShortsLike(this)">
							<span style="font-size: 16px;">❤️</span>
							<span class="shorts-action-label" id="shortsLikeCount">328</span>
						</button>
						<button class="shorts-action-btn" onclick="alert('Tautan video berhasil disalin!')">
							<span style="font-size: 16px;">↗️</span>
							<span class="shorts-action-label">Bagikan</span>
						</button>
					</div>

					<!-- Navigation Up/Down Arrows -->
					<div class="shorts-nav-arrows">
						<button class="nav-arrow-btn" onclick="navigateShorts(-1)" title="Video Sebelumnya">▲</button>
						<button class="nav-arrow-btn" onclick="navigateShorts(1)" title="Video Berikutnya">▼</button>
					</div>
				</div>
			</div>

			<script>
			let currentShortIndex = 0;
			const baznasShortsData = <?php echo json_encode($shorts_list); ?>;

			function openShortsViewer(index) {
				if (!baznasShortsData || baznasShortsData.length === 0) return;
				currentShortIndex = index;
				renderShortVideo();
				const modal = document.getElementById('baznasShortsModal');
				modal.classList.add('active');
				document.body.style.overflow = 'hidden';
			}

			function closeShortsViewer() {
				const modal = document.getElementById('baznasShortsModal');
				modal.classList.remove('active');
				document.getElementById('shortsModalPlayer').innerHTML = '';
				document.body.style.overflow = 'auto';
			}

			function navigateShorts(direction) {
				currentShortIndex += direction;
				if (currentShortIndex < 0) currentShortIndex = baznasShortsData.length - 1;
				if (currentShortIndex >= baznasShortsData.length) currentShortIndex = 0;
				renderShortVideo();
			}

			function renderShortVideo() {
				const item = baznasShortsData[currentShortIndex];
				if (!item) return;

				document.getElementById('shortsModalTitle').textContent = item.title;
				const playerContainer = document.getElementById('shortsModalPlayer');

				if (item.type === 'mp4') {
					playerContainer.innerHTML = `<video controls autoplay loop style="width: 100%; height: 100%; object-fit: cover;"><source src="${item.embed_url}" type="video/mp4"></video>`;
				} else {
					playerContainer.innerHTML = `<iframe src="${item.embed_url}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
				}
			}

			function toggleShortsLike(btn) {
				btn.classList.toggle('liked');
				const countElem = document.getElementById('shortsLikeCount');
				let count = parseInt(countElem.textContent || '328');
				if (btn.classList.contains('liked')) {
					countElem.textContent = count + 1;
				} else {
					countElem.textContent = count - 1;
				}
			}

			function slideShortsTrack(direction) {
				const track = document.querySelector('.baznas-shorts-track');
				if (track) {
					const scrollAmount = direction * 350;
					track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
				}
			}

			// Keyboard Navigation Support (Panah Atas / Bawah / Esc)
			document.addEventListener('keydown', function(e) {
				const modal = document.getElementById('baznasShortsModal');
				if (modal && modal.classList.contains('active')) {
					if (e.key === 'ArrowUp') {
						e.preventDefault();
						navigateShorts(-1);
					} else if (e.key === 'ArrowDown') {
						e.preventDefault();
						navigateShorts(1);
					} else if (e.key === 'Escape') {
						closeShortsViewer();
					}
				}
			});

			// Touch Swipe Support untuk Ponsel (Geser Atas / Bawah)
			let touchStartY = 0;
			document.addEventListener('touchstart', function(e) {
				const modal = document.getElementById('baznasShortsModal');
				if (modal && modal.classList.contains('active')) {
					touchStartY = e.changedTouches[0].screenY;
				}
			}, {passive: true});

			document.addEventListener('touchend', function(e) {
				const modal = document.getElementById('baznasShortsModal');
				if (modal && modal.classList.contains('active')) {
					const touchEndY = e.changedTouches[0].screenY;
					const diffY = touchStartY - touchEndY;
					if (Math.abs(diffY) > 50) {
						if (diffY > 0) {
							navigateShorts(1); // Swipe up -> Video berikutnya
						} else {
							navigateShorts(-1); // Swipe down -> Video sebelumnya
						}
					}
				}
			}, {passive: true});
			</script>

			<?php
			$ia = $this->model_utama->view_where_ordering_limit('iklantengah', array('posisi' => 'home'), 'id_iklantengah', 'ASC', 0, 1)->row_array();
			if ($ia):
				echo "<a href='$ia[url]' target='_blank' style='display:block; margin-bottom: 25px;'>";
				$string = $ia['gambar'];
				if ($ia['gambar'] != '') {
					if (preg_match("/swf\z/i", $string)) {
						echo "<embed src='" . base_url() . "asset/foto_iklantengah/$ia[gambar]' width='100%' height='90' quality='high' type='application/x-shockwave-flash'>";
					} else {
						echo "<img loading='lazy' width='100%' src='" . base_url() . "asset/foto_iklantengah/$ia[gambar]' title='$ia[judul]' alt='$ia[judul]' style='border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);' />";
					}
				}
				echo "</a>";
			endif;
			?>

			<!-- Widget Artikel -->
			<section class="modern-widget" aria-labelledby="articles-title">
				<div class="widget-header-modern">
					<h3 id="articles-title">Artikel</h3>
				</div>
				<div class="modern-article-list">
					<?php
					$pilihan = $this->db->query("SELECT berita.*, users.nama_lengkap, kategori.nama_kategori, kategori.kategori_seo FROM berita 
						LEFT JOIN users ON berita.username=users.username
						LEFT JOIN kategori ON berita.id_kategori=kategori.id_kategori 
						WHERE berita.status='Y' AND berita.tag LIKE '%artikel%' 
						ORDER BY id_berita DESC LIMIT 5");
					foreach ($pilihan->result_array() as $pi):
						$total_komentar = $this->model_utama->view_where('komentar', array('id_berita' => $pi['id_berita']))->num_rows();
						$isi_berita = strip_tags($pi['isi_berita']);
						$isi = substr($isi_berita, 0, 150) . '...';
						$thumb = (!empty($pi['gambar'])) ? base_url() . "asset/foto_berita/$pi[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";
						?>
						<article class="modern-article-item">
							<a href="<?php echo base_url() . $pi['judul_seo']; ?>" class="ma-img-wrapper"
								aria-label="<?php echo $pi['judul']; ?>">
								<img src="<?php echo $thumb; ?>" loading="lazy" class="ma-thumb-lg"
									alt="<?php echo $pi['judul']; ?>" title="<?php echo $pi['judul']; ?>">
							</a>
							<div class="ma-content">
								<header>
									<a href="<?php echo base_url() . "kategori/detail/$pi[kategori_seo]"; ?>"
										class="cat-pill"><?php echo $pi['nama_kategori']; ?></a>
									<h4><a
											href="<?php echo base_url() . $pi['judul_seo']; ?>"><?php echo $pi['judul']; ?></a>
									</h4>
								</header>
								<div class="ma-meta">
									<time datetime="<?php echo $pi['tanggal']; ?>">📅
										<?php echo tgl_indo($pi['tanggal']); ?></time>
									<span>🕒 <?php echo $pi['jam']; ?></span>
									<span>💬 <?php echo $total_komentar; ?></span>
								</div>
								<p class="ma-excerpt"><?php echo $isi; ?></p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<div class="widget-footer-modern">
					<a href="<?php echo base_url(); ?>tag/detail/artikel" class="more-pill">Lihat Semua Artikel</a>
				</div>
			</section>

			<!-- Widget Foto Galeri -->
			<section class="modern-widget" aria-labelledby="gallery-title">
				<div class="widget-header-modern">
					<h3 id="gallery-title">Galeri Foto</h3>
				</div>
				<div class="modern-grid-card gallery">
					<?php
					$q_foto = $this->db->query("SELECT g.jdl_gallery, g.gbr_gallery, a.album_seo FROM gallery g JOIN album a ON g.id_album=a.id_album ORDER BY g.id_gallery DESC LIMIT 6");
					foreach ($q_foto->result_array() as $f):
						$f_img = (!empty($f['gbr_gallery'])) ? base_url() . "asset/img_galeri/$f[gbr_gallery]" : base_url() . "asset/foto_berita/no-image.jpg";
						?>
						<a href="<?php echo base_url() . "albums/detail/$f[album_seo]"; ?>" class="gallery-item-card"
							aria-label="<?php echo $f['jdl_gallery']; ?>">
							<img src="<?php echo $f_img; ?>" loading="lazy" alt="<?php echo $f['jdl_gallery']; ?>"
								title="<?php echo $f['jdl_gallery']; ?>">
							<div class="gallery-overlay">
								<span><?php echo substr($f['jdl_gallery'], 0, 20); ?>...</span>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
				<div class="widget-footer-modern">
					<a href="<?php echo base_url(); ?>albums" class="more-pill">Lihat Galeri</a>
				</div>
			</section>

			<!-- Widget Rekomendasi BAZNAS (Random News) -->
			<section class="modern-widget" aria-labelledby="random-news-title">
				<div class="widget-header-modern">
					<h3 id="random-news-title">Rekomendasi BAZNAS</h3>
				</div>
				<div class="modern-article-list">
					<?php
					$random = $this->db->query("SELECT * FROM berita a JOIN kategori b ON a.id_kategori=b.id_kategori WHERE a.status='Y' AND (a.tag NOT LIKE '%artikel%' OR a.tag IS NULL) AND (b.kategori_seo != 'artikel' OR b.kategori_seo IS NULL) ORDER BY RAND() LIMIT 5");
					foreach ($random->result_array() as $r):
						$total_komentar = $this->model_utama->view_where('komentar', array('id_berita' => $r['id_berita']))->num_rows();
						$isi_berita = strip_tags($r['isi_berita']);
						$isi = substr($isi_berita, 0, 150) . '...';
						$thumb = (!empty($r['gambar'])) ? base_url() . "asset/foto_berita/$r[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";
						?>
						<article class="modern-article-item">
							<a href="<?php echo base_url() . $r['judul_seo']; ?>" class="ma-img-wrapper"
								aria-label="<?php echo $r['judul']; ?>">
								<img src="<?php echo $thumb; ?>" loading="lazy" class="ma-thumb-lg"
									alt="<?php echo $r['judul']; ?>" title="<?php echo $r['judul']; ?>">
							</a>
							<div class="ma-content">
								<header>
									<a href="<?php echo base_url() . "kategori/detail/$r[kategori_seo]"; ?>"
										class="cat-pill"><?php echo $r['nama_kategori']; ?></a>
									<h4><a href="<?php echo base_url() . $r['judul_seo']; ?>"><?php echo $r['judul']; ?></a>
									</h4>
								</header>
								<div class="ma-meta">
									<time datetime="<?php echo $r['tanggal']; ?>">📅
										<?php echo tgl_indo($r['tanggal']); ?></time>
									<span>🕒 <?php echo $r['jam']; ?></span>
									<span>💬 <?php echo $total_komentar; ?></span>
								</div>
								<p class="ma-excerpt"><?php echo $isi; ?></p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<div class="widget-footer-modern">
					<a href="<?php echo base_url(); ?>berita" class="more-pill">Semua Berita</a>
				</div>
			</section>


		</div>
		<div class="content-block left hidden-xs">
			<?php include "sidebar_kiri.php"; ?>
		</div>
	</div>
</div>
<div class="main-sidebar right hidden-xs">
	<?php include "sidebar_kanan.php"; ?>
</div>
<div style="clear: both; height: 1px;"></div>

<!-- Widget Iklan Tengah Grid -->
<section class="modern-widget ad-row"
	style="background: transparent; border: none; box-shadow: none; padding: 0; margin-top: 20px;">
	<div class="modern-grid-card" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
		<?php
		$advetorial = $this->model_utama->view_where_ordering_limit('iklantengah', array('posisi' => 'home_tengah'), 'id_iklantengah', 'ASC', 0, 6);
		foreach ($advetorial->result_array() as $row):
			$img_src = base_url() . "asset/foto_iklantengah/$row[gambar]";
			?>
			<div class="ad-card-item">
				<a href="<?php echo $row['url']; ?>" target="_blank" class="ad-link-modern">
					<?php if (preg_match("/swf\z/i", $row['gambar'])): ?>
						<embed src="<?php echo $img_src; ?>" width="100%" height="90" quality="high"
							type="application/x-shockwave-flash">
					<?php else: ?>
						<img src="<?php echo $img_src; ?>" loading="lazy" alt="<?php echo $row['judul']; ?>"
							class="ad-img-modern">
					<?php endif; ?>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<?php /* End of polished content */ ?>