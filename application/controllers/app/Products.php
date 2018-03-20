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

		$this->form_validation->set_rules('product_name'		, 'Product Name'	, 'trim|required');
		$this->form_validation->set_rules('product_price'		, 'Product Price'	, 'trim|required');
		$this->form_validation->set_rules('product_position'	, 'Position'		, 'trim|required');
		$this->form_validation->set_rules('category'			, 'Category'		, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['hash_id'] = $id;
			$this->data['main_page'] = "backend/page/products/update_products";
			$this->data['page_name'] = "Update Product";
			$this->data['result']	 = $this->product->view_productsbyid($product_id);
			$this->data['category_list'] = $this->product->get_category();
			$this->load->view('backend/master' , $this->data);

		}else{

			if($response = $this->product->update_product()){

				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated the Product');	

				redirect("app/products/?product_id=".$id.'?submit=submit' , 'refresh');

			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/products/edit_products/".$id , 'refresh');
			}

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

	public function product_info($product_id){
		$product_id = $this->hash->decrypt($product_id);
		$this->data['result']	 = $this->product->view_productsbyid($product_id);

		$this->data['main_page'] = "backend/page/products/product_info";
		$this->data['page_name'] = "Product Details";
		$this->data['category_list'] = $this->product->get_category();
		$this->data['card_info'] = $this->product->get_product_cards_info($product_id);
		//print_r_die($this->data['card_info']);

		$this->load->view("backend/master", $this->data);
	}

	public function price(){
		$this->data['main_page'] = "backend/page/products/price";
		$this->data['page_name'] = "Price Group";
		$this->data['result']	 = $this->product->get_price_group();
		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/categories/") ;
		$this->data['config']["total_rows"] = $this->product->get_price_group(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->load->view("backend/master", $this->data);
	}

	public function add_group(){
		

		$this->form_validation->set_rules('category_name'		, 'Name'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['main_page'] = "backend/page/products/add_group";
			$this->data['page_name'] = "Add Price Group";
			$this->data['product_list'] = $this->product->get_products();


			$this->load->view("backend/master", $this->data);

		}else{
			if($new_category_id = $this->product->add_new_group()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a Category');	

				redirect("app/products/price/?group_id=".$this->hash->encrypt($new_category_id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/products/add_group" , 'refresh');
			}
		}
	}


    public function remove_group($id){
        if($id){
            $id = $this->hash->decrypt($id);

            $this->db->where("price_book_id" , $id)->update("price_book" , [
                "deleted" => time()
            ]);

            $this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Deleted Group');

			redirect("app/products/price" , 'refresh');
        }
    }

    public function update_group($id){
    	$id = $this->hash->decrypt($id);

    	$this->form_validation->set_rules('category_name'		, 'Name'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['main_page'] = "backend/page/products/update_group";
			$this->data['page_name'] = "Update Price Group";
			$this->data['info'] 	 = $this->product->get_group_price_by_id($id);

			$this->load->view("backend/master", $this->data);

		}else{

			if($this->product->update_new_group($id)){

				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated a Group');	

				redirect("app/products/price/?group_id=".$this->hash->encrypt($id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/products/update_group/".$this->hash->encrypt($id) , 'refresh');
			}
		}
    }

    public function get_group_list($id){
    	$id = $this->hash->decrypt($id);

    	$this->db->select('p.price , p.product_name , pb.price as custom_price , pb.product_id');
    	$this->db->join("products p" , "p.product_id = pb.product_id");
    	$result = $this->db->where("price_book_id" , $id)->order_by("product_name" , "ASC")->get("price_book_products pb")->result();	

    	foreach ($result as $key => $value) {
    		$result[$key]->price = custom_money_format($value->price);
    		$result[$key]->custom_price = custom_money_format($value->custom_price);
    	}

    	echo json_encode($result);
    }
}
