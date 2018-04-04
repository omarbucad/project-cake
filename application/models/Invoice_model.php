<?php

class Invoice_model extends CI_Model {

	public function get_order($count = false , $view_all = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;
        $sort_by = ($this->input->get("sort_by")) ? $this->input->get("sort_by") : "co.order_id";
        $sort = ($this->input->get("sort")) ? $this->input->get("sort") : "DESC";

        $this->db->select("c.display_name , c.company_name , c.email, u.name, a.*, co.*, co.pay_method");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("users u" , "u.user_id = co.driver_id" , "LEFT");
        $this->db->join("address a" , "a.address_id = co.address_id");

        // SEARCH

        if($name = $this->input->get("name")){
            $this->db->like("c.display_name" , $name);
            $this->db->or_like("c.company_name" , $name);
            $this->db->or_like("c.email" , $name);
        }

        if($order_no = $this->input->get("order_no")){
            $this->db->where("co.order_number" , $order_no);
        }

        if($date = $this->input->get("date")){
            $date  = explode("-", $date);
            $start = strtotime(trim($date[0].' 00:00'));
            $end   = strtotime(trim($date[1].' 23:59'));


            $this->db->where("co.created >= " , $start);
            $this->db->where("co.created <= " , $end);
        }   

        if($pay_method = $this->input->get("payment_method")){
           
            $this->db->where("co.pay_method" , $pay_method);   
                    
        }

        if($order_status = $this->input->get("order_stat")){
            if($order_status == ""){

            }
            else if($order_status == "C"){
                $this->db->where("co.status" , 0);
            }
            else{
                $this->db->where("co.status" , $order_status);
            }
            
        }
        
        $this->db->order_by($sort_by , $sort);

        if($count){
            return $this->db->get("customer_order co")->num_rows();
        }else if($view_all){
            $result = $this->db->get("customer_order co")->result();
        }else{
            $result = $this->db->limit($limit , $skip)->get("customer_order co")->result();
        }

        foreach($result as $k => $r){
            $this->db->select("op.product_name , op.product_price , op.quantity , op.total_price , op.product_id" );

            $result[$k]->product_list = $this->db->where("order_id" , $r->order_id)->get("customer_order_product op")->result();

            foreach($r->product_list as $key => $row){
                $result[$k]->product_list[$key]->product_price = custom_money_format($row->product_price);
                $result[$k]->product_list[$key]->total_price = custom_money_format($row->total_price);
                $result[$k]->product_list[$key]->images = $this->db->where("product_id" , $row->product_id)->order_by("primary_image" , "DESC")->get("products_images")->result();
            }

            $result[$k]->created = convert_timezone($r->created , true);
            $result[$k]->total_price = custom_money_format($r->total_price );
            $result[$k]->start_driving = convert_timezone($r->start_driving , true);
            $result[$k]->gst_price = custom_money_format($r->gst_price );
            $result[$k]->total_price_with_gst = custom_money_format( $r->total_price_with_gst );

            $result[$k]->status_raw_number = $r->status;
            $result[$k]->status_raw = convert_order_status($r->status , true);
            $result[$k]->status = convert_order_status($r->status);
            $result[$k]->pay_method = convert_payment_status($r->pay_method);

            $result[$k]->delivered_date = convert_timezone($r->delivered_date , true);
            $result[$k]->place_delivery_date = convert_timezone($r->place_delivery_date , true);


            $result[$k]->address = $result[$k]->street1;
            $result[$k]->address .= ($result[$k]->street2) ? ", <span>".$result[$k]->street2 : "";
            $result[$k]->address .= ($result[$k]->suburb) ? ",<br>".$result[$k]->suburb : "";
            $result[$k]->address .= ($result[$k]->state) ? ", <span>".$result[$k]->state : "";
            $result[$k]->address .= ($result[$k]->postcode) ? ",<br>".$result[$k]->postcode : "";
            $result[$k]->address .= ($result[$k]->city) ? " <span>".$result[$k]->city : "";

            $item_image = $this->db->where("order_no" , $r->order_number)->where("deleted IS NULL")->get("customer_order_images")->result();
            
            $result[$k]->item_image = array();

            foreach($item_image as $key => $row){
                $result[$k]->item_image[$row->i_type][] = $row;
            }

            if(!isset($result[$k]->item_image["AFTER"]) AND $result[$k]->item_image){
                $result[$k]->item_image["AFTER"] = array();
            }
        }

        return $result;
    }

