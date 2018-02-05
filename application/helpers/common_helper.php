<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('print_r_die'))
{
    function print_r_die($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }   
}

if( ! function_exists('create_index_html')){
    
    function create_index_html($folder) {
        $content = "<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
        $folder = $folder.'/index.html';
        $fp = fopen($folder , 'w');
        fwrite($fp , $content);
        fclose($fp);
    }
}

if( ! function_exists('convert_gender')){
    
    function convert_gender($gender) {
        if($gender){
            if($gender == "M"){
                return "Male";
            }else{
                return "Female";
            }
        }

        return false;
    }
}


if( ! function_exists('date_of_birth')){
    
    function date_of_birth($d , $m , $y) {
        if($d AND $m AND $y){
            return $d.' '.DateTime::createFromFormat('!m', $m)->format("F").' '.$y;
        }else{
            return false;
        }
    }
}

if( ! function_exists('register_details')){
    
    function register_details($data) {
        $tmp = "<ul class='register_details_ul'>";
        $time = uniqid();

        if($data->select_user_for_next_sale){
            $tmp .= "<li>Select user for next sale</li>";
        }else{
            $tmp .= "<li>Don't select user for next sale</li>";
        }

        if($data->email_receipt){
            $tmp .= '<div class="collapse" id="_'.$time.'"><li>Email Receipt</li>';
        }else{
            $tmp .= '<div class="collapse" id="_'.$time.'"><li>Dont Email Receipt</li>';
        }

        if($data->print_receipt){
            $tmp .= "<li>Print Receipt</li>";
        }else{
            $tmp .= "<li>Don't Print Receipt</li>";
        }

        if($data->ask_for_a_note == 0){
            $tmp .= "<li>Never ask for note</li>";
        }else if($data->ask_for_a_note == 1){
            $tmp .= "<li>Ask for note on save/Layby/Account/Return</li>";
        }else if($data->ask_for_a_note == 2){
            $tmp .= "<li>Ask for note on all sales</li>";
        }

        if($data->print_note_on_receipt){
            $tmp .= "<li>Print note on Receipt</li>";
        }else{
            $tmp .= "<li>Don't Print note on Receipt</li>";
        }

        if($data->show_discount_on_receipt){
            $tmp .= "<li>Show discount on Receipt</li></div>";
        }else{
            $tmp .= "<li>Don't show discount on Receipt</li></div>";
        }
         $tmp .= '<li><a class="link-style read-more" role="button" data-toggle="collapse" href="#_'.$time.'" aria-expanded="false" aria-controls="collapseExample">More Details</a></li>';
        $tmp .= "</ul>";
       

        return $tmp;
    }
}


if ( ! function_exists('convert_timezone'))
{
    function convert_timezone($time , $with_hours = false , $with_timezone = true , $hour_only = false , $custom_format_date_with_hour = "M d Y h:i:s A" , $custom_format_date = "M d Y" , $custom_format_hour = "h:i:s A")
    {

        if(!$time OR $time == 0){
            return "NA";
        }

        if($with_timezone){
            //$timezone = get_timezone();
            $timezone = "Europe/London";

            if($with_hours){
                $date_format = $custom_format_date_with_hour;
            }else if($hour_only){
                $date_format = $custom_format_hour;
            }else{
                $date_format = $custom_format_date;
            }
            
            $triggerOn = date($date_format , $time);

            $tz = new DateTimeZone($timezone);
            $datetime = new DateTime($triggerOn);
            $datetime->setTimezone($tz);

            return $datetime->format( $date_format );
        }else{
            if($with_hours){
                $date_format = $custom_format_date_with_hour;
            }else if($hour_only){
                $date_format = $custom_format_hour;
            }else{
                $date_format = $custom_format_date;
            }

            return date($date_format , $time);
        }
    }   
}

if(!function_exists("fromNow")){

    function fromNow($time) {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
        }
    }
}


if ( ! function_exists('custom_money_format'))
{
    function custom_money_format($money)
    {
        $obj =& get_instance();

        if(!$money){
            $money = "0.00";
        }
   
        $formatted = $obj->session->userdata("user")->currency_symbol;

        $formatted .= number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $money)), 2);
        return $money < 0 ? "({$formatted})" : "{$formatted}";
    }   
}

if ( ! function_exists('compute_time_hours'))
{
    function compute_time_hours($start , $end)
    {
        $start = strtotime($start);
        $end   = strtotime($end);

        if($start > $end){
            $end = $end + 86400;
        }

        $total = abs($end - $start);
        $total = ($total / 60) / 60;

        return $total;
    }   
}



