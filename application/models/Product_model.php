<?php

class Product_model extends CI_Model {

	public function add_product(){

        $this->db->trans_start();

        $this->db->insert("products" , [
            "product_name"          => $this->input->post("product_name"), 
            "product_description"   => nl2br($this->input->post("description")), 
            "product_position"      => $this->input->post("product_position"), 
            "price"                 => $this->input->post("product_price"),
            "created"               => time()
        ]);

        $last_id = $this->db->insert_id();

        $this->multiple_upload($last_id , true);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $last_id;
        }
    }


    private function multiple_upload($product_id , $default = false){

        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/product/".$year."/".$month;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            mkdir($folder.'/thumbnail', 0777, true);
            create_index_html($folder);
        }

        $config['upload_path']          = $folder;
        $config['allowed_types']        = "gif|jpg|jpeg|png";

        $data = $_FILES['other_file'];

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if(!empty($_FILES['other_file']['name'])){
            $filesCount = count($_FILES['other_file']['name']);

            for($i = 0; $i < $filesCount; $i++){
                $_FILES['other_file']['name'] = $data['name'][$i];
                $_FILES['other_file']['type'] = $data['type'][$i];
                $_FILES['other_file']['tmp_name'] = $data['tmp_name'][$i];
                $_FILES['other_file']['error'] = $data['error'][$i];
                $_FILES['other_file']['size'] = $data['size'][$i];

                $config['file_name'] = md5($product_id).'_'.time().'_'.$data['name'][$i];

                $this->upload->initialize($config);

                if ( $this->upload->do_upload('other_file')){

                    $image = $this->upload->data();

                    $this->db->insert('products_images' , [
                        "product_id"      => $product_id ,
                        "image_name"      => $image['file_name'] ,
                        "image_path"      => $year.'/'.$month,
                        "primary_image"   => ($i == 0 AND $default == true) ? 1 : 0
                    ]);
                }
            }//end for loop

        }//end if

    }

    public function get_products(){
        $result = $this->db->order_by("product_position" , "ASC")->get("products p")->result();

        foreach($result as $key => $row){
            $result[$key]->images     = $this->db->where("product_id" , $row->product_id)->where("primary_image" , 1)->get("products_images")->row();
            $result[$key]->product_id = $this->hash->encrypt($row->product_id);
            $result[$key]->price      = custom_money_format($row->price);
            $result[$key]->status     = convert_status($row->status);
        }

        return $result;
    }
}