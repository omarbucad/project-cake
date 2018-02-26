<?php

class Product_model extends CI_Model {

	public function add_product(){

        $this->db->trans_start();

        $this->db->insert("products" , [
            "product_name"          => $this->input->post("product_name"), 
            "product_description"   => nl2br($this->input->post("description")), 
            "short_description"     => $this->input->post("short_description"), 
            "product_position"      => $this->input->post("product_position"), 
            "price"                 => $this->input->post("product_price"),
            "category_id"           => $this->hash->decrypt($this->input->post("category")),
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

    public function get_products($count = false){
        $this->db->join("category c" ,"c.category_id = p.category_id");

        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;
        
        /*
            TODO :: SEARCHING LOGIN HERE
        */

        if($name = $this->input->get("name")){
            $this->db->like("product_name" , $name);
        }

        if($category = $this->input->get("category_id")){
            $this->db->where("p.category_id" , $this->hash->decrypt($category));
        }

        if($status = $this->input->get("status")){
            switch ($status) {
                case 'ACTIVE':
                    $this->db->where("p.status" , 1);
                    break;
                 case 'INACTIVE':
                    $this->db->where("p.status" , 0);
                    break;
                default:
                    # code...
                    break;
            }
        }


        if($count){
            return $this->db->order_by("product_position" , "ASC")->get("products p")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->order_by("product_position" , "ASC")->get("products p")->result();
        }

        foreach($result as $key => $row){
            $result[$key]->images     = $this->db->where("product_id" , $row->product_id)->where("primary_image" , 1)->get("products_images")->row();
            $result[$key]->product_id = $this->hash->encrypt($row->product_id);
            $result[$key]->price      = custom_money_format($row->price);
            $result[$key]->status     = convert_status($row->status);
            $result[$key]->product_description = strlen($row->product_description) > 100 ? substr($row->product_description,0,100)."..." : $row->product_description;
        }

        return $result;
    }

    public function get_category(){
        $result = $this->db->order_by("category_name" , "ASC")->where("status" , 1)->get("category")->result();
      
        foreach($result as $key => $row){
            $result[$key]->category_id = $this->hash->encrypt($row->category_id);
        }

        return $result;
    }

    public function get_shop_list($category_id , $search = false , $count = false){

        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        $this->db->join("category c" , "c.category_id = p.category_id");

        if($search){
            if($category_id){
                $this->db->like("p.product_name" , $category_id);
            }
        }else{
            if($category_id != "all"){
                $category_id = $this->hash->decrypt($category_id);
                $this->db->where("c.category_id" , $category_id);
            }
        }

        if($count){
            return $this->db->order_by("p.product_position" , "ASC")->get("products p")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->order_by("p.product_position" , "ASC")->get("products p")->result();
        }

        $tmp = array();
        $tmp2 = array();
        foreach($result as $key => $row){
            $images = $this->db->where("product_id" , $row->product_id)->order_by("primary_image" , "DESC")->get("products_images")->result();
            $result[$key]->images = $images;
            $result[$key]->price = custom_money_format($row->price); 
            $result[$key]->product_description = strlen($row->product_description) > 100 ? substr($row->product_description,0,100)."..." : $row->product_description;

            if(count($tmp) == 2){
                $tmp[] = $result[$key];
                $tmp2[] = $tmp;
                $tmp = array();
            }else{
                $tmp[] = $result[$key];
            }

        }

        if(count($tmp) != 0){
            $tmp2[] = $tmp;
        }

        return $tmp2;
    }

    public function get_product_by_id($product_id){
        $result = $this->db->where("product_id" , $product_id)->where("status" , 1)->get("products")->row();

        $result->images = $this->db->where("product_id" , $result->product_id)->order_by("primary_image" , "DESC")->get("products_images")->result();
        $result->price_raw = $result->price;
        $result->price = custom_money_format($result->price);

        return $result;
    }

    public function checkout(){
        
        $this->db->trans_start();
        $cart_data = $this->input->post("quantity");
        $product_id = array();

        foreach($cart_data as $id => $quantity){
            $product_id[] = $id;
        }

        $product_data = $this->db->where_in("product_id" , $product_id)->get("products")->result();

        /*
            0 - cancelled Order
            1 - Placed an order
            2 - Admin Confirm
            3 - On Delivery
            4 - Delivered
        */

        $order = array(
            "customer_id" => $this->session->userdata("customer")->customer_id ,
            "status"      => 1 ,
            "total_price" => 0,
            "created"     => time()
        );

        $this->db->insert("customer_order" , $order);
        
        $order_id = $this->db->insert_id();

        $total_price = 0;
        $product_array = array();
        $items = 0;

        foreach($product_data as $row){
            $total_price += $row->price * $cart_data[$row->product_id];
            $items += $cart_data[$row->product_id];

            $product_array[] = array(
                "order_id"      => $order_id ,
                "product_id"    => $row->product_id,
                "product_name"  => $row->product_name ,
                "product_price" => $row->price ,
                "quantity"      => $cart_data[$row->product_id],
                "total_price"   => $row->price * $cart_data[$row->product_id]
            );
        }

        $this->db->insert_batch("customer_order_product" , $product_array);

        $this->db->where("order_id" , $order_id)->update("customer_order" , [
            "order_number"      => date("mdY").'-'.sprintf('%05d', $order_id) ,
            "total_price"       => $total_price ,
            "items"             => $items
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return date("mdY").'-'.sprintf('%05d', $order_id);
        }
    }

    public function get_orders($count = false){
        $customer_id = $this->session->userdata("customer")->customer_id;

        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        /*
            TODO :: SEARCHING LOGIN HERE
        */

        if($count){
           return $this->db->where("customer_id" , $customer_id)->order_by("order_id" , "DESC")->get("customer_order")->num_rows();
        }else{
            $result = $this->db->where("customer_id" , $customer_id)->limit($limit , $skip)->order_by("order_id" , "DESC")->get("customer_order")->result();
        }

        foreach($result as $key => $row){
            $result[$key]->total_price = custom_money_format($row->total_price);
            $result[$key]->created = convert_timezone($row->created );
            $result[$key]->status = convert_order_status($row->status , true);
        }

        return $result;
    }

    public function get_order_by_id($order_number , $raw = false){
        $result = $this->db->where("order_number" , $order_number)->get("customer_order")->row();

        if($result){
            $this->db->select("op.product_name , op.product_price , op.quantity , op.total_price , op.product_id" );

            $result->product_list = $this->db->where("order_id" , $result->order_id)->get("customer_order_product op")->result();

            foreach($result->product_list as $key => $row){
                $result->product_list[$key]->product_price = custom_money_format($row->product_price);
                $result->product_list[$key]->total_price = custom_money_format($row->total_price);
                $result->product_list[$key]->images = $this->db->where("product_id" , $row->product_id)->order_by("primary_image" , "DESC")->get("products_images")->result();
            }

            $result->created = convert_timezone($result->created , true);
            $result->total_price = custom_money_format($result->total_price );
            $result->status_raw = $result->status;
            $result->status = convert_order_status($result->status , $raw);
        }

        return $result;
    }

    public function get_wishlist () {
        $result = $this->db->select("u.name , p.product_name , p.short_description ,p.price  ,pi.* ")->from('customer_wish_product w ')
        ->join('users u' , 'u.user_id = w.customer_id')
        ->join('products p' , 'p.product_id = w.product_id')
        ->join('products_images pi' , 'pi.product_id = p.product_id')
        ->where("pi.primary_image" , 1)
        ->where('w.customer_id' , $this->session->userdata("customer")->customer_id)
        ->group_by('product_id')
        ->get("customer_wish_product")->result();
        
        return $result;
    }

    public function remove_wish ($id) {

        $this->db->where('product_id' , $id);
        $this->db->delete('customer_wish_product'); 

    }

    public function view_productsbyid ($id) {
        $result = $this->db->select("*")
        ->from('products p')
        ->join('products_images pi' , 'p.product_id = pi.product_id' )
        ->where('pi.primary_image' , 1)
        ->where('p.product_id' , $id)
        ->get()->row(); 

        if($result){
            $result->images = $this->db->where('product_id', $id)->order_by('primary_image','DESC')->get('products_images')->result();
        }
      
        return $result;
    }

    public function update_product ($data) {
        $this->db->trans_start();
        $arr = array(
            "product_name"          => $data['product_name'] ,
            "price"                 => $data['product_price'] ,
            "product_position"      => $data['product_position'] ,
            "category_id"           => $this->hash->decrypt($data['category']) ,
            "short_description"     => $data['short_description'] ,
            "product_description"   => $data['description']
            );

        $this->db->where("product_id" , $data['product_id']);
        $this->db->update("products" , $arr);


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            $this->session->set_flashdata('message_name', 'This is my message');
            redirect("app/products");

        }

    }

    public function delete_productimage($image_id){
        $this->db->trans_start();
        
        $this->db->select('image_path, image_name, primary_image, product_id');
        $imageinfo = $this->db->where('image_id', $image_id)->get("products_images")->row();
        
        unlink("./public/upload/product/".$imageinfo->image_path."/".$imageinfo->image_name);

        $this->db->where('image_id',$image_id);
        $this->db->delete("products_images");

        if($imageinfo->primary_image == 1){
            
            $result = $this->db->where("product_id" , $imageinfo->product_id)->order_by("primary_image" , "DESC")->get("products_images")->row();
            $this->db->where('image_id', $result->image_id);
            $this->db->update("products_images" , [
                "primary_image" => 1
            ]);

        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }

    public function set_product_primary_image($image_id){
        $this->db->trans_start();

        $this->db->select('product_id');
        $product = $this->db->where('image_id', $image_id)->get("products_images")->row();

        $this->db->where('product_id', $product->product_id);
        $this->db->where('primary_image', 1);
        $this->db->update("products_images" , [
            "primary_image" => 0
        ]);

        $this->db->where("image_id", $image_id);
        $this->db->update("products_images" , [
            "primary_image" => 1
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }        
    }

    public function update_product_position($product_id, $position_value){
        $product = $this->hash->decrypt($product_id);
        $this->db->trans_start();

        $this->db->where('product_id', $product);
        $this->db->update("products" , [
            "product_position" => $position_value
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }      
    }
}