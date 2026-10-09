<div class="main-page left">
	<div class="single-block">
		<div class="content-block main left">
			<div class="block">
				<div class="block-title">
					<a href="<?php echo base_url(); ?>" class="right">Kembali ke Beranda</a>
					<h2>Daftar Dokumen & File Unduhan</h2>
				</div>
				<div class="block-content">
					<div class="shortcode-content">
						<div class="paragraph-row">
							<div class="column12">
								
								<style>
									.baznas-dl-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px; }
									.baznas-dl-item { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: #fff; border: 1px solid #eaeaea; border-radius: 8px; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
									.baznas-dl-item:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.06); border-color: #d0d0d0; }
									.baznas-dl-left { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0; }
									.baznas-dl-icon { display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: #e8f5e9; border-radius: 50%; flex-shrink: 0; }
									.baznas-dl-icon svg { width: 24px; height: 24px; fill: #2e7d32; }
									.baznas-dl-info { display: flex; flex-direction: column; min-width: 0; }
									.baznas-dl-title { font-size: 15px; font-weight: 600; color: #222; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
									.baznas-dl-meta { font-size: 13px; color: #777; margin-top: 5px; display: flex; align-items: center; gap: 15px; }
									.baznas-dl-meta span { display: inline-flex; align-items: center; gap: 4px; }
									.baznas-dl-meta svg { width: 14px; height: 14px; fill: #999; }
									.baznas-dl-btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #015E32; color: #fff !important; border-radius: 30px; font-size: 13px; font-weight: bold; text-decoration: none; transition: background 0.2s; white-space: nowrap; flex-shrink: 0; }
									.baznas-dl-btn:hover { background: #014424; }
									.baznas-dl-btn svg { width: 16px; height: 16px; fill: #fff; }
									@media(max-width: 600px) {
										.baznas-dl-item { flex-direction: column; align-items: flex-start; gap: 15px; padding: 15px; }
										.baznas-dl-left { width: 100%; }
										.baznas-dl-title { white-space: normal; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
										.baznas-dl-btn { width: 100%; justify-content: center; }
									}
								</style>

								<div class="baznas-dl-list">
								<?php
									$no=$this->uri->segment(3)+1;
									if(empty($no)) $no=1;
									foreach ($download->result_array() as $r) {	
										echo "<div class='baznas-dl-item'>
												<div class='baznas-dl-left'>
													<div class='baznas-dl-icon' title='Nomor $no'>
														<svg viewBox='0 0 24 24'><path d='M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z'/></svg>
													</div>
													<div class='baznas-dl-info'>
														<div class='baznas-dl-title'>$r[judul]</div>
														<div class='baznas-dl-meta'>
															<span><svg viewBox='0 0 24 24'><path d='M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z'/></svg> Telah diunduh $r[hits] kali</span>
														</div>
													</div>
												</div>
												<a href='".base_url()."download/file/$r[nama_file]' class='baznas-dl-btn'>
													<svg viewBox='0 0 24 24'><path d='M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z'/></svg>
													Unduh File
												</a>
											  </div>";
										$no++;
									}
								?>
								</div>
								<div class="pagination">
									<?php echo $this->pagination->create_links(); ?>
								</div>
							</div>
						</div>
						
						<?php
						$diklan = $this->model_utama->view_where_ordering_limit('iklantengah',array('posisi'=>'hal_download'),'id_iklantengah','ASC',0,5);
						foreach ($diklan->result_array() as $ia) {
							echo "<a href='$ia[url]' target='_blank'>";
								$string = $ia['gambar'];
								if ($ia['gambar'] != ''){
									if(preg_match("/swf\z/i", $string)) {
										echo "<embed style='margin-top:-10px' src='".base_url()."asset/foto_iklantengah/$ia[gambar]' width='100%' height=90px quality='high' type='application/x-shockwave-flash'>";
									} else {
										echo "<img style='margin-top:-10px; margin-bottom:5px' width='100%' src='".base_url()."asset/foto_iklantengah/$ia[gambar]' title='$ia[judul]' />";
									}
								}
							echo "</a>";
							if (trim($ia['source']) != ''){ echo "$ia[source]"; }
						}
						?>
						<div class="article-title">
							<div class="share-block right">
								<div>
									<div class="share-article left">
										<span>Social media</span>
										<strong>Share this article</strong>
									</div>
									<div class="left">
										<script language="javascript">
										document.write("<a href='http://www.facebook.com/share.php?u=" + document.URL + " ' target='_blank' class='custom-soc icon-text'>&#62220;</a> <a href='http://twitter.com/home/?status=" + document.URL + "' target='_blank' class='custom-soc icon-text'>&#62217;</a> <a href='https://plus.google.com/share?url=" + document.URL + "' target='_blank' class='custom-soc icon-text'>&#62223;</a>");
										</script>
										<a href="#" class="custom-soc icon-text">&#62232;</a>
										<a href="#" class="custom-soc icon-text">&#62226;</a>
									</div>
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