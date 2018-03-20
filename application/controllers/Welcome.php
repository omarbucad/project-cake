<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {


	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");

       if(!$this->session->userdata("customer")){
			redirect("/login", "refresh");
		}
    }

	public function index(){
		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['shop_list'] = $this->product->get_category();
		
		$s = ($this->input->get("shop_list")) ? $this->input->get("shop_list") : "all";

		if($this->input->get("s")){

			$this->data['main_page'] = "frontend/pages/shop";

			//PAGINATION
			$this->data['config']["base_url"] = base_url("welcome/?s=".$this->input->get("s")) ;
			$this->data['config']["total_rows"] = $this->product->get_shop_list($this->input->get("s") , true , true);
			$this->data['config']["per_page"] = 12;
			$this->pagination->initialize($this->data['config']);
			$this->data["links"] = $this->pagination->create_links();

		 	$this->data['result'] = $this->product->get_shop_list($this->input->get("s") , true);
		}else{

			$this->data['main_page'] = "frontend/pages/shop";

			//PAGINATION
			$this->data['config']["base_url"] = base_url("welcome/?shop_list=".$s) ;
			$this->data['config']["per_page"] = 12;
			$this->data['config']["total_rows"] = $this->product->get_shop_list($s , false , true);
			$this->pagination->initialize($this->data['config']);
			$this->data["links"] = $this->pagination->create_links();

			$this->data['result'] = $this->product->get_shop_list($s);
		}

		$this->load->view('frontend/master' , $this->data);
	}

}
