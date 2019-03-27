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

	public function check_cart(){
		$data = $this->session->userdata("cart");
		$product_id = $this->input->post("id");

		if(isset($data["list"][$product_id])){
			echo json_encode(["status" => false , "message" => "Product has been already added to cart"]);
		}else{
			echo json_encode(["status" => true ]);
		}
	}

	public function add_cart(){

		$data = $this->session->userdata("cart");
		$product_id = $this->input->post("id");
		$qty = $this->input->post("qty");

		if(isset($data["list"][$product_id])){

			echo json_encode(["status" => false , "message" => "Product has been already added to cart"]);

		}else{

			$data['items'] += 1;

			$result = $this->product->get_product_by_id($product_id);

			
			if($result){
				$price_book = $this->db->where([
	                "price_book_id" =>  $this->data['session_customer']->price_book_id ,
	                "product_id"    => $product_id
	            ])->get("price_book_products")->row(); 

	            $result->price_raw = $price_book->price;
			}

			$result->qty = $qty;
			$result->price = custom_money_format($result->price_raw);

			$data['price'] += ($result->price_raw * $result->qty);

			$data['list'][$product_id] = $result;

			$this->session->set_userdata("cart" , $data);
			
			$data['price_raw'] = $data['price'];
			$data['price'] = custom_money_format($data['price']);

			echo json_encode(["status" => true , "message" => "Successfully added to cart" , "data" => $data]);

		}
		
	}

}
