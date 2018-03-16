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
        $this->db->join("address a" , "a.address_id = co.address_id");

        if($active){
        	$this->db->where("co.status" , 3)->where("co.delivered_date IS NULL");
        }

		$result = $this->db->where("co.driver_id" , $driver_id)->order_by("created" , "DESC")->get("customer_order co")->result();

		foreach($result as $key => $row){
			$result[$key]->created = convert_timezone($row->created , true);
			$result[$key]->status  = convert_order_status($row->status , true);

			$result[$key]->address = $row->street1.",<br>";
            $result[$key]->address .= ($row->street2) ? $row->street2.",<br>" : "";
            $result[$key]->address .= ($row->suburb) ? $row->suburb.",<br>" : "";
            $result[$key]->address .= ($row->state) ? $row->state.",<br>" : "";
            $result[$key]->address .= ($row->postcode) ? $row->postcode.",<br>" : "";
            $result[$key]->address .= ($row->city) ? $row->city : "";
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
			$result->show_delivery_button = ($result->order_images AND $result->status == "On Delivery") ? true : false;
			echo json_encode(["status" => true  , "data" => $result]);
		}else{
			echo json_encode(["status" => false , "message" => "No Results..."]);	
		}
	}

	public function get_items_images($order_number){
		$result = $this->db->where("order_no" , $order_number)->where("deleted IS NULL")->get("customer_order_images")->result();

		$tmp = array();

		foreach($result as $key => $row){
			$tmp[$row->i_type][] = [
				"image_id"		=> $row->image_id,
				"image"	    	=> site_url("thumbs/images/items/".$row->image_path."/350/350/".$row->image_name) ,
				"image_large"	=> site_url("thumbs/images/items/".$row->image_path."/550/550/".$row->image_name)
			];
		}

		if(!isset($tmp["AFTER"])){
			$tmp["AFTER"] = array();
		}

		if($tmp){
			echo json_encode(["status" => true  , "data" => $tmp]);
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

				$this->notification->notify_admin([
					"sender" 	=> $this->post->driver_id ,
					"ref_type"  => "DRIVER",
					"reference" => 'order #'.$order_number.' has been delivered',
					"ref_id" 	=> $order_number
				]);

				echo json_encode(["status"=> true]);
			}else{
				echo json_encode(["status"=> false]);
			}
		}
	}

	public function change_password(){
		if($this->post){
			$user_id = $this->post->driver_id;
			$password = md5($this->post->password);

			$this->db->where("user_id" , $user_id)->update("users" , [
				"password"	=> $password
			]);

			echo json_encode(["status" => true]);
		}
	}

	public function save_token(){
		if($this->post){

			$check = $this->db->where([
				"device_id"	=> $this->post->device_id
			])->get("push_token")->row();

			if(!$check){
				$this->db->insert("push_token" , [
					"user_id"	=> $this->post->user_id ,
					"token"		=> $this->post->token ,
					"device_id" => $this->post->device_id ,
					"updated"	=> time()
				]);
			}else{
				$this->db->where("id" , $check->id)->update("push_token" , [
					"updated" => time() ,
					"user_id" => $this->post->user_id
				]);
			}

			

			echo json_encode(["status"=> true]);
		}
	}

	public function driver_logout(){
		if($this->post){

			$this->db->where([
				"user_id"	=> $this->post->user_id ,
				"device_id"	=> $this->post->device_id
			])->delete("push_token");

			echo json_encode(["status" => true]);
		}
	}

	public function send_items_image(){
		if($this->post){
			$order_number = $this->post->order_number;
			$file = $this->save_image($order_number , false);

			$this->db->insert("customer_order_images" , [
				"order_no" 		=> $order_number,
				"image_path" 	=> $file["image_path"] ,
				"image_name"	=> $file["image_name"],
				"i_type"		=> $this->post->type ,
				"created"		=> time()
			]);

			$last_id = $this->db->insert_id();

			echo json_encode([
				"image_id"		=> $last_id ,
				"image"			=> site_url("thumbs/images/items/".$file["image_path"]."/350/350/".$file["image_name"]) ,
				"image_large"	=> site_url("thumbs/images/items/".$file["image_path"]."/550/550/".$file["image_name"]) 
			]);
		}
	}

	public function remove_image_items($image_id){
		$this->db->where("image_id" , $image_id)->update("customer_order_images" , ["deleted" => time()]);
		echo json_encode(["status" => true]);
	}

	public function start_driving(){
		if($this->post){
			$this->db->where("order_number" , $this->post->order_number)->update("customer_order" , [
				"start_driving" => time()
			]);
		}
	}

	private function save_image($order_number , $signature = true){
		$image = $this->post->image;

		$name = $order_number.'_'.time().'.PNG';
        $year = date("Y");
        $month = date("m");
        
        if($signature){
        	$folder = "./public/upload/signature/".$year."/".$month;
        }else{
        	$folder = "./public/upload/items/".$year."/".$month;
        }
        

        $date = time();

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            mkdir($folder.'/thumbnail', 0777, true);
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

        if($signature){
        	return $year."/".$month.'/'.$name;
        }else{
        	return [
        		"image_path" => $year."/".$month.'/',
        		"image_name" => $name
        	];
        }
	}
}

?>