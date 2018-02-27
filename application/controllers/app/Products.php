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

		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/products/") ;
		$this->data['config']["total_rows"] = $this->product->get_products(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result']	 = $this->product->get_products();
		$this->data['category_list'] = $this->product->get_category();
		$this->load->view('backend/master' , $this->data);
	}

	public function add(){
		
		$this->form_validation->set_rules('product_name'		, 'Product Name'	, 'trim|required');
		$this->form_validation->set_rules('product_price'		, 'Product Price'	, 'trim|required');
		$this->form_validation->set_rules('product_position'	, 'Position'		, 'trim|required');
		$this->form_validation->set_rules('category'			, 'Category'		, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Products";
			$this->data['main_page'] = "backend/page/products/add";
			$this->data['category_list'] = $this->product->get_category();

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

	public function add_category(){
		if($this->input->post()){
			$this->db->insert("category" , [
				"category_name"		=> $this->input->post("product_category") ,
				"status"			=> 1
			]);

			$id = $this->db->insert_id();

			echo json_encode(['id' => $this->hash->encrypt($id) , "name" => $this->input->post("product_category")]);
		}
	}

	public function edit_product ($id) {
		$product_id = $this->hash->decrypt($id);
		$this->data['main_page'] = "backend/page/products/updateproducts";
		$this->data['page_name'] = "Update Product";
		$this->data['result']	 = $this->product->view_productsbyid($product_id);
		$this->data['category_list'] = $this->product->get_category();
		$this->load->view('backend/master' , $this->data);
	}

	public function update_product () {
		if($this->input->post()){
			$this->product->update_product($_POST);
		}
	}

	public function delete_product_image ($image_id) {
		$delete = $this->product->delete_productimage($image_id);
		if($delete){
			echo json_encode(['status' => $delete]);
		}
	}

	public function set_primary_image ($image_id) {
		$set = $this->product->set_product_primary_image($image_id);
		if($set){
			echo json_encode(['status' => $set]);

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Added a Product');	
		}
	}

	public function update_product_position($product_id, $position){
		$update_position = $this->product->update_product_position($product_id,$position);
		if($update_position){
			echo json_encode(['status' => $update_position]);
		}
	}

	public function delete_product($product_id){

		$delete_product = $this->product->delete_product($product_id);
		if($delete_product){

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Deleted Product');

			redirect("app/products" , 'refresh');

		}
	}
}
