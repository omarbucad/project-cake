<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Products extends CI_Controller{

	public function __construct() {
		parent::__construct();

		if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
	}

	#get all active category list
	public function get_category(){
		
		$this->db->select(" (SELECT MIN(product_id) FROM products WHERE products.category_id = c.category_id ) as p_id , c.* ");
		$result = $this->db->where("c.status" , 1)->where("c.deleted IS NULL")->get("category c")->result();

		foreach($result as $key => $row){
			$image = $this->db->where("primary_image" , 1)->where("product_id" , $row->p_id)->get("products_images")->row();

			$result[$key]->image = site_url("thumbs/images/product/".$image->image_path."/250/250/".$image->image_name);
		}

		if($result){
			echo json_encode(["status" => true , "data" => $result]);
		}else{
			echo json_encode(["status" => false , "message" => "No Results..."]);
		}
	}
	
	#get all products
	public function get_products($category_id) {

		$category_id = $this->security->xss_clean($category_id);

		$this->db->select("product_id , product_name , price , category_id , short_description");

		if($category_id != "all" AND is_numeric($category_id)){
			$this->db->where("category_id" , $category_id);
		}

		$result = $this->db->where("status" , 1)->order_by("product_position" , "ASC")->get("products p")->result();

		foreach($result as $key => $row){
			$image = $this->db->where("primary_image" , 1)->where("product_id" , $row->product_id)->get("products_images")->row();

			$result[$key]->image = site_url("thumbs/images/product/".$image->image_path."/250/250/".$image->image_name);
			$result[$key]->short_description = htmlentities($row->short_description);
			$result[$key]->price = custom_money_format($row->price);
		}

		if($result){
			echo json_encode(["status" => true , "data" => $result]);
		}else{
			echo json_encode(["status" => false , "message" => "No Results..."]);
		}
	}

	#get product by id
	public function get_product_by_id($product_id) {
		
		$product_id = $this->security->xss_clean($product_id);

		$this->db->select("product_id , product_name , product_description , price");
		$result = $this->db->where("product_id" , $product_id)->where("status" , 1)->get("products p")->row();

		if($result){
			$images = $this->db->where("product_id" , $result->product_id)->order_by("primary_image" , "DESC")->get("products_images")->result();

			foreach($images as $key => $row){
				$result->images[] = site_url("thumbs/images/product/".$row->image_path."/250/250/".$row->image_name);
			}

			$result->product_description = urlencode($result->product_description);
			$result->price = custom_money_format($result->price);

			echo json_encode(["status" => true , "data" => $result]);
		}else{
			echo json_encode(["status" => false , "message" => "No Results..."]);
		}
	}

}

?>