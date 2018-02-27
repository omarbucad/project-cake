<?php

class Invoice_model extends CI_Model {

	public function get_order($count = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        $this->db->select("co.* , c.display_name , c.email");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");

        if($name = $this->input->get("name")){
            $this->db->like("c.display_name" , $name);
            $this->db->or_like("c.email" , $name);
            $this->db->or_where("co.order_number" , $name);
        }


        if($status = $this->input->get("status")){
            
            if($status == "C"){
                $status = 0;
            }
            $this->db->where("co.status" , $status);

        }else{
            $this->db->where_in("co.status" , [ 1 , 2 , 3 ]);
        }

        if($date = $this->input->get("date")){
            $date  = explode("-", $date);
            $start = strtotime(trim($date[0].' 00:00'));
            $end   = strtotime(trim($date[1].' 23:59'));


            $this->db->where("co.created >= " , $start);
            $this->db->where("co.created <= " , $end);
        }

        if($count){
            return $this->db->get("customer_order co")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->order_by("order_id" , "DESC")->get("customer_order co")->result();
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
            $result[$k]->status_raw = $result[$k]->status;
            $result[$k]->status = convert_order_status($r->status);
        }

        return $result;

    }

    public function create_invoice($order_id){
        $user_id = $this->session->userdata("user")->user_id;

        $order_info = $this->db->where("order_id" , $order_id)->get("customer_order")->row();

        $this->db->insert("invoice" , [
            "order_id"      => $order_id ,
            "price"         => $order_info->total_price ,
            "created_by"    => $user_id ,
            "payment_type"  => "UNPAID" ,
            "invoice_date"  => time() ,
            "gst"           => 6 ,
            "total_price"   => ($order_info->total_price * 0.06) + $order_info->total_price,
            "invoice_no"    => $order_info->order_number
        ]);

        $last_id = $this->db->insert_id();

        return $last_id;
    }

    public function get_driver_list(){
        $result = $this->db->where([
            "account_type"  => "DRIVER" ,
            "status"        => 1
        ])->get("users")->result();

        return $result;
    }

    public function get_invoice($count = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;
        
        $this->db->select("i.* , co.* , c.display_name , c.email , u.name");
        $this->db->join("customer_order co" , "co.order_id = i.order_id");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("users u" , "u.user_id = co.driver_id" , "LEFT");

        /*
            TODO :: SEARCHING LOGIC HERE
        */

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

        if($count){
            return $this->db->get("invoice i")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->order_by("invoice_date" , "DESC")->get("invoice i")->result();
        }
        
        foreach($result as $key => $row){
            $result[$key]->invoice_date = convert_timezone($row->invoice_date);
            $result[$key]->paid_date = convert_timezone($row->paid_date);
            $result[$key]->price_raw = $row->price;
            $result[$key]->price = custom_money_format($row->price);
            $result[$key]->files = $this->db->where("invoice_id" , $row->invoice_id)->get("invoice_files")->result();
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
        }

        return $result;
    }

    public function get_dashboard_invoice(){
        $this->db->select("i.* , co.* , c.display_name , c.email , u.name");
        $this->db->join("customer_order co" , "co.order_id = i.order_id");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("users u" , "u.user_id = co.driver_id");
        $result = $this->db->where("i.payment_type" , "UNPAID")->order_by("invoice_date" , "DESC")->get("invoice i")->result();

        foreach($result as $key => $row){
            $result[$key]->invoice_date = convert_timezone($row->invoice_date);
            $result[$key]->paid_date = convert_timezone($row->paid_date);
            $result[$key]->price_raw = $row->price;
            $result[$key]->price = custom_money_format($row->price);
            $result[$key]->files = $this->db->where("invoice_id" , $row->invoice_id)->get("invoice_files")->result();
        }

        return $result;
    }

    public function get_invoice_by_id($invoice_id){
        $this->db->select("i.invoice_no , i.invoice_date , i.price , i.gst , i.total_price , i.payment_method");
        $this->db->select("c.display_name , c.email , a.street1 , a.street2 , a.suburb , a.state , a.postcode , a.city");
        $this->db->select("co.items , co.order_id");
        $this->db->join("customer_order co" , "co.order_id = i.order_id");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("address a" , "a.address_id = c.physical_address_id");
        $invoice_information = $this->db->where("invoice_id" , $invoice_id)->get("invoice i")->row();

        if($invoice_information){
            $invoice_information->price = custom_money_format($invoice_information->price , true);
            $invoice_information->gst = round($invoice_information->gst).'%';
            $invoice_information->gst_price = round($invoice_information->gst).'%';
            $invoice_information->total_price = custom_money_format($invoice_information->total_price , true);

            $order_list = $this->db->where("order_id" , $invoice_information->order_id)->get("customer_order_product")->result();

            foreach($order_list as $key => $row){
                $order_list[$key]->product_price = custom_money_format($row->product_price , true );
                $order_list[$key]->total_price = custom_money_format($row->total_price , true);
            }

            $invoice_information->items_list = $order_list;

        }

        return $invoice_information;
    }
}