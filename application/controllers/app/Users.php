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

		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/users/") ;
		$this->data['config']["total_rows"] = $this->users->view_users(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result']	 = $this->users->view_users();

		$this->load->view('backend/master' , $this->data);
	}

	public function add(){
		$this->form_validation->set_rules('display_name'		, 'Name'			        , 'trim|required');
		$this->form_validation->set_rules('username'		    , 'UserName'			    , 'trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('password'		    , 'Password'			    , 'trim|required|md5');
		$this->form_validation->set_rules('confirm_password'    , 'Confirm Password'	    , 'trim|required|matches[password]|md5');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Add Users";
			$this->data['main_page'] = "backend/page/users/add";

			$this->load->view('backend/master' , $this->data);

		}else{

			if($last_id = $this->users->add_users()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a User');	

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

		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/users/customer") ;
		$this->data['config']["total_rows"] = $this->users->view_customer(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();
		$this->data['result']	 = $this->users->view_customer();
		$this->load->view('backend/master' , $this->data);
	}

	public function add_customer(){
		$this->form_validation->set_rules('email'				, 'Email'			    , 'trim|required|is_unique[customer.email]');
		$this->form_validation->set_rules('display_name'		, 'Name'			    , 'trim|required');
		$this->form_validation->set_rules('password'		    , 'Password'			, 'trim|required|md5');
		$this->form_validation->set_rules('confirm_password'    , 'Confirm Password'	, 'trim|required|matches[password]|md5');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Add Customer";
			$this->data['main_page'] = "backend/page/users/add_customer";

			$this->load->view('backend/master' , $this->data);

		}else{
			if($last_id = $this->users->add_customer()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a Customer');	

				redirect("app/users/customer/?user_id=".$this->hash->encrypt($last_id).'?submit=submit' , 'refresh');
				
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/users/add_customer" , 'refresh');
			}
		}
	}

	public function edit_customer($customer_id){

		$this->form_validation->set_rules('email'			, 'Email'   , 'trim|required');
		$this->form_validation->set_rules('display_name'	, 'Name'   , 'trim|required');

		if ($this->form_validation->run() == FALSE){ 
			$this->data['page_name'] = "Edit Customer";
			$this->data['main_page'] = "backend/page/users/edit_customer";
			$this->data['customer_info'] = $this->users->get_customer_information($customer_id);
			$this->data['customer_address'] = $this->users->get_customer_address($this->data['customer_info']->physical_address_id);

			//print_r_die($this->data['customer_address']);

			$this->load->view('backend/master' , $this->data);
		}else{
			if($last_id = $this->users->update_customer($customer_id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated Customer');	

				redirect("app/users/customer/?customer_id=".$this->hash->encrypt($customer_id).'?submit=submit' , 'refresh');
				
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/users/add_customer" , 'refresh');
			}

		}
	}

	public function edit_user($user_id){

		$this->form_validation->set_rules('display_name'		, 'Name'			        , 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Edit User";
			$this->data['main_page'] = "backend/page/users/update";

			$this->data['user_info'] = $this->users->get_user_information($user_id);
			
			$this->load->view('backend/master' , $this->data);
		}else{
			if($last_id = $this->users->update($user_id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated User');	

				redirect("app/users/?user_id=".$this->hash->encrypt($last_id).'?submit=submit' , 'refresh');
				
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/users/add" , 'refresh');
			}
		}
	}
}

