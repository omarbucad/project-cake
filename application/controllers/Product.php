<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");
    }

	public function index(){
		$product_id = $this->input->get("id");

		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['shop_list']  = $this->product->get_category();
		$this->data['result'] 	  = $this->product->get_product_by_id($product_id);
		$this->data['main_page']  = "frontend/pages/single_product";

		$this->load->view('frontend/master' , $this->data);

	}

	public function add_cart(){

		$data = $this->session->userdata("cart");
		$product_id = $this->input->post("id");
		
		if(isset($data["list"][$product_id])){

			echo json_encode(["status" => false , "message" => "Product has been already added to cart"]);

		}else{
			$data['items'] += 1;

			$result = $this->product->get_product_by_id($product_id);

			$data['price'] += $result->price_raw;

			$data['list'][$product_id] = $result;

			$this->session->set_userdata("cart" , $data);
			
			$data['price'] = custom_money_format($data['price']);

			echo json_encode(["status" => true , "message" => "Successfully added to cart" , "data" => $data]);
		}
		
	}

	#add wish 
	public function add_wish () {
		$product_id = $this->input->post("product_id");
		$data = $this->session->userdata("wish");

		$data['items'] += 1;

			$result = $this->product->get_product_by_id($product_id);

			$data['price'] += $result->price_raw;

			$data['list'][$product_id] = $result;

			$this->session->set_userdata("wish" , $data);
			
			$data['price'] = custom_money_format($data['price']);

			echo json_encode(["status" => true , "message" => "Successfully added to cart" , "data" => $data]);

			$arr = array(
				"product_id" => $product_id , 
				"customer_id" => $this->session->userdata('customer')->customer_id
			);

			$this->db->insert('customer_wish_product' , $arr);
	}
}
