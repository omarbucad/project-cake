<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function index(){

		$this->load->view('backend/page/login/login' , $this->data);
	}
}
