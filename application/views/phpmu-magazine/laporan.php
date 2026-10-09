<div class="main-page left">
	<div class="single-block">
		<div class="content-block main left">
			<div class="block">
				<div class="block-title">
					<a href="<?php echo base_url(); ?>" class="right">Kembali ke Beranda</a>
					<h2>Laporan Keuangan BAZNAS</h2>
				</div>
				<div class="block-content">
					<div class="shortcode-content">
						<div class="paragraph-row">
							<div class="column12">

								<style>
									.lk-tab-nav { display: flex; gap: 10px; margin-bottom: 25px; padding: 0; list-style: none; }
									.lk-tab-btn { display: inline-block; padding: 10px 28px; border-radius: 30px; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; transition: all 0.2s; border: 2px solid #ddd; background: #f5f5f5; color: #555; }
									.lk-tab-btn:hover { background: #eee; color: #333; text-decoration: none; }
									.lk-tab-btn.active { background: #015E32; color: #fff !important; border-color: #015E32; box-shadow: 0 4px 12px rgba(1,94,50,0.3); }
									.lk-tab-panel { display: none; }
									.lk-tab-panel.active { display: block; }

									.lk-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px; }
									.lk-item { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: #fff; border: 1px solid #eaeaea; border-radius: 8px; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
									.lk-item:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.06); border-color: #d0d0d0; }
									.lk-left { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0; }
									.lk-icon { display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: #e8f5e9; border-radius: 50%; flex-shrink: 0; }
									.lk-icon svg { width: 24px; height: 24px; fill: #2e7d32; }
									.lk-icon.blue { background: #e3f2fd; }
									.lk-icon.blue svg { fill: #1565c0; }
									.lk-info { display: flex; flex-direction: column; min-width: 0; }
									.lk-title { font-size: 15px; font-weight: 600; color: #222; line-height: 1.4; margin-bottom: 2px; }
									.lk-meta { font-size: 13px; color: #777; margin-top: 5px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
									.lk-meta span { display: inline-flex; align-items: center; gap: 4px; }
									.lk-meta svg { width: 14px; height: 14px; fill: #999; }
									.lk-actions { display: flex; gap: 8px; flex-shrink: 0; margin-left: 15px; }
									.lk-btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 30px; font-size: 13px; font-weight: bold; text-decoration: none; transition: all 0.2s; white-space: nowrap; flex-shrink: 0; }
									.lk-btn:hover { text-decoration: none; }
									.lk-btn svg { width: 16px; height: 16px; fill: currentColor; }
									.lk-btn-preview { background: #fff; color: #015E32 !important; border: 2px solid #015E32; }
									.lk-btn-preview:hover { background: #e8f5e9; color: #015E32 !important; }
									.lk-btn-download { background: #015E32; color: #fff !important; border: 2px solid #015E32; }
									.lk-btn-download:hover { background: #014424; color: #fff !important; }
									.lk-empty { padding: 30px; text-align: center; color: #888; font-size: 14px; background: #fafafa; border-radius: 8px; border: 1px dashed #ddd; }
									@media(max-width: 600px) {
										.lk-item { flex-direction: column; align-items: flex-start; gap: 15px; padding: 15px; }
										.lk-left { width: 100%; }
										.lk-title { white-space: normal; }
										.lk-actions { width: 100%; margin-left: 0; }
										.lk-btn { flex: 1; justify-content: center; }
										.lk-tab-nav { flex-wrap: wrap; }
									}
								</style>

								<p style="color:#555; margin-bottom: 20px; font-size: 14px; line-height: 1.6;">Transparansi dan Akuntabilitas pengelolaan dana Zakat, Infak, dan Sedekah. Berikut adalah laporan keuangan tahunan dan bulanan BAZNAS Kabupaten Sumbawa.</p>

								<div class="lk-tab-nav">
									<a href="javascript:void(0)" class="lk-tab-btn active" onclick="switchTab('tahunan', this)">Laporan Tahunan</a>
									<a href="javascript:void(0)" class="lk-tab-btn" onclick="switchTab('bulanan', this)">Laporan Bulanan</a>
								</div>

								<!-- Panel Tahunan -->
								<div id="panel-tahunan" class="lk-tab-panel active">
									<?php if(count($tahunan) == 0): ?>
										<div class="lk-empty">Belum ada arsip Laporan Tahunan.</div>
									<?php else: ?>
										<div class="lk-list">
											<?php foreach($tahunan as $t): ?>
												<div class="lk-item">
													<div class="lk-left">
														<div class="lk-icon">
															<svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
														</div>
														<div class="lk-info">
															<div class="lk-title"><?= $t['judul']; ?> (<?= $t['tahun']; ?>)</div>
															<div class="lk-meta">
																<span><svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg> <?= tgl_indo(substr($t['tgl_posting'],0,10)); ?></span>
																<span><svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg> Diunduh <?= $t['hits']; ?> kali</span>
															</div>
														</div>
													</div>
													<div class="lk-actions">
														<a href="<?= base_url('laporan/detail/'.$t['id_laporan']); ?>" class="lk-btn lk-btn-preview">
															<svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg> Preview
														</a>
														<a href="<?= base_url('laporan/download/'.$t['id_laporan']); ?>" class="lk-btn lk-btn-download">
															<svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg> Unduh
														</a>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>

								<!-- Panel Bulanan -->
								<div id="panel-bulanan" class="lk-tab-panel">
									<?php if(count($bulanan) == 0): ?>
										<div class="lk-empty">Belum ada arsip Laporan Bulanan.</div>
									<?php else: ?>
										<div class="lk-list">
											<?php foreach($bulanan as $b): ?>
												<div class="lk-item">
													<div class="lk-left">
														<div class="lk-icon blue">
															<svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
														</div>
														<div class="lk-info">
															<div class="lk-title"><?= $b['judul']; ?> (<?= $b['bulan'].' '.$b['tahun']; ?>)</div>
															<div class="lk-meta">
																<span><svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg> <?= tgl_indo(substr($b['tgl_posting'],0,10)); ?></span>
																<span><svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg> Diunduh <?= $b['hits']; ?> kali</span>
															</div>
														</div>
													</div>
													<div class="lk-actions">
														<a href="<?= base_url('laporan/detail/'.$b['id_laporan']); ?>" class="lk-btn lk-btn-preview">
															<svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg> Preview
														</a>
														<a href="<?= base_url('laporan/download/'.$b['id_laporan']); ?>" class="lk-btn lk-btn-download">
															<svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg> Unduh
														</a>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='main-sidebar right'>
	<?php include "sidebar_halaman.php"; ?>
</div>

<script>
function switchTab(tab, el) {
	document.querySelectorAll('.lk-tab-panel').forEach(function(p){ p.classList.remove('active'); });
	document.querySelectorAll('.lk-tab-btn').forEach(function(b){ b.classList.remove('active'); });
	document.getElementById('panel-' + tab).classList.add('active');
	el.classList.add('active');
}
</script>
