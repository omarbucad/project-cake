<?php

class Users_model extends CI_Model {

	public function add_users(){

		$this->db->trans_start();

		$this->db->insert("users" , [
			"email"		   => $this->input->post("email") ,
			"username" 	   => $this->input->post("username") ,
			"password"     => $this->input->post("password"),
			"name"		   => $this->input->post("display_name") ,
			"account_type" => $this->input->post("role") ,
			"created"	   => time()
		]);

		$last_id = $this->db->insert_id();

		$this->do_upload($last_id);

		$this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $last_id;
        }
	}

	public function view_users($count = false){
		
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        /*
            TODO :: SEARCHING LOGIN HERE
        */
        
        if($name = $this->input->get("name")){
            $this->db->like("username" , $name);
            $this->db->or_like("name" , $name);
        }

        if($roles = $this->input->get("roles")){
            $this->db->where("account_type" , $roles);
        }

        if($status = $this->input->get("status")){
            switch ($status) {
                case 'ACTIVE':
                    $this->db->where("status" , 1);
                    break;
                 case 'INACTIVE':
                    $this->db->where("status" , 0);
                    break;
                default:
                    # code...
                    break;
            }
        }
        $this->db->where("deleted IS NULL");

        if($count){
            return $result = $this->db->get("users")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->order_by("name" , "ASC")->get("users")->result();
        }
		
		foreach($result as $key => $row){
			$result[$key]->user_id = $this->hash->encrypt($row->user_id);
            $result[$key]->status = convert_status($row->status);
		}

		return $result;
	}

	private function do_upload($user_id){
        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/user/".$year."/".$month;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            mkdir($folder.'/thumbnail', 0777, true);

            create_index_html($folder);
        }
    
        $image_name = md5($user_id).'_'.time().'_'.$_FILES['file']['name'];
        $image_name = str_replace("^", "_", $image_name);
       
        $config['upload_path']          = $folder;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $image_name;

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if ($this->upload->do_upload('file')){
            $this->db->where("user_id" , $user_id)->update("users" , [
                "image_path" => $year."/".$month ,
                "image_name" => $image_name
            ]);
        }
    }

    public function view_customer($count = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        $this->db->join("address a ", "a.address_id = c.physical_address_id");
        $this->db->where("c.deleted IS NULL");

        /*
            TODO :: SEARCHING LOGIN HERE
        */

        if($name = $this->input->get("name")){
            $this->db->like("email" , $name);
            $this->db->or_like("display_name" , $name);
        }

        if($status = $this->input->get("status")){
            switch ($status) {
                case 'ACTIVE':
                    $this->db->where("status" , 1);
                    break;
                 case 'INACTIVE':
                    $this->db->where("status" , 2);
                    break;
                default:
                    # code...
                    break;
            }
        }

        if($customer_id = $this->input->get("user_id")){
            $this->db->where("customer_id" , $this->hash->decrypt($customer_id));
        }

        if($count){
            return $this->db->get("customer c")->num_rows();
        }else{
            $result = $this->db->order_by("display_name" , "ASC")->limit($limit , $skip)->get("customer c")->result();
        }

        foreach($result as $key => $row){
            $result[$key]->status = convert_customer_status($row->status);

            $result[$key]->address = $row->street1.",";
            $result[$key]->address .= ($row->street2) ? $row->street2."," : "";
            $result[$key]->address .= ($row->suburb) ? $row->suburb."," : "";
            $result[$key]->address .= ($row->state) ? $row->state."," : "";
            $result[$key]->address .= ($row->postcode) ? $row->postcode."," : "";
            $result[$key]->address .= ($row->city) ? $row->city : "";
        }

        return $result;
    }

    public function add_customer(){
        $this->db->trans_start();

        $this->db->insert("address" , $this->input->post("physical"));
        $address_id = $this->db->insert_id();

        $activation_code = $this->hash->encrypt(time().'_'.$this->input->post("email"));

        $this->db->insert("customer" , [
            "password"              => $this->input->post("password") ,
            "email"                 => $this->input->post("email") ,
            "activation_code"       => $activation_code,
            "physical_address_id"   => $address_id ,
            "display_name"          => $this->input->post("display_name"),
            "company_name"          => $this->input->post("company_name"),
            "status"                => 1 ,
            "created"               => time()
        ]);

        $last_id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $last_id;
        }
    }

    public function get_customer_information($customer_id){
        $result = $this->db->where("customer_id" , $customer_id)->get("customer")->row();
        return $result;
    }

    public function get_customer_address($address_id){
        $result = $this->db->where("address_id" , $address_id)->get("address")->row();
        return $result;
    }

    public function update_customer($customer_id){
        $this->db->trans_start();

        $post = $this->input->post();

        $this->db->where('address_id', $post['physical_address_id']);
        $this->db->update("address" , $this->input->post("physical"));

        if($post['account_type'] == 'PERSONAL'){
            $this->db->where("customer_id", $customer_id);
            $this->db->update("customer", [
                "display_name"  => $post["fullname"] ,
                "phone_number"  => $post["phone_number"] ,
                "company_name"  => NULL,
                "account_type"  => $post['account_type'] ,
                "status"        => $post['status']
            ]);
        }
        else{
            
            $this->db->where("customer_id", $customer_id);
            $this->db->update("customer", [
                "display_name"  => $post["manager_name"] ,
                "company_name"  => $post["company_name"] ,
                "phone_number"  => $post["phone_number"] ,
                "account_type"  => $post['account_type'] ,
                "status"        => $post['status']
            ]);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $customer_id;
        }
    }

    public function get_user_information($user_id){

        $result = $this->db->where("user_id" , $user_id)->get("users")->row();
        $result->user_id = $this->hash->encrypt($result->user_id);
        
        return $result;
    }

    public function update($user_id){

        $this->db->trans_start();

        $post = $this->input->post();


        $this->db->where('user_id' , $user_id);
        $this->db->update("users" , [
            "name"          => $post["display_name"],
            "account_type"  => $post['role']
        ]);

        if($_FILES['file']){

            $this->db->select('image_path, image_name');
            $imageinfo = $this->db->where('user_id', $user_id)->get("users")->row();
                       

            unlink("./public/upload/user/".$imageinfo->image_path."/".$imageinfo->image_name);

            $this->do_upload($user_id);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $user_id;
        }
    }

    public function change_user_password($user_id){
        
        $this->db->trans_start();

        $this->db->where("user_id", $user_id);
        $this->db->update("users", [
            "password"  => md5($this->input->post("password"))
        ]);
        $this->db->trans_complete();


        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $user_id;
        }
    }

    public function get_customer_orders_info($customer_id){
        $this->db->trans_start();

        $this->db->where("customer_id", $customer_id);
        $customer_order_info["total_orders"] = $totalorder = $this->db->get("customer_order")->num_rows();

        $this->db->where("customer_id", $customer_id);
        $this->db->where("status !=",0);
        $orderdetail = $this->db->get("customer_order")->result();

        $totalprice = 0;
        $totaldelivered = 0;
        $totalondelivery = 0;

        foreach ($orderdetail as $key => $value) {
           
            $totalprice = $totalprice + $orderdetail[$key]->total_price;

            if($orderdetail[$key]->status = 4){     $totaldelivered++;      }
            elseif($orderdetail[$key]->status = 3){    $totalondelivery++;     }
            else{  }
        }

        $customer_order_info["total_price"] = custom_money_format($totalprice);
        $customer_order_info["on_delivery"] = $totalondelivery;
        $customer_order_info["delivered"] = $totaldelivered;

        $this->db->trans_complete();


        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $customer_order_info;
        }

    }

    public function get_user_total_confirmed_orders($user_id){
        $this->db->trans_start();

        $this->db->join("users u", "u.user_id = i.created_by", "INNER");
        $this->db->where("i.created_by",$user_id);

        $confirmed = $this->db->get("invoice i")->num_rows();

        $this->db->trans_complete();


        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $confirmed;
        }
    }

    public function delete_customer($customer_id){
        $this->db->trans_start();

        $this->db->where('customer_id', $customer_id);
        $this->db->update("customer" , [
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    public function delete_user($user_id){
        $this->db->trans_start();

        $this->db->where('user_id', $user_id);
        $this->db->update("users" , [
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    public function customer_edit_profile($customer_id){
        $this->db->trans_start();

        $this->db->where("customer_id", $customer_id);

        if($this->session->userdata("customer")->account_type == 'PERSONAL'){
            $this->db->update("customer", [
                "display_name"  => $this->input->post("fullname") ,
                "phone_number"  => $this->input->post("phone_number")
            ]);
        }
        else{
            $this->db->update("customer", [
                "display_name"  => $this->input->post("manager_name") ,
                "company_name"  => $this->input->post("company_name") ,
                "phone_number"  => $this->input->post("phone_number")
            ]);
        }
        

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    public function change_customer_password($customer_id){
        
        $this->db->trans_start();

        $this->db->where("customer_id", $customer_id);
        $this->db->update("customer", [
            "password"  => md5($this->input->post("password"))
        ]);
        $this->db->trans_complete();


        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $customer_id;
        }
    }
}