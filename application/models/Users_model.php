<?php

class Users_model extends CI_Model {

	public function add_users(){

		$this->db->trans_start();

		$this->db->insert("users" , [
			"email"		   => $this->input->post("email") ,
			"username" 	   => $this->input->post("username") ,
			"password"     => md5($this->input->post("password")),
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

        if($count){
            return $result = $this->db->get("users")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->order_by("name" , "ASC")->get("users")->result();
        }
		
		foreach($result as $key => $row){
			$result[$key]->user_id = $this->hash->encrypt($row->user_id);
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
}