    public function create_invoice($order_id){
        $user_id = $this->session->userdata("user")->user_id;

        $order_info = $this->db->where("order_id" , $order_id)->get("customer_order")->row();

        $this->db->insert("invoice" , [
            "order_id"      => $order_id ,
            "price"         => $order_info->total_price,
            "created_by"    => $user_id ,
            "payment_type"  => "UNPAID" ,
            "payment_method"=> $order_info->pay_method,
            "invoice_date"  => time() ,
            "gst"           => 6 ,
            "total_price"   => $order_info->total_price_with_gst,
            "invoice_no"    => $order_info->order_number
        ]);

        $last_id = $this->db->insert_id();

        $this->db->insert("invoice_logs" , [
            "invoice_id"        => $last_id ,
            "payment_method"    => $order_info->pay_method ,
            "notes"             => "Initial" ,
            "user_id"           => $user_id ,
            "created"           => time()
        ]);

        return $last_id;
    }

    public function get_driver_list(){
        $result = $this->db->where([
            "account_type"  => "DRIVER" ,
            "status"        => 1
        ])->get("users")->result();

        return $result;
    }

    public function get_invoice($count = false , $view_all = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = $this->input->get("limit");
        $sort_by = ($this->input->get("sort_by")) ? $this->input->get("sort_by") : "i.invoice_date";
        $sort = ($this->input->get("sort")) ? $this->input->get("sort") : "DESC";
        
        $this->db->select("co.*, i.* , co.created as ordered_date , c.display_name , c.company_name , c.email, c.physical_address_id , u.name , u2.name as updated_by , u3.name as created_by, a.address_id as shipping_address_id, a.street1 as shipping_address_street1, a.street2 as shipping_address_street2, a.suburb as shipping_address_suburb, a.city as shipping_address_city, a.postcode as shipping_address_postcode, a.state as shipping_address_state ,a2.*");

        $this->db->join("customer_order co" , "co.order_id = i.order_id");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("address a" , "a.address_id = co.address_id");
        $this->db->join("address a2" , "a2.address_id = c.physical_address_id");
        $this->db->join("users u" , "u.user_id = co.driver_id" , "LEFT");
        $this->db->join("users u2" , "u2.user_id = i.updated_by" , "LEFT");
        $this->db->join("users u3" , "u3.user_id = i.created_by" , "LEFT");

        /*
            TODO :: SEARCHING LOGIC HERE
        */

        if($name = $this->input->get("name")){
            $this->db->like("c.display_name" , $name);
            $this->db->or_like("c.company_name" , $name);
            $this->db->or_like("c.email" , $name);
        }

        if($invoice_no = $this->input->get("invoice_no")){
            $this->db->where("invoice_no" , $invoice_no);
        }

        if($status = $this->input->get("status")){
            $this->db->where("payment_type" , $status);
        }

        if($date = $this->input->get("date")){
            $date  = explode("-", $date);
            $start = strtotime(trim($date[0].' 00:00'));
            $end   = strtotime(trim($date[1].' 23:59'));


            $this->db->where("i.invoice_date >= " , $start);
            $this->db->where("i.invoice_date <= " , $end);
        }   

        if($payment_method = $this->input->get("payment_method")){
            $this->db->where("i.payment_method" , $payment_method);
        }

        if($order_status = $this->input->get("order_status")){
            $this->db->where("co.status" , $order_status);
        }

        $this->db->order_by($sort_by , $sort);

        if($count){
            return $this->db->get("invoice i")->num_rows();
        }else if($view_all){
            $result = $this->db->get("invoice i")->result();
        }else{
            $result = $this->db->limit($limit , $skip)->get("invoice i")->result();
        }
        
        foreach($result as $key => $row){
            $result[$key]->invoice_date = convert_timezone($row->invoice_date , true);
            $result[$key]->ordered_date = convert_timezone($row->ordered_date , true);
            $result[$key]->start_driving = convert_timezone($row->start_driving , true);
            $result[$key]->paid_date = convert_timezone($row->paid_date);
            $result[$key]->delivered_date = convert_timezone($row->delivered_date , true);
            $result[$key]->place_delivery_date = convert_timezone($row->place_delivery_date , true);
            $result[$key]->price_raw = $row->price;
            $result[$key]->price = custom_money_format($row->price);
            $result[$key]->total_price_raw = $row->total_price;
            $result[$key]->total_price = custom_money_format($row->total_price);
            $result[$key]->gst_price = custom_money_format($row->gst_price);
            $result[$key]->total_price_with_gst = custom_money_format($row->total_price_with_gst);
            $result[$key]->files = $this->db->where("invoice_id" , $row->invoice_id)->get("invoice_files")->result();
            $result[$key]->invoice_pdf = $this->config->base_url($row->invoice_pdf);
            $result[$key]->delivery_order_pdf = $this->config->base_url($row->delivery_order_pdf);
            $result[$key]->status_raw = $row->status;
            $result[$key]->payment_type_raw = $row->payment_type;
            $result[$key]->payment_method_raw = $row->payment_method;
            $result[$key]->payment_method = convert_payment_status($row->payment_method);
            $result[$key]->payment_type = convert_invoice_status($row->payment_type);
            $result[$key]->status_raw = convert_order_status($row->status,true);
            $result[$key]->status = convert_order_status($row->status);

            $result[$key]->shipping_address = $result[$key]->shipping_address_street1;
            $result[$key]->shipping_address .= ($result[$key]->shipping_address_street2) ? ", <span>".$result[$key]->shipping_address_street2 : "";
            $result[$key]->shipping_address .= ($result[$key]->shipping_address_suburb) ? ",<br>".$result[$key]->shipping_address_suburb : "";
            $result[$key]->shipping_address .= ($result[$key]->shipping_address_city) ? ", <span>".$result[$key]->shipping_address_city : "";
            $result[$key]->shipping_address .= ($result[$key]->shipping_address_postcode) ? ",<br>".$result[$key]->shipping_address_postcode : "";
            $result[$key]->shipping_address .= ($result[$key]->shipping_address_state) ? " <span>".$result[$key]->shipping_address_state : "";

            $result[$key]->address = $result[$key]->street1;
            $result[$key]->address .= ($result[$key]->street2) ? ", <span>".$result[$key]->street2 : "";
            $result[$key]->address .= ($result[$key]->suburb) ? ",<br>".$result[$key]->suburb : "";
            $result[$key]->address .= ($result[$key]->city) ? ", <span>".$result[$key]->city : "";
            $result[$key]->address .= ($result[$key]->postcode) ? ",<br>".$result[$key]->postcode : "";
            $result[$key]->address .= ($result[$key]->state) ? " <span>".$result[$key]->state : "";

            $item_image = $this->db->where("order_no" , $row->order_number)->where("deleted IS NULL")->get("customer_order_images")->result();
            $result[$key]->item_image = array();
            
            foreach($item_image as $k => $r){
                $result[$key]->item_image[$r->i_type][] = $r;
            }

            if($result[$key]->item_image AND !isset($result[$key]->item_image['AFTER'])){
                $result[$key]->item_image['AFTER'] = array();
            }
        }

        return $result;
    }

