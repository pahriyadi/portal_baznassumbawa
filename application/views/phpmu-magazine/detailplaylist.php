<div class="main-page left">
	<div class="double-block">
		<div class="content-block main left">
			<div class="block">
				<div class="block-title" style="background: var(--baznas-green); border-bottom: 3px solid var(--baznas-yellow); border-radius: 0px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
					<h2 style="color: #fff; font-size: 18px; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;"><i class="fa fa-folder-open" style="color: var(--baznas-yellow);"></i> Kategori: <?php echo "$rows[jdl_playlist]"; ?></h2>
					<a href="<?php echo base_url(); ?>playlist" style="color: #fff; font-size: 13px; font-weight: 600;"><i class="fa fa-arrow-left"></i> Semua Kategori</a>
				</div>
				<div class="block-content" style="padding: 20px 0;">
					<?php 
					$playlist_shorts_list = array();
					$short_idx = 0;

					if ($detailplaylist->num_rows() > 0): ?>
						<div class="modern-grid-card">
							<?php foreach ($detailplaylist->result_array() as $vd): 
								$jns = isset($vd['jenis_video']) ? trim($vd['jenis_video']) : '';
								$raw_url = trim($vd['youtube']);
								$embed_url = '';
								$type = 'youtube';

								if (preg_match("/\.(mp4|webm|ogg)$/i", $raw_url)) {
									$type = 'mp4';
									$embed_url = base_url() . 'asset/video/' . $raw_url;
									if (filter_var($raw_url, FILTER_VALIDATE_URL)) {
										$embed_url = $raw_url;
									}
								} else if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $raw_url, $match)) {
									$type = 'youtube';
									$embed_url = "https://www.youtube.com/embed/" . $match[1];
								} else {
									$type = 'iframe';
									$embed_url = $raw_url;
								}

								$t_img = (!empty($vd['gbr_video'])) ? base_url() . "asset/img_video/" . $vd['gbr_video'] : base_url() . "asset/img_video/no-image.jpg";
								if (empty($vd['gbr_video']) && $type === 'youtube' && !empty($match[1])) {
									$t_img = "https://img.youtube.com/vi/" . $match[1] . "/hqdefault.jpg";
								}

								if ($jns === 'short') {
									$playlist_shorts_list[] = array(
										'id' => $vd['id_video'],
										'title' => $vd['jdl_video'],
										'embed_url' => $embed_url,
										'type' => $type,
										'thumb' => $t_img
									);
									$current_short_idx = $short_idx++;
							?>
									<article class="vid-card-item" onclick="openPlaylistShortsViewer(<?php echo $current_short_idx; ?>)" style="cursor: pointer;">
										<div class="vid-thumb-wrapper" style="position: relative;">
											<span style="position: absolute; top: 8px; left: 8px; background: #ff0050; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 6px; z-index: 5;"><i class="fa fa-bolt"></i> SHORTS</span>
											<img src="<?php echo $t_img; ?>" loading="lazy" alt="<?php echo $vd['jdl_video']; ?>" title="<?php echo $vd['jdl_video']; ?>">
											<div class="play-btn-overlay">
												<svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
											</div>
										</div>
										<h4 class="vid-title"><?php echo $vd['jdl_video']; ?></h4>
									</article>
							<?php } else { ?>
									<article class="vid-card-item" onclick="window.location.href='<?php echo base_url() . 'playlist/watch/' . $vd['video_seo']; ?>'" style="cursor: pointer;">
										<div class="vid-thumb-wrapper" style="position: relative;">
											<span style="position: absolute; top: 8px; left: 8px; background: var(--baznas-green); color: var(--baznas-yellow); font-size: 10px; font-weight: 800; padding: 2px 6px; z-index: 5;"><i class="fa fa-film"></i> VIDEO</span>
											<img src="<?php echo $t_img; ?>" loading="lazy" alt="<?php echo $vd['jdl_video']; ?>" title="<?php echo $vd['jdl_video']; ?>">
											<div class="play-btn-overlay">
												<svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
											</div>
										</div>
										<h4 class="vid-title"><?php echo $vd['jdl_video']; ?></h4>
									</article>
							<?php } ?>
							<?php endforeach; ?>
						</div>
					<?php else: ?>
						<div style="padding: 30px; text-align: center; color: #777; font-size: 13px; background: #fafafa; border: 1px dashed #ddd;">Belum ada video pada kategori ini.</div>
					<?php endif; ?>
					
					<div class="pagination" style="margin-top: 20px;">
						<?php echo $this->pagination->create_links(); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="content-block right">
			<?php include "sidebar_kiri.php"; ?>
		</div>
	</div>
</div>

