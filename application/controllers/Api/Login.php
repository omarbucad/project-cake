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

		$this->post = file_get_contents("php://input");
	}
	
	public function index () {
		
		if($this->post) {
			$username = $this->security->xss_clean( $this->post->username );
			$password = $this->security->xss_clean( $this->post->password );

			$result = $this->db->where("email" , $this->post->username)->where("password" => $password)->get('customer')->row();

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
			//TODO: Customer Register Logic Here
		}else{
			echo json_encode(["status" => false , "message" => "Please Complete the form"]);
		}
	}
}


?>