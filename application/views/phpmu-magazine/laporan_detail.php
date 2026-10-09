<?php $periode = $rows['kategori'] == 'Tahunan' ? 'Tahun '.$rows['tahun'] : $rows['bulan'].' '.$rows['tahun']; ?>
<div class="main-page left">
	<div class="single-block">
		<div class="content-block main left">
			<div class="block">
				<div class="block-title">
					<a href="<?= base_url('laporan'); ?>" class="right">Kembali ke Daftar Laporan</a>
					<h2>Detail Laporan <?= $rows['kategori']; ?></h2>
				</div>
				<div class="block-content">
					<div class="shortcode-content">
						<div class="paragraph-row">
							<div class="column12">

								<style>
									.lkd-breadcrumb { font-size: 13px; color: #888; margin-bottom: 15px; }
									.lkd-breadcrumb a { color: #015E32; text-decoration: none; }
									.lkd-breadcrumb a:hover { text-decoration: underline; }
									.lkd-header h1 { font-size: 22px; font-weight: 700; color: #222; margin: 0 0 15px 0; line-height: 1.4; }
									.lkd-meta { font-size: 13px; color: #888; padding-bottom: 18px; margin-bottom: 20px; border-bottom: 1px solid #eee; display: flex; gap: 20px; flex-wrap: wrap; }
									.lkd-meta span { display: inline-flex; align-items: center; gap: 5px; }
									.lkd-meta svg { width: 15px; height: 15px; fill: #aaa; }
									.lkd-body { font-size: 15px; line-height: 1.8; color: #444; margin-bottom: 30px; }
									.lkd-body img { max-width: 100% !important; height: auto !important; }
									.lkd-download-box { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; background: #e8f5e9; padding: 20px 25px; border-radius: 10px; border-left: 5px solid #015E32; margin-bottom: 30px; }
									.lkd-download-info h4 { margin: 0; font-size: 16px; font-weight: 700; color: #015E32; }
									.lkd-download-info p { margin: 5px 0 0; font-size: 13px; color: #555; }
									.lkd-download-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: #015E32; color: #fff !important; border-radius: 30px; font-size: 14px; font-weight: 700; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 12px rgba(1,94,50,0.25); }
									.lkd-download-btn:hover { background: #014424; color: #fff !important; text-decoration: none; box-shadow: 0 6px 18px rgba(1,94,50,0.35); }
									.lkd-download-btn svg { width: 18px; height: 18px; fill: #fff; }
									.lkd-preview-title { font-size: 16px; font-weight: 700; color: #333; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 3px solid #015E32; display: inline-block; }
									.lkd-preview-title svg { width: 18px; height: 18px; fill: #d32f2f; vertical-align: middle; margin-right: 5px; }
									.lkd-iframe-wrap { border: 1px solid #ccc; border-radius: 8px; overflow: hidden; background: #525659; }
									.lkd-iframe-wrap iframe { width: 100%; height: 650px; border: none; display: block; }
									.lkd-error { padding: 20px; text-align: center; color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px; font-size: 14px; }
									@media(max-width: 600px) {
										.lkd-download-box { flex-direction: column; align-items: flex-start; }
										.lkd-download-btn { width: 100%; justify-content: center; }
										.lkd-iframe-wrap iframe { height: 400px; }
									}
								</style>

								<div class="lkd-breadcrumb">
									<a href="<?= base_url('laporan'); ?>">Laporan Keuangan</a> &raquo; <?= $rows['kategori']; ?> &raquo; <?= $rows['judul']; ?>
								</div>

								<div class="lkd-header">
									<h1><?= $rows['judul']; ?> (<?= $periode; ?>)</h1>
								</div>

								<div class="lkd-meta">
									<span><svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg> <?= tgl_indo(substr($rows['tgl_posting'],0,10)); ?></span>
									<span><svg viewBox="0 0 24 24"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg> Laporan <?= $rows['kategori']; ?></span>
									<span><svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg> Diunduh <?= $rows['hits']; ?> kali</span>
								</div>

								<?php if(!empty(trim($rows['keterangan']))): ?>
								<div class="lkd-body">
									<?= $rows['keterangan']; ?>
								</div>
								<?php endif; ?>

								<div class="lkd-download-box">
									<div class="lkd-download-info">
										<h4>Unduh Dokumen Lengkap</h4>
										<p>File: <?= $rows['nama_file']; ?></p>
									</div>
									<a href="<?= base_url('laporan/download/'.$rows['id_laporan']); ?>" class="lkd-download-btn">
										<svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg> DOWNLOAD SEKARANG
									</a>
								</div>

								<?php if(!empty($rows['nama_file']) && file_exists('asset/laporan/'.$rows['nama_file'])): ?>
									<div class="lkd-preview-title">
										<svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg> Preview Dokumen
									</div>
									<div class="lkd-iframe-wrap">
										<iframe src="<?= base_url('asset/laporan/'.$rows['nama_file']); ?>"></iframe>
									</div>
								<?php else: ?>
									<div class="lkd-error">Dokumen PDF tidak ditemukan di server.</div>
								<?php endif; ?>

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
