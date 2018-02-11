<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Users_model" , "users");
    }


	public function index(){
		$this->data['page_name'] = "Users";
		$this->data['main_page'] = "backend/page/users/view";
		$this->data['result']	 = $this->users->view_users();

		$this->load->view('backend/master' , $this->data);
	}

	public function add(){
		$this->form_validation->set_rules('username'		, 'UserName'			, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Add Users";
			$this->data['main_page'] = "backend/page/users/add";

			$this->load->view('backend/master' , $this->data);

		}else{

			if($last_id = $this->users->add_users()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a Customer');	

				redirect("app/users/?user_id=".$this->hash->encrypt($last_id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/users/add" , 'refresh');
			}
		}
		
	}

	public function customer(){
		$this->data['page_name'] = "Customer";
		$this->data['main_page'] = "backend/page/users/customer";
		$this->data['result']	 = $this->users->view_customer();
		$this->load->view('backend/master' , $this->data);
	}
}
