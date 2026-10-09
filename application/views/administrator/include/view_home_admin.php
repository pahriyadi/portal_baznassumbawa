<?php 
  $number1 = $this->model_app->view('berita')->num_rows(); 
  $number2 = $this->model_app->view('halamanstatis')->num_rows(); 
  $number3 = $this->model_app->view('agenda')->num_rows(); 
  $number4 = $this->model_app->view('users')->num_rows();   

  // Realtime Analytics Data
  $waktu_online = time() - 600; // 10 menit terakhir
  $tgl_ini = date("Y-m-d");
  $q_online = $this->db->query("SELECT COUNT(*) as jml FROM statistik WHERE online > '$waktu_online'");
  $stat_online = ($q_online->num_rows() > 0) ? $q_online->row()->jml : 0;
  
  $q_today = $this->db->query("SELECT COUNT(*) as jml, SUM(hits) as hits FROM statistik WHERE tanggal = '$tgl_ini'");
  $stat_today_vis = ($q_today->num_rows() > 0 && $q_today->row()->jml != null) ? $q_today->row()->jml : 0;
  $stat_today_hits = ($q_today->num_rows() > 0 && $q_today->row()->hits != null) ? $q_today->row()->hits : 0;
  
  $q_total = $this->db->query("SELECT COUNT(*) as jml, SUM(hits) as hits FROM statistik");
  $stat_total_vis = ($q_total->num_rows() > 0 && $q_total->row()->jml != null) ? $q_total->row()->jml : 0;
  $stat_total_hits = ($q_total->num_rows() > 0 && $q_total->row()->hits != null) ? $q_total->row()->hits : 0;

  echo $this->session->flashdata('message'); 
       $this->session->unset_userdata('message');
?>

