<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Invoice_model" , "invoice");
    }

	public function index(){
		
		$this->super_admin_only();
	
		$this->data['page_name'] = "Dashboard";
		$this->data['main_page'] = "backend/page/dashboard/welcome";
		$this->data['new_order'] = $this->invoice->get_dashboard_order();
		$this->data['total_pending_order'] = count($this->data['new_order']);
		$this->data['unpaid_invoice'] = $this->invoice->get_dashboard_invoice();
		$this->data['total_confirmed_order'] = count($this->data['unpaid_invoice']);
		$this->data['card_info'] = $this->invoice->get_dashboard_cards_info();
		$this->data['sales_data'] = $this->invoice->get_sales_data();
		$this->load->view('backend/master' , $this->data);
	}

	public function read_notif(){
		if($this->input->post()){
			$this->notification->notify_read($this->input->post("id"));
		}
	}
}
