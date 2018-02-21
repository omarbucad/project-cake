<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Products extends CI_Controller{

	private $post;

	public function __construct() {
		parent::__construct();

		if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        $this->post = json_decode(file_get_contents("php://input"));
        $this->load->model("Product_model" , "product");
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

			$result[$key]->image = array(
				"thumbnail"	=> site_url("thumbs/images/product/".$image->image_path."/500/500/".$image->image_name) ,
				"large_image" => site_url("thumbs/images/product/".$image->image_path."/700/700/".$image->image_name)
			);
			$result[$key]->short_description = htmlentities($row->short_description);
			$result[$key]->price_raw = $row->price;
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
				$result->images[] = array(
					"thumbnail" => site_url("thumbs/images/product/".$row->image_path."/350/350/".$row->image_name) ,
					"large_image" =>site_url("thumbs/images/product/".$row->image_path."/550/550/".$row->image_name)
				);
			}

			$result->product_description = $result->product_description;
			$result->price_raw = $result->price;
			$result->price = custom_money_format($result->price);

			echo json_encode(["status" => true , "data" => $result]);
		}else{
			echo json_encode(["status" => false , "message" => "No Results..."]);
		}
	}

	#cart module
	public function checkOut(){

		if($this->post){
			
			$this->db->trans_start();

			$order = array(
	            "customer_id" => $this->post->customer_id ,
	            "status"      => 1 ,
	            "total_price" => 0,
	            "created"     => time()
	        );

	        $this->db->insert("customer_order" , $order);
	        
	        $order_id = $this->db->insert_id();

	        $product_data = $this->post->cart_list;
	        $total_price = 0;
        	$product_array = array();
        	$items = 0;

        	foreach($product_data as $row){
	            $total_price += $row->price_raw * $row->quantity;
	            $items 		 += $row->quantity;

	            $product_array[] = array(
	                "order_id"      => $order_id ,
	                "product_id"    => $row->product_id,
	                "product_name"  => $row->product_name ,
	                "product_price" => $row->price_raw ,
	                "quantity"      => $row->quantity,
	                "total_price"   => $row->price_raw * $row->quantity
	            );
	        }

	        $this->db->insert_batch("customer_order_product" , $product_array);

	        $this->db->where("order_id" , $order_id)->update("customer_order" , [
	            "order_number"      => date("mdY").'-'.sprintf('%05d', $order_id) ,
	            "total_price"       => $total_price ,
	            "items"             => $items
	        ]);
			
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){
	            echo json_encode(["status" => false , "message" => "Checkout Failed , Please Try Again Later"]);
	        }else{
	        	echo json_encode(["status" => true , "order_number" => date("mdY").'-'.sprintf('%05d', $order_id)]);
	        }
		}
	}

	#getting all the customer order history
	public function get_order_list($customer_id){

		$result = $this->db->where("customer_id" , $customer_id)->order_by("order_id" , "DESC")->get("customer_order")->result();

        foreach($result as $key => $row){
            $result[$key]->total_price = custom_money_format($row->total_price);
            $result[$key]->created = convert_timezone($row->created , true);
            $result[$key]->status = convert_order_status($row->status , true);
        }

        if($result){
        	echo json_encode(["status" => true , "data" => $result]);
        }else{
        	echo json_encode(["status" => false , "message" => "No Results..."]);
        }
        
	}

	#getting the order information
	public function get_order_information_by_id($order_number){
		$result = $this->product->get_order_by_id($order_number , true);
		

		foreach($result->product_list as $key => $row){

			$image =  array(
				"thumbnail" => site_url("thumbs/images/product/".$row->images[0]->image_path."/350/350/".$row->images[0]->image_name) ,
				"large_image" =>site_url("thumbs/images/product/".$row->images[0]->image_path."/550/550/".$row->images[0]->image_name)
			);
			$result->product_list[$key]->images = $image;
		}

		if($result){
			echo json_encode(["status" => true  , "data" => $result]);
		}else{
			echo json_encode(["status" => false , "message" => "No Results..."]);	
		}
		
	}

	#cancel customer order
	public function cancel_order(){
		if($this->post){
			$this->db->where("order_number" , $this->post->order_number)->update("customer_order" , ["status" => 0]);
			echo json_encode(["status" => true]);
		}
	}
}

?>