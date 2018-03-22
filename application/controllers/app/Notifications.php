<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MY_Controller {

	public function __construct() {
       parent::__construct();

       
    }

	public function index(){

		$this->data['page_name'] = "Notifications";
		$this->data['main_page'] = "backend/page/notification/view";

		$this->data['result'] = $this->notification->notify_list(true);
		$this->load->view('backend/master' , $this->data);
	}
	
	public function mark_all_read(){

		$this->db->where("unread" , 0)->update("notifications" , [ "unread" => true ]);

		redirect('app/notifications', "refresh");

	}
}
