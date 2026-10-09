<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bayarzakat extends CI_Controller {
	public function index(){
		$data['title'] = 'Bayar Zakat BAZNAS Kabupaten Sumbawa';
		$data['description'] = 'Mari tunaikan zakat Anda Ke BAZNAS Melalui Transfer Ke rekening ZIS BAZNAS Kabupaten Sumbawa. Dilengkapi dengan pembayaran QRIS.';
		$data['keywords'] = 'bayar zakat, zis, baznas, sumbawa, rekening baznas, qris baznas';
		
        // Ambil data identitas untuk QRIS dan No Telp WA
        $data['iden'] = $this->model_utama->view_where('identitas',array('id_identitas' => 1))->row_array();
        
        // Ambil data rekening secara dinamis dari database
        $data['rekening'] = $this->model_app->view_ordering('rekening_zakat','id_rekening','ASC');

		// Load template aktif dan panggil view bayarzakat.php
		$this->template->load(template().'/template',template().'/bayarzakat',$data);
	}

	public function konfirmasi(){
		$data['title'] = 'Konfirmasi Pembayaran ZIS - BAZNAS Kabupaten Sumbawa';
		$data['description'] = 'Kirim bukti setor transfer bank zakat, infak, atau sedekah Anda untuk diverifikasi oleh petugas BAZNAS.';
		$data['keywords'] = 'konfirmasi zakat, bukti transfer, setoran zakat, zis, baznas, sumbawa';
		
		// Ambil data identitas
		$data['iden'] = $this->model_utama->view_where('identitas', array('id_identitas' => 1))->row_array();
		// Ambil data rekening
		$data['rekening'] = $this->model_app->view_ordering('rekening_zakat','id_rekening','ASC');

		if ($this->input->post('submit')) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim|strip_tags');
			$this->form_validation->set_rules('no_telp', 'Nomor Telepon/WA', 'required|trim|strip_tags');
			$this->form_validation->set_rules('jenis_dana', 'Jenis Dana', 'required');
			$this->form_validation->set_rules('jumlah', 'Nominal Pembayaran', 'required|numeric');
			$this->form_validation->set_rules('bank_pengirim', 'Bank Pengirim', 'required|trim|strip_tags');
			$this->form_validation->set_rules('rek_tujuan', 'Rekening Tujuan', 'required|trim|strip_tags');
			$this->form_validation->set_rules('tanggal_transfer', 'Tanggal Transfer', 'required');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('pesan_error', 'Isian formulir tidak valid. Harap periksa kembali.');
				$this->template->load(template().'/template', template().'/konfirmasi', $data);
			} else {
				// Process file upload
				$config['upload_path'] = 'asset/bukti_transfer/';
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['max_size'] = 2048; // 2MB
				$config['encrypt_name'] = TRUE;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('bukti_transfer')) {
					$data['error_upload'] = $this->upload->display_errors();
					$this->session->set_flashdata('pesan_error', 'Gagal mengunggah bukti transfer: ' . strip_tags($data['error_upload']));
					$this->template->load(template().'/template', template().'/konfirmasi', $data);
				} else {
					$upload_data = $this->upload->data();
					$file_name = $upload_data['file_name'];

					$db_data = array(
						'nama' => $this->input->post('nama', TRUE),
						'email' => $this->input->post('email', TRUE),
						'no_telp' => $this->input->post('no_telp', TRUE),
						'jenis_dana' => $this->input->post('jenis_dana', TRUE),
						'jumlah' => $this->input->post('jumlah', TRUE),
						'bank_pengirim' => $this->input->post('bank_pengirim', TRUE),
						'rek_tujuan' => $this->input->post('rek_tujuan', TRUE),
						'bukti_transfer' => $file_name,
						'tanggal_transfer' => $this->input->post('tanggal_transfer', TRUE),
						'status' => 'Pending'
					);

					$this->db->insert('pembayaran_konfirmasi', $db_data);

					$this->session->set_flashdata('pesan_sukses', 'Konfirmasi pembayaran berhasil dikirim! Petugas kami akan melakukan verifikasi segera.');
					redirect('bayarzakat/konfirmasi');
				}
			}
		} else {
			$this->template->load(template().'/template', template().'/konfirmasi', $data);
		}
	}
}
