<?php

class Invoice_model extends CI_Model {

	public function get_order(){
        $this->db->select("co.* , c.display_name , c.email");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $result = $this->db->order_by("order_id" , "DESC")->get("customer_order co")->result();

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
            "invoice_date"  => time()
        ]);

        $last_id = $this->db->insert_id();

        $this->db->where("invoice_id" , $last_id)->update("invoice" , [
            "invoice_no"    => date("mdY").'-'.sprintf('%05d', $last_id)
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

    public function get_invoice(){
        $this->db->select("i.* , co.* , c.display_name , c.email , u.name");
        $this->db->join("customer_order co" , "co.order_id = i.order_id");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("users u" , "u.user_id = co.driver_id");
        $result = $this->db->order_by("invoice_date" , "DESC")->get("invoice i")->result();

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
}