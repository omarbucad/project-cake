<?php
ini_set("memory_limit","512M");

defined('BASEPATH') OR exit('No direct script access allowed');

class Thumbs extends CI_Controller {
 
    public function __construct(){
        parent::__construct();
        $this->load->library('image_lib');
    }

    public function images($type , $year , $month , $width, $height, $img){
        $path = $year.'/'.$month;

        // Checking if the file exists, otherwise we use a default image
        $img = is_file('public/upload/'.$type.'/'.$path.'/'.$img) ? $img : false;
      
        // If the thumbnail already exists, we just read it
        // No need to use the GD library again
        if( !is_file('public/upload/'.$type.'/'.$path.'/thumbnail/'.$width.'x'.$height.'_'.$img) ){

            $config['image_library'] = 'gd2';
            $config['source_image'] = 'public/upload/'.$type.'/'.$path.'/'.$img;
            $config['new_image'] = 'public/upload/'.$type.'/'.$path.'/thumbnail/'.$width.'x'.$height.'_'.$img;
            $config['width'] = $width;
            $config['height'] = $height;
            $config['quality'] = "60%";
            $config['maintain_ratio'] = true;

            

            $this->image_lib->clear(); 
            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            $this->image_lib->clear();
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'public/upload/'.$type.'/'.$path.'/thumbnail/'.$width.'x'.$height.'_'.$img;

            $imgdata = exif_read_data('public/upload/'.$type.'/'.$path.'/'.$img , 'IFD0');

            switch($imgdata['Orientation']) {
                case 3:
                    $config['rotation_angle'] = '180';
                break;
                case 6:
                    $config['rotation_angle'] = '270';
                break;
                case 8:
                    $config['rotation_angle'] = '90';
                break;
            }

            $this->image_lib->initialize($config); 
            $this->image_lib->rotate();

        }
        header('Content-Type: image/jpg');
        if($img){
            readfile('public/upload/'.$type.'/'.$path.'/thumbnail/'.$width.'x'.$height.'_'.$img);
        }else{
            readfile('public/img/person-placeholder.jpg');
        }
        
    }
 
}