    public function get_dashboard_order(){
        $this->db->select("co.* , c.display_name , c.email");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $result = $this->db->where("co.status" , 1)->order_by("order_id" , "DESC")->get("customer_order co")->result();
       
        foreach($result as $k => $r){
            $this->db->select("op.product_name , op.product_price , op.quantity , op.total_price , op.product_id" );

            $result[$k]->product_list = $this->db->where("order_id" , $r->order_id)->get("customer_order_product op")->result();

            foreach($r->product_list as $key => $row){
                $result[$k]->product_list[$key]->product_price = custom_money_format($row->product_price);
                $result[$k]->product_list[$key]->total_price = custom_money_format($row->total_price);
                $result[$k]->product_list[$key]->images = $this->db->where("product_id" , $row->product_id)->order_by("primary_image" , "DESC")->get("products_images")->result();
            }

            $result[$k]->created = convert_timezone($r->created , true);
            $result[$k]->total_price = custom_money_format($r->total_price );
            $result[$k]->status_raw = $result[$k]->status;
            $result[$k]->status = convert_order_status($r->status);

            $result[$k]->gst_price = custom_money_format($r->gst_price);
            $result[$k]->total_price_with_gst = custom_money_format($r->total_price_with_gst);
            
        }

        return $result;
    }

    public function get_dashboard_invoice(){

        $this->db->select(", co.* , i.* , c.display_name , c.email , u.name");
        $this->db->join("customer_order co" , "co.order_id = i.order_id");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("users u" , "u.user_id = co.driver_id" , "LEFT");
        $result = $this->db->where("i.payment_type" , "UNPAID")->order_by("invoice_date" , "DESC")->get("invoice i")->result();

        foreach($result as $key => $row){
            $result[$key]->invoice_date = convert_timezone($row->invoice_date);
            $result[$key]->paid_date = convert_timezone($row->paid_date);
            $result[$key]->price_raw = $row->price;
            $result[$key]->total_price_raw = $row->total_price;
            $result[$key]->price = custom_money_format($row->price);
            $result[$key]->total_price = custom_money_format($row->total_price);
            $result[$key]->files = $this->db->where("invoice_id" , $row->invoice_id)->get("invoice_files")->result();
        }

        return $result;
    }

