<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    public function index() {
        // Database 1: Penyaluran (Distribusi)
        $db_penyaluran_host = "localhost";
        $db_penyaluran_user = "root";
        $db_penyaluran_pass = "";
        $db_penyaluran_name = "db_baznas_sumbawa";

        // Database 2: Pengumpulan (Penerimaan)
        $db_pengumpulan_host = "localhost";
        $db_pengumpulan_user = "root";
        $db_pengumpulan_pass = "";
        $db_pengumpulan_name = "pengumpulan_zis";

        // Koneksi ke Database Penyaluran
        $conn_peny = @mysqli_connect($db_penyaluran_host, $db_penyaluran_user, $db_penyaluran_pass, $db_penyaluran_name);
        if (!$conn_peny) {
            show_error("Koneksi Database Penyaluran Gagal: " . mysqli_connect_error());
        }

        // Koneksi ke Database Pengumpulan
        $conn_peng = @mysqli_connect($db_pengumpulan_host, $db_pengumpulan_user, $db_pengumpulan_pass, $db_pengumpulan_name);
        if (!$conn_peng) {
            mysqli_close($conn_peny);
            show_error("Koneksi Database Pengumpulan Gagal: " . mysqli_connect_error());
        }

        // A. Ringkasan Pengumpulan (Total Dana, Jumlah Muzaki, Jumlah UPZ)
        $res_total_peng = mysqli_fetch_assoc(mysqli_query($conn_peng, "SELECT SUM(jumlah_pembayaran) as total FROM transaksi"));
        $total_pengumpulan = $res_total_peng['total'] ?: 0;

        $res_total_muzaki = mysqli_fetch_assoc(mysqli_query($conn_peng, "SELECT COUNT(*) as total FROM muzaki"));
        $total_muzaki = $res_total_muzaki['total'] ?: 0;

        $res_total_upz = mysqli_fetch_assoc(mysqli_query($conn_peng, "SELECT COUNT(*) as total FROM upz"));
        $total_upz = $res_total_upz['total'] ?: 0;

        // B. Target Tahunan Pengumpulan RKAT 2026
        $res_target = mysqli_fetch_assoc(mysqli_query($conn_peng, "SELECT nominal_target FROM target_pengumpulan_tahunan WHERE tahun = 2026 LIMIT 1"));
        $target_rkat = $res_target['nominal_target'] ?: 7641000000; // Fallback ke default target RKAT 2026 jika kosong
        $realisasi_persen = ($total_pengumpulan / $target_rkat) * 100;

        // C. Proporsi Pengumpulan per Jenis Pembayaran (Zakat, Infak, DSKL, CSR)
        $query_jenis = "
            SELECT j.nama_jenis, SUM(t.jumlah_pembayaran) as total 
            FROM transaksi t 
            JOIN jenis_pembayaran j ON t.id_jenis_pembayaran = j.id 
            GROUP BY j.id
        ";
        $res_jenis = mysqli_query($conn_peng, $query_jenis);
        $jenis_labels = [];
        $jenis_data = [];
        while ($row = mysqli_fetch_assoc($res_jenis)) {
            $jenis_labels[] = $row['nama_jenis'];
            $jenis_data[] = (float)$row['total'];
        }

        // D. Pengumpulan Bulanan Tahun 2026
        $pengumpulan_monthly = array_fill(1, 12, 0);
        $q_peng_monthly = mysqli_query($conn_peng, "
            SELECT MONTH(tanggal_transaksi) as bulan, SUM(jumlah_pembayaran) as total 
            FROM transaksi 
            WHERE YEAR(tanggal_transaksi) = 2026 
            GROUP BY MONTH(tanggal_transaksi)
        ");
        if ($q_peng_monthly) {
            while ($row = mysqli_fetch_assoc($q_peng_monthly)) {
                $pengumpulan_monthly[(int)$row['bulan']] = (float)$row['total'];
            }
        }

        // =========================================================================
        // 3. QUERY DATA DARI DATABASE PENYALURAN
        // =========================================================================

        // A. Ringkasan Penyaluran (Total Tersalurkan, Jumlah Mustahik)
        $current_year = date('Y');
        $res_total_peny = mysqli_fetch_assoc(mysqli_query($conn_peny, "SELECT SUM(jumlah_dana) as total FROM penyaluran WHERE YEAR(tanggal) = $current_year"));
        $total_penyaluran = $res_total_peny['total'] ?: 0;

        $res_total_mustahik = mysqli_fetch_assoc(mysqli_query($conn_peny, "SELECT SUM(jumlah_mustahik) as total FROM penyaluran WHERE YEAR(tanggal) = $current_year"));
        $total_mustahik = $res_total_mustahik['total'] ?: 0;

        // B. Hitung Kecamatan dan Desa Terjangkau
        $res_kec_terjangkau = mysqli_fetch_assoc(mysqli_query($conn_peny, "SELECT COUNT(DISTINCT id_kecamatan) as total FROM penyaluran WHERE jumlah_dana > 0 AND YEAR(tanggal) = $current_year"));
        $total_kec_terjangkau = $res_kec_terjangkau['total'] ?: 0;

        $res_desa_terjangkau = mysqli_fetch_assoc(mysqli_query($conn_peny, "SELECT COUNT(DISTINCT id_desa) as total FROM penyaluran WHERE jumlah_dana > 0 AND YEAR(tanggal) = $current_year"));
        $total_desa_terjangkau = $res_desa_terjangkau['total'] ?: 0;

        // C. Ambil detail data penyaluran untuk Tabel Transparansi + koordinat desa
        $query_detail_peny = "
            SELECT 
                p.id,
                p.tanggal,
                k.nama_kecamatan,
                d.nama_desa,
                pr.nama_program,
                sp.nama_sub_program,
                p.jumlah_dana,
                p.jumlah_mustahik,
                p.keterangan,
                COALESCE(NULLIF(p.latitude,''), NULLIF(d.latitude,'')) AS latitude,
                COALESCE(NULLIF(p.longitude,''), NULLIF(d.longitude,'')) AS longitude
            FROM penyaluran p
            JOIN kecamatan k ON p.id_kecamatan = k.id
            JOIN desa d ON p.id_desa = d.id
            JOIN sub_program sp ON p.id_sub_program = sp.id
            JOIN program pr ON sp.id_program = pr.id
            ORDER BY p.tanggal DESC, p.id DESC
        ";
        $res_detail_peny = mysqli_query($conn_peny, $query_detail_peny);
        $detail_penyaluran = [];
        if ($res_detail_peny) {
            while ($row = mysqli_fetch_assoc($res_detail_peny)) {
                $detail_penyaluran[] = $row;
            }
        }

        // B. Penyaluran Bulanan Tahun 2026
        $penyaluran_monthly = array_fill(1, 12, 0);
        $q_peny_monthly = mysqli_query($conn_peny, "
            SELECT MONTH(tanggal) as bulan, SUM(jumlah_dana) as total 
            FROM penyaluran 
            WHERE YEAR(tanggal) = 2026 
            GROUP BY MONTH(tanggal)
        ");
        if ($q_peny_monthly) {
            while ($row = mysqli_fetch_assoc($q_peny_monthly)) {
                $penyaluran_monthly[(int)$row['bulan']] = (float)$row['total'];
            }
        }

        // C. Ambil data GIS Kecamatan (untuk lingkaran sebaran)
        $query_gis_kec = "
            SELECT
                k.id,
                k.nama_kecamatan,
                k.latitude,
                k.longitude,
                COALESCE(SUM(p.jumlah_dana), 0) AS total_dana,
                COALESCE(SUM(p.jumlah_mustahik), 0) AS total_mustahik,
                COUNT(DISTINCT p.id_desa) AS total_desa,
                GROUP_CONCAT(DISTINCT d.nama_desa ORDER BY d.nama_desa SEPARATOR '||') AS desa_list,
                GROUP_CONCAT(DISTINCT pr.nama_program ORDER BY pr.nama_program SEPARATOR ', ') AS program_list
            FROM kecamatan k
            LEFT JOIN penyaluran p ON k.id = p.id_kecamatan
            LEFT JOIN desa d ON p.id_desa = d.id
            LEFT JOIN sub_program sp ON p.id_sub_program = sp.id
            LEFT JOIN program pr ON sp.id_program = pr.id
            GROUP BY k.id, k.nama_kecamatan, k.latitude, k.longitude
            ORDER BY k.nama_kecamatan
        ";
        $res_gis_kec = mysqli_query($conn_peny, $query_gis_kec);
        $data_gis_kec = [];
        while ($row = mysqli_fetch_assoc($res_gis_kec)) {
            $row['desa_array'] = $row['desa_list'] ? explode('||', $row['desa_list']) : [];
            $data_gis_kec[] = $row;
        }

        // D. Ambil data GIS Desa (untuk marker pin detail)
        $query_gis_desa = "
            SELECT
                d.id AS desa_id,
                d.nama_desa,
                d.latitude,
                d.longitude,
                k.nama_kecamatan,
                GROUP_CONCAT(DISTINCT pr.nama_program ORDER BY pr.nama_program SEPARATOR ', ') AS program_list,
                COALESCE(SUM(p.jumlah_dana), 0) AS total_dana,
                COALESCE(SUM(p.jumlah_mustahik), 0) AS total_mustahik
            FROM penyaluran p
            JOIN desa d ON p.id_desa = d.id
            JOIN kecamatan k ON p.id_kecamatan = k.id
            JOIN sub_program sp ON p.id_sub_program = sp.id
            JOIN program pr ON sp.id_program = pr.id
            GROUP BY p.id_desa, d.nama_desa, d.latitude, d.longitude, k.nama_kecamatan
            ORDER BY k.nama_kecamatan, d.nama_desa
        ";
        $res_gis_desa = mysqli_query($conn_peny, $query_gis_desa);
        $data_gis_desa = [];
        while ($row = mysqli_fetch_assoc($res_gis_desa)) {
            $data_gis_desa[] = $row;
        }

        // Mapping koordinat Kecamatan untuk fallback
        $kec_coords = [];
        foreach ($data_gis_kec as $row) {
            if (!empty($row['latitude']) && !empty($row['longitude'])) {
                $kec_coords[$row['nama_kecamatan']] = [(float)$row['latitude'], (float)$row['longitude']];
            }
        }

        // Close connections
        mysqli_close($conn_peny);
        mysqli_close($conn_peng);

        // SEO and general template data
        $iden = $this->db->query("SELECT * FROM identitas ORDER BY id_identitas DESC LIMIT 1")->row_array();
        $logo = $this->db->query("SELECT gambar FROM logo ORDER BY id_logo DESC LIMIT 1")->row_array();
        $data['logo_image'] = $logo ? $logo['gambar'] : '';
        
        $data['title'] = "Portal Integrasi & Monitoring ZIS BAZNAS - " . $iden['nama_website'];
        $data['description'] = "Portal Publik Integrasi Data Zakat, Infak, dan Sedekah (ZIS). Monitoring real-time pengumpulan dan peta sebaran penyaluran geografis BAZNAS Kabupaten Sumbawa.";
        $data['keywords'] = "monitoring zis, baznas sumbawa, peta penyaluran zakat, geografis zis sumbawa";

        // View data variables
        $data['total_pengumpulan'] = $total_pengumpulan;
        $data['total_muzaki'] = $total_muzaki;
        $data['total_upz'] = $total_upz;
        $data['target_rkat'] = $target_rkat;
        $data['realisasi_persen'] = $realisasi_persen;
        $data['jenis_labels'] = $jenis_labels;
        $data['jenis_data'] = $jenis_data;
        $data['pengumpulan_monthly'] = $pengumpulan_monthly;
        $data['total_penyaluran'] = $total_penyaluran;
        $data['total_mustahik'] = $total_mustahik;
        $data['total_kec_terjangkau'] = $total_kec_terjangkau;
        $data['total_desa_terjangkau'] = $total_desa_terjangkau;
        $data['detail_penyaluran'] = $detail_penyaluran;
        $data['penyaluran_monthly'] = $penyaluran_monthly;
        $data['data_gis_kec'] = $data_gis_kec;
        $data['data_gis_desa'] = $data_gis_desa;
        $data['kec_coords'] = $kec_coords;

        // Load view using active template wrapper
        $this->template->load(template() . '/template', template() . '/monitoring', $data);
    }
}
