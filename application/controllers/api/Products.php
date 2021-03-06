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

			@$result[$key]->image = site_url("thumbs/images/product/".$image->image_path."/250/250/".$image->image_name);
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
		$price_book_id = $this->getPriceBookId();

		$this->db->select("p.product_id , p.product_name , pb.price , p.category_id , p.short_description");
		$this->db->join("price_book_products pb" , "pb.product_id = p.product_id");
		
		if($category_id != "all" AND is_numeric($category_id)){
			$this->db->where("category_id" , $category_id);
		}

		$result = $this->db->where("status" , 1)->where("pb.price_book_id" , $price_book_id)->where("p.deleted IS NULL")->order_by("product_position" , "ASC")->get("products p")->result();

		foreach($result as $key => $row){
			$image = $this->db->where("primary_image" , 1)->where("product_id" , $row->product_id)->get("products_images")->row();

			$result[$key]->image = array(
				"thumbnail"	=> site_url("thumbs/images/product/".$image->image_path."/500/500/".$image->image_name) ,
				"large_image" => site_url("thumbs/images/product/".$image->image_path."/700/700/".$image->image_name)
			);
			$result[$key]->short_description = htmlentities($row->short_description);
			$result[$key]->price_raw = $row->price;
			$result[$key]->price = custom_money_format($row->price);
			$result[$key]->qty = 1;
		}

		if($result){
			echo json_encode(["status" => true , "data" => $result]);
		}else{
			echo json_encode(["status" => false , "message" => "No Results..."]);
		}
	}

	#get all products
	public function get_products_by_name($product_name) {

		$price_book_id = $this->getPriceBookId();

		$this->db->select("p.* , pb.price");
		$this->db->join("price_book_products pb" , "pb.product_id = p.product_id");
		$result = $this->db->like("p.product_name" , $product_name)->where("pb.price_book_id" , $price_book_id)->where("p.status" , 1)->where("p.deleted IS NULL")->order_by("p.product_name" , "ASC")->get("products p")->result();

		foreach($result as $key => $row){
			$image = $this->db->where("primary_image" , 1)->where("product_id" , $row->product_id)->get("products_images")->row();

			$result[$key]->image = array(
				"thumbnail"	=> site_url("thumbs/images/product/".$image->image_path."/250/250/".$image->image_name) ,
				"large_image" => site_url("thumbs/images/product/".$image->image_path."/500/500/".$image->image_name)
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
		$price_book_id = $this->getPriceBookId();

		$this->db->select("p.product_id , p.product_name , p.product_description , pb.price");
		$this->db->join("price_book_products pb" , "pb.product_id = p.product_id");
		$result = $this->db->where("p.product_id" , $product_id)->where("pb.price_book_id" , $price_book_id)->where("p.status" , 1)->where("p.deleted IS NULL")->get("products p")->row();

		if($result){
			$images = $this->db->where("product_id" , $result->product_id)->order_by("primary_image" , "DESC")->get("products_images")->result();

			foreach($images as $key => $row){
				$result->images[] = array(
					"thumbnail" => site_url("thumbs/images/product/".$row->image_path."/250/250/".$row->image_name) ,
					"large_image" =>site_url("thumbs/images/product/".$row->image_path."/500/500/".$row->image_name)
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
	            "pay_method"  => $this->post->pay_method,
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

	        $order_number = date("mdY").'-'.sprintf('%05d', $order_id);

	        if(!$this->post->is_same){
	        	$this->db->insert("address" , $this->post->physical);
				$address_id = $this->db->insert_id();
	        }else{
	        	$address_id = $this->post->address_id;
	        }

	        $this->db->where("order_id" , $order_id)->update("customer_order" , [
	            "order_number"      	=> $order_number,
	            "total_price"       	=> $total_price ,
	            "items"             	=> $items ,
	            "gst_price"				=> $total_price * 0.06,
	            "total_price_with_gst" 	=> ($total_price * 0.06) + $total_price ,
	            "address_id"			=> $address_id
	        ]);

	        $this->notification->notify_admin([
				"sender" 	=> $this->post->customer_id ,
				"ref_type"  => "CUSTOMER",
				"reference" => 'Added a new order #'.$order_number,
				"ref_id" 	=> $order_number
			]);
			
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){
	            echo json_encode(["status" => false , "message" => "Checkout Failed , Please Try Again Later"]);
	        }else{
	        	echo json_encode(["status" => true , "order_number" => $order_number]);
	        }
		}
	}

	#getting all the customer order history
	public function get_order_list($customer_id){

		$result = $this->db->where("customer_id" , $customer_id)->order_by("order_id" , "DESC")->get("customer_order")->result();

        foreach($result as $key => $row){
            $result[$key]->total_price = custom_money_format($row->total_price);
            $result[$key]->total_price_with_gst = custom_money_format($row->total_price_with_gst);
            $result[$key]->created = convert_timezone($row->created , true);
            $result[$key]->status = convert_order_status($row->status , true);
        }

        if($result){
        	echo json_encode(["status" => true , "data" => $result]);
        }else{
        	echo json_encode(["status" => false , "message" => "No Results..."]);
        }
        
	}

	private function getPriceBookId(){
		$customer_id = $this->post->customer_id;
		return $this->db->select("price_book_id")->where("customer_id" , $customer_id)->get("customer")->row()->price_book_id;
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

	#currently processing order
	public function currently_order($customer_id){
		$result = $this->db->where("customer_id" , $customer_id)->where_in("status" , [1,2,3])->order_by("order_id" , "DESC")->get("customer_order")->result();

        foreach($result as $key => $row){
            $result[$key]->total_price = custom_money_format($row->total_price);
            $result[$key]->gst_price = custom_money_format($row->gst_price);
            $result[$key]->total_price_with_gst = custom_money_format($row->total_price_with_gst);
            $result[$key]->created = convert_timezone($row->created , true);
            $result[$key]->status = convert_order_status($row->status , true);
        }

        if($result){
        	echo json_encode(["status" => true , "data" => $result]);
        }else{
        	echo json_encode(["status" => false , "message" => "No Results..." , "data" => []]);
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