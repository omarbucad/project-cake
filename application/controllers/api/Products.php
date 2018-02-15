<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Products extends CI_Controller
{
	private $post;	
	function __construct() {
		parent::__construct();
	}
	
	#get all products
	public function get_products () {
		if($this->post) {
			$result = $this->db->select("p.product_id ,p.product_name , pi.* ")->from("products p ")
			->join("products_images pi " , "pi.product_id = p.product_id")
			->where("pi.primary_image " , 1)->get()->result();

			echo json_encode($result);
		}
	}

	#get product by id
	public function get_product_id() {
		$tmp = array();
		if($this->post){
			$result = $this->db->select("p.product_id ,p.product_name , pi.* ")->from("products p ")
			->join("products_images pi " , "pi.product_id = p.product_id")
			->where("p.product_id " , 5)->get()->result();
			foreach ($result as $key => $value) {
				$tmp[$value->product_id][] = $value;
			}
			echo json_encode($tmp);
		}

	}

}

?>