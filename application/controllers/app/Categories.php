<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Category_model" , "category");
       
    }

	public function index(){

		$this->data['page_name'] = "Categories";
		$this->data['main_page'] = "backend/page/categories/view";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/categories/") ;
		$this->data['config']["total_rows"] = $this->category->get_categories(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result'] = $this->category->get_categories();
		$this->load->view('backend/master' , $this->data);
	}

	public function add_category(){

		$this->form_validation->set_rules('category_name'		, 'Category Name'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Categories";
			$this->data['main_page'] = "backend/page/categories/add";
			$this->data['result'] = $this->category->get_categories();

			$this->load->view('backend/master' , $this->data);

		}else{
			if($new_category_id = $this->category->add_new_category()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a Category');	

				redirect("app/categories/?category_id=".$this->hash->encrypt($new_category_id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/categories/add_category" , 'refresh');
			}
		}

	}

	public function update_category($category_id){

		$this->form_validation->set_rules('category_name'		, 'Category Name'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Categories";
			$this->data['main_page'] = "backend/page/categories/update_category";
			$this->data['result'] = $this->category->get_category($category_id);

			$this->load->view('backend/master' , $this->data);

		}else{
			if($category_id = $this->category->update_category_details($category_id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated a Category');	

				redirect("app/categories/", 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/categories" , 'refresh');
			}
		}

	}

	public function delete_category($category_id){
		$delete_category = $this->category->delete_category($category_id);
		if($delete_category){

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Deleted Product');

			redirect("app/categories" , 'refresh');

		}
	}
	
}