    public function get_invoice_by_id($invoice_id){
        $this->db->select("i.invoice_no , i.invoice_date , i.price , i.gst , i.total_price , i.payment_method , i.invoice_pdf");
        $this->db->select("c.display_name , c.company_name , c.email , a.street1 , a.street2 , a.suburb , a.state , a.postcode , a.city , c.phone_number");
        $this->db->select("co.items , co.order_id");
        $this->db->join("customer_order co" , "co.order_id = i.order_id");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("address a" , "a.address_id = c.physical_address_id");
        $invoice_information = $this->db->where("invoice_id" , $invoice_id)->get("invoice i")->row();

        if($invoice_information){
            $invoice_information->gst_price     = custom_money_format((($invoice_information->gst / 100) * $invoice_information->price) , true);
            $invoice_information->total_price   = custom_money_format($invoice_information->total_price , true);
            $invoice_information->price         = custom_money_format($invoice_information->price , true);
            $invoice_information->gst           = round($invoice_information->gst).'%';


            $invoice_information->address = $invoice_information->street1.",<br>";
            $invoice_information->address .= ($invoice_information->street2) ? $invoice_information->street2.",<br>" : "";
            $invoice_information->address .= ($invoice_information->suburb) ? $invoice_information->suburb.",<br>" : "";
            $invoice_information->address .= ($invoice_information->state) ? $invoice_information->state.",<br>" : "";
            $invoice_information->address .= ($invoice_information->postcode) ? $invoice_information->postcode.",<br>" : "";
            $invoice_information->address .= ($invoice_information->city) ? $invoice_information->city : "";

            $order_list = $this->db->where("order_id" , $invoice_information->order_id)->get("customer_order_product")->result();

            foreach($order_list as $key => $row){
                $order_list[$key]->product_price = custom_money_format($row->product_price , true );
                $order_list[$key]->total_price = custom_money_format($row->total_price , true);
            }

            $invoice_information->items_list = $order_list;

        }

        return $invoice_information;
    }

