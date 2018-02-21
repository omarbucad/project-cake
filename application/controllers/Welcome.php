<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {


	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");
    }

	public function index(){
		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['shop_list'] = $this->product->get_category();

	if($this->input->get("shop_list")){
			$this->data['main_page'] = "frontend/pages/shop";
			$this->data['result']	 = $this->product->get_shop_list($this->input->get("shop_list"));
		 }else if($this->input->get("s")){
			$this->data['main_page'] = "frontend/pages/shop";
	 	$this->data['result']	 = $this->product->get_shop_list($this->input->get("s") , true);
	 }else{
			$this->data['main_page'] = "frontend/pages/main";
		}

		$this->load->view('frontend/master' , $this->data);
	}

}