<style>
  /* BAZNAS Real-Time Analytics Dashboard Styling — Paper White Design System (desain_tabel.md) */
  .rt-dashboard-header {
    background: #ffffff !important;
    color: #333333 !important;
    padding: 20px 25px;
    border-radius: 0px !important;
    border: 1px solid #b8b8b8 !important;
    border-left: 5px solid #006937 !important;
    box-shadow: none !important;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
  }
  .rt-title-box h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #333333 !important;
  }
  .rt-title-box p {
    margin: 5px 0 0 0;
    font-size: 13px;
    color: #666666 !important;
  }
  .rt-sync-badge {
    background: #f8f9fa !important;
    border: 1px solid #b8b8b8 !important;
    color: #333333 !important;
    padding: 8px 16px;
    border-radius: 0px !important;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .rt-pulse-dot {
    width: 10px;
    height: 10px;
    background-color: #006937;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 8px #006937;
    animation: rtPulse 1.5s infinite;
  }
  @keyframes rtPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 105, 55, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(0, 105, 55, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 105, 55, 0); }
  }
  
  .rt-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }
  .rt-card {
    background: #ffffff !important;
    border: 1px solid #b8b8b8 !important;
    border-radius: 0px !important;
    padding: 20px;
    box-shadow: none !important;
    position: relative;
    overflow: hidden;
    transition: background-color 0.15s ease-in-out !important;
  }
  .rt-card:hover {
    background-color: #f0f7f3 !important;
    border-color: #006937 !important;
  }
  .rt-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: #006937;
  }
  .rt-card.gold::before { background: #F4C10F; }
  .rt-card.blue::before { background: #17a2b8; }
  .rt-card.red::before { background: #e74c3c; }
  
  .rt-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
  }
  .rt-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #666666 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .rt-card-icon {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa !important;
    border: 1px solid #b8b8b8 !important;
    color: #006937 !important;
    font-size: 18px;
    border-radius: 0px !important;
  }
  .rt-card.gold .rt-card-icon { color: #d4a017 !important; }
  .rt-card.blue .rt-card-icon { color: #17a2b8 !important; }
  .rt-card.red .rt-card-icon { color: #e74c3c !important; }
  
  .rt-card-value {
    font-size: 30px;
    font-weight: 800;
    color: #333333 !important;
    margin-bottom: 5px;
    font-family: 'Outfit', 'Inter', sans-serif;
  }
  .rt-card-subtext {
    font-size: 12px;
    color: #666666 !important;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  
  /* Override Info Box & Card for Paper White Consistency */
  .info-box, .card, .btn, .alert {
    border-radius: 0px !important;
    box-shadow: none !important;
    border: 1px solid #b8b8b8 !important;
    background-color: #ffffff !important;
  }
  .info-box .info-box-icon {
    background-color: #f8f9fa !important;
    border-right: 1px solid #b8b8b8 !important;
    color: #006937 !important;
    border-radius: 0px !important;
  }
  .info-box .info-box-content .info-box-text {
    color: #666666 !important;
    font-weight: 600 !important;
  }
  .info-box .info-box-content .info-box-number {
    color: #333333 !important;
    font-weight: 700 !important;
  }
  .card-header {
    background: #ffffff !important;
    border-bottom: 1px solid #b8b8b8 !important;
    padding: 15px 20px !important;
  }
  .card-title {
    font-weight: 800 !important;
    color: #333333 !important;
    font-size: 16px !important;
  }
  
  /* Leaderboard Styling */
  .rt-leaderboard-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .rt-leaderboard-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-bottom: 1px solid #b8b8b8 !important;
    background-color: #ffffff !important;
    transition: background-color 0.15s ease-in-out !important;
  }
  .rt-leaderboard-item:hover {
    background-color: #f0f7f3 !important;
  }
  .rt-rank-badge {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 12px;
    color: #333333 !important;
    background: #f8f9fa !important;
    border: 1px solid #b8b8b8 !important;
    margin-right: 15px;
    flex-shrink: 0;
    border-radius: 0px !important;
  }
  .rt-rank-1 { background: #F4C10F !important; color: #000 !important; }
  .rt-rank-2 { background: #e0e0e0 !important; color: #000 !important; }
  .rt-rank-3 { background: #f5d7b5 !important; color: #000 !important; }
  
  .rt-item-title {
    flex-grow: 1;
    font-weight: 600;
    font-size: 13px;
    color: #333333 !important;
    text-decoration: none;
    line-height: 1.4;
    padding-right: 15px;
  }
  .rt-item-title:hover {
    color: #006937 !important;
  }
  .rt-item-views {
    background: #f8f9fa !important;
    color: #333333 !important;
    font-weight: 700;
    font-size: 12px;
    padding: 5px 12px;
    white-space: nowrap;
    border: 1px solid #b8b8b8 !important;
    border-radius: 0px !important;
  }
</style>

<!-- Real-Time Analytics Banner -->
<div class="rt-dashboard-header">
  <div class="rt-title-box">
    <h2><i class="fas fa-chart-line" style="color: #F4C10F; margin-right: 10px;"></i> BAZNAS Real-Time Traffic Intelligence</h2>
    <p>Pantau arus kunjungan masyarakat, performa literasi zakat, dan interaksi publik secara live tanpa jeda.</p>
  </div>
  <div class="rt-sync-badge">
    <span class="rt-pulse-dot"></span>
    <span>LIVE SYNC: AKTIF (Auto-refresh 15s)</span>
    <span style="color: #F4C10F;">• <span id="rt-sync-time"><?php echo date("H:i:s"); ?></span> WITA</span>
  </div>
</div>

<!-- Real-Time Stats Grid -->
<div class="rt-grid">
  <!-- Card 1: Online Now -->
  <div class="rt-card red">
    <div class="rt-card-header">
      <span class="rt-card-title">Pengunjung Online</span>
      <div class="rt-card-icon"><i class="fas fa-signal"></i></div>
    </div>
    <div class="rt-card-value" id="rt-online-val"><?php echo $stat_online; ?></div>
    <div class="rt-card-subtext"><span style="color: #e74c3c; font-weight: 700;">● Live Now</span> (Aktif dalam 10 menit terakhir)</div>
  </div>
  
  <!-- Card 2: Today Visitors -->
  <div class="rt-card">
    <div class="rt-card-header">
      <span class="rt-card-title">Kunjungan Hari Ini</span>
      <div class="rt-card-icon"><i class="fas fa-user-check"></i></div>
    </div>
    <div class="rt-card-value" id="rt-today-vis-val"><?php echo number_format($stat_today_vis, 0, ',', '.'); ?></div>
    <div class="rt-card-subtext"><i class="far fa-calendar-alt"></i> Unique IP (<?php echo tgl_indo(date("Y-m-d")); ?>)</div>
  </div>
  
  <!-- Card 3: Today Hits -->
  <div class="rt-card gold">
    <div class="rt-card-header">
      <span class="rt-card-title">Pageviews Hari Ini</span>
      <div class="rt-card-icon"><i class="fas fa-fire"></i></div>
    </div>
    <div class="rt-card-value" id="rt-today-hits-val"><?php echo number_format($stat_today_hits, 0, ',', '.'); ?></div>
    <div class="rt-card-subtext"><i class="fas fa-mouse-pointer"></i> Total halaman dibaca hari ini</div>
  </div>
  
  <!-- Card 4: Total Visitors All Time -->
  <div class="rt-card blue">
    <div class="rt-card-header">
      <span class="rt-card-title">Total Akumulasi</span>
      <div class="rt-card-icon"><i class="fas fa-globe-asia"></i></div>
    </div>
    <div class="rt-card-value" id="rt-total-vis-val"><?php echo number_format($stat_total_vis, 0, ',', '.'); ?></div>
    <div class="rt-card-subtext"><i class="fas fa-chart-bar"></i> Total Pengunjung Sepanjang Masa</div>
  </div>
</div>

<!-- Info boxes (Konten Web) -->
<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1" style="border-radius: 0px !important;"><i class="fas fa-newspaper"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Berita & Artikel</span>
        <span class="info-box-number"><?php echo $number1; ?></span>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1" style="border-radius: 0px !important;"><i class="fas fa-file-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Halaman Statis</span>
        <span class="info-box-number"><?php echo $number2; ?></span>
      </div>
    </div>
  </div>
  <div class="clearfix hidden-md-up"></div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1" style="border-radius: 0px !important;"><i class="fas fa-calendar-check"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Agenda Kegiatan</span>
        <span class="info-box-number"><?php echo $number3; ?></span>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-warning elevation-1" style="border-radius: 0px !important;"><i class="fas fa-users-cog"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Administrator</span>
        <span class="info-box-number"><?php echo $number4; ?></span>
      </div>
    </div>
  </div>
</div>

<!-- Main Content Row -->
<div class="row">
  <!-- Left Column: Komentar Terbaru -->
  <div class="col-lg-6">
    <div class='card'>
      <div class='card-header'>
        <h3 class='card-title'><i class="fas fa-comments" style="color: #006937; margin-right: 8px;"></i> Komentar Terbaru</h3>
        <div class="card-tools">
          <a href='<?php echo base_url().$this->uri->segment(1); ?>/komentarberita' class="btn btn-sm btn-outline-success" style="border-radius: 0px !important; font-weight: 600;">Lihat Semua</a>
        </div>
      </div>
      <div class='card-body p-0'>
        <?php 
          $record = $this->db->query("SELECT a.*, b.judul, b.judul_seo FROM komentar a JOIN berita b ON a.id_berita=b.id_berita ORDER BY a.id_komentar DESC LIMIT 5");
          if ($record->num_rows() > 0) {
              foreach ($record->result_array() as $row){
                if ($row['aktif']=='N'){ $color = '#e67e22'; $bold = '700'; $bg = '#f8f9fa'; }else{ $color = '#333333'; $bold = '600'; $bg = '#ffffff'; }
                
                $isi_komentar = strip_tags($row['isi_komentar']); 
                $isi = substr($isi_komentar, 0, 110); 
                $space_pos = strrpos($isi, " ");
                if ($space_pos !== false) {
                    $isi = substr($isi_komentar, 0, $space_pos); 
                }
                $waktu = cek_terakhir($row['tgl'].' '.$row['jam_komentar']);            
                echo "<div style='background:$bg; padding: 15px 20px; border-bottom: 1px solid #b8b8b8; transition: background-color 0.15s ease-in-out;' onmouseover=\"this.style.backgroundColor='#f0f7f3'\" onmouseout=\"this.style.backgroundColor='$bg'\">
                       <a target='_BLANK' href='".base_url()."berita/detail/$row[judul_seo]' style='font-weight: 700; color: #006937; text-decoration: none;'>$row[judul]</a><br>
                      <span style='color:$color; font-weight:$bold; font-size: 13px;'><i class='fas fa-user-circle' style='margin-right: 4px;'></i> $row[nama_komentar]</span> <span class='text-muted' style='font-size: 12px;'>• $waktu yang lalu</span><br>
                      <p style='margin: 6px 0 8px 0; color: #444444; font-size: 13px; line-height: 1.4;'>\"$isi...\"</p>
                      <div>
                        <a href='".base_url().$this->uri->segment(1)."/edit_komentarberita/$row[id_komentar]' class='btn btn-xs btn-info' style='border-radius: 0px !important; box-shadow: none !important; border: 1px solid #b8b8b8 !important; padding: 2px 8px; font-size: 11px;'><i class='fas fa-edit'></i> Edit</a> 
                        <a href='".base_url().$this->uri->segment(1)."/delete_komentarberita/$row[id_komentar]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\" class='btn btn-xs btn-danger' style='border-radius: 0px !important; box-shadow: none !important; border: 1px solid #b8b8b8 !important; padding: 2px 8px; font-size: 11px;'><i class='fas fa-trash-alt'></i> Hapus</a>
                      </div>
                      </div>";
              }
          } else {
              echo "<div style='padding: 30px; text-align: center; color: #666666;'>Belum ada komentar masuk.</div>";
          }
        ?>
      </div>
    </div>
  </div><!-- /.Left col -->

  <!-- Right Column: Grafik & Top Artikel -->
  <div class="col-lg-6">
    <div class="card">
      <?php include "grafik.php"; ?>
    </div>
  </div><!-- right col -->
</div>
<!-- /.row -->

<!-- AJAX Real-Time Polling Script -->
<script>
  function fetchRealtimeStats() {
    $.ajax({
      url: '<?php echo base_url(); ?>administrator/realtime_stats',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          updateValueWithFlash('#rt-online-val', response.online);
          updateValueWithFlash('#rt-today-vis-val', response.today_vis);
          updateValueWithFlash('#rt-today-hits-val', response.today_hits);
          updateValueWithFlash('#rt-total-vis-val', response.total_vis);
          
          $('#rt-sync-time').text(response.time_updated);
          
          var chart = $('#container').highcharts();
          if (chart && response.chart_data && response.chart_labels) {
            chart.xAxis[0].setCategories(response.chart_labels, false);
            chart.series[0].setData(response.chart_data, true);
          }
        }
      },
      error: function() {
        console.log('Gagal menyinkronkan data realtime.');
      }
    });
  }

  function updateValueWithFlash(selector, newValue) {
    var el = $(selector);
    if (el.text() !== newValue.toString()) {
      el.css('color', '#F4C10F').fadeOut(150, function() {
        $(this).text(newValue).fadeIn(150, function() {
          $(this).css('color', '#1a1a1a');
        });
      });
    }
  }

  // Poll setiap 15 detik
  setInterval(fetchRealtimeStats, 15000);
</script>

