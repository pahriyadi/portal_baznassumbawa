<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	public function index(){
        // Get identities
		$iden = $this->db->query("SELECT * FROM identitas ORDER BY id_identitas DESC LIMIT 1")->row_array();
		$data['title'] = "Laporan Keuangan - ".$iden['nama_website'];
		$data['description'] = "Laporan Keuangan Tahunan dan Bulanan ".$iden['nama_website'];
		$data['keywords'] = "laporan keuangan, baznas sumbawa, zakat, infak, sedekah";

        // Query data
        $data['tahunan'] = $this->db->query("SELECT * FROM laporan_keuangan WHERE kategori='Tahunan' ORDER BY tahun DESC")->result_array();
        $data['bulanan'] = $this->db->query("SELECT * FROM laporan_keuangan WHERE kategori='Bulanan' ORDER BY tahun DESC, id_laporan DESC")->result_array();

        // Load View
		$this->template->load(template().'/template',template().'/laporan',$data);
	}

    public function detail(){
        $id = $this->uri->segment(3);
        $record = $this->db->query("SELECT * FROM laporan_keuangan WHERE id_laporan='$id'");
        
        if ($record->num_rows() == 0){
            redirect('laporan');
        } else {
            $row = $record->row_array();
            $iden = $this->db->query("SELECT * FROM identitas ORDER BY id_identitas DESC LIMIT 1")->row_array();
            
            // Hit +1
            $this->db->query("UPDATE laporan_keuangan SET hits=hits+1 WHERE id_laporan='$id'");
            
            $data['title'] = $row['judul']." - ".$iden['nama_website'];
            $data['description'] = substr(strip_tags($row['keterangan']),0,150);
            $data['keywords'] = "laporan, ".$row['judul'].", baznas";
            
            $data['rows'] = $row;
            $this->template->load(template().'/template',template().'/laporan_detail',$data);
        }
    }

    public function download(){
        $id = $this->uri->segment(3);
        $row = $this->db->query("SELECT nama_file FROM laporan_keuangan WHERE id_laporan='$id'")->row_array();
        
        if ($row && file_exists('asset/laporan/'.$row['nama_file'])) {
            $this->db->query("UPDATE laporan_keuangan SET hits=hits+1 WHERE id_laporan='$id'");
            $this->load->helper('download');
            force_download('asset/laporan/'.$row['nama_file'], NULL);
        } else {
            echo "<script>window.alert('Mohon maaf, File Laporan tidak ditemukan pada server.');
                  window.location.href='".base_url('laporan')."';</script>";
        }
    }
}
