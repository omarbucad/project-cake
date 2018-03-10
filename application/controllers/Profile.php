<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");
       $this->load->model("Users_model" , "users");
       if(!$this->session->userdata("customer")){
       		redirect("/login" , "refresh");
       }
       elseif($this->session->userdata("customer")->status == 2){
       		redirect("/login/resend_activation_email", "refresh");
       }
    }
	
	public function index(){
		$this->form_validation->set_rules('password'		   , 'Password'			, 'trim|required|min_length[5]');
		$this->form_validation->set_rules('confirm_password'   , 'Confirm Password'	, 'trim|required|matches[password]');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
			$this->data['main_page'] = "frontend/pages/profile";
			$this->data['shop_list'] = $this->product->get_category();

			$this->db->join("address a" , "a.address_id = c.physical_address_id");
			$this->data['profile_information'] = $this->db->where("c.customer_id" , $this->session->userdata("customer")->customer_id)->get("customer c")->row();

			$this->load->view('frontend/master' , $this->data);

		}else{

			$customer_id = $this->session->userdata("customer")->customer_id;

			if($last_id = $this->users->change_customer_password($customer_id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Changed Password');	

			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

			}

			redirect("profile/" , 'refresh');
		}


		
	}

	public function update_address(){


		$this->db->where("address_id" , $this->input->post("address_id"))->update("address" , $this->input->post("physical"));

		redirect('/profile', 'refresh');
	}

	public function edit_profile($customer_id){
		if($this->session->userdata("customer")->account_type == 'PERSONAL'){
			$this->form_validation->set_rules('fullname'		, 'Full Name'    , 'trim|required');
		}
		else{
			$this->form_validation->set_rules('company_name'		, 'Company Name'    , 'trim|required');
			$this->form_validation->set_rules('manager_name'		, 'Manager Name'    , 'trim|required');
		}

		$this->form_validation->set_rules('phone_number'		, 'Phone Number'    , 'trim|required');


		if ($this->form_validation->run() == FALSE){ 
			$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
			$this->data['main_page'] = "frontend/pages/profile";
			$this->data['shop_list'] = $this->product->get_category();

			$this->db->join("address a" , "a.address_id = c.physical_address_id");
			$this->data['profile_information'] = $this->db->where("c.customer_id" , $this->session->userdata("customer")->customer_id)->get("customer c")->row();

			$this->load->view('frontend/master' , $this->data);
		}else{
			if($last_id = $this->users->customer_edit_profile($customer_id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated Account Details');	

				redirect("profile/?customer_id=".$this->hash->encrypt($customer_id).'?submit=submit' , 'refresh');
				
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("profile/" , 'refresh');
			}

		}
	}
}
