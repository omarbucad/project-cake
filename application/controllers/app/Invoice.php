<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Invoice_model" , "invoice");
    }

	public function index(){

		$this->data['page_name'] = "Invoice";
		$this->data['main_page'] = "backend/page/invoice/invoice";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/invoice") ;
		$this->data['config']["total_rows"] = $this->invoice->get_invoice(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result'] = $this->invoice->get_invoice();
		$this->load->view('backend/master' , $this->data);
	}

	public function order(){
		$this->data['page_name'] = "Order's";
		$this->data['main_page'] = "backend/page/invoice/order";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/invoice/order") ;
		$this->data['config']["total_rows"] = $this->invoice->get_order(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result']	 = $this->invoice->get_order();
		$this->data['driver_list'] = $this->invoice->get_driver_list();
		$this->load->view('backend/master' , $this->data);
	}

	public function update_status_order(){
		$btn = $this->input->post("btn_click");
		$order_id = $this->input->post("order_id");

		switch ($btn) {
			case 'cancel':

				$this->db->where("order_id" , $order_id)->update("customer_order" , ["status" => 0]);

				echo json_encode(["status" => true , "message" => "<span class='label label-danger'>Cancelled</span>"]);

				break;

			case 'confirm':

				if($invoice_id = $this->invoice->create_invoice($order_id)){
					
					$this->create_invoice_pdf($invoice_id);

					$this->db->where("order_id" , $order_id)->update("customer_order" , ["status" => 2]);

					echo json_encode(["status" => true , "message" => "<span class='label label-success'>Confirmed Ordered</span>"]);
				}else{
					echo json_encode(["status" => false , "message" => "Creating Invoice Failed"]);
				}
				
				break;

			case 'on_delivery':

				$this->db->where("order_id" , $order_id)->update("customer_order" , [
					"status" 			=> 3 ,
					"driver_id"			=> $this->input->post("selected_driver"),
					"driver_note"		=> $this->input->post("note")
				]);

				$this->push_notify_driver($this->input->post("selected_driver") , $order_id);

				echo json_encode(["status" => true , "message" => "<span class='label label-success'>On Delivery</span>"]);

				break;
			case 'delivered':

				$this->db->where("order_id" , $order_id)->update("customer_order" , ["status" => 4]);

				echo json_encode(["status" => true , "message" => "<span class='label label-success'>Delivered</span>"]);

				break;
			default:
				# code...
				break;
		}
	}

	private function create_invoice_pdf($invoice_id){
		
	}

	private function push_notify_driver($driver_id , $order_id){

	}
}
