<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class News extends CI_Controller
{
	private $page = null;
	private $params = null;

	public function __construct()
	{
		parent::__construct();
		$this->page = $this->uri->segment(1);
		$this->reroute();
	}

	public function _remap($page, $params = array())
	{
		if (count($params) > 0) {
			if (strlen($params[0]) > 0) {
				$this->params = $params;
			}
		}

		if ($this->params) {
			$method = strtolower(trim($this->params[0]));
			if (method_exists($this, $method)) {
				return call_user_func_array(array($this, $method), $this->params);
			} else {
				$this->index();
			}
		} else {
			$this->index();
		}
	}

	function index($id = null)
	{
		$slug    = $this->uri->segment(1);
		$query   = $this->model_utama->view_join_two('berita', 'users', 'kategori', 'username', 'id_kategori', array('judul_seo' => $slug), 'id_berita', 'DESC', 0, 1);

		if ($query->num_rows() <= 0) {
			// Fallback: cek apakah slug cocok dengan halamanstatis
			$halaman = $this->model_utama->view_join_one(
				'halamanstatis', 'users', 'username',
				array('judul_seo' => $slug),
				'id_halaman', 'DESC', 0, 1
			);

			if ($halaman->num_rows() > 0) {
				// Muat halaman statis langsung dari sini
				$row = $halaman->row_array();

				$data['title']       = cetak($row['judul']);
				$data['description'] = cetak_meta($row['isi_halaman'], 0, 200);
				$data['keywords']    = cetak(str_replace(' ', ', ', $row['judul']));
				$data['rows']        = $row;

				// Cookie protection
				$cookie_name = 'viewed_halaman_' . $row['id_halaman'];
				if (!$this->input->cookie($cookie_name)) {
					$this->model_utama->update(
						'halamanstatis',
						array('dibaca' => $row['dibaca'] + 1),
						array('id_halaman' => $row['id_halaman'])
					);
					$this->input->set_cookie(array(
						'name'   => $cookie_name,
						'value'  => 'yes',
						'expire' => '86400',
						'secure' => FALSE
					));
				}

				$this->template->load(template() . '/template', template() . '/detailhalaman', $data);
			} else {
				// Tidak ada berita maupun halaman → ke halaman utama
				redirect('main');
			}
		} else {
			$row = $query->row_array();
			$data['title']       = cetak($row['judul']);
			$data['description'] = cetak_meta($row['isi_berita'], 0, 500);
			$data['keywords']    = cetak($row['tag']);
			$data['rows']        = $row;

			// Cookie protection untuk view counter berita
			$cookie_name = 'viewed_berita_' . $row['id_berita'];
			if (!$this->input->cookie($cookie_name)) {
				$this->model_utama->update(
					'berita',
					array('dibaca' => $row['dibaca'] + 1),
					array('id_berita' => $row['id_berita'])
				);
				$this->input->set_cookie(array(
					'name'   => $cookie_name,
					'value'  => 'yes',
					'expire' => '86400',
					'secure' => FALSE
				));
			}

			$this->load->helper('captcha');
			$cap = create_captcha(array(
				'img_path'    => './captcha/',
				'img_url'     => base_url() . 'captcha/',
				'font_size'   => 17,
				'img_width'   => '150',
				'img_height'  => 30,
				'border'      => 0,
				'word_length' => 5,
				'expiration'  => 7200
			));
			$data['image'] = $cap['image'];
			$this->session->set_userdata('mycaptcha', $cap['word']);

			$this->template->load(template() . '/template', template() . '/detailberita', $data);
		}
	}

	private function reroute()
	{
		if ($this->page == $this->router->class) {
			if ($this->uri->total_segments() > 1) {
				$this->load->helper('url');
				$uri = substr($this->uri->uri_string, strlen($this->page) + 1);
				redirect($uri);
			}
		}
	}

	private function noroute()
	{
		redirect('main');
	}
}