    public function pay_invoice(){
        $user_id = $this->session->userdata("user")->user_id;

        $this->db->trans_start();

        //UPDATE INVOICE TABLE TO PAID
        $this->db->where("invoice_id" , $this->input->post("invoice_id"))->update("invoice" , [
            "paid_date"       => strtotime($this->input->post("paid_date")),
            "payment_method"  => $this->input->post("payment_method"),
            "payment_type"    => "PAID" ,
            "updated_by"      => $user_id
        ]);

        //ADD INVOICE LOGS
        $this->db->insert("invoice_logs" , [
            "invoice_id"     => $this->input->post("invoice_id"),
            "payment_method" => $this->input->post("payment_method") ,
            "notes"          => $this->input->post("notes") ,
            "paid_date"      => strtotime($this->input->post("paid_date")) ,
            "cheque_no"      => $this->input->post("cheque_no"),
            "user_id"        => $user_id ,
            "created"        => time()
        ]);

        $this->multiple_upload($this->input->post("invoice_id"));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }

    private function multiple_upload($invoice_id){

        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/files/".$year."/".$month;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            create_index_html($folder);
        }

        $config['upload_path']          = $folder;
        $config['allowed_types']        = "gif|jpg|jpeg|png|pdf";
        $data = $_FILES['file'];

        $this->load->library('upload', $config);