if ( ! function_exists('build_today_td'))
{
    function build_today_td($data)
    {
        $arr = array();
        $td = "";
        
        if(!$data->shift_information){

            for($x = 0 ; $x < 24 ; $x++){
                $td .= "<td></td>";
            }

            return $td;
        }


        $start = strtotime($data->shift_information->start_time);
        $end   = strtotime($data->shift_information->end_time);

        if($start > $end){
            $end = $end + 86400;
        }

        $start_time = substr(date("h:ia" , $start) , 0, -1);
        $end_time = substr(date("h:ia" , $end) , 0, -1);

        $total = abs($end - $start);
        $total = ($total / 60) / 60;

        $arr['total_number'] = ceil($total);
        $arr['total_hrs'] = round($total , 1);
        $arr['start'] = date("H" , $start);
        $arr['end'] = $arr['start'] + $arr['total_number'];

        $arr['a'] = (check_number($arr["start"]) + $arr['total_number']);
        $total_number = (check_number($arr["start"]) + $arr['total_number']);

        if($total_number > 24 ){

            $arr['shift'][0] = array(
                "colspan" => abs($total_number - 24),
                "start"   => check_number("06")
            );

            $arr['shift'][1] = array(
                "colspan" => abs(check_number($arr["start"]) - 24)+1,
                "start"   => check_number($arr["start"])
            );

            
        }else{
            $arr['shift'][0] = array(
                "colspan" => $arr['total_number'] ,
                "start"   => check_number($arr['start'])
            );
        }


        if(count($arr['shift']) == 2){
            $td .= "<td colspan='".$arr['shift'][0]['colspan']."'>";

               if($arr['shift'][0]['colspan'] > $arr['shift'][1]['colspan']){
                    $td .= '<a href="javascript:void(0);" style="background-color: '.$data->shift_information->block_color.'" data-shiftcolor="'.$data->shift_information->block_color.'" data-positioncolor="'.$data->shift_information->block_color.'">'.$start_time.' - '.$end_time.'<small> @ '.$data->shift_information->outlet_name.'</small> <span> '.$data->shift_information->group_name.'</span></a>';
               }else{
                    $td .= '<a href="javascript:void(0);" style="background-color: '.$data->shift_information->block_color.';" data-shiftcolor="'.$data->shift_information->block_color.'" data-positioncolor="'.$data->shift_information->block_color.'" >&nbsp;</a>';
               }

            $td .= "</td>";

            $each = abs($arr['shift'][0]['colspan'] - ($arr['shift'][1]['start'] - 1));

            for($x = 0 ; $x < $each ; $x++){
                $td .= "<td></td>";
            }

            $td .= "<td colspan='".$arr['shift'][1]['colspan']."'>";
                if($arr['shift'][0]['colspan'] > $arr['shift'][1]['colspan']){
                    $td .= '<a href="javascript:void(0);" style="background-color: '.$data->shift_information->block_color.';" data-shiftcolor="'.$data->shift_information->block_color.'" data-positioncolor="'.$data->shift_information->block_color.'" >&nbsp;</a>';
                }else{
                    $td .= '<a href="javascript:void(0);" style="background-color: '.$data->shift_information->block_color.'" data-shiftcolor="'.$data->shift_information->block_color.'" data-positioncolor="'.$data->shift_information->block_color.'">'.$start_time.' - '.$end_time.'<small> @ '.$data->shift_information->outlet_name.'</small> <span> '.$data->shift_information->group_name.'</span></a>';
                }
                
            $td .= "</td>";
        }else{

            if($arr['shift'][0]['start'] != 1){
                
                $each = ($arr['shift'][0]['start'] - 1);

                for($x = 0 ; $x < $each ; $x++){
                    $td .= "<td></td>";
                }
            }

            $td .= "<td colspan='".$arr['shift'][0]['colspan']."'>";
                $td .= '<a href="javascript:void(0);"  style="background-color: '.$data->shift_information->block_color.'" data-shiftcolor="'.$data->shift_information->block_color.'" data-positioncolor="'.$data->shift_information->block_color.'">'.$start_time.' - '.$end_time.'<small> @ '.$data->shift_information->outlet_name.'</small> <span> '.$data->shift_information->group_name.'</span></a>';
            $td .= "</td>";

            $each =  $arr['shift'][0]['colspan'] + ($arr['shift'][0]['start'] - 1 ) ;
            
            if($each  != 24){

                $each = abs($each - 24);

                for($x = 0 ; $x < $each ; $x++){
                    $td .= "<td></td>";
                }
            }

        }

        return $td;
    }   
}


if ( ! function_exists('check_number'))
{
    function check_number($index)
    {
        $arr = array();

        $arr["06"] = 1; 
        $arr["07"] = 2; 
        $arr["08"] = 3; 
        $arr["09"] = 4; 
        $arr["10"] = 5; 
        $arr["11"] = 6; 
        $arr["12"] = 7; 
        $arr["13"] = 8; 
        $arr["14"] = 9; 
        $arr["15"] = 10; 
        $arr["16"] = 11; 
        $arr["17"] = 12; 
        $arr["18"] = 13; 
        $arr["19"] = 14; 
        $arr["20"] = 15; 
        $arr["21"] = 16; 
        $arr["22"] = 17; 
        $arr["23"] = 18; 
        $arr["0"]  = 19; 
        $arr["01"]  = 20; 
        $arr["02"]  = 21; 
        $arr["03"]  = 22; 
        $arr["04"]  = 23; 
        $arr["05"]  = 24; 
        
        return $arr[$index];
    }   
}

if ( ! function_exists('loop_date'))
{
    function loop_date($start , $end , $date_only = false)
    {
        $begin = new DateTime( $start );
        $end = new DateTime( $end .'+1 day');
        $tmp = array();

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ( $period as $dt ){
            if($date_only){
                $tmp[] = strtoupper($dt->format("F j Y"));
            }else{
                 $tmp[$dt->format( "D" )] = [
                    "value" => strtoupper($dt->format("D j")) ,
                    "date"  => strtoupper($dt->format("F j Y"))
                ];
            }
        }

        return $tmp;
    }   
}

