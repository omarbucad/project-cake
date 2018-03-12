<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");
       $this->data['shop_list'] = $this->product->get_category();
    }

	public function index(){

			$this->form_validation->set_rules('username'		, 'Email Address'	, 'trim|required');
			$this->form_validation->set_rules('password'		, 'Password'	    , 'trim|required|md5');

		if ($this->form_validation->run() == FALSE){

			$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
			$this->data['main_page'] = "frontend/pages/login";
			$this->data['shop_list'] = $this->product->get_category();
			$this->load->view('frontend/master' , $this->data);

		}else{
			$result = $this->db->where([
				"email"		=> $this->input->post("username") ,
				"password"	=> $this->input->post("password")
			])->where("deleted IS NULL")->get("customer")->row();

			
			if($result){
				if($result->status == 2){
					$this->session->set_userdata("customer" , $result);

					redirect("/login/resend_activation_email" , "refresh");
					
				}else{
					$this->session->set_userdata("customer" , $result);
				
					redirect("/welcome" , "refresh");
				}
			}else{
				redirect("/login/?error=wrong_password" , "refresh");
			}
		}

	}

	public function code($code){
		$this->data['title_page'] = "Activate Your Account";
		$this->data['main_page'] = "frontend/pages/activation";
		$this->data['shop_list']  = $this->product->get_category();
		
		if($code){
			$result = $this->db->where("activation_code" , $code)->get("customer")->row();

			if($result->status == 2){
				$this->db->where("customer_id" , $result->customer_id)->update("customer" , ["status" => 1]);
				$result->status = 1;
			}

			$this->session->set_userdata("customer" , $result);

			$this->data['result'] = $result;
		}

		$this->load->view('frontend/master' , $this->data);
	}

	public function logout(){
		if($this->input->get("from") == "customer"){
			unset($_SESSION["customer"]);
			redirect("/login" , "refresh");
		}else{
			unset($_SESSION["user"]);
			redirect("/app/login" , "refresh");
		}		
	}

	private function send_activation_email($data){
		$email_address   = $data['email_address'];

		$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
		$this->email->to($email_address);

		$this->email->subject('Gravybaby Cake Ordering account activation');
		$this->email->message($this->load->view('frontend/pages/email' , $data , TRUE));
		$this->email->set_mailtype('html');

		$this->email->send();
	}

	public function resend_activation_email(){
		if(!$this->session->userdata("customer")){
			redirect("login","refresh");
		}
		$email = ($this->input->get("email")) ? $this->input->get("email") : $this->session->userdata("customer")->email;

		if ($this->form_validation->run() == FALSE){ 

			$this->data['customer_email'] = $email;
			$this->data['title_page'] = "Resend Activation Email";
			$this->data['main_page'] = "/frontend/pages/resend_activation";

			$this->load->view('frontend/master' , $this->data);

		}else{

			$data['email_address'] = $this->input->post('email');
			$data['activation_code'] = $this->hash->encrypt(time().'_'.$this->input->post("email"));

			if($email == $this->input->get("email")){
				$this->db->select('display_name');
				$this->db->where('email',$data['email_address']);
				$name = $this->db->get('customer')->row();

				$data['name'] = $name->display_name;
			}
			else{
				$data['name'] = $this->session->userdata("customer")->display_name;
			}
			

			$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
			$this->email->to($email_address);

			$this->email->subject('Gravybaby Cake Ordering account activation');
			$this->email->message($this->load->view('frontend/pages/email' , $data , TRUE));
			$this->email->set_mailtype('html');

			$this->email->send();

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Sent Activation Email');

			redirect("/login/resend_activation_email/?success=email_sent" , "refresh");
		}
	}
	

	public function forgot_password(){

		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['title_page'] = "Resend Activation Email";
			$this->data['main_page'] = "/frontend/pages/forgot_password";

			$this->load->view('frontend/master' , $this->data);

		}else{
			$email = $this->input->post("email");

			$info = $this->db->select("display_name")->where("email", $email)->get("customer")->row();

			if($info){

					$code = $this->hash->encrypt($email);
					$link = site_url("/login/change_password/".$code);					

					$data['link'] = $link;

					$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
					$this->email->to($email);
					$this->email->set_mailtype("html");
					$this->email->subject('Gravybaby Cake Ordering Account - Forgotten Password Reset');
					$this->email->message($this->load->view('frontend/pages/change_password_email', $data , true));

					$this->email->send();

					//redirect('/login/forgot_password/?status=forgottenpasswordsend', 'refresh');

			}else{

					redirect('/login');
			}

		}
	}

	public function change_password($code){

		if($code){
			$email = $this->hash->decrypt($code);

			$this->form_validation->set_rules('password'		, 'Password'	    , 'trim|required|min_length[5]');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

			if ($this->form_validation->run() == FALSE){ 
				$this->data['code'] = $code;
				$this->data['customer_email'] = $email;
				$this->data['title_page'] = "Change Password";
				$this->data['main_page'] = "frontend/pages/change_password";
				$this->data['shop_list']  = $this->product->get_category();

				$this->load->view('frontend/master' , $this->data);

			}else{

				$result = $this->db->select("email")->where("email" , $email)->get("customer")->row();

				if($result){
					$this->db->where("email" , $result->email)->update("customer" , [
						"password" => md5($this->input->post("password"))
					]);
					$this->session->set_flashdata('status' , 'success');	
					$this->session->set_flashdata('message' , 'Successfully Changed Password');

					redirect("/login/change_password/?success=change_password", "refresh");
				}			
			}
		}else{
			redirect("/login/forgot_password/$code?error=invalid_emailaddress", "refresh");
		}
	}

	public function register(){

		$this->form_validation->set_rules('username'		, 'Email Address'	, 'trim|required|valid_email|min_length[3]|is_unique[customer.email]');
		$this->form_validation->set_rules('password'		, 'Password'	    , 'trim|required|min_length[5]');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

		if($this->input->post('account_type') == 'PERSONAL'){
			$this->form_validation->set_rules('fullname'	, 'Full Name'	, 'trim|required');
		}else{
			$this->form_validation->set_rules('manager_name', 'Manager Name'	, 'trim|required');
			$this->form_validation->set_rules('company_name', 'Company Name'	, 'trim|required');
		}

		$this->form_validation->set_rules('phone_number'	, 'Phone Number'    , 'trim|required');
		$this->form_validation->set_rules('street1'			, 'Street 1'    	, 'trim|required');
		$this->form_validation->set_rules('city'			, 'City'    		, 'trim|required');
		$this->form_validation->set_rules('state'			, 'State'    		, 'trim|required');

		if ($this->form_validation->run() == FALSE){

			$this->data['title_page'] = "Register";
			$this->data['main_page'] = "frontend/pages/register";
			$this->data['shop_list'] = $this->product->get_category();
			$this->load->view('frontend/master' , $this->data);

		}else{
			$this->db->trans_start();

			$this->db->insert("address" , ["street1" => $this->input->post("street1")]);
			$address_id = $this->db->insert_id();

			$activation_code = $this->hash->encrypt(time().'_'.$this->input->post("email"));

			$this->db->insert("customer" , [
				"password"				=> $this->input->post("password") ,
				"email"					=> $this->input->post("username") ,
				"phone_number"			=> $this->input->post("phone_number") ,
				"activation_code" 		=> $activation_code,
				"physical_address_id" 	=> $address_id ,
				"display_name"			=> $this->input->post("manager_name"),
				"company_name"			=> ($this->input->post("account_type") == "COMPANY") ? $this->input->post("company_name") : "" ,
				"status"				=> 2 ,
				"created"				=> time(),
				"account_type"			=> $this->input->post('account_type')
			]);
			

			$this->db->where("address_id", $address_id);
			$this->db->update("address", [
	            "street2"	=> $this->input->post("street2") ,
	            "suburb"	=> $this->input->post("suburb") ,
	            "city"		=> $this->input->post("city") ,
	            "postcode"	=> $this->input->post("postcode") ,
	            "state"		=> $this->input->post("state") 
	        ]);

			$customer_id = $this->db->insert_id();

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){

	            redirect("/login/register/?error=register" , "refresh");

	        }else{
	        	$this->send_activation_email([
	        		"email_address"		=> $this->input->post("username") ,
	        		"activation_code"	=> $activation_code ,
	        		"name"				=> $this->input->post("account_type") == "COMPANY" ? $this->input->post("company_name") : $this->input->post("fullname") 
	        	]);
	            

	            $this->data["email"] 		= $this->input->post("username");
	            $this->data['title_page'] 	= "Registered Successfully";
				$this->data['main_page'] 	= "frontend/pages/register_success";

	            $this->load->view('frontend/master' , $this->data);

	            //redirect("/login/code/".$activation_code);
	        }
		}
	}
}
