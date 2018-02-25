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
}