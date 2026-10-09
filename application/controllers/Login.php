<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	function index(){
		redirect('administrator');
	}

	function logout(){
		$this->session->sess_destroy();
		redirect('main');
	}
}
