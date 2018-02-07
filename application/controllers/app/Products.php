<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");
    }

	public function index(){

		$this->data['page_name'] = "Products";
		$this->data['main_page'] = "backend/page/products/view";
		$this->data['result']	 = $this->product->get_products();

		$this->load->view('backend/master' , $this->data);
	}

	public function add(){
		
		$this->form_validation->set_rules('product_name'		, 'Product Name'	, 'trim|required');
		$this->form_validation->set_rules('product_price'		, 'Product Price'	, 'trim|required');
		$this->form_validation->set_rules('product_position'	, 'Position'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Products";
			$this->data['main_page'] = "backend/page/products/add";
			$this->load->view('backend/master' , $this->data);

		}else{
			if($last_id = $this->product->add_product()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a Product');	

				redirect("app/products/?product_id=".$this->hash->encrypt($last_id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/products/add" , 'refresh');
			}
		}
		
	}
}
