<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Login extends API_Controller {

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
	
	public function login () {

		if($this->post) {
			$result = $this->db->select("*")->from('users')->get()->result();

			foreach ($result as $key => $value) {
				$result[$key]->image = site_url("thumbs/images/user/".$value->image_path."/50/50/".$value->image_name);
			}
			
			echo json_encode($result);

		}
	}



}


?>