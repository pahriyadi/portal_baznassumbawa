<script type="text/javascript" src="<?php echo base_url(); ?>asset/admin/plugins/jQuery/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            data: {
                table: 'datatable'
            },
            chart: {
                type: 'column',
                style: {
                    fontFamily: "'Outfit', 'Inter', 'Segoe UI', sans-serif"
                }
            },
            title: {
                text: ''
            },
            colors: ['#006937', '#0b7c44', '#F4C10F'],
            xAxis: {
                labels: {
                    style: { color: '#444', fontWeight: '600', fontSize: '11px' }
                }
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Jumlah Kunjungan',
                    style: { color: '#666666', fontWeight: '700' }
                },
                gridLineColor: '#e0e0e0'
            },
            plotOptions: {
                column: {
                    borderRadius: 0,
                    borderWidth: 1,
                    borderColor: '#b8b8b8',
                    colorByPoint: false,
                    pointPadding: 0.1,
                    groupPadding: 0.15
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                backgroundColor: '#1a1a1a',
                style: { color: '#ffffff' },
                borderWidth: 0,
                borderRadius: 0,
                shadow: false,
                formatter: function () {
                    return '<b style="color:#F4C10F">' + this.point.name + '</b><br/>' +
                        'Kunjungan: <b>' + this.point.y + ' Orang</b>';
                }
            }
        });
    });
</script>

<div class="card-header">
    <h3 class="card-title"><i class="fas fa-chart-bar" style="color: #006937; margin-right: 8px;"></i> Grafik Tren Kunjungan (10 Hari Terakhir)</h3>
</div>

<div class="card-body chat" id="chat-card" style="padding: 20px;">
<div id="container" style="min-width: 310px; height: 230px; margin: 0 auto"></div>
<table id="datatable" style='display:none'>
<thead>
    <tr>
        <th></th>
        <th>Jumlah Kunjungan</th>
    </tr>
</thead>
<tbody>
    <?php 
        $grafik = $this->model_app->grafik_kunjungan();
        // Urutkan dari tanggal terlama ke terbaru agar grafik berurut ke kanan dengan benar
        $arr_grafik = array_reverse($grafik->result_array());
        foreach ($arr_grafik as $row){
            echo "<tr>
                    <th>".tgl_grafik($row['tanggal'])."</th>
                    <td>$row[jumlah]</td>
                    </tr>";
        }
    ?>
</tbody>
</table>

<div class="card-header" style="border-top: 1px solid #b8b8b8; margin-top: 20px; padding: 15px 0 !important;">
    <h3 class="card-title"><i class="fas fa-trophy" style="color: #F4C10F; margin-right: 8px;"></i> 10 Artikel Terpopuler Sepanjang Masa</h3>
</div>
<div class="card-body p-0" style="margin-top: 10px;">
    <ul class="rt-leaderboard-list">
    <?php 
        $teratas = $this->db->query("SELECT * FROM berita ORDER BY dibaca DESC LIMIT 10");
        $rank = 1;
        foreach ($teratas->result_array() as $row){
            $rank_class = ($rank <= 3) ? "rt-rank-$rank" : "";
            $dibaca_fmt = number_format($row['dibaca'], 0, ',', '.');
            echo "<li class='rt-leaderboard-item'>
                    <span class='rt-rank-badge $rank_class'>#$rank</span>
                    <a target='_BLANK' href='".base_url()."$row[judul_seo]' class='rt-item-title' title='$row[judul]'>$row[judul]</a>
                    <span class='rt-item-views'><i class='fas fa-fire' style='color: #e74c3c; margin-right: 4px;'></i> $dibaca_fmt dibaca</span>
                  </li>";
            $rank++;
        }
    ?>
    </ul>
</div>
</div><!-- /.card (chat card) -->

