<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$json_gis_kec = json_encode($data_gis_kec);
$json_gis_desa = json_encode($data_gis_desa);
$json_kec_coords = json_encode($kec_coords);
$compare_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
$compare_peng = array_values($pengumpulan_monthly);
$compare_peny = array_values($penyaluran_monthly);
?>

<!-- Leaflet & DataTables CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css" />

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .monitoring-container {
        --primary-green: #006937;
        --dark-green: #004d20;
        --light-green: #e8f5ee;
        --accent-gold: #F4C10F;
        --dark-gold: #c9a00c;
        --text-dark: #1a2e1f;
        --text-muted: #5a7a65;
        --card-shadow: 0 4px 20px rgba(0, 105, 55, 0.10);
        --font-family: 'Inter', sans-serif;

        background: #f0f7f3;
        color: var(--text-dark);
        font-family: var(--font-family);
        border-radius: 16px;
        padding: 25px;
        margin-top: 10px;
        border: 1px solid #c8e6d6;
        position: relative;
    }

    .monitoring-container * {
        box-sizing: border-box;
    }

    /* Micro Bootstrap Grid scoped to monitoring-container */
    .monitoring-container .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -12px;
        margin-left: -12px;
    }

    .monitoring-container [class*="col-"] {
        position: relative;
        width: 100%;
        padding-right: 12px;
        padding-left: 12px;
        margin-bottom: 24px;
    }

    .monitoring-container .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    @media (min-width: 768px) {
        .monitoring-container .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (min-width: 1200px) {
        .monitoring-container .col-xl-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
        .monitoring-container .col-xl-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .monitoring-container .col-xl-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
    }

    /* ── CARDS ── */
    .monitoring-container .glass-card {
        background: #ffffff;
        border: 1px solid #d4eade;
        border-radius: 14px;
        box-shadow: var(--card-shadow);
        height: calc(100% - 24px);
        display: flex;
        flex-direction: column;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
    }
    .monitoring-container .glass-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 32px rgba(0, 105, 55, 0.18);
    }
    .monitoring-container .glass-card-header {
        padding: 16px 22px;
        background: var(--primary-green);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px solid var(--accent-gold);
    }
    .monitoring-container .glass-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .monitoring-container .glass-card-body {
        padding: 22px;
        flex-grow: 1;
    }

    /* ── METRIC WIDGETS ── */
    .monitoring-container .metric-card {
        padding: 22px 20px 20px;
        position: relative;
        overflow: hidden;
        border-top: 5px solid var(--primary-green);
    }
    .monitoring-container .metric-card.pengumpulan { border-top-color: var(--primary-green); }
    .monitoring-container .metric-card.penyaluran  { border-top-color: #1565c0; }
    .monitoring-container .metric-card.muzaki       { border-top-color: var(--accent-gold); }
    .monitoring-container .metric-card.mustahik     { border-top-color: #6a1b9a; }

    .monitoring-container .metric-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 100%;
        background: linear-gradient(180deg, rgba(0,105,55,0.04) 0%, transparent 60%);
        pointer-events: none;
    }

    .monitoring-container .metric-icon {
        font-size: 40px;
        position: absolute;
        right: 18px;
        bottom: 14px;
        opacity: 0.08;
        color: var(--primary-green);
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    .monitoring-container .metric-card:hover .metric-icon {
        transform: scale(1.15) rotate(5deg);
        opacity: 0.16;
    }
    .monitoring-container .metric-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 8px;
        letter-spacing: 1.2px;
    }
    .monitoring-container .metric-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--primary-green);
        margin-bottom: 6px;
        line-height: 1.2;
    }
    .monitoring-container .metric-card.penyaluran .metric-value { color: #1565c0; }
    .monitoring-container .metric-card.mustahik .metric-value   { color: #6a1b9a; }
    .monitoring-container .metric-sub {
        font-size: 12px;
        color: var(--text-muted);
    }

    /* ── RKAT PROGRESS BAR ── */
    .monitoring-container .progress-bar-custom {
        height: 10px;
        border-radius: 5px;
        background: #d4eade;
        overflow: hidden;
        margin-top: 10px;
        margin-bottom: 6px;
    }
    .monitoring-container .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-green), var(--accent-gold));
        border-radius: 5px;
        width: 0;
        transition: width 1.5s cubic-bezier(0.1, 0.8, 0.2, 1);
    }

    /* ── MAP ── */
    /* isolation: isolate membuat stacking context baru sehingga
       z-index internal Leaflet (400-700) tidak bocor ke header situs */
    .monitoring-container {
        isolation: isolate;
    }
    .monitoring-container #map-monitoring {
        height: 600px;
        width: 100%;
        border-radius: 0 0 14px 14px;
        background: #e8f4ec;
        isolation: isolate;
    }
    .monitoring-container .leaflet-container {
        background: #e8f4ec !important;
    }
    .monitoring-container .legend-box {
        background: rgba(0, 50, 20, 0.92) !important;
        backdrop-filter: blur(6px);
        border: 1px solid rgba(244, 193, 15, 0.4) !important;
        color: #ffffff !important;
        padding: 12px 16px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        line-height: 22px;
        font-size: 12px;
    }
    .monitoring-container .legend-box h4 {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--accent-gold);
        border-bottom: 1px solid rgba(244,193,15,0.3);
        padding-bottom: 4px;
    }
    .monitoring-container .legend-box i {
        width: 14px;
        height: 14px;
        float: left;
        margin-right: 8px;
        margin-top: 4px;
        border-radius: 3px;
    }

    /* ── LEAFLET TOOLTIPS & POPUPS ── */
    .kec-label {
        background: rgba(0, 105, 55, 0.92) !important;
        color: #ffffff !important;
        border: 1px solid #004d20 !important;
        font-size: 9px !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        padding: 2px 8px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3) !important;
    }
    .kec-label::before { border-right-color: rgba(0, 105, 55, 0.92) !important; }

    .desa-label {
        background: rgba(21, 101, 192, 0.92) !important;
        color: #ffffff !important;
        border: 1px solid #0d47a1 !important;
        font-size: 8px !important;
        font-weight: 600 !important;
        border-radius: 12px !important;
        padding: 1px 6px !important;
    }
    .desa-label::before { border-right-color: rgba(21, 101, 192, 0.92) !important; }

    .leaflet-popup-content-wrapper {
        background: #0d2217 !important; /* Premium dark forest green */
        color: #ffffff !important;
        border: 2px solid var(--accent-gold);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4) !important;
        border-radius: 12px !important;
        font-family: var(--font-family);
    }
    .leaflet-popup-tip {
        background: #0d2217 !important;
    }
    .popup-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
        color: white;
        padding: 8px 12px;
        margin: -14px -20px 10px;
        border-radius: 11px 11px 0 0;
        font-weight: 700;
        font-size: 13px;
        border-bottom: 2px solid var(--accent-gold);
    }
    .popup-row {
        display: flex;
        justify-content: space-between;
        margin: 5px 0;
        font-size: 12px;
    }
    .popup-label { color: #a2bca6 !important; }
    .popup-value { font-weight: 700; color: #ffffff !important; }

    /* ── DATA TABLE ── */
    .monitoring-container .table-responsive-wrapper {
        background: #f8fdf9;
        border-radius: 10px;
        padding: 14px;
        border: 1px solid #d4eade;
        overflow-x: auto;
    }
    .monitoring-container .dataTables_wrapper {
        color: var(--text-dark);
        font-family: var(--font-family);
    }
    .monitoring-container table.dataTable {
        border-collapse: collapse !important;
        background: transparent !important;
        color: var(--text-dark) !important;
        width: 100% !important;
    }
    .monitoring-container table.dataTable thead {
        background: var(--primary-green) !important;
    }
    .monitoring-container table.dataTable thead th {
        color: #ffffff !important;
        border-bottom: 3px solid var(--accent-gold) !important;
        font-weight: 700 !important;
        font-size: 13px;
        padding: 12px 10px !important;
    }
    .monitoring-container table.dataTable tbody td {
        border-bottom: 1px solid #e8f5ee !important;
        background: transparent !important;
        color: var(--text-dark) !important;
        vertical-align: middle;
        padding: 10px !important;
    }
    .monitoring-container table.dataTable tbody tr:hover td {
        background: #edf7f2 !important;
    }
    .monitoring-container table.dataTable tbody tr:nth-child(even) td {
        background: #f5fcf8 !important;
    }
    .monitoring-container table.dataTable tbody tr:nth-child(even):hover td {
        background: #e0f2e9 !important;
    }

    /* ── Pagination Controls ── */
    .monitoring-container .dataTables_wrapper .dataTables_paginate {
        margin-top: 14px;
    }
    .monitoring-container .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-block;
        min-width: 34px;
        height: 34px;
        line-height: 34px;
        text-align: center;
        padding: 0 10px !important;
        margin: 2px !important;
        border-radius: 6px !important;
        border: 1px solid #c8e6d6 !important;
        background: #ffffff !important;
        color: var(--primary-green) !important;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: none !important;
        text-decoration: none !important;
    }
    .monitoring-container .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--light-green) !important;
        border-color: var(--primary-green) !important;
        color: var(--primary-green) !important;
    }
    .monitoring-container .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .monitoring-container .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--primary-green) !important;
        border-color: var(--primary-green) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(0,105,55,0.3) !important;
    }
    .monitoring-container .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .monitoring-container .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f0f0f0 !important;
        border-color: #e0e0e0 !important;
        color: #bbb !important;
        cursor: default;
    }
    .monitoring-container .dataTables_wrapper .dataTables_paginate .ellipsis {
        color: var(--text-muted);
        padding: 0 6px;
    }

    /* ── Search & Length ── */
    .monitoring-container .dataTables_filter input,
    .monitoring-container .dataTables_length select {
        background-color: #ffffff !important;
        border: 1px solid #c8e6d6 !important;
        color: var(--text-dark) !important;
        border-radius: 6px;
        padding: 5px 10px;
        outline: none;
        transition: border-color 0.2s;
    }
    .monitoring-container .dataTables_filter input:focus,
    .monitoring-container .dataTables_length select:focus {
        border-color: var(--primary-green) !important;
        box-shadow: 0 0 0 3px rgba(0,105,55,0.1) !important;
    }
    .monitoring-container .dataTables_filter label,
    .monitoring-container .dataTables_length label,
    .monitoring-container .dataTables_info {
        color: var(--text-muted) !important;
        font-size: 13px;
    }
    .monitoring-container .dataTables_info {
        margin-top: 14px;
        padding-top: 0 !important;
    }
    .monitoring-container .badge-program {
        background: rgba(0, 105, 55, 0.15);
        color: #81c784;
        border: 1px solid rgba(0, 105, 55, 0.3);
        margin: 2px;
        font-size: 11px;
        display: inline-block;
        padding: .25em .4em;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
    }
    .monitoring-container .db-badge {
        background: rgba(0, 105, 55, 0.2);
        color: #81c784;
        border: 1px solid rgba(81, 199, 132, 0.4);
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .monitoring-container .d-flex { display: flex; }
    .monitoring-container .align-items-center { align-items: center; }
    .monitoring-container .justify-content-between { justify-content: space-between; }
    .monitoring-container .ml-auto { margin-left: auto; }
    .monitoring-container .mr-3 { margin-right: 1rem; }
    .monitoring-container .mt-4 { margin-top: 1.5rem; }
    .monitoring-container .w-100 { width: 100%; }
    .monitoring-container .text-right { text-align: right; }
    .monitoring-container .text-center { text-align: center; }
    .monitoring-container .text-success { color: #81c784 !important; }
    .monitoring-container .text-info { color: #00bcd4 !important; }
    .monitoring-container .text-warning { color: var(--accent-gold) !important; }
    .monitoring-container .font-weight-bold { font-weight: bold; }

    /* Custom style for dynamic filter selects */
    .monitoring-container .select-custom {
        width: 100% !important;
        border: 1px solid #c8e6d6 !important;
        border-radius: 6px !important;
        padding: 7px 12px !important;
        outline: none !important;
        background: #ffffff !important;
        color: var(--text-dark) !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
        height: 38px !important;
        cursor: pointer;
    }
    .monitoring-container .select-custom:focus {
        border-color: var(--primary-green) !important;
        box-shadow: 0 0 0 3px rgba(0,105,55,0.1) !important;
    }
</style>

<div class="full-width">
    <div class="block">
        <div class="block-title" style="border-bottom: 3px solid var(--baznas-yellow); padding-bottom: 10px; margin-bottom: 30px;">
            <a href="<?php echo base_url(); ?>" class="right" style="color: #ffffff !important; font-weight: 700; text-decoration: none;">&larr; Kembali Beranda</a>
            <h2 style="color: #ffffff !important; font-weight: 800; font-size: 24px;">Geografis Penyaluran & Monitoring Real-time ZIS</h2>
        </div>
        <div class="block-content">
            
            <div class="monitoring-container">
                
                <!-- ROW 1: Summary Statistics (Penyaluran ZIS) -->
                <div class="row">
                    
                    <!-- 1. Total Mustahik (DB Penyaluran) -->
                    <div class="col-xl-4 col-md-4 col-12">
                        <div class="glass-card metric-card mustahik">
                            <div class="metric-title"><i class="fa fa-users text-purple mr-3"></i> PENERIMA MANFAAT</div>
                            <div class="metric-value"><?= number_format($total_mustahik) ?> <span style="font-size:14px; font-weight:500;">Jiwa/Lembaga</span></div>
                            <div class="metric-sub">Mustahik Penerima Manfaat ZIS</div>
                            <i class="fa fa-heart metric-icon"></i>
                        </div>
                    </div>

                    <!-- 2. Kecamatan Terjangkau (DB Penyaluran) -->
                    <div class="col-xl-4 col-md-4 col-12">
                        <div class="glass-card metric-card muzaki">
                            <div class="metric-title"><i class="fa fa-map-o text-warning mr-3"></i> KECAMATAN TERJANGKAU</div>
                            <div class="metric-value"><?= number_format($total_kec_terjangkau) ?> <span style="font-size:14px; font-weight:500;">Kecamatan</span></div>
                            <div class="metric-sub">Dari total 24 Kecamatan di Sumbawa</div>
                            <i class="fa fa-map-marker metric-icon"></i>
                        </div>
                    </div>

                    <!-- 3. Desa Terjangkau (DB Penyaluran) -->
                    <div class="col-xl-4 col-md-4 col-12">
                        <div class="glass-card metric-card pengumpulan">
                            <div class="metric-title"><i class="fa fa-home text-success mr-3"></i> DESA / KELURAHAN</div>
                            <div class="metric-value"><?= number_format($total_desa_terjangkau) ?> <span style="font-size:14px; font-weight:500;">Wilayah</span></div>
                            <div class="metric-sub">Sebaran desa penerima bantuan</div>
                            <i class="fa fa-home metric-icon"></i>
                        </div>
                    </div>

                </div>

                <!-- ROW 1.5: Dynamic Multi-Filter Panel -->
                <div class="glass-card" style="margin-bottom: 24px; border-top: 4px solid var(--accent-gold); box-shadow: var(--card-shadow);">
                    <div class="glass-card-body" style="padding: 16px 20px;">
                        <div class="row" style="margin-bottom: 0; align-items: center;">
                            <div class="col-12 col-md-3" style="margin-bottom: 0;">
                                <label style="font-size: 11px; font-weight: 700; color: var(--primary-green); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block;">Tahun Penyaluran</label>
                                <select id="filter-tahun" class="form-control select-custom">
                                </select>
                            </div>
                            <div class="col-12 col-md-3" style="margin-bottom: 0;">
                                <label style="font-size: 11px; font-weight: 700; color: var(--primary-green); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block;">Filter Kecamatan</label>
                                <select id="filter-kecamatan" class="form-control select-custom">
                                    <option value="all" selected>Semua Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3" style="margin-bottom: 0;">
                                <label style="font-size: 11px; font-weight: 700; color: var(--primary-green); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block;">Filter Desa / Kelurahan</label>
                                <select id="filter-desa" class="form-control select-custom">
                                    <option value="all" selected>Semua Desa</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3" style="margin-bottom: 0;">
                                <label style="font-size: 11px; font-weight: 700; color: var(--primary-green); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block;">Filter Program ZIS</label>
                                <select id="filter-program" class="form-control select-custom">
                                    <option value="all" selected>Semua Program</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW 2: GIS Map Fullwidth -->
                <div class="row">
                    
                    <!-- Peta Sebaran Penyaluran GIS - Full Width -->
                    <div class="col-12">
                        <div class="glass-card" style="margin-bottom:0;">
                            <div class="glass-card-header">
                                <h5 class="glass-card-title">
                                    <i class="fa fa-globe text-success mr-2"></i> PETA SPASIAL DISTRIBUSI & PENYALURAN DANA ZIS
                                </h5>
                                <span class="db-badge"><i class="fa fa-database mr-1"></i> Integrasi Real-Time</span>
                            </div>
                            <div class="p-0">
                                <div id="map-monitoring"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ROW 3: Transparansi Tabel Distribusi per Kecamatan (DB Penyaluran) -->
                <div class="row" style="margin-top: 24px;">
                    <div class="col-12">
                        <div class="glass-card" style="margin-bottom:0;">
                            <div class="glass-card-header">
                                <h5 class="glass-card-title">
                                    <i class="fa fa-table text-success mr-2"></i> TABEL TRANSPARANSI PENYALURAN ZIS PER KECAMATAN
                                </h5>
                            </div>
                            <div class="glass-card-body">
                                <div class="table-responsive-wrapper">
                                    <table id="dtMonitoringGis" class="table table-hover w-100">
                                        <thead>
                                            <tr>
                                                <th width="50">No</th>
                                                <th>Tanggal</th>
                                                <th>Kecamatan</th>
                                                <th>Desa / Kelurahan</th>
                                                <th>Program</th>
                                                <th>Bantuan ZIS</th>
                                                <th class="text-center">Penerima</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</div>

<!-- JS Library CDNs -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<script>
// Kamus Bahasa Indonesia untuk DataTables
var dataTablesIndonesian = {
    "sEmptyTable":   "Tidak ada data yang tersedia pada tabel ini",
    "sProcessing":   "Sedang memproses...",
    "sLengthMenu":   "Tampilkan _MENU_ entri",
    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
    "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
    "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
    "sInfoPostFix":  "",
    "sSearch":       "Pencarian:",
    "sUrl":          "",
    "oPaginate": {
        "sFirst":    "Pertama",
        "sPrevious": "Sebelumnya",
        "sNext":     "Selanjutnya",
        "sLast":     "Terakhir"
    }
};

$(document).ready(function() {
    var rawData = <?= json_encode($detail_penyaluran) ?>;
    var kecCoords = <?= $json_kec_coords ?>;

    // 1. Inisialisasi DataTable (empty on load, filled by updateDashboard)
    var datatable = $('#dtMonitoringGis').DataTable({
        language: dataTablesIndonesian,
        responsive: true,
        autoWidth: false,
        order: [[1, 'desc']],
        columnDefs: []
    });

    // 2. SPASIAL MAP (LEAFLET.JS) — Default: OSM Standard
    var map = L.map('map-monitoring', { zoomControl: true }).setView([-8.62, 117.42], 9);
    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    osmLayer.addTo(map);

    var kecLayer = L.layerGroup().addTo(map);
    var desaLayer = L.layerGroup().addTo(map);

    function getColor(dana) {
        return dana > 100000000 ? '#c0392b' :
               dana > 50000000  ? '#e67e22' :
               dana > 10000000  ? '#f1c40f' :
               dana > 0         ? '#006937' :
                                  '#bdc3c7';
    }

    function formatRupiah(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function formatDateString(str) {
        if (!str) return '-';
        var parts = str.split('-');
        return parts.length === 3 ? parts[2] + '-' + parts[1] + '-' + parts[0] : str;
    }

    function escapeHtml(text) {
        if (!text) return '';
        var m = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(c) { return m[c]; });
    }

    // ── Filter init ──────────────────────────────────────────────
    function initFilters() {
        var kecSet = new Set(), progSet = new Set(), tahunSet = new Set();
        rawData.forEach(function(row) {
            if (row.nama_kecamatan) kecSet.add(row.nama_kecamatan);
            if (row.nama_program)   progSet.add(row.nama_program);
            if (row.tanggal)        tahunSet.add(row.tanggal.substring(0, 4));
        });

        // Tahun — default to current year (2026)
        var currentYear = "<?= date('Y') ?>";
        var yrSelect = $('#filter-tahun');
        Array.from(tahunSet).sort().reverse().forEach(function(yr) {
            var opt = new Option(yr, yr, yr === currentYear, yr === currentYear);
            yrSelect.append(opt);
        });

        // Kecamatan
        var kecSelect = $('#filter-kecamatan');
        Array.from(kecSet).sort().forEach(function(k) {
            kecSelect.append(new Option('Kec. ' + k, k));
        });

        // Program
        var progSelect = $('#filter-program');
        Array.from(progSet).sort().forEach(function(p) {
            progSelect.append(new Option(p, p));
        });

        updateDesaDropdown();
    }

    function updateDesaDropdown() {
        var selKec = $('#filter-kecamatan').val();
        var desaSelect = $('#filter-desa');
        desaSelect.html('<option value="all" selected>Semua Desa / Kelurahan</option>');
        var desaSet = new Set();
        rawData.forEach(function(row) {
            if ((selKec === 'all' || row.nama_kecamatan === selKec) && row.nama_desa) {
                desaSet.add(row.nama_desa);
            }
        });
        Array.from(desaSet).sort().forEach(function(d) {
            desaSelect.append(new Option(d, d));
        });
    }

    // ── Main dashboard update ────────────────────────────────────
    function updateDashboard() {
        var selTahun = $('#filter-tahun').val();
        var selKec   = $('#filter-kecamatan').val();
        var selDesa  = $('#filter-desa').val();
        var selProg  = $('#filter-program').val();

        var filtered = rawData.filter(function(row) {
            var yr = row.tanggal ? row.tanggal.substring(0, 4) : '';
            return (selTahun === 'all' || yr === selTahun) &&
                   (selKec   === 'all' || row.nama_kecamatan === selKec) &&
                   (selDesa  === 'all' || row.nama_desa === selDesa) &&
                   (selProg  === 'all' || row.nama_program === selProg);
        });

        // Update cards
        var totalMustahik = 0, uniqueKec = new Set(), uniqueDesa = new Set();
        filtered.forEach(function(row) {
            totalMustahik += parseInt(row.jumlah_mustahik) || 0;
            if (row.nama_kecamatan) uniqueKec.add(row.nama_kecamatan);
            if (row.nama_desa)      uniqueDesa.add(row.nama_desa);
        });
        $('.metric-card.mustahik    .metric-value').html(totalMustahik.toLocaleString('id-ID') + ' <span style="font-size:14px;font-weight:500;color:#5a7a65!important;">Jiwa/Lembaga</span>');
        $('.metric-card.muzaki      .metric-value').html(uniqueKec.size  + ' <span style="font-size:14px;font-weight:500;color:#5a7a65!important;">Kecamatan</span>');
        $('.metric-card.pengumpulan .metric-value').html(uniqueDesa.size + ' <span style="font-size:14px;font-weight:500;color:#5a7a65!important;">Wilayah</span>');

        kecLayer.clearLayers();
        desaLayer.clearLayers();

        // ── Kecamatan layer ──────────────────────────────────────
        var kecAgg = {};
        filtered.forEach(function(row) {
            var k = row.nama_kecamatan; if (!k) return;
            if (!kecAgg[k]) kecAgg[k] = { dana: 0, mustahik: 0, desas: new Set(), programs: new Set() };
            kecAgg[k].dana     += parseFloat(row.jumlah_dana)    || 0;
            kecAgg[k].mustahik += parseInt(row.jumlah_mustahik)  || 0;
            kecAgg[k].desas.add(row.nama_desa);
            kecAgg[k].programs.add(row.nama_program);
        });

        Object.keys(kecAgg).forEach(function(key) {
            if (!kecCoords[key]) return;
            var obj = kecAgg[key];
            var circle = L.circleMarker(kecCoords[key], {
                radius: 13, fillColor: getColor(obj.dana),
                color: '#ffffff', weight: 2, fillOpacity: 0.87
            });

            // Build detailed popup rows
            var kecRows = filtered.filter(function(r) { return r.nama_kecamatan === key; });
            var rowsHtml = '<div style="max-height:160px;overflow-y:auto;margin-top:8px;border-top:1px solid rgba(244,193,15,0.3);padding-top:6px;">';
            kecRows.forEach(function(r) {
                rowsHtml +=
                    '<div style="margin-bottom:6px;padding-bottom:4px;border-bottom:1px dashed rgba(255,255,255,0.1);">' +
                    '<strong style="color:#F4C10F!important;">' + escapeHtml(r.nama_desa) + '</strong><br>' +
                    '<span style="color:#a2bca6!important;">Program:</span> <span style="color:#fff!important;">' + escapeHtml(r.nama_program) + '</span><br>' +
                    '<span style="color:#a2bca6!important;">Bantuan:</span> <span style="color:#fff!important;">' + escapeHtml(r.nama_sub_program) + '</span><br>' +
                    '<span style="color:#a2bca6!important;">Penerima:</span> <span style="color:#fff!important;">' + (parseInt(r.jumlah_mustahik)||0).toLocaleString('id-ID') + ' Jiwa</span>' +
                    '</div>';
            });
            rowsHtml += '</div>';

            var popup =
                '<div style="min-width:250px;font-family:Inter,sans-serif;color:#fff!important;">' +
                '<div style="background:linear-gradient(135deg,#006937,#004d20);color:#fff!important;font-weight:700;font-size:14px;padding:8px 12px;margin:-14px -20px 10px;border-radius:11px 11px 0 0;border-bottom:2px solid #F4C10F;">Kec. ' + escapeHtml(key) + '</div>' +
                '<div style="display:flex;justify-content:space-between;font-size:12px;margin:5px 0;color:#fff!important;"><span style="color:#a2bca6!important;">Penerima Manfaat:</span> <span style="font-weight:700;color:#F4C10F!important;">' + obj.mustahik.toLocaleString('id-ID') + ' Jiwa</span></div>' +
                '<div style="display:flex;justify-content:space-between;font-size:12px;margin:5px 0;color:#fff!important;"><span style="color:#a2bca6!important;">Desa Terjangkau:</span> <span style="font-weight:700;color:#fff!important;">' + obj.desas.size + ' Desa</span></div>' +
                '<div style="font-weight:700;font-size:11px;color:#F4C10F!important;text-transform:uppercase;letter-spacing:.5px;margin-top:8px;">Rincian Penyaluran:</div>' +
                rowsHtml + '</div>';

            circle.bindPopup(popup, { maxWidth: 310 });
            circle.bindTooltip('<b>Kec. ' + escapeHtml(key) + '</b>', {
                permanent: false, direction: 'top', className: 'kec-label'
            });
            kecLayer.addLayer(circle);
        });

        // ── Desa layer ───────────────────────────────────────────
        var desaAgg = {};
        filtered.forEach(function(row) {
            var d = row.nama_desa; if (!d) return;
            if (!desaAgg[d]) {
                desaAgg[d] = {
                    kec: row.nama_kecamatan,
                    lat: parseFloat(row.latitude),
                    lng: parseFloat(row.longitude),
                    mustahik: 0, dana: 0, programs: new Set()
                };
            }
            desaAgg[d].mustahik += parseInt(row.jumlah_mustahik) || 0;
            desaAgg[d].dana     += parseFloat(row.jumlah_dana)    || 0;
            desaAgg[d].programs.add(row.nama_program);
        });

        Object.keys(desaAgg).forEach(function(key) {
            var obj = desaAgg[key];
            if (isNaN(obj.lat) || isNaN(obj.lng) || obj.lat === 0 || obj.lng === 0) return;
            var marker = L.circleMarker([obj.lat, obj.lng], {
                radius: 7, fillColor: '#00bcd4',
                color: '#ffffff', weight: 1.5, fillOpacity: 0.95
            });

            var popupHtml =
                '<div style="min-width:220px;font-family:Inter,sans-serif;color:#fff!important;">' +
                '<div style="background:linear-gradient(135deg,#00838f,#006064);color:#fff!important;font-weight:700;font-size:13px;padding:6px 10px;margin:-14px -20px 10px;border-radius:11px 11px 0 0;border-bottom:2px solid #F4C10F;">' +
                '<i class="fa fa-home mr-1"></i>' + escapeHtml(key) + '</div>' +
                '<div style="display:flex;justify-content:space-between;font-size:11px;margin:4px 0;color:#fff!important;"><span style="color:#a2bca6!important;">Kecamatan:</span> <span style="font-weight:700;color:#fff!important;">Kec. ' + escapeHtml(obj.kec) + '</span></div>' +
                '<div style="display:flex;justify-content:space-between;font-size:11px;margin:4px 0;color:#fff!important;"><span style="color:#a2bca6!important;">Penerima:</span> <span style="font-weight:700;color:#F4C10F!important;">' + obj.mustahik.toLocaleString('id-ID') + ' Jiwa</span></div>' +
                '<div style="font-weight:700;font-size:10px;color:#F4C10F!important;text-transform:uppercase;margin-top:6px;">Program ZIS:</div>' +
                '<div style="font-size:10px;color:#fff!important;margin-top:4px;padding-top:4px;border-top:1px solid rgba(255,255,255,.1);">' +
                Array.from(obj.programs).map(function(p){ return '&bull; ' + escapeHtml(p); }).join('<br>') +
                '</div></div>';

            marker.bindPopup(popupHtml, { maxWidth: 260 });
            marker.bindTooltip('<b>' + escapeHtml(key) + '</b>', {
                permanent: true, direction: 'right', offset: [6, 0], className: 'desa-label'
            });
            desaLayer.addLayer(marker);
        });

        // ── DataTable update ─────────────────────────────────────
        datatable.clear();
        filtered.forEach(function(row, i) {
            datatable.row.add([
                i + 1,
                formatDateString(row.tanggal),
                '<b>Kec. ' + escapeHtml(row.nama_kecamatan) + '</b>',
                row.nama_desa,
                row.nama_program,
                row.nama_sub_program,
                (parseInt(row.jumlah_mustahik)||0).toLocaleString() + ' Jiwa'
            ]);
        });
        datatable.draw();
    }

    // Map layer controls
    var overlays = {
        '<i class="fa fa-circle text-warning mr-1"></i> Layer Kecamatan':   kecLayer,
        '<i class="fa fa-circle text-info mr-1"></i> Layer Desa/Kelurahan': desaLayer
    };
    L.control.layers({}, overlays, { collapsed: false, position: 'bottomright' }).addTo(map);

    // Legend
    var legend = L.control({ position: 'bottomleft' });
    legend.onAdd = function() {
        var div = L.DomUtil.create('div', 'legend-box');
        div.innerHTML = '<h4>SEBARAN PENYALURAN</h4>' +
            '<i style="background:#bdc3c7"></i> Belum disalurkan<br>' +
            '<i style="background:#006937"></i> &lt; 10 Juta<br>' +
            '<i style="background:#f1c40f"></i> 10 - 50 Juta<br>' +
            '<i style="background:#e67e22"></i> 50 - 100 Juta<br>' +
            '<i style="background:#c0392b"></i> &gt; 100 Juta<br>' +
            '<hr style="margin:8px 0;border-top:1px solid rgba(255,255,255,.1)">' +
            '<i style="background:#00bcd4"></i> Titik Desa Penerima';
        return div;
    };
    legend.addTo(map);

    // Event listeners
    $('#filter-tahun, #filter-program').on('change', updateDashboard);
    $('#filter-kecamatan').on('change', function() { updateDesaDropdown(); updateDashboard(); });
    $('#filter-desa').on('change', updateDashboard);

    // Bootstrap
    initFilters();
    updateDashboard();
    setTimeout(function() { map.invalidateSize(); }, 500);
});
</script>

