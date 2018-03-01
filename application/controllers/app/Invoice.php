<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends MY_Controller {

	public function __construct() {
       parent::__construct();

       $this->load->model("Invoice_model" , "invoice");
    }

	public function index(){

		$this->data['page_name'] = "Invoice Dashboard";
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
		$this->data['page_name'] = "Product Order Dashboard";
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
					
					//CREATING INVOICE PDF
					$this->create_invoice_pdf($invoice_id);

					//CREATING DELIVERY ORDER PDF
					$this->create_do_pdf($invoice_id);

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

				$this->send_push_notification($this->input->post("selected_driver"));

				echo json_encode(["status" => true , "message" => "<span class='label label-success'>On Delivery</span>"]);

				break;
			
			case 'delivered':

				$this->db->where("order_id" , $order_id)->update("customer_order" , ["status" => 4]);

				echo json_encode(["status" => true , "message" => "<span class='label label-success'>Delivered</span>"]);

				break;
			
			default:
				echo json_encode(["status" => false , "message" => "Error"]);
				break;
		}
	}

	public function pay_invoice(){
		if($response = $this->invoice->pay_invoice()){

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Updated Invoice #'.$this->input->post("invoice_no"));	

			header('Location: ' . $_SERVER['HTTP_REFERER']);

		}else{
			$this->session->set_flashdata('status' , 'error');
			$this->session->set_flashdata('message' , 'Something went wrong');	

			redirect("app/invoice" , 'refresh');
		}
	}
	
	private function create_invoice_pdf($invoice_id){

		$invoice_information = $this->invoice->get_invoice_by_id($invoice_id);


		if($pdf = $this->pdf->create_invoice($invoice_information)){

			$this->db->where("invoice_id" , $invoice_id)->update("invoice" , [
				"invoice_pdf" => $pdf['file']
			]);

			$this->send_email_invoice($invoice_information , $pdf['attachment']);

		}

	}

	private function send_email_invoice($invoice_information , $pdf_file){

		// $this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
		// $this->email->to($invoice_information->email);

		// $this->email->subject('Gravybaby Bill Statement');
		// $this->email->message($this->load->view('backend/email/send_invoice' , $invoice_information , TRUE));
		// $this->email->attach($pdf_file);
		// $this->email->set_mailtype('html');

		// $this->email->send();
	}


	private function create_do_pdf($invoice_id){
		$invoice_information = $this->invoice->get_invoice_by_id($invoice_id);

		if($pdf = $this->pdf->create_do($invoice_information)){

			$this->db->where("invoice_id" , $invoice_id)->update("invoice" , [
				"delivery_order_pdf" => $pdf['file']
			]);

		}

	}

	public function view_invoice_log($invoice_id){
		$data = $this->invoice->view_invoice_log($invoice_id);
		if($data){
			echo json_encode(["status" => true , "data" => $data]);
		}else{
			echo json_encode(["status" => false]);
		}
	}

}
