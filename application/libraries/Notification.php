<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Notification {

	private $CI;

	function __construct( ) {
		$this->CI =& get_instance();
	}


	public function notify_admin($data){
		$this->CI->db->insert("notifications" , [
			"unread"	=> false ,
			"sender"	=> $data['sender'] ,
			"ref_type"	=> $data['ref_type'] ,
			"reference" => $data['reference'],
			"ref_id"    => $data['ref_id'],
			"created"	=> time()
		]);
	}

	public function notify_read($notification_id){
		$this->CI->db->where("id" , $notification_id)->update("notifications" , ["unread" => true]);
	}

	public function notify_list($all = false , $count = false){
		
		if(!$all){
			$this->CI->db->where("unread" , 0);
		}

		if(!$count AND !$all){
			$this->CI->db->limit(10);
		}

		$result =  $this->CI->db->order_by("created" , "DESC")->get("notifications")->result();

		foreach($result as $key => $row){
			if($row->ref_type == "CUSTOMER"){
				$result[$key]->sender = $this->CI->db->select("display_name as name")->where("customer_id" , $row->sender)->get("customer")->row();
				$result[$key]->url    = $this->CI->config->site_url("app/invoice/order?order_no=".$row->ref_id);
			}else{
				$result[$key]->sender = $this->CI->db->select("name")->where("user_id" , $row->sender)->get("users")->row();
				$result[$key]->url    = $this->CI->config->site_url("app/invoice?invoice_no=".$row->ref_id);
			}
			$result[$key]->created = convert_timezone($row->created , true);
 		}
		return $result;
	}
}