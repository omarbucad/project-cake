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

        $price_book = $this->db->select("price_book_id")->where("deleted IS NULL")->get("price_book")->result();

        $price_book_batch = array();

        foreach($price_book as $b){
            $price_book_batch[] = array(
                "price_book_id" => $b->price_book_id ,
                "product_id"    => $last_id ,
                "price"         => $this->input->post("product_price")
            );
        }

        $this->db->insert_batch("price_book_products" , $price_book_batch);

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

        $this->db->select("p.status, p.product_name, p.product_id, p.short_description, 
            p.product_description, p.product_position, p.price, p.category_id, c.category_name"
        );
        $this->db->where('p.deleted IS NULL');

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

        if($product_id = $this->input->get("product_id")){
            $this->db->where("p.product_id" , $this->hash->decrypt($product_id));
        }

        if($count){
            return $this->db->order_by("product_position" , "ASC")->get("products p")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->order_by("product_position" , "ASC")->get("products p")->result();
        }
       //print_r_die($result);

        foreach($result as $key => $row){
            $result[$key]->images     = $this->db->where("product_id" , $row->product_id)->where("primary_image" , 1)->get("products_images")->row();
            $result[$key]->product_id = $this->hash->encrypt($row->product_id);
            $result[$key]->price_raw  = $row->price;
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
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 12;

        $this->db->join("category c" , "c.category_id = p.category_id");
        $this->db->join("price_book_products pb" , "pb.product_id = p.product_id");
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

        $this->db->where("p.deleted IS NULL");

        if($count){
            return $this->db->order_by("p.product_position" , "ASC")->get("products p")->num_rows();
        }else{
            $result = $this->db->limit($limit , $skip)->where("pb.price_book_id" ,  $this->data['session_customer']->price_book_id)->where("p.status" , 1)->order_by("p.product_position" , "ASC")->get("products p")->result();
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

        $this->db->select("pb.price , p.product_id , p.product_name");
        $this->db->join("price_book_products pb" , "pb.product_id = p.product_id");
        $product_data = $this->db->where_in("p.product_id" , $product_id)->where("pb.price_book_id" ,  $this->data['session_customer']->price_book_id)->get("products p")->result();

        /*
            0 - cancelled Order
            1 - Placed an order
            2 - Admin Confirm
            3 - On Delivery
            4 - Delivered
        */

        if($this->input->post('is_same') == 0){
            $this->db->insert("address", [
                "street1"   => $this->input->post('street1') ,
                "street2"   => $this->input->post('street2') ,
                "suburb"    => $this->input->post('suburb')  ,
                "city"      => $this->input->post('city')    ,
                "postcode"  => $this->input->post('postcode'),
                "state"     => $this->input->post('state')   
            ]);
            $address_id = $this->db->insert_id();
        }
        else{
            $this->db->select('physical_address_id');
            $address = $this->db->where("customer_id", $this->session->userdata("customer")->customer_id)->get("customer")->row();
            $address_id = $address->physical_address_id;
        }


        $order = array(
            "customer_id"    => $this->session->userdata("customer")->customer_id ,
            "status"         => 1 ,
            "total_price"    => 0,
            "pay_method"     => $this->input->post("payment_method") ,
            "created"        => time()
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

        $gst_price = $total_price * 0.06;
        $order_number = date("dmY").'-'.sprintf('%05d', $order_id);

        $this->db->where("order_id" , $order_id)->update("customer_order" , [
            "order_number"          => $order_number ,
            "total_price"           => $total_price ,
            "total_price_with_gst"  => $total_price + $gst_price,
            "gst_price"             => $gst_price ,
            "items"                 => $items,
            "address_id"            => $address_id
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $order_number;
        }
    }

    public function get_orders($count = false){
        $customer_id = $this->session->userdata("customer")->customer_id;

        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        /*
            TODO :: SEARCHING LOGIN HERE
        */

        if($order_no = $this->input->get("order_no")){
            $this->db->where("order_number" , $order_no);
        }

        if($date = $this->input->get("date")){
            $date  = explode("-", $date);
            $start = strtotime(trim($date[0].' 00:00'));
            $end   = strtotime(trim($date[1].' 23:59'));


            $this->db->where("created >= " , $start);
            $this->db->where("created <= " , $end);
        }

        if($count){
           return $this->db->where("customer_id" , $customer_id)->order_by("order_id" , "DESC")->get("customer_order")->num_rows();
        }else{
            $result = $this->db->where("customer_id" , $customer_id)->limit($limit , $skip)->order_by("order_id" , "DESC")->get("customer_order")->result();
        }

        foreach($result as $key => $row){
            $result[$key]->total_price = custom_money_format($row->total_price);
            $result[$key]->gst_price = custom_money_format($row->gst_price);
            $result[$key]->total_price_with_gst = custom_money_format($row->total_price_with_gst);
            $result[$key]->created = convert_timezone($row->created );
            $result[$key]->status = convert_order_status($row->status );
            $result[$key]->pay_method = convert_payment_status($row->pay_method );
        }

        return $result;
    }

    public function get_order_by_id($order_number , $raw = false){
        $this->db->select("co.* , a.* , c.display_name , c.company_name");
        $this->db->join("customer c" , "c.customer_id = co.customer_id");
        $this->db->join("address a" , "a.address_id = co.address_id");
        $result = $this->db->where("order_number" , $order_number)->get("customer_order co")->row();

        if($result){
            $this->db->select("op.product_name , op.product_price , op.quantity , op.total_price , op.product_id" );

            $result->product_list = $this->db->where("order_id" , $result->order_id)->get("customer_order_product op")->result();

            foreach($result->product_list as $key => $row){
                $result->product_list[$key]->product_price = custom_money_format($row->product_price);
                $result->product_list[$key]->total_price = custom_money_format($row->total_price);
                $result->product_list[$key]->images = $this->db->where("product_id" , $row->product_id)->order_by("primary_image" , "DESC")->get("products_images")->result();
            }

            $result->order_images = $this->db->where("order_no" , $result->order_number)->get("customer_order_images")->num_rows();

            $result->created = convert_timezone($result->created , true);
            $result->start_driving = convert_timezone($result->start_driving , true);
            $result->delivered_date = convert_timezone($result->delivered_date , true);
            $result->total_price = custom_money_format($result->total_price );
            $result->gst_price = custom_money_format($result->gst_price );
            $result->total_price_with_gst = custom_money_format($result->total_price_with_gst );
            $result->status_raw = $result->status;
            $result->status = convert_order_status($result->status , $raw);
            $result->pay_method_raw = $result->pay_method;
            $result->pay_method = convert_payment_status($result->pay_method , $raw);
            //$result->payment_method = convert_payment_status($result->payment_method , $raw);

            $result->address = $result->street1;
            $result->address .= ($result->street2) ? ",<br>".$result->street2 : "";
            $result->address .= ($result->suburb) ? ",<br>".$result->suburb : "";
            $result->address .= ($result->city) ? ",<span>".$result->city : "";
            $result->address .= ($result->postcode) ? ",<br>".$result->postcode : "";
            $result->address .= ($result->state) ? " <span>".$result->state : "";
        }

        return $result;
    }




    public function view_productsbyid ($id) {
        $this->db->join('products_images pi' , 'p.product_id = pi.product_id');
        $this->db->where([
            "pi.primary_image" => 1 ,
            "p.product_id"     => $id
        ]);
        
        $result = $this->db->get('products p')->row(); 

        if($result){
            $result->images = $this->db->where('product_id', $id)->order_by('primary_image','DESC')->get('products_images')->result();
            $result->price_raw = round($result->price , 2);
            $result->price = custom_money_format($result->price);
        }
        
        return $result;
    }

    public function update_product () {

        $data = $this->input->post();

        $this->db->trans_start();

        $this->db->where("product_id" , $data['product_id'])->update("products" , array(
            "product_name"          => $data['product_name'] ,
            "price"                 => $data['product_price'] ,
            "product_position"      => $data['product_position'] ,
            "category_id"           => $this->hash->decrypt($data['category']) ,
            "short_description"     => $data['short_description'] ,
            "product_description"   => $data['description'],
            "status"                => $data['productstatus']
        ));

        if($_FILES['other_file']){
             $this->multiple_upload($data['product_id'] , false);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }

    }

    public function delete_productimage($image_id){
        $this->db->trans_start();
        
        $this->db->select('image_path, image_name, primary_image, product_id');
        $imageinfo = $this->db->where('image_id', $image_id)->get("products_images")->row();
        if(is_file("./public/upload/product/".$imageinfo->image_path."/".$imageinfo->image_name)){

            unlink("./public/upload/product/".$imageinfo->image_path."/".$imageinfo->image_name);
        }
        

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
    public function delete_product($product_id){
        $this->db->trans_start();

        $this->db->where('product_id', $product_id);
        $this->db->update("products" , [
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    public function get_product_cards_info($product_id){
        $result = array();

        //TODAY
        $start  = strtotime("today midnight");
        $end    = strtotime("today 23:59:59");
        
        $result["day"]["current"]["sales"] = $this->get_product_sales(["start" => $start , "end" => $end, "product_id" => $product_id ]);
        $result["day"]["current"]["date"]  = date("d M Y");

        //YESTERDAY
        $start  = strtotime("yesterday midnight");
        $end    = strtotime("today midnight - 1 seconds");
        
        $result["day"]["previous"]["sales"] = $this->get_product_sales(["start" => $start , "end" => $end, "product_id" => $product_id]);
        $result["day"]["previous"]["date"]  = date("d M Y" , strtotime("yesterday"));


        //WEEKLY
        $start  = strtotime("monday this week 00:00:00");
        $end    = strtotime("monday next week -1 seconds");

        $result["week"]["current"]["sales"] = $this->get_product_sales(["start" => $start , "end" => $end, "product_id" => $product_id]);
        $result["week"]["current"]["date"]  = date("d M Y" , $start).' - '.date("d M Y" , $end);

        //WEEK PREVIOUS
        $start  = strtotime("monday last week 00:00:00");
        $end    = strtotime("monday this week -1 seconds");

        $result["week"]["previous"]["sales"] = $this->get_product_sales(["start" => $start , "end" => $end, "product_id" => $product_id]);
        $result["week"]["previous"]["date"]  = date("d M Y" , $start).' - '.date("d M Y" , $end);


        //MONTHLY 
        $start  = strtotime("first day of this month 00:00:00");
        $end    = strtotime("last day of this month 23:59:59");

        $result["month"]["current"]["sales"] = $this->get_product_sales(["start" => $start , "end" => $end, "product_id" => $product_id]);
        $result["month"]["current"]["date"]  = date("d M Y" , $start).' - '.date("d M Y" , $end);

        //MONTHLY PREVIOUS
        $start  = strtotime("first day of last month 00:00:00");
        $end    = strtotime("last day of last month 23:59:59");

        $result["month"]["previous"]["sales"] = $this->get_product_sales(["start" => $start , "end" => $end, "product_id" => $product_id]);
        $result["month"]["previous"]["date"]  = date("d M Y" , $start).' - '.date("d M Y" , $end);

        return $result;

    }

    private function get_product_sales($q){

        $this->db->select("i.*, cp.* ");
        $this->db->join("customer_order_product cp","cp.order_id = i.order_id");
        $this->db->where("i.invoice_date >= " , $q['start']);
        $this->db->where("i.invoice_date <= " , $q['end']);
        $this->db->where("cp.product_id",$q['product_id']);

        $this->db->select_sum("cp.quantity");
        $q = $this->db->get("invoice i")->row()->quantity;
        return ($q) ? $q : 0;
       //return $this->db->get("invoice i")->result();
    }

    public function get_price_group_select(){
        $result = $this->db->where("deleted IS NULL")->order_by("group_name" , "ASC")->get("price_book")->result();

        foreach($result as $r => $value){
            $result[$r]->price_book_id = $this->hash->encrypt($value->price_book_id);
            $result[$r]->status = convert_status($value->status);
            $result[$r]->created = convert_timezone($value->created , true);
        }

        return $result;
    }

    public function get_price_group($count = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        /*
            TODO :: SEARCHING LOGIN HERE
        */

        if($name = $this->input->get("name")){
            $this->db->like("group_name" , $name);
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
            return $result = $this->db->where("deleted IS NULL")->get("price_book")->num_rows();
        }else if($id = $this->input->get("group_id")){
            $result = $this->db->where("deleted IS NULL")->where("price_book_id" , $this->hash->decrypt($id))->get("price_book")->result();
        }else{
            $result = $this->db->where("deleted IS NULL")->limit($limit , $skip)->order_by("group_name" , "ASC")->get("price_book")->result();
        }

        foreach($result as $r => $value){
            $result[$r]->price_book_id = $this->hash->encrypt($value->price_book_id);
            $result[$r]->status = convert_status($value->status);
            $result[$r]->created = convert_timezone($value->created , true);
        }

        return $result;
    }

    public function add_new_group(){

        $this->db->trans_start();
        
        $this->db->insert("price_book" , [
            "group_name"     => $this->input->post("category_name"),
            "status"         => $this->input->post("category_status"),
            "deletable"      => "YES" ,
            "created"        => time()
        ]);

        $last_id = $this->db->insert_id();


        $products = $this->get_products();
        $products_batch = array();

        foreach($products as $row){
            $products_batch[] = array(
                "price_book_id"     => $last_id,
                "product_id"        => $this->hash->decrypt($row->product_id),
                "price"             => $row->price_raw
            );
        }

        $this->db->insert_batch("price_book_products" , $products_batch);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $last_id;
        }
    }

    public function get_group_price_by_id($id){
        $result = $this->db->where("price_book_id" , $id)->get("price_book")->row();

        $this->db->join("products p" , "p.product_id = pb.product_id");
        $result->products = $this->db->where("price_book_id" , $id)->where("p.status" , 1)->where("p.deleted IS NULL")->get("price_book_products pb")->result();

        foreach($result->products as $key => $row){
            $result->products[$key]->price = round($row->price , 2);
            $result->products[$key]->product_id = $this->get_product_by_id($row->product_id);
            $result->products[$key]->product_id->product_id = $this->hash->encrypt($result->products[$key]->product_id->product_id);
        }

        return $result;
    }

    public function update_new_group($id){

        $this->db->trans_start();

        $this->db->where("price_book_id" , $id)->update("price_book",[
            "group_name"    => $this->input->post("category_name"),
            "status"        => $this->input->post("category_status")
        ]);

        $product = $this->input->post("product_id");

        foreach($product as $product_id => $value){
            $this->db->where([
                "price_book_id" => $id ,
                "product_id"    => $this->hash->decrypt($product_id)
            ])->update("price_book_products" , [
                "price"         => $value
            ]);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }

}