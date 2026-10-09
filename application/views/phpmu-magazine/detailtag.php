					<div class="main-content">
						<div class="main-page left">
							<div class="double-block">
								<div class="content-block main left">
									<div class="block">
										<div class="block-title" style="background: var(--baznas-green); border-bottom: 3px solid var(--baznas-yellow);">
											<a href="<?php echo base_url(); ?>" class="right">Kembali ke Beranda</a>
											<h2>Informasi Tag "<?php echo "$rows[nama_tag]"; ?>"</h2>
										</div>
										<div class="block-content">
											<div class="modern-article-list">
											<?php
											  foreach ($beritatag->result_array() as $r) {	
												  $baca = $r['dibaca']+1;	
												  $isi_berita = strip_tags($r['isi_berita']); 
												  $isi = substr($isi_berita, 0, 180); 
												  $space_pos = strrpos($isi, " ");
												  if ($space_pos !== false) {
												  	$isi = substr($isi_berita, 0, $space_pos);
												  }
												  $isi = $isi . '...';
												  $judul = $r['judul']; 
												  $total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r['id_berita']))->num_rows();
												  $thumb = (!empty($r['gambar'])) ? base_url() . "asset/foto_berita/$r[gambar]" : base_url() . "asset/foto_berita/small_no-image.jpg";
												  
												  echo "<div class='modern-article-item'>
                            <a href='".base_url()."$r[judul_seo]' class='ma-img-wrapper' aria-label='$r[judul]'>
                                <img src='$thumb' loading='lazy' class='ma-thumb-lg' alt='$r[judul]' title='$r[judul]' />
                            </a>
							<div class='ma-content'>
								<header>
									<a href='".base_url()."kategori/detail/$r[kategori_seo]' class='cat-pill'>$r[nama_kategori]</a>
									<h4><a title='$r[judul]' href='".base_url()."$r[judul_seo]'>$judul</a></h4>
								</header>
								<div class='ma-meta'>
									<time datetime='$r[tanggal]'>📅 ".tgl_indo($r['tanggal'])."</time>
									<span>🕒 $r[jam]</span>
									<span>💬 $total_komentar</span>
								</div>
								<p class='ma-excerpt'>$isi</p>
							</div>
						</div>";
											  }
											?>
											</div>
											<div class="pagination">
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

					</div>
					
				<!-- END .wrapper -->
				</div>
				
			</div>