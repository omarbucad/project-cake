<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");
    }
	public function index(){
		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['main_page'] = "frontend/pages/order";
		$this->data['shop_list'] = $this->product->get_category();
		$this->data['order_list'] = $this->product->get_orders();
		$this->load->view('frontend/master' , $this->data);
	}

	public function view($order_number){
		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['main_page'] = "frontend/pages/order_view";
		$this->data['shop_list'] = $this->product->get_category();
		$this->data['order_data'] = $this->product->get_order_by_id($order_number);
		$this->load->view('frontend/master' , $this->data);
	}
}
