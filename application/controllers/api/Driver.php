<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Driver extends CI_Controller{

	private $post;

	public function __construct() {
		parent::__construct();

		if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        $this->post = json_decode(file_get_contents("php://input"));
	}

	public function get_my_active_order_list($driver_id){
		/*
            0 - cancelled Order
            1 - Placed an order
            2 - Admin Confirm
            3 - On Delivery
            4 - Delivered
        */
        $this->db->select("order_number , order_id , driver_note , created");
		$result = $this->db->where("status" , 3)->where("driver_id" , $driver_id)->order_by("created" , "DESC")->get("customer_order")->result();

		foreach($result as $key => $row){
			$result[$key]->created = convert_timezone($row->created , true);
		}

		echo json_encode($result);
	}

	public function get_order_history($driver_id){
		$this->db->select("order_number , order_id , driver_note , created");
		$result = $this->db->where("driver_id" , $driver_id)->order_by("created" , "DESC")->get("customer_order")->result();

		foreach($result as $key => $row){
			$result[$key]->created = convert_timezone($row->created , true);
		}

		echo json_encode($result);
	}

	public function get_order_information($order_number){
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
}

?>