        if(!empty($_FILES['file']['name'])){

            $filesCount = count($_FILES['file']['name']);

            for($i = 0; $i < $filesCount; $i++){
      
                $_FILES['file']['name']     = $data['name'][$i];
                $_FILES['file']['type']     = $data['type'][$i];
                $_FILES['file']['tmp_name'] = $data['tmp_name'][$i];
                $_FILES['file']['error']    = $data['error'][$i];
                $_FILES['file']['size']     = $data['size'][$i];

                $config['file_name'] = md5($invoice_id).'_'.time().'_'.$data['name'][$i];

                $this->upload->initialize($config);

                if ( $this->upload->do_upload('file')){

                    $image = $this->upload->data();

                    $this->db->insert("invoice_files" , [
                        "invoice_id"    => $invoice_id ,
                        "file_path"     => $year."/".$month."/".$image['file_name'],
                        "file_type"     => $image['file_type'] ,
                        "file_name"     => $image['file_name']
                    ]);

                }
            }//end for loop

        }//end if
    }

    public function view_invoice_log($invoice_id){

        $this->db->select("il.* , u.name ");
        $this->db->join("invoice i", "i.invoice_id = il.invoice_id");
        $this->db->join("users u", "u.user_id = il.user_id");
        $this->db->where("il.invoice_id", $invoice_id);

        $result = $this->db->get("invoice_logs il")->result();

        foreach($result as $key => $value) {
            $result[$key]->paid_date = convert_timezone($value->paid_date , true);
            $result[$key]->created = convert_timezone($value->created , true);
        }

        return $result;
    }

    public function get_dashboard_cards_info(){
        $result = array();

        //TODAY
        $start  = strtotime("today midnight");
        $end    = strtotime("today 23:59:59");
        
        $result["day"]["current"]["sales"] = $this->get_sales(["start" => $start , "end" => $end]);
        $result["day"]["current"]["date"]  = date("d M Y");

        //YESTERDAY
        $start  = strtotime("yesterday midnight");
        $end    = strtotime("today midnight - 1 seconds");
        
        $result["day"]["previous"]["sales"] = $this->get_sales(["start" => $start , "end" => $end]);
        $result["day"]["previous"]["date"]  = date("d M Y" , strtotime("yesterday"));


        //WEEKLY
        $start  = strtotime("monday this week 00:00:00");
        $end    = strtotime("monday next week -1 seconds");

        $result["week"]["current"]["sales"] = $this->get_sales(["start" => $start , "end" => $end]);
        $result["week"]["current"]["date"]  = date("d M Y" , $start).' - '.date("d M Y" , $end);

        //WEEK PREVIOUS
        $start  = strtotime("monday last week 00:00:00");
        $end    = strtotime("monday this week -1 seconds");

        $result["week"]["previous"]["sales"] = $this->get_sales(["start" => $start , "end" => $end]);
        $result["week"]["previous"]["date"]  = date("d M Y" , $start).' - '.date("d M Y" , $end);


        //MONTHLY 
        $start  = strtotime("first day of this month 00:00:00");
        $end    = strtotime("last day of this month 23:59:59");

        $result["month"]["current"]["sales"] = $this->get_sales(["start" => $start , "end" => $end]);
        $result["month"]["current"]["date"]  = date("d M Y" , $start).' - '.date("d M Y" , $end);

        //MONTHLY PREVIOUS
        $start  = strtotime("first day of last month 00:00:00");
        $end    = strtotime("last day of last month 23:59:59");

        $result["month"]["previous"]["sales"] = $this->get_sales(["start" => $start , "end" => $end]);
        $result["month"]["previous"]["date"]  = date("d M Y" , $start).' - '.date("d M Y" , $end);

        //print_r_die($result);
        return $result;

    }

    private function get_sales($q){
        $this->db->select_sum("total_price");
        $this->db->where("invoice_date >= " , $q['start']);
        $this->db->where("invoice_date <= " , $q['end']);
        return custom_money_format($this->db->get("invoice")->row()->total_price);
    }


    public function get_sales_data(){
        //TODAY
        $start  = strtotime("today midnight");
        $end    = strtotime("today 23:59:59");

        $this->db->where("invoice_date >= " , $start);
        $this->db->where("invoice_date <= " , $end);
        $result["today"] = $this->db->get("invoice")->result();

        //WEEKLY
        $weekstart  = strtotime("monday this week 00:00:00");
        $weekend    = strtotime("monday next week -1 seconds");

        $this->db->where("invoice_date >= " , $weekstart);
        $this->db->where("invoice_date <= " , $weekend);
        $result["week"] = $this->db->get("invoice")->result();
        

        //MONTHLY 
        $monthstart  = strtotime("first day of this month 00:00:00");
        $monthend    = strtotime("last day of this month 23:59:59");

        $this->db->where("invoice_date >= " , $monthstart);
        $this->db->where("invoice_date <= " , $monthend);
        $result["month"] = $this->db->get("invoice")->result();


        foreach ($result["month"] as $key => $value) {
           $result["month"][$key]->invoice_date = convert_timezone($value->invoice_date, true);
           $result["month"][$key]->total_price = custom_money_format($value->total_price);
           $result["month"][$key]->payment_method = convert_payment_status($value->payment_method);
           $result["month"][$key]->price = custom_money_format($value->price);
        }

        foreach ($result["week"] as $key => $value) {
           $result["week"][$key]->invoice_date = convert_timezone($value->invoice_date, true);
           $result["week"][$key]->total_price = custom_money_format($value->total_price);
           $result["week"][$key]->payment_method = convert_payment_status($value->payment_method);
           $result["week"][$key]->price = custom_money_format($value->price);
        }

        foreach ($result["today"] as $key => $value) {
           $result["today"][$key]->invoice_date = convert_timezone($value->invoice_date, true);
           $result["today"][$key]->total_price = custom_money_format($value->total_price);
           $result["today"][$key]->payment_method = convert_payment_status($value->payment_method);
           $result["today"][$key]->price = custom_money_format($value->price);
        }



        return $result;
    }
}