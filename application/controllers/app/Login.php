<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function index(){

		$this->load->view('backend/page/login/login' , $this->data);
	}

	public function do_login(){

		if($this->input->post()){

			$username = $this->input->post("username");
			$password = md5($this->input->post("password"));

			$check = $this->db->where(array(
				"username"	=> $username ,
				"password"	=> $password
			))->get("users")->row();

			if($check){
				$this->session->set_userdata("user" , $check);
				$this->session->set_flashdata('status' , 'success');
				redirect('/app/dashboard', 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'failed');
				$this->session->set_flashdata('message' , 'Incorrect Username or Password');

				redirect('app/login', 'refresh');
			}
		}

	}
}
