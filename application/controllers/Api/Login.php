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

		$this->post = json_decode(file_get_contents("php://input"));
	}
	
	public function index () {
		
		if($this->post) {
			$username = $this->security->xss_clean( $this->post->username );
			$password = $this->security->xss_clean( $this->post->password );

			$result = $this->db->where("email" , $this->post->username)->where("password" , md5($password))->get('customer')->row();

			if($result){
				echo json_encode(["status" => true , "message" => "Login Successfully"  , "data" => $result]);
			}else{
				echo json_encode(["status" => false , "message" => "Incorrect username / Password"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "Incorrect username / Password"]);
		}
	}


	public function register(){
		if($this->post){

			$display_name = $this->security->xss_clean( $this->post->display_name );
			$email = $this->security->xss_clean( $this->post->email );
			$password = md5($this->security->xss_clean( $this->post->password ));

			$this->db->trans_start();

			$this->db->insert("address" , ["street1" => ""]);
			$address_id = $this->db->insert_id();

			$activation_code = $this->hash->encrypt(time().'_'.$this->input->post("email"));

			$this->db->insert("customer" , [
				"password"				=> $password  ,
				"email"					=> $email ,
				"activation_code" 		=> $activation_code,
				"physical_address_id" 	=> $address_id ,
				"display_name"			=> $display_name,
				"status"				=> 2 ,
				"created"				=> time()
			]);

			$customer_id = $this->db->insert_id();

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){

				echo json_encode(["status" => false , "message" => "Server Error Please try again Later"]);

			}else{

				$this->send_activation_email([
					"email_address"		=> $email ,
					"activation_code"	=> $activation_code ,
					"name"				=> $display_name,
				]);

				$result = $this->db->where("email" , $email)->where("password" , $password)->get('customer')->row();

				echo json_encode(["status" => true , "message" => "Please check you email to activate your account" , "data" => $result]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "Please Complete the form"]);
		}
	}
}


?>