<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");

       if(!$this->session->userdata("customer")){
       		redirect("/login" , "refresh");
       }
    }

	public function index(){
		$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
		$this->data['shop_list'] = $this->product->get_category();
		$this->data['main_page'] = "frontend/pages/cart";
		$this->data['cart_list'] = $this->session->userdata("cart");
		
		$this->load->view('frontend/master' , $this->data);
	}

	public function remove_items($product_id){
		$data = $this->session->userdata("cart");

		$a = $data['list'][$product_id];
		unset($data['list'][$product_id]);

		$data['items'] -= 1;
		$data['price'] -= $a->price_raw;


		$this->session->set_userdata("cart" , $data);

		redirect("/cart" , "refresh");
	}

	public function cancel_order($order_number){
		$this->db->where("order_number" , $order_number)->update("customer_order" , ["status" => 0]);
		redirect("/order" , "refresh");
	}

	public function checkout(){

		if($order_number = $this->product->checkout()){

            $this->session->set_userdata("cart" , [
            	"items"	=> 0 ,
            	"price" => 0 ,
            	"list"	=> array()
            ]);

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Placed an order');	

			redirect("/order/view/".$order_number , "refresh");
		}else{

			$this->session->set_flashdata('status' , 'error');
			$this->session->set_flashdata('message' , 'Something went wrong');	

			redirect("/cart" , "refresh");
		}
	}
}
