<div class="full-width">
	<div class="block">
		<div class="block-title" style="border-bottom: 3px solid var(--baznas-yellow); padding-bottom: 10px; margin-bottom: 30px;">
			<a href="<?php echo base_url('bayarzakat'); ?>" class="right" style="color: var(--baznas-green); font-weight: 700; text-decoration: none;">&larr; Rekening ZIS</a>
			<h2 style="color: var(--baznas-green); font-weight: 800; font-size: 24px;">Konfirmasi Pembayaran Zakat / ZIS Online</h2>
		</div>
		<div class="block-content">
			<style>
				.form-container {
					max-width: 650px;
					margin: 0 auto 50px auto;
					background: #ffffff;
					border-radius: 16px;
					box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
					border: 1px solid #f0f0f0;
					padding: 35px;
					box-sizing: border-box;
				}
				.form-desc {
					text-align: center;
					margin-bottom: 30px;
				}
				.form-desc h3 {
					color: var(--baznas-green);
					font-weight: 800;
					font-size: 22px;
					margin-bottom: 8px;
				}
				.form-desc p {
					font-size: 14px;
					color: #666;
					line-height: 1.6;
				}
				.form-group {
					margin-bottom: 20px;
					display: flex;
					flex-direction: column;
					gap: 6px;
				}
				.form-group label {
					font-size: 13px;
					font-weight: 700;
					color: #333333;
				}
				.form-control {
					width: 100%;
					padding: 12px 16px;
					border-radius: 10px;
					border: 1px solid #ddd;
					font-size: 14px;
					box-sizing: border-box;
					transition: all 0.25s ease;
					font-family: inherit;
				}
				.form-control:focus {
					outline: none;
					border-color: var(--baznas-green);
					box-shadow: 0 0 0 3px rgba(0, 105, 55, 0.1);
				}
				.form-row {
					display: grid;
					grid-template-columns: 1fr 1fr;
					gap: 20px;
				}
				.upload-box {
					border: 2px dashed #ccc;
					border-radius: 12px;
					padding: 20px;
					text-align: center;
					background: #fafafa;
					cursor: pointer;
					transition: all 0.25s ease;
					position: relative;
				}
				.upload-box:hover {
					border-color: var(--baznas-green);
					background: #f4f8f4;
				}
				.upload-box svg {
					width: 40px;
					height: 40px;
					fill: #999;
					margin-bottom: 10px;
					transition: fill 0.25s ease;
				}
				.upload-box:hover svg {
					fill: var(--baznas-green);
				}
				.upload-box p {
					margin: 0;
					font-size: 13px;
					color: #666;
					font-weight: 500;
				}
				.upload-box span {
					font-size: 11px;
					color: #999;
					display: block;
					margin-top: 4px;
				}
				.upload-box input[type="file"] {
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					opacity: 0;
					cursor: pointer;
				}
				.btn-submit {
					background: var(--baznas-green);
					color: #ffffff !important;
					font-weight: 700;
					font-size: 15px;
					border: none;
					padding: 14px 30px;
					border-radius: 10px;
					width: 100%;
					cursor: pointer;
					box-shadow: 0 4px 15px rgba(0, 105, 55, 0.2);
					transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
					text-align: center;
					margin-top: 15px;
				}
				.btn-submit:hover {
					background: #004d28;
					transform: translateY(-2px);
					box-shadow: 0 6px 20px rgba(0, 105, 55, 0.3);
				}
				.alert {
					padding: 15px 20px;
					border-radius: 12px;
					font-size: 14px;
					line-height: 1.5;
					margin-bottom: 25px;
					display: flex;
					align-items: center;
					gap: 12px;
				}
				.alert-success {
					background: #e8f5e9;
					border: 1px solid #c8e6c9;
					color: #2e7d32;
				}
				.alert-error {
					background: #ffebee;
					border: 1px solid #ffcdd2;
					color: #c62828;
				}
				.alert svg {
					width: 20px;
					height: 20px;
					fill: currentColor;
					flex-shrink: 0;
				}
				@media (max-width: 600px) {
					.form-row {
						grid-template-columns: 1fr;
						gap: 0;
					}
					.form-container {
						padding: 20px;
					}
				}
			</style>

			<div class="form-container">
				<div class="form-desc">
					<h3>Verifikasi Setoran Zakat & Sedekah</h3>
					<p>Silakan isi detail pembayaran dan unggah bukti transfer Anda di bawah ini agar petugas BAZNAS Kabupaten Sumbawa dapat segera memproses dan mencatatnya.</p>
				</div>

				<?php if ($this->session->flashdata('pesan_sukses')): ?>
					<div class="alert alert-success">
						<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
						<div><?php echo $this->session->flashdata('pesan_sukses'); ?></div>
					</div>
				<?php endif; ?>

				<?php if ($this->session->flashdata('pesan_error')): ?>
					<div class="alert alert-error">
						<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
						<div><?php echo $this->session->flashdata('pesan_error'); ?></div>
					</div>
				<?php endif; ?>

				<?php if (validation_errors()): ?>
					<div class="alert alert-error" style="align-items: flex-start;">
						<svg viewBox="0 0 24 24" style="margin-top: 2px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
						<div>
							<strong>Harap perbaiki kesalahan berikut:</strong>
							<ul style="margin: 5px 0 0 15px; padding: 0; font-size: 13px;">
								<?php echo validation_errors('<li>', '</li>'); ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>

				<?php echo form_open_multipart('bayarzakat/konfirmasi'); ?>
					<div class="form-group">
						<label for="nama">Nama Lengkap Muzakki / Pengirim <span style="color: red;">*</span></label>
						<input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Ahmad Fauzi" required value="<?php echo set_value('nama'); ?>">
					</div>

					<div class="form-row">
						<div class="form-group">
							<label for="email">Alamat Email (Opsional)</label>
							<input type="email" id="email" name="email" class="form-control" placeholder="Contoh: ahmad@gmail.com" value="<?php echo set_value('email'); ?>">
						</div>
						<div class="form-group">
							<label for="no_telp">Nomor WhatsApp / HP <span style="color: red;">*</span></label>
							<input type="tel" id="no_telp" name="no_telp" class="form-control" placeholder="Contoh: 08123456789" required value="<?php echo set_value('no_telp'); ?>">
						</div>
					</div>

					<div class="form-row">
						<div class="form-group">
							<label for="jenis_dana">Pilih Jenis Dana <span style="color: red;">*</span></label>
							<select id="jenis_dana" name="jenis_dana" class="form-control" required>
								<option value="">-- Pilih Jenis Dana --</option>
								<option value="Zakat" <?php echo set_select('jenis_dana', 'Zakat'); ?>>Zakat</option>
								<option value="Infak" <?php echo set_select('jenis_dana', 'Infak'); ?>>Infak</option>
								<option value="Sedekah" <?php echo set_select('jenis_dana', 'Sedekah'); ?>>Sedekah</option>
								<option value="Fidyah" <?php echo set_select('jenis_dana', 'Fidyah'); ?>>Fidyah / Kaffarah</option>
							</select>
						</div>
						<div class="form-group">
							<label for="jumlah">Nominal Transfer (Rp) <span style="color: red;">*</span></label>
							<input type="number" id="jumlah" name="jumlah" class="form-control" placeholder="Contoh: 250000" required value="<?php echo set_value('jumlah'); ?>">
						</div>
					</div>

					<div class="form-group">
						<label for="bank_pengirim">Nama Bank Pengirim Anda <span style="color: red;">*</span></label>
						<input type="text" id="bank_pengirim" name="bank_pengirim" class="form-control" placeholder="Contoh: Bank NTB Syariah / BSI / Mandiri" required value="<?php echo set_value('bank_pengirim'); ?>">
					</div>

					<div class="form-group">
						<label for="rek_tujuan">Rekening Tujuan BAZNAS <span style="color: red;">*</span></label>
						<select id="rek_tujuan" name="rek_tujuan" class="form-control" required>
							<option value="">-- Pilih Rekening Tujuan BAZNAS --</option>
							<?php foreach($rekening as $rek): ?>
								<?php if(!empty($rek['rek_zakat'])): ?>
									<option value="<?php echo $rek['nama_bank'] . ' (Zakat) - ' . $rek['rek_zakat']; ?>" <?php echo set_select('rek_tujuan', $rek['nama_bank'] . ' (Zakat) - ' . $rek['rek_zakat']); ?>>
										<?php echo $rek['nama_bank']; ?> Zakat (<?php echo $rek['rek_zakat']; ?>)
									</option>
								<?php endif; ?>
								<?php if(!empty($rek['rek_infaq'])): ?>
									<option value="<?php echo $rek['nama_bank'] . ' (Infaq) - ' . $rek['rek_infaq']; ?>" <?php echo set_select('rek_tujuan', $rek['nama_bank'] . ' (Infaq) - ' . $rek['rek_infaq']); ?>>
										<?php echo $rek['nama_bank']; ?> Infaq/Sedekah (<?php echo $rek['rek_infaq']; ?>)
									</option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label for="tanggal_transfer">Tanggal Transfer <span style="color: red;">*</span></label>
						<input type="date" id="tanggal_transfer" name="tanggal_transfer" class="form-control" required value="<?php echo set_value('tanggal_transfer', date('Y-m-d')); ?>">
					</div>

					<div class="form-group">
						<label>Unggah Foto Resi / Bukti Transfer <span style="color: red;">*</span></label>
						<div class="upload-box" id="upload-wrapper">
							<svg viewBox="0 0 24 24" id="upload-icon">
								<path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
							</svg>
							<p id="upload-text">Seret & lepas foto bukti transfer, atau klik untuk memilih file</p>
							<span>Mendukung: JPG, JPEG, PNG (Maks. 2MB)</span>
							<input type="file" id="bukti_transfer" name="bukti_transfer" required accept="image/*" onchange="previewFile(this)">
						</div>
					</div>

					<button type="submit" name="submit" value="1" class="btn-submit">Kirim Konfirmasi Pembayaran</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	function previewFile(input) {
		const wrapper = document.getElementById('upload-wrapper');
		const icon = document.getElementById('upload-icon');
		const text = document.getElementById('upload-text');
		
		if (input.files && input.files[0]) {
			const file = input.files[0];
			
			// Ubah UI menjadi status file terpilih
			wrapper.style.borderColor = 'var(--baznas-green)';
			wrapper.style.background = '#f4f8f4';
			icon.innerHTML = '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>'; // Ikon checklist centang
			icon.style.fill = 'var(--baznas-green)';
			text.innerHTML = '<strong>' + file.name + '</strong> (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
		}
	}
</script>