<div class="main-sidebar right">
	<?php include "sidebar_kanan.php"; ?>
</div>
<div class="clear-float"></div>

<!-- TikTok / YouTube Shorts Fullscreen Modal Player untuk Halaman Detail Playlist -->
<div id="baznasShortsModal" class="shorts-modal-overlay">
	<div class="shorts-phone-frame">
		<div class="shorts-modal-topbar">
			<div class="shorts-topbar-title"><i class="fa fa-bolt" style="color: #ff0050;"></i> BAZNAS SHORTS</div>
			<button class="shorts-close-btn" onclick="closePlaylistShortsViewer()" title="Tutup">✕</button>
		</div>
		<div class="shorts-player-container" id="shortsModalPlayer"></div>
		<div class="shorts-modal-bottom-info">
			<div class="shorts-author-tag">@BAZNASSumbawa <span class="verified-badge-icon">✔</span></div>
			<div class="shorts-modal-title" id="shortsModalTitle">Judul Video Singkat</div>
			<div class="shorts-music-ticker"><i class="fa fa-music"></i> <span>Suara asli - BAZNAS Sumbawa</span></div>
		</div>
		<div class="shorts-side-actions">
			<button class="shorts-action-btn" onclick="this.classList.toggle('liked')">
				<span style="font-size: 16px;">❤️</span>
				<span class="shorts-action-label">Suka</span>
			</button>
			<button class="shorts-action-btn" onclick="alert('Tautan video berhasil disalin!')">
				<span style="font-size: 16px;">↗️</span>
				<span class="shorts-action-label">Bagikan</span>
			</button>
		</div>
		<div class="shorts-nav-arrows">
			<button class="nav-arrow-btn" onclick="navigatePlaylistShorts(-1)" title="Video Sebelumnya">▲</button>
			<button class="nav-arrow-btn" onclick="navigatePlaylistShorts(1)" title="Video Berikutnya">▼</button>
		</div>
	</div>
</div>

<script>
let currentPlShortIndex = 0;
const plShortsData = <?php echo json_encode($playlist_shorts_list); ?>;

function openPlaylistShortsViewer(index) {
	if (!plShortsData || plShortsData.length === 0) return;
	currentPlShortIndex = index;
	renderPlaylistShortVideo();
	const modal = document.getElementById('baznasShortsModal');
	modal.classList.add('active');
	document.body.style.overflow = 'hidden';
}

function closePlaylistShortsViewer() {
	const modal = document.getElementById('baznasShortsModal');
	modal.classList.remove('active');
	document.getElementById('shortsModalPlayer').innerHTML = '';
	document.body.style.overflow = 'auto';
}

function navigatePlaylistShorts(direction) {
	currentPlShortIndex += direction;
	if (currentPlShortIndex < 0) currentPlShortIndex = plShortsData.length - 1;
	if (currentPlShortIndex >= plShortsData.length) currentPlShortIndex = 0;
	renderPlaylistShortVideo();
}

function renderPlaylistShortVideo() {
	const item = plShortsData[currentPlShortIndex];
	if (!item) return;
	document.getElementById('shortsModalTitle').textContent = item.title;
	const playerContainer = document.getElementById('shortsModalPlayer');
	if (item.type === 'mp4') {
		playerContainer.innerHTML = `<video controls autoplay loop style="width: 100%; height: 100%; object-fit: cover;"><source src="${item.embed_url}" type="video/mp4"></video>`;
	} else {
		playerContainer.innerHTML = `<iframe src="${item.embed_url}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
	}
}

document.addEventListener('keydown', function(e) {
	const modal = document.getElementById('baznasShortsModal');
	if (modal && modal.classList.contains('active')) {
		if (e.key === 'ArrowUp') { e.preventDefault(); navigatePlaylistShorts(-1); }
		else if (e.key === 'ArrowDown') { e.preventDefault(); navigatePlaylistShorts(1); }
		else if (e.key === 'Escape') { closePlaylistShortsViewer(); }
	}
});

let plTouchStartY = 0;
document.addEventListener('touchstart', function(e) {
	const modal = document.getElementById('baznasShortsModal');
	if (modal && modal.classList.contains('active')) { plTouchStartY = e.changedTouches[0].screenY; }
}, {passive: true});

document.addEventListener('touchend', function(e) {
	const modal = document.getElementById('baznasShortsModal');
	if (modal && modal.classList.contains('active')) {
		const diffY = plTouchStartY - e.changedTouches[0].screenY;
		if (Math.abs(diffY) > 50) {
			if (diffY > 0) navigatePlaylistShorts(1);
			else navigatePlaylistShorts(-1);
		}
	}
}, {passive: true});
</script>