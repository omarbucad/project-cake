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
		$this->load->view('frontend/master' , $this->data);
	}
}
