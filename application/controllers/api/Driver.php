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
        $this->load->model("Product_model" , "product");
	}

	public function get_order_list($driver_id , $active = false){
		/*
            0 - cancelled Order
            1 - Placed an order
            2 - Admin Confirm
            3 - On Delivery
            4 - Delivered
        */
        $this->db->select("co.order_number , co.order_id , co.driver_note , co.created , co.items , co.status , c.display_name");
        $this->db->select("a.street1 , a.street2 , a.suburb , a.city , a.postcode , a.state ");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("address a" , "a.address_id = c.physical_address_id");

        if($active){
        	$this->db->where("co.status" , 3)->where("co.delivered_date IS NULL");
        }

		$result = $this->db->where("co.driver_id" , $driver_id)->order_by("created" , "DESC")->get("customer_order co")->result();

		foreach($result as $key => $row){
			$result[$key]->created = convert_timezone($row->created , true);
			$result[$key]->status = convert_order_status($row->status , true);
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

	public function save_signature(){
		if($this->post){
			if($file_path = $this->save_image($this->post->order_number)){
				$order_number = $this->post->order_number;

				$this->db->where("order_number" , $order_number)->update("customer_order" , [
					"delivered_date"	=> time() ,
					"customer_name"		=> $this->post->customer_name ,
					"notes"				=> $this->post->notes ,
					"image"				=> $file_path ,
					"status"			=> 4
				]);

				echo json_encode(["status"=> true]);
			}else{
				echo json_encode(["status"=> false]);
			}
		}
	}

	private function save_image($order_number){
		$image = $this->post->image;

		$name = $order_number.'_'.time().'.PNG';
        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/signature/".$year."/".$month;

        $date = time();

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            create_index_html($folder);
        }

        $path = $folder.'/'.$name;

        $encoded = $image;

	    //explode at ',' - the last part should be the encoded image now
	    $exp = explode(',', $encoded);

	    //we just get the last element with array_pop
	    $base64 = array_pop($exp);

	    //decode the image and finally save it
	    $data = base64_decode($base64);


	    //make sure you are the owner and have the rights to write content
	    file_put_contents($path, $data);

        return $year."/".$month.'/'.$name;
	}
}

?>