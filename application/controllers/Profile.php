<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");
    }
	
	public function index(){
		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['main_page'] = "frontend/pages/profile";
		$this->data['shop_list'] = $this->product->get_category();

		$this->db->join("address a" , "a.address_id = c.physical_address_id");
		$this->data['profile_information'] = $this->db->where("c.customer_id" , $this->session->userdata("customer")->customer_id)->get("customer c")->row();

		$this->load->view('frontend/master' , $this->data);
	}

	public function update_address(){


		$this->db->where("address_id" , $this->input->post("address_id"))->update("address" , $this->input->post("physical"));

		redirect('/profile', 'refresh');
	}
}
