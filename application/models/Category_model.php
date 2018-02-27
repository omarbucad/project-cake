<?php

class Category_model extends CI_Model {

     public function get_categories($count = false){

        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        /*
            TODO :: SEARCHING LOGIN HERE
        */

        if($name = $this->input->get("name")){
            $this->db->like("category_name" , $name);
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
            return $result = $this->db->get("category")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->order_by("category_name" , "ASC")->get("category")->result();
        }

        foreach($result as $r => $value){
            $result[$r]->status = convert_status($value->status);
        }

        return $result;
    }

    public function get_category($category_id){
        $this->db->where("category_id", $category_id);
        $result = $this->db->get("category")->row();

        return $result;
    }

    public function add_new_category(){

        $this->db->trans_start();

        $this->db->insert("category" , [
            "category_name"  => $this->input->post("category_name"),
            "status"         => $this->input->post("category_status")
        ]);

        $last_id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $last_id;
        }
    }

    public function update_category_details($category_id){

        $this->db->trans_start();

        $post = $this->input->post();
        $this->db->where("category_id", $category_id);
        $this->db->update("category" , [
            "category_name"        => $post["category_name"] ,
            "status"     => $post["category_status"]
        ]);


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }
	
}