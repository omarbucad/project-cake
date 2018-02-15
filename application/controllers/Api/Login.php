<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Login extends CI_Controller {

	function __construct( ) {
		parent::__construct();

	}
	
	public function login () {
		if($_POST) {
			$result = $this->db->select("*")->from('users')->get()->result();
			foreach ($result as $key => $value) {
				$result[$key]->image = site_url("thumbs/images/user/".$value->image_path."/50/50/".$value->image_name);
			}
			return $result;
		}
	}



}


?>