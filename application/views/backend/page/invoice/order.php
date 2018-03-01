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
                success : function(response){
                    console.log(response);
                    var json = jQuery.parseJSON(response);

                    if(json.status){
                        if(type == "cancel"){
                           $me.closest("tr").find(".status-here").html(json.message);
                           $me.parent().html(" ");

                        }else if(type == "confirm"){

                            $me.closest("tr").find(".status-here").html(json.message);

                            var a = $("<a>" , {href : "javascript:void(0);" , class : "btn btn-success btn-xs btn-open-modal" , "data-id" : id , text : "On Delivery"});

                            $me.parent().html(a);

                        }else if(type == "on_delivery"){

                            var click = $me.closest(".modal").data("click");
                            click.closest("tr").find(".status-here").html(json.message);

                            var a = $("<a>" , {href : "javascript:void(0);" , class : "btn btn-success btn-xs btn-click" , "data-type" : "delivered" , "data-id" : id , text : "Delivered"});
                            click.parent().html(a);

                            $me.closest(".modal").modal("hide");

                        }else if(type == "delivered"){
                            $me.closest("tr").find(".status-here").html(json.message);
                            $me.parent().html("");
                        }
                    }else{
                        alert(json.message);
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
</script>
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
                                    <label for="s_name">Search by name or email or order #</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get("name"); ?>" id="s_name" placeholder="Search by name , email , order #">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Status</label>
                                    <select class="form-control" name="status" id="s_roles">
                                        <option value="">- Select Status-</option>
                                        <option value="C" <?php echo ($this->input->get("status") == "C") ? "selected" : "" ; ?> >Cancelled Order</option>
                                        <option value="1"  <?php echo ($this->input->get("status") == "1") ? "selected" : "" ; ?>>Placed Order</option>
                                        <option value="2"  <?php echo ($this->input->get("status") == "2") ? "selected" : "" ; ?>>Admin Confirm</option>
                                        <option value="3"  <?php echo ($this->input->get("status") == "3") ? "selected" : "" ; ?>>On Delivery</option>
                                        <option value="4"  <?php echo ($this->input->get("status") == "4") ? "selected" : "" ; ?>>Delivered</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Date period</label>
                                    <input type="text" name="date" class="form-control daterange" value="<?php echo $this->input->get("date"); ?>" id="s_name" placeholder="Search by date">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3  text-right">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                            </div>
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
        <div class="container ">

            <table class="customer-table">
                <thead>
                    <tr>
                        <th width="20%">Name</th>
                        <th width="10%">Items</th>
                        <th width="10%">Price</th>
                        <th width="10%">Price w/ GST</th>
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
                                    <small class="help-block"><?php echo $row->email; ?></small>
                                </td>
                                <td ><span ><?php echo $row->items; ?></span></td>
                                <td ><span ><?php echo $row->total_price; ?></span></td>
                                <td ><span ><?php echo $row->total_price_with_gst; ?> <br><small><?php echo $row->gst_price; ?> @6%</small></span></td>
                                <td class="status-here"><?php echo $row->status; ?></td>
                                <td ><span ><?php echo $row->created; ?></span></td>
                                <td class="text-right">
                                    <span>
                                        <?php if($row->status_raw == 1) : ?>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-xs btn-click" data-type="cancel" data-id="<?php echo $row->order_id; ?>">Cancel Order</a>
                                            <a href="javascript:void(0);" class="btn btn-success btn-xs btn-click" data-type="confirm" data-id="<?php echo $row->order_id; ?>">Confirm Order</a>
                                        <?php elseif($row->status_raw == 2) : ?>
                                            <a href="javascript:void(0);" class="btn btn-success btn-xs btn-open-modal" data-id="<?php echo $row->order_id; ?>" >On Delivery</a>
                                        <?php elseif($row->status_raw == 3) : ?>
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
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($row->product_list as $r) : ?>
                                                <tr>
                                                    <td width="60%">
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
                                                    <td width="20%">
                                                        <span><?php echo $r->quantity; ?></span>
                                                    </td>
                                                    <td width="20%">
                                                        <span><?php echo $r->total_price; ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
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