<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	public function __construct() {
       parent::__construct();

       $this->load->model("Product_model" , "product");
    }

	public function index(){

		if($this->input->get("action") == "login"){

			$this->form_validation->set_rules('username'		, 'Email Address'	, 'trim|required');
			$this->form_validation->set_rules('password'		, 'Password'	    , 'trim|required|md5');

		}else if($this->input->get("action") == "register"){

			$this->form_validation->set_rules('username'		, 'Email Address'	, 'trim|required|valid_email|min_length[3]|is_unique[customer.email]');
			$this->form_validation->set_rules('password'		, 'Password'	    , 'trim|required|md5');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|md5|matches[password]');
			$this->form_validation->set_rules('name'			, 'Full Name'	    , 'trim|required');
		}

		if ($this->form_validation->run() == FALSE){

			$this->data['title_page'] = "Welcome to Gravybaby Cake Ordering";
			$this->data['main_page'] = "frontend/pages/login";
			$this->data['shop_list'] = $this->product->get_category();
			$this->load->view('frontend/master' , $this->data);

		}else{

			if($this->input->get("action") == "login"){

				$result = $this->db->where([
					"email"		=> $this->input->post("username") ,
					"password"	=> $this->input->post("password")
				])->get("customer")->row();

				

				if($result){
					if($result->status == 2){

						redirect("/welcome?status=unactivate" , "refresh");
						
					}else{
						$this->session->set_userdata("customer" , $result);
					
						redirect("/welcome" , "refresh");
					}
				}else{
					redirect("/login/?error=wrong_password" , "refresh");
				}
				

			}else if($this->input->get("action") == "register"){

				$this->db->trans_start();

				$this->db->insert("address" , ["street1" => ""]);
				$address_id = $this->db->insert_id();

				$activation_code = $this->hash->encrypt(time().'_'.$this->input->post("email"));

				$this->db->insert("customer" , [
					"password"				=> $this->input->post("password") ,
					"email"					=> $this->input->post("username") ,
					"activation_code" 		=> $activation_code,
					"physical_address_id" 	=> $address_id ,
					"display_name"			=> $this->input->post("name"),
					"status"				=> 2 ,
					"created"				=> time()
				]);

				$customer_id = $this->db->insert_id();

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE){

		            redirect("/login/?action=register" , "refresh");

		        }else{

		            $this->send_activation_email([
		            	"email_address"		=> $this->input->post("email") ,
		            	"activation_code"	=> $this->input->post("activation_code") ,
		            	"name"				=> $this->input->post("name") ,
		            ]);

		            redirect("/welcome?status=unactivate" , "refresh");
		        }
			}

		}

	}

	public function code($code){
		$this->data['title_page'] = "Activate Your Account";
		$this->data['main_page'] = "frontend/pages/activation";

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
		$this->session->sess_destroy();
		redirect("/welcome" , "refresh");
	}

	private function send_activation_email($data){
		$email_address   = $data['email_address'];

		$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
		$this->email->to($email_address);

		$this->email->subject('Gravybaby Cake Ordering account activation');
		$this->email->message($this->load->view('frontend/pages/email' , $data , TRUE));

		$this->email->send();
	}
}
