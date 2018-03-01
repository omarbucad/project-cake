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

	public function notify_list(){
		$result =  $this->CI->db->where("unread" , 0)->order_by("created" , "DESC")->get("notifications")->result();

		foreach($result as $key => $row){
			if($row->ref_type == "CUSTOMER"){
				$result[$key]->sender = $this->CI->db->select("display_name as name")->where("customer_id" , $row->sender)->get("customer")->row();
				$result[$key]->url    = $this->CI->config->site_url("app/invoice/order?name=".$row->ref_id);
			}else{
				$result[$key]->sender = $this->CI->db->select("name")->where("user_id" , $row->sender)->get("users")->row();
				$result[$key]->url    = $this->CI->config->site_url("app/invoice?invoice_no=".$row->ref_id);
			}
			$result[$key]->created = convert_timezone($row->created , true);
 		}
		return $result;
	}
}