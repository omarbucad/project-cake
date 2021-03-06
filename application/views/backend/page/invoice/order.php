<script type="text/javascript">
     $(document).on('click' , '.customer-row' , function(){
        if($(this).hasClass("active")){
            $(this).removeClass("active");
            $(this).next().addClass("hidden");
            $(this).next().removeClass("open");
        }else{
            $(this).addClass("active");
            $(this).next().removeClass("hidden");
            $(this).next().addClass("open");
        }
    });

    $(document).on('click' , '.btn-click' , function(){
        var url = '<?php echo site_url("app/invoice/update_status_order"); ?>';
        var type = $(this).data("type");
        var id = $(this).data("id");
        var $me = $(this);
        var selected_driver = $('#selected_driver').val();
        var note = $('#note').val();
        var c = confirm("Are you sure?");

        if(c == true){
            $.ajax({
                url : url ,
                data : { btn_click : type , order_id : id , selected_driver : selected_driver , note : note},
                method : "POST",
                beforeSend : function(){
                    $me.closest("span").find("a").addClass("disabled");
                },
                success : function(response){

                    $me.closest("span").find("a").removeClass("disabled");

                    var json = jQuery.parseJSON(response);

                    if(json.status){

                        if(type == "cancel"){

                           $me.closest("tr").find(".status-here > span:last-child").remove();

                        }else if(type == "confirm"){

                            $me.closest("tr").find(".status-here > span:last-child").remove();
                            $me.closest("tr").find(".status-here").append(json.message);

                            var a = $("<a>" , {href : "javascript:void(0);" , class : "btn btn-success btn-xs btn-open-modal" , "data-id" : id , text : "Assign Driver"});

                            $me.parent().html(a);

                        }else if(type == "on_delivery"){

                            var click = $me.closest(".modal").data("click");
                            click.closest("tr").find(".status-here > span:last-child").remove();
                            click.closest("tr").find(".status-here").append(json.message);

                            var a = $("<a>" , {href : "javascript:void(0);" , class : "btn btn-success btn-xs btn-click" , "data-type" : "delivered" , "data-id" : id , text : "Delivered"});
                            click.parent().html(a);

                            $me.closest(".modal").modal("hide");                            

                        }else if(type == "delivered"){
                            $me.closest("tr").find(".status-here > span:last-child").remove();
                            $me.closest("tr").find(".status-here").append(json.message);
                            $me.parent().html("");
                        }

                        // EDIT :: sa json_encode nilagyan ko ng new element na "response" bale dun mo lalagay ung mga message mo sa notify
                        $.notify(json.response , { className:  "success" , position : "top center"});
                        

                        /* 
                            EDIT :: wag mo i reload dito sayang ung pagka ajax . para magamit ung notify mag return ka lang ng additional field dun sa echo json_encode sa
                            invoice controller update_status_order 

                            $.notify("<?php echo $this->session->flashdata("message"); ?>" , { className:  "<?php echo $this->session->flashdata("status"); ?>" , position : "top center"});
                            location.reload();
                        */ 

                    }else{

                        $.notify(json.message , { className:  "danger" , position : "top center"});
                    }
                }
            });
        }
        

    });

    $(document).on("click" , ".btn-open-modal" , function(){
 
        var modal = $('#myModal').modal("show");
        modal.data("click" , $(this));
        modal.find(".btn-click").data("id" , $(this).data("id"));
    });

    $(document).ready(function() {
        $('.animated-thumbnail').lightGallery({
            thumbnail:true
        });
    });

    $(document).on('click' , '.more-filter' , function(){
        var val = $(this).data('value');

        if(val == "hidden"){
            $(this).data("value" , "show");
            $('#view_advance_search').removeClass("hide");
            $(this).text("Less Filter");
            $('#_advance_search_value').val("true");
        }else{
            $(this).data("value" , "hidden");
            $('#view_advance_search').addClass("hide");
            $(this).text("More Filter");
             $('#_advance_search_value').val("false");
        }
    });
   

</script>
<style type="text/css">
    .daterangepicker.dropdown-menu {
        z-index: 100001 !important;
    }
    .lg-backdrop{
        z-index: 999999!important;
    }
    .lg-outer{
        z-index: 999999!important;
    }
