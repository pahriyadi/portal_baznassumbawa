<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends CI_Controller {
	public function index(){
		$data['title'] = title();
		$data['description'] = description();
		$data['keywords'] = keywords();
		$this->template->load(template().'/template',template().'/content',$data);
	}

    public function kalkulator_zakat(){
		$data['title'] = "Kalkulator Zakat Komprehensif - BAZNAS Sumbawa";
		$data['description'] = "Hitung kewajiban zakat Anda dengan mudah dan akurat menggunakan Kalkulator Zakat resmi BAZNAS Kabupaten Sumbawa.";
		$data['keywords'] = "kalkulator zakat, zakat profesi, zakat maal, zakat pertanian, zakat peternakan, baznas sumbawa";
		$this->template->load(template().'/template',template().'/kalkulator_zakat',$data);
	}
}
