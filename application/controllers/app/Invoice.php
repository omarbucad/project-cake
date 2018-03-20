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

		if($this->input->get("export")){

			$this->data['result'] = $this->invoice->get_invoice(false , true);
			$this->download_excel_invoice($this->data['result']);

		}else{

			//PAGINATION
			$this->data['config']["base_url"] = base_url("app/invoice") ;
			$this->data['config']["total_rows"] = $this->invoice->get_invoice(true);
			$this->pagination->initialize($this->data['config']);
			$this->data["links"] = $this->pagination->create_links();

			$this->data['result'] = $this->invoice->get_invoice();
			$this->data['card_info'] = $this->invoice->get_dashboard_cards_info();

			$this->load->view('backend/master' , $this->data);
		}
	}

	public function order(){
		$this->data['page_name'] = "Product Order Dashboard";
		$this->data['main_page'] = "backend/page/invoice/order";

		if($this->input->get("export")){

			$this->data['result'] = $this->invoice->get_order(false , true);
			$this->download_excel_order($this->data['result']);

		}else{

			//PAGINATION
			$this->data['config']["base_url"] = base_url("app/invoice/order") ;
			$this->data['config']["total_rows"] = $this->invoice->get_order(true);
			$this->pagination->initialize($this->data['config']);
			$this->data["links"] = $this->pagination->create_links();

			$this->data['result']	 = $this->invoice->get_order();
			$this->data['driver_list'] = $this->invoice->get_driver_list();
			$this->load->view('backend/master' , $this->data);
		}

		
	}

	public function update_status_order(){
		$btn = $this->input->post("btn_click");
		$order_id = $this->input->post("order_id");

		$this->db->select("email , c.customer_id , co.order_number");
		$email = $this->db->join("customer c", "c.customer_id = co.customer_id")->where("order_id",$order_id)->get("customer_order co")->row();

		$data['order_no'] = $order_id;

		$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
		$this->email->to($email->email);
		$this->email->set_mailtype("html");
		$this->email->subject('Gravybaby Cake Ordering - Order Status');

		switch ($btn) {
			case 'cancel':

				$this->db->where("order_id" , $order_id)->update("customer_order" , ["status" => 0]);

				//SEND ORDER STATUS EMAIL
				$data['status'] = "Cancelled";					
				$this->email->message($this->load->view('backend/email/order_status_email', $data , true));
				$this->email->send();


				/*
					EDIT :: since ajax request to kahit wag mo na gamitin tong set_flashdata gagana lang to sa next reload mo so ang magandang gawin i return mo nalang sa json mo ung response na gusto mo at un ung lagay sa notify sa script

					$this->session->set_flashdata('status' , 'success');	
					$this->session->set_flashdata('message' , 'Successfully Cancelled an Order');	

				*/
				

				// EDIT :: gamitin ang helper na convert_order_status 
				//echo json_encode(["status" => true , "message" => "<span class='label label-danger'>Cancelled</span>"]);

				echo json_encode(["status" => true , "message" => convert_order_status(0) , "response" => "Successfully Cancelled an Order"]);		

				break;

			case 'confirm':

				if($invoice_id = $this->invoice->create_invoice($order_id)){
					
					//CREATING INVOICE PDF
					$this->create_invoice_pdf($invoice_id);

					//CREATING DELIVERY ORDER PDF
					$this->create_do_pdf($invoice_id);

					$this->db->where("order_id" , $order_id)->update("customer_order" , ["status" => 2]);

					//SEND ORDER STATUS EMAIL
					$data['status'] = "Confirmed";					
					$this->email->message($this->load->view('backend/email/order_status_email', $data , true));
					$this->email->send();

					/*
						EDIT :: since ajax request to kahit wag mo na gamitin tong set_flashdata gagana lang to sa next reload mo so ang magandang gawin i return mo nalang sa json mo ung response na gusto mo at un ung lagay sa notify sa script

						$this->session->set_flashdata('status' , 'success');	
						$this->session->set_flashdata('message' , 'Successfully Updated Status to Confirmed Ordered');	
					*/

					

					echo json_encode(["status" => true , "message" => convert_order_status(2) , "response" => 'Successfully Updated Status to Confirmed Ordered']);
				}else{
					echo json_encode(["status" => false , "message" => "Creating Invoice Failed"]);
				}
				
				break;

			case 'on_delivery':

				$this->db->where("order_id" , $order_id)->update("customer_order" , [
					"status" 				=> 3 ,
					"driver_id"				=> $this->input->post("selected_driver"),
					"driver_note"			=> $this->input->post("note"),
					"place_delivery_date"	=> time()
				]);
				
				//PUSH FOR DRIVER
				$this->send_push_notification($this->input->post("selected_driver"));

				//PUSH FOR CUSTOMER
				$this->send_push_notification([
					"id" => $email->customer_id  ,
					"order_no" => $email->order_number
				], "DELIVERY");

				//SEND ORDER STATUS EMAIL
				$data['status'] = "On-Delivery";					
				$this->email->message($this->load->view('backend/email/order_status_email', $data , true));
				$this->email->send();

				/*
					EDIT :: since ajax request to kahit wag mo na gamitin tong set_flashdata gagana lang to sa next reload mo so ang magandang gawin i return mo nalang sa json mo ung response na gusto mo at un ung lagay sa notify sa script

					$this->session->set_flashdata('status' , 'success');	
					$this->session->set_flashdata('message' , 'Successfully Updated Status to On-Delivery');	
				*/
				

				echo json_encode(["status" => true , "message" => convert_order_status(3)]);

				break;
			
			case 'delivered':

				$this->db->where("order_id" , $order_id)->update("customer_order" , ["status" => 4]);

				//SEND ORDER STATUS EMAIL
				$data['status'] = "Delivered";					
				$this->email->message($this->load->view('backend/email/order_status_email', $data , true));
				$this->email->send();


				/*
					EDIT :: since ajax request to kahit wag mo na gamitin tong set_flashdata gagana lang to sa next reload mo so ang magandang gawin i return mo nalang sa json mo ung response na gusto mo at un ung lagay sa notify sa script

					$this->session->set_flashdata('status' , 'success');	
					$this->session->set_flashdata('message' , 'Successfully Updated Status to Delivered');
				*/
				

				echo json_encode(["status" => true , "message" => convert_order_status(4) , "response" => 'Successfully Updated Status to Delivered']);

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

	private function send_email_invoice($invoice_information , $pdf_file , $ajax = false){

		$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
		$this->email->to($invoice_information->email);

		$this->email->subject('Gravybaby Bill Statement');
		$this->email->message($this->load->view('backend/email/send_invoice' , $invoice_information , TRUE));
		$this->email->attach($pdf_file);
		$this->email->set_mailtype('html');

		if($ajax){
			if($this->email->send()){
				echo json_encode(["status" => true , "message" => "Email has been sent"]);
			}else{
				echo json_encode(["status" => false , "message" => "Sending Failed"]);
			}
		}else{
			$this->email->send();
		}
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

	private function download_excel_invoice($data){

		$export = array();

		foreach($data as $key => $row){
			$export[] = array(
				"Order No" 		 => $row->order_number ,
				"Customer" 		 => $row->display_name,
				"Company Name" 	 => $row->company_name ,
				"Ordered Items " => $row->items,
				"Price w/o GST"	 => $row->price,
				"GST @6%"		 => $row->gst_price,
				"Total Price"	 => $row->total_price_with_gst,
				"Ordered Date"	 => $row->ordered_date,
 				"Status"   		 => $row->status_raw,
 				"Invoice No"	 => $row->invoice_no,
 				"Pay Method"	 => $row->payment_method_raw,
 				"Invoice Date"	 => $row->invoice_date,
 				"Invoice Status" => $row->payment_type_raw ,
 				"Created By"	 => $row->created_by
			);
		}


		download_send_headers('invoice_' . date("Y-m-d") . ".csv");
		echo array2csv($export);
	}

	private function download_excel_order($data){

		$export = array();

		foreach($data as $key => $row){
			$export[] = array(
				"Order No" 		 => $row->order_number ,
				"Customer" 		 => $row->display_name,
				"Company Name" 	 => $row->company_name ,
				"Ordered Items " => $row->items,
				"Price w/o GST"	 => $row->total_price,
				"GST @6%"		 => $row->gst_price,
				"Total Price"	 => $row->total_price_with_gst,
				"Ordered Date"	 => $row->created,
 				"Status"   		 => $row->status_raw
			);
		}


		download_send_headers('order_' . date("Y-m-d") . ".csv");
		echo array2csv($export);
	}

	public function send_invoice_email_ajax($invoice_id){

		$invoice_information = $this->invoice->get_invoice_by_id($invoice_id);

		$this->send_email_invoice($invoice_information , FCPATH.$invoice_information->invoice_pdf , true);

		
	}

	public function get_invoice_info($invoice_id){
		$invoice_information = $this->invoice->get_invoice_by_id($invoice_id);


		//print_r_die(json_encode(["status" => true, "data" => $invoice_information ]));
		echo json_encode(["status" => true, "data" => $invoice_information ]);
	}

}
