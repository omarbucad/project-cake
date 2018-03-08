<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");

       if(!$this->session->userdata("customer")){
       		redirect("/login" , "refresh");
       }
       elseif($this->session->userdata("customer")->status == 2){
       		redirect("/login/resend_activation_email", "refresh");
       }
    }
	public function index(){
		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['main_page'] = "frontend/pages/order";
		$this->data['shop_list'] = $this->product->get_category();

		//PAGINATION
		$this->data['config']["base_url"] = base_url("order/") ;
		$this->data['config']["total_rows"] = $this->product->get_orders(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();


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

	public function wishlist(){
		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['shop_list'] = $this->product->get_category();
		$this->data['main_page'] = "frontend/pages/wishlist";
		$this->data['wishlist'] = $this->product->get_wishlist();
		$this->load->view('frontend/master' , $this->data);
	}

	public function remove_wish(){
		if($this->input->post()){
			$this->product->remove_wish($_POST['product_id']);
		}
	}
}