</style>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container">
        	<h1>Order</h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo my_current_url("export=true"); ?>" class="btn btn-primary ">Export</a>
                        <a href="<?php echo site_url("app/invoice"); ?>" class="btn btn-success ">Go To Invoice</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("app/invoice/order"); ?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Order #</label>
                                    <input type="text" name="order_no" value="<?php echo $this->input->get("order_no"); ?>" class="form-control" id="s_name" placeholder="Search by Order #">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <div class="form-group">
                                    <label for="s_name">Search by Name , Company Name or Email</label>
                                    <input type="text" name="name" value="<?php echo $this->input->get("name"); ?>" class="form-control" id="s_name" placeholder="Search by Name , Company Name or Email">
                                </div>
                            </div>
                           
                           
                            <div class="col-xs-12 col-lg-3 text-right">
                                <button type="button" class="btn btn-link btn-vertical-center btn-same-size more-filter" data-value="hidden">More Filter</button>
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                            </div>
                        </div>
                        <div class="row hide" id="view_advance_search">

                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Date Period</label>
                                    <input type="text" name="date" class="form-control daterange" autocomplete="off" value="<?php echo $this->input->get("date"); ?>" id="s_name" placeholder="Search by date">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_brand">Payment Method</label>
                                     <select class="form-control" name="payment_method" id="s_brand">
                                        <option value="">All Payment Method</option>
                                        <option value="COD" <?php echo ($this->input->get("payment_method") == "COD") ? "selected" : "" ; ?>>Cash On Delivery</option>
                                        <option value="CHEQUE" <?php echo ($this->input->get("payment_method") == "CHEQUE") ? "selected" : "" ; ?>>Pay By Cheque</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Order Status</label>
                                    <select class="form-control" name="order_stat" id="s_roles">
                                        <option value="">- Select Order Status-</option>
                                        <option value="C"  <?php echo ($this->input->get("order_stat") == "0") ? "selected" : "" ; ?>>Cancelled</option>
                                        <option value="1"  <?php echo ($this->input->get("order_stat") == "1") ? "selected" : "" ; ?>>Processing</option>
                                        <option value="2"  <?php echo ($this->input->get("order_stat") == "2") ? "selected" : "" ; ?>>Admin Confirm</option>
                                        <option value="3"  <?php echo ($this->input->get("order_stat") == "3") ? "selected" : "" ; ?>>On Delivery</option>
                                        <option value="4"  <?php echo ($this->input->get("order_stat") == "4") ? "selected" : "" ; ?>>Delivered</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container ">

            <table class="customer-table" id="order">
                <thead>
                    <tr>
                        <th width="20%">Name</th>
                        <th width="10%">Items</th>
                        <th width="10%">Price</th>
                        <th width="10%">Price w/ SST</th>
                        <th width="10%">Status</th>
                        <th width="15%">Created</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr class="customer-row">
                                <td>
                                    <a href="javascript:void(0);"><?php echo $row->order_number; ?> ( <?php echo $row->display_name; ?> )</a><br>
                                    <small class="help-block">
                                        <?php if($row->company_name) : ?>
                                            <?php echo $row->company_name; ?><br>
                                        <?php endif; ?>
                                        <?php echo $row->email; ?>
                                    </small>
                                </td>
                                <td ><span ><?php echo $row->items; ?></span></td>
                                <td ><span ><?php echo $row->total_price; ?></span></td>
                                <td ><span ><?php echo $row->total_price_with_gst; ?> <br><small><?php echo $row->gst_price; ?> @6%</small></span></td>
                                <td class="status-here">
                                    <?php echo $row->pay_method; ?>
                                    <br><?php echo $row->status; ?>
                                </td>
                                <td ><span ><?php echo $row->created; ?></span></td>
                                <td class="text-right">
                                    <span>
                                        <?php if($row->status_raw_number == 1) : ?>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-xs btn-click" data-type="cancel" data-id="<?php echo $row->order_id; ?>">Cancel Order</a>
                                            <a href="javascript:void(0);" class="btn btn-success btn-xs btn-click" data-type="confirm" data-id="<?php echo $row->order_id; ?>">Confirm Order</a>
                                        <?php elseif($row->status_raw_number == 2) : ?>
                                            <a href="javascript:void(0);" class="btn btn-success btn-xs btn-open-modal" data-id="<?php echo $row->order_id; ?>" >Assign Driver</a>
                                        <?php elseif($row->status_raw_number == 3) : ?>
                                            <a href="javascript:void(0);" class="btn btn-success btn-xs btn-click" data-type="delivered" data-id="<?php echo $row->order_id; ?>">Delivered</a>
                                        <?php endif;?>
                                    </span>
                                </td>
                            </tr>
                            <tr class="customer-info hidden">
                                <td colspan="7">
                                    <table class="table table-bordered" style="width:96%;margin:10px auto;">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th colspan="2">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($row->product_list as $r) : ?>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-xs-6 col-lg-2 no-margin-bottom">
                                                                <img src="<?php echo site_url("thumbs/images/product/".$r->images[0]->image_path."/80/80/".$r->images[0]->image_name); ?>" class="img img-responsive thumbnail no-margin-bottom">
                                                            </div>
                                                            <div class="col-xs-6 col-lg-10 no-margin-bottom">
                                                                <a href="1"><?php echo $r->product_name; ?></a><br>
                                                                <small class="help-block"><?php echo $r->product_price; ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td >
                                                        <span><?php echo $r->quantity; ?></span>
                                                    </td>
                                                    <td  colspan="2">
                                                        <span><?php echo $r->total_price; ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                                <tr>
                                                    <td></td>
                                                    <td>Sub Total</td>
                                                    <td><?php echo $row->total_price; ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>SST 6%</td>
                                                    <td><?php echo $row->gst_price; ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Total Price</td>
                                                    <td><?php echo $row->total_price_with_gst; ?></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3">Shipping Address</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><?php echo $row->address;?></td>
                                                </tr>
                                            <?php if($row->name) : ?>
                                                
                                                <tr>
                                                    <th>Driver</th>
                                                    <th colspan="3">Signature</th>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $row->name; ?> </td>
                                                    <td colspan="3">
                                                        <?php if($row->image) : ?>
                                                            <small>Receiver name : <?php echo $row->customer_name; ?></small><br>
                                                            <img src="<?php echo site_url("public/upload/signature/".$row->image); ?>" style="height: 100px;">
                                                        <?php endif; ?>
                                                        <?php if($row->delivered_date != "NA") : ?>
                                                            <br>
                                                            <small>Placed Delivered : <?php echo $row->place_delivery_date; ?></small>
                                                            <br>
                                                            <small>Start Driving : <?php echo $row->start_driving; ?></small><br>
                                                            <small>Delivered : <?php echo $row->delivered_date; ?></small><br>
                                                            <small>notes : <i><?php echo $row->notes; ?></i></small>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if($row->item_image) : ?>
                                                <tr>
                                                    <th colspan="4">BEFORE DELIVERY</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="animated-thumbnail">
                                                        <?php foreach($row->item_image["BEFORE"] as $r) : ?>
                                                            <a href="<?php echo site_url("thumbs/images/items/".$r->image_path."/850/850/".$r->image_name); ?>">
                                                             <img src="<?php echo site_url("thumbs/images/items/".$r->image_path."/80/80/".$r->image_name); ?>">
                                                            </a>
                                                        <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th colspan="4">AFTER DELIVERY</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="animated-thumbnail">
                                                        <?php foreach($row->item_image["AFTER"] as $r) : ?>
                                                            <a href="<?php echo site_url("thumbs/images/items/".$r->image_path."/850/850/".$r->image_name); ?>">
                                                             <img src="<?php echo site_url("thumbs/images/items/".$r->image_path."/80/80/".$r->image_name); ?>">
                                                            </a>
                                                        <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>    
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="customer-row">
                            <td colspan="7" class="text-center"><span>No Result</span></td>
                        </tr>
                    <?php endif; ?>
                    
                </tbody>
            </table>
            <div class="customer-table-showing margin-bottom">
                <span class="pull-left">
                    <?php 
                        $x = 1;

                        if( $this->input->get("per_page") ){
                            $x = $this->input->get("per_page") + 1;
                        }

                    ?>
                    <small>Displaying <?php echo $x; ?> – <?php echo ($x-1) + count($result) ; ?> of <?php echo $config['total_rows']; ?></small>
                </span>
                <div class="pull-right">
                    <nav aria-label="Page navigation">
                      <?php echo $links; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Select Driver</h4>
      </div>
      <div class="modal-body">
            <div class="form-group">
                <label>Driver</label>
                <select class="form-control" id="selected_driver">
                    <?php foreach($driver_list as $row) : ?>
                        <option value="<?php echo $row->user_id; ?>"><?php echo $row->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Note to Driver</label>
                <textarea class="form-control" id="note"></textarea>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-click" data-type="on_delivery">Send to Driver</button>
      </div>
    </div>
  </div>
</div>