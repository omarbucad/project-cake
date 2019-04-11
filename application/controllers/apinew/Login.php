<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Login extends CI_Controller {

	private $post;

	public function __construct( ) {
		parent::__construct();

		//http://stackoverflow.com/questions/18382740/cors-not-working-php
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

		//$this->post = json_decode(file_get_contents("php://input"));
		$this->post = (object)$this->input->post();
	}
	
	public function index () {
		
		if($this->post) {
			$username = $this->security->xss_clean( $this->post->username );
			$password = $this->security->xss_clean( $this->post->password );

			$this->db->join("address a" , "a.address_id = c.physical_address_id");
			$result = $this->db->where("email" , $this->post->username)->where("password" , md5($password))->get('customer c')->row();

			if($result){
				echo json_encode(["status" => true , "message" => "Login Successfully"  , "account_type" => "CUSTOMER" , "data" => $result, "action" => "login"]);
			}else{
				$result = $this->db->where("username" , $username)->where("account_type" , "DRIVER")->where("password" , md5($password))->get("users")->row();
				
				if($result){
					echo json_encode(["status" => true , "message" => "Login Successfully" , "account_type" => "DRIVER" , "data" => $result, "action" => "login"]);
				}else{
					echo json_encode(["status" => false , "message" => "Incorrect username / Password", "action" => "login"]);
				}
			}
		}else{
			echo json_encode(["status" => false , "message" => "Incorrect username / Password", "action" => "login"]);
		}
	}


	public function register(){
		if($this->post){

			$display_name = $this->security->xss_clean( $this->post->display_name );
			$company = $this->security->xss_clean( $this->post->company );
			$account_type = $this->security->xss_clean( $this->post->account_type );
			$phone_number = $this->security->xss_clean( $this->post->phone_number );

			$email = $this->security->xss_clean( $this->post->email );
			$password = md5($this->security->xss_clean( $this->post->password ));

			$this->db->trans_start();

			$this->db->insert("address" , $this->post->physical);
			$address_id = $this->db->insert_id();

			$activation_code = $this->hash->encrypt(time().'_'.$this->input->post("email"));

			$this->db->insert("customer" , [
				"password"				=> $password  ,
				"email"					=> $email ,
				"activation_code" 		=> $activation_code,
				"physical_address_id" 	=> $address_id ,
				"display_name"			=> $display_name,
				"company_name"			=> ($account_type == "PERSONAL") ? NULL : $company,
				"account_type"			=> $account_type,
				"phone_number"			=> $phone_number,
				"status"				=> 2 ,
				"created"				=> time()
			]);

			$customer_id = $this->db->insert_id();

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){

				echo json_encode(["status" => false , "message" => "Server Error Please try again Later", "action" => "register"]);

			}else{

				$this->send_activation_email([
					"email_address"		=> $email ,
					"activation_code"	=> $activation_code ,
					"name"				=> ($account_type == "COMPANY") ? $company : $display_name ,
				]);

				$result = $this->db->where("email" , $email)->where("password" , $password)->get('customer')->row();

				echo json_encode(["status" => true , "message" => "Please check you email to activate your account" , "data" => $result, "action" => "register"]);
			}

		}else{
			echo json_encode(["status" => false , "message" => "Please Complete the form", "action" => "register"]);
		}
	}

	public function resendEmail(){
		if($this->post){
			$res = $this->db->where("customer_id" , $this->post->customer_id)->get("customer")->row();

			$data = [
				"email_address"		=> $res->email ,
				"activation_code"	=> $res->activation_code,
				"name"				=> $res->display_name,
			];

			$sent = $this->send_activation_email($data);
			if($sent){
				echo json_encode(["status" => true , "message" => "message has been sent to ".$data["email_address"], "action" => "resendEmail"]);
			}else{
				echo json_encode(["status" => false , "message" => "Failed to send email to ".$data["email_address"], "action" => "resendEmail"]);
			}
			
		}
	}

	private function send_activation_email($data){
		$email_address   = $data['email_address'];

		$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
		$this->email->to($email_address);

		$this->email->subject('Gravybaby Cake Ordering account activation');
		$this->email->message($this->load->view('frontend/pages/email' , $data , TRUE));
		$this->email->set_mailtype('html');

		$sent = $this->email->send();
		if($sent){
			return true;
		}else{
			return false;
		}
	}

	public function customer_info($customer_id){
		$this->db->join("address a" , "a.address_id = c.physical_address_id");
		$result = $this->db->where("c.customer_id" , $customer_id)->get("customer c")->row();

		if($result){
			echo json_encode(["status" => true , "data" => $result, "action" => "customer_info"]);
		}else{
			echo json_encode(["status" => false , "message" => "Invalid Customer Id", "action" => "customer_info"]);	
		}
		
	}

	public function update_profile(){
		if($this->post){
			$update = $this->db->where("customer_id" , $this->post->customer_id)->update("customer" , [
				"display_name" => $this->post->name ,
				"company_name" => ($this->post->company != '') ? $this->post->company : NULL ,
				"phone_number" => ($this->post->phone != '') ? $this->post->phone : NULL
			]);
			if($update){
				echo json_encode(["status" => true, "action" => "update_profile"]);
			}else{
				echo json_encode(["status" => false, "action" => "update_profile"]);
			}			
		}
	}

	public function update_address(){
		if($this->post){
			$update = $this->db->where("address_id" , $this->post->address_id)->update("address" , $this->post->physical);
			if($update){
				echo json_encode(["status" => true, "action" => "update_address"]);
			}else{
				echo json_encode(["status" => false, "action" => "update_address"]);
			}			
		}
	}

	public function change_password(){
		if($this->post){
			$customer_id = $this->post->customer_id;
			$password = md5($this->post->password);
			$this->db->trans_start();
			$update = $this->db->where("customer_id" , $customer_id)->update("customer" , ["password" => $password]);
			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				echo json_encode(["status" => false, "action" => "change_password"]);
			}else{
				echo json_encode(["status" => true, "action" => "change_password"]);
			}
		}
	}

	public function customer_logout(){
		if($this->post){

			$this->db->where([
				"user_id"	=> $this->post->user_id ,
				"device_id"	=> $this->post->device_id ,
				"type"		=> "CUSTOMER"
			])->delete("push_token");

			echo json_encode(["status" => true, "action" => "customer_logout"]);
		}
	}
}


?>