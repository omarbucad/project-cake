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

    $(document).on('click' , '.view_invoice_pdf' , function(){

        var modal = $('#invoice_pdf_modal').modal("show");

        var id = $(this).data("id");

        var a = $("<a>" , {href : $(this).data("pdf") , text:$(this).data("pdf") });
        var object = '<object data="'+$(this).data("pdf") +'" , type="application/pdf" style="width:100%;height:800px;">'+a+'</object>';

        $('#_pdfViewer').html(object);  
    });

    $(document).on('click' , '.send_invoice_email' , function(){

        var c = confirm("Are you sure you want to send an invoice email?");

        if(c == true){
            $.ajax({
                url : $(this).data("href") ,
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    if(json.status){
                        $.notify(json.message , { className:  "success" , position : "top center"});
                    }else{
                        $.notify(json.message , { className:  "error" , position : "top center"});
                    }
                }
            });
        }
    });

    $(document).on("click" , ".pay_invoice" , function(){

        var href = $(this).data("href");
        var invoice_id = $(this).data("id");
        var invoice_no = $(this).data("invoiceno");

        $.ajax({
                url : href ,
                method : 'get' ,
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    var modal = $('#invoice_pay').modal("show");
                    var info = json.data;
                    console.log(json.data);
                    if(info.payment_method == "COD"){
                        modal.find('#_paymethod option[value=COD]').attr('selected', true);
                    }else{
                        modal.find('#_paymethod option[value=CHEQUE]').attr('selected', true);
                    }

                    modal.find(".modal-title").html("Invoice #"+ invoice_no);
                    modal.find('#_invoice_id').val(invoice_id);
                    modal.find('#_invoice_no').val(invoice_no);
                    
                    
                }
            });
        
       
    });

    $(document).on("click" , ".view_logs" , function(){
        var invoice_id = $(this).data("id");
        var invoice_no = $(this).data("invoiceno");
        

        var url = "<?php echo site_url("app/invoice/view_invoice_log/");?>"+invoice_id;

        $.ajax({
            url : url ,
            method : "POST" ,
            success : function(response){
                var json = jQuery.parseJSON(response);
                if(json.status){
                    
                    $("#invoice-logs-table").find("tbody").html(" ");

                    $.each(json.data , function(k,v){
                        var tr = $("<tr>");
                        var td = $("<td>").html(v.payment_method);
                        tr.append(td);
                        var td = $("<td>").html(v.notes);
                        tr.append(td);
                        var td = $("<td>").html(v.paid_date);
                        tr.append(td);
                        var td = $("<td>").html(v.cheque_no);
                        tr.append(td);
                        var td = $("<td>").html(v.name);
                        tr.append(td);
                        var td = $("<td>").html(v.created);
                        tr.append(td);
                        $("#invoice-logs-table").find("tbody").append(tr);

                    });
               

                    
                }
            }
        });

        var modal = $('#invoice_logs').modal("show");
        modal.find(".modal-title").html("Invoice #"+invoice_no);
    });

    $(document).on("change" , "#_paymethod" , function(){
        var v = $(this).val();

        if(v == "COD"){
            $('._cheque').addClass("hide").find("input").attr("required" , false);
        }else{
            $('._cheque').removeClass("hide").find("input").attr("required" , true);
        }
    });
    $(document).on("click" , "#submitForm" , function(){
        var form = $(this).closest(".modal").find("form");
        var c = confirm("Are you sure?");
        
        if(c == true){
            $('#_paymethod').attr("disabled",false);
            form.submit();
        }

    });

    $(document).ready(function() {
        $('.animated-thumbnail').lightGallery({
            thumbnail:true
        });
        
    });

    $(document).on("click" , ".tr_invoice_price" , function(){
        if($(this).attr("checked","true")){

            var a = 0.00;
            a = parseFloat(a);
            
            $(".tr_invoice_price:checked").each(function() {
                var rmprice =  $(this).data("id");
                var priceonly = rmprice.replace(/[^0-9\.]/g, '');
                price = parseFloat(priceonly);
                a = a + price;
                a = parseFloat(Number(a).toFixed(2));
            });
            var totalselected = $(".tr_invoice_price:checked").length;
            var checkboxes = $(".tr_invoice_price").length;

            $("#totalinvoice").text("RM " + a);
            $("#selected_of").text(totalselected + " of " + checkboxes + " Invoices Selected")
        }
        

    });

     $(document).on("click" , "#select_all" , function(){

        if($(this).is(":checked")){
            var a = 0.00;
            a = parseFloat(a);

            $(".tr_invoice_price").each(function() {
                $(this).prop("checked", true);

                var rmprice =  $(this).data("id");
                var priceonly = rmprice.replace(/[^0-9\.]/g, '');
                price = parseFloat(priceonly);
                a = a + price;
                a = parseFloat(Number(a).toFixed(2));
            });

            var totalselected = $(".tr_invoice_price:checked").length;
            var checkboxes = $(".tr_invoice_price").length;

            $("#totalinvoice").text("RM " + a);
            $("#selected_of").text(totalselected + " of " + checkboxes + " Invoices Selected");
        }else{
            var checkboxes = $(".tr_invoice_price").length;
            $(".tr_invoice_price:checked").each(function() {
                $(this).prop("checked" , false);
                 $("#totalinvoice").text("RM " + 0.00);
                $("#selected_of").text("0 of " + checkboxes + " Invoices Selected");
            });
        }

    });

    $(document).on("change", "#selectlimit", function(){
        var url = "<?php echo site_url('app/invoice/?limit='); ?>" + $("#selectlimit").find(":selected").val();

        window.location.assign(url);
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
        <?php $this->load->view("backend/common/sales_box"); ?>

        <div class="container">
        	<h1>Invoice</h1>
        </div>

        
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo my_current_url("export=true"); ?>" class="btn btn-primary ">Export</a>
                        <a href="<?php echo site_url("app/invoice/order"); ?>" class="btn btn-success ">Go To Order</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("app/invoice"); ?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Invoice #</label>
                                    <input type="text" name="invoice_no" value="<?php echo $this->input->get("invoice_no"); ?>" class="form-control" id="s_name" placeholder="Search by Invoice #">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <div class="form-group">
                                    <label for="s_name">Search by Name , Company Name or Email</label>
                                    <input type="text" name="name" value="<?php echo $this->input->get("name"); ?>" class="form-control" id="s_name" placeholder="Search by Name , Company Name or Email">
                                </div>
                            </div>
                           
                           
                            <div class="col-xs-12 col-lg-3 text-right">
                                <button type="button" class="btn btn-link btn-vertical-center btn-same-size more-filter" data-value="hidden">More filter</button>
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                            </div>
                        </div>
                        <div class="row hide" id="view_advance_search">

                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Date period</label>
                                    <input type="text" name="date" class="form-control daterange" autocomplete="off" value="<?php echo $this->input->get("date"); ?>" id="s_name" placeholder="Search by date">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Status</label>
                                    <select class="form-control" name="status" id="s_roles">
                                        <option value="">- Select Status -</option>
                                        <option value="UNPAID" <?php echo ($this->input->get("status") == "UNPAID") ? "selected" : "" ;?> >Unpaid</option>
                                        <option value="PAID" <?php echo ($this->input->get("status") == "PAID") ? "selected" : "" ;?>>Paid</option>
                                    </select>
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
                                    <select class="form-control" name="order_status" id="s_roles">
                                        <option value="">- Select Order Status-</option>
                                        <option value="2"  <?php echo ($this->input->get("order_status") == "2") ? "selected" : "" ; ?>>Admin Confirm</option>
                                        <option value="3"  <?php echo ($this->input->get("order_status") == "3") ? "selected" : "" ; ?>>On Delivery</option>
                                        <option value="4"  <?php echo ($this->input->get("order_status") == "4") ? "selected" : "" ; ?>>Delivered</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row col-lg-4">
                    <table class="table table-bordered">
                    <tr>
                        <td style="width: 15%;"><h3>Total: </h3></td>
                        <td><h3><span id="totalinvoice"></span></h3> <span id="selected_of" class="help-block"></span></td>
                    </tr>
                    </table>
                </div>
                <div class="form-group col-lg-2 pull-right" style="position: absolute;right: 0;bottom: 0;">
                    <label for="limit" style="display: inline-block;margin-right: 10px;">Show </label>
                    <div style="display: inline-block;">
                        <select name="limit" id="selectlimit" class="form-control" value="<?php echo $this->input->get('limit');?>" >
                            <option value="all" <?php echo ($this->input->get('limit') == "all") ? "selected": ""; ?>>All</option>
                            <option value="10" <?php echo ($this->input->get('limit') == "10") ? "selected": ""; ?>>10</option>
                            <option value="25" <?php echo ($this->input->get('limit') == "25") ? "selected": ""; ?>>25</option>
                            <option value="50" <?php echo ($this->input->get('limit') == "50") ? "selected": ""; ?>>50</option>
                        </select>                        
                    </div>
                </div>
            </div>
        </div>

        <div class="container ">
            <table class="customer-table">
                <thead>
                    <tr>
                        <th><input id="select_all" type="checkbox" class="tr_invoice_all"><label for="select_all"></label></th>
                        <th width="25%">Invoice No</th>
                        <th width="10%">Price</th>
                        <th width="10%">Payment Method</th>
                        <th width="15%">Status</th>
                        <th width="20%">Invoice Date</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                <td>
                                    <input data-id="<?php echo $row->total_price; ?>" type="checkbox" class="tr_invoice_price">
                                </td>
                                <td>
                                    <span><?php echo $row->invoice_no; ?></span>
                                </td>
                                <td ><span ><?php echo $row->total_price; ?></span></td>
                                <td >
                                    <span ><?php echo $row->payment_method; ?></span>
                                    <br>
                                    <?php echo $row->status; ?>
                                </td>
                                <td >
                                    <span data-toggle="tooltip" data-placement="top" title="<?php echo $row->updated_by; ?>"><?php echo $row->payment_type; ?> <?php echo ($row->payment_type_raw != "UNPAID") ? "<br><small>".$row->paid_date."</small>" : "" ; ?></span>
                                </td>
                                <td>
                                    <span>
                                       <?php echo $row->invoice_date; ?>
                                    </span>
                                </td>
                                <td class="text-left">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-right">
                                        <?php if($row->payment_type_raw == "UNPAID") : ?>
                                            <li><a href="javascript:void(0);" class="pay_invoice" data-id="<?php echo $row->invoice_id; ?>" data-href="<?php echo site_url('app/invoice/get_invoice_info/').$row->invoice_id; ?>" data-invoiceno="<?php echo $row->invoice_no; ?>">Pay Invoice</a></li>
                                        <?php else : ?>
                                             <li><a href="javascript:void(0);" class="view_logs" data-id="<?php echo $row->invoice_id; ?>" data-invoiceno="<?php echo $row->invoice_no; ?>">Invoice Logs</a></li>
                                        <?php endif; ?>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="javascript:void(0);" data-href="<?php echo site_url('app/invoice/send_invoice_email_ajax/'.$row->invoice_id);?>" data-id="<?php echo $row->invoice_id; ?>" class="send_invoice_email">Send Invoice Email</a></li>
                                        <li><a href="javascript:void(0);" data-pdf="<?php echo $row->invoice_pdf; ?>" class="view_invoice_pdf" data-id="<?php echo $row->invoice_id; ?>">View Invoice</a></li>
                                        <li><a href="javascript:void(0);" data-pdf="<?php echo $row->delivery_order_pdf; ?>" class="view_invoice_pdf" data-id="<?php echo $row->invoice_id; ?>">View Delivery Order</a></li>
                                      </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr class="customer-info hidden">
                                <td colspan="6">
                                    <table class="table table-bordered" style="width:91%;margin:10px auto;">
                                        <thead>
                                            <tr>
                                                <th colspan="4">Order Information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th width="20%;">Customer</th>
                                                <td width="30%;">
                                                    <a href="javascript:void(0);"><?php echo $row->order_number; ?> ( <?php echo $row->display_name; ?> )</a><br>
                                                    <?php if($row->company_name) : ?>
                                                        <small class="help-block"><?php echo $row->company_name; ?></small>
                                                    <?php endif; ?>
                                                    <small class="help-block"><?php echo $row->email; ?></small>
                                                    <small class="help-block"><?php echo $row->address; ?></small>
                                                </td>
                                                <th width="20%;">Items</th>
                                                <td width="30%;"><?php echo $row->items; ?> </td>
                                            </tr>
                                            <tr>
                                                <th>Driver</th>
                                                <td><?php echo $row->name; ?> </td>
                                                <th>Signature</th>
                                                <td>
                                                    <?php if($row->image) : ?>
                                                        <small>Receiver Name : <?php echo $row->customer_name; ?></small><br>
                                                        <img src="<?php echo site_url("public/upload/signature/".$row->image); ?>" style="height: 100px;">
                                                    <?php endif; ?>
                                                    <?php if($row->delivered_date != "NA") : ?>
                                                        <br>
                                                        <small>Assigned : <?php echo $row->place_delivery_date; ?></small>
                                                        <br>
                                                        <small>Start Driving : <?php echo $row->start_driving; ?></small><br>
                                                        <small>Delivered : <?php echo $row->delivered_date; ?></small><br>
                                                        <small>Shipping Address: <?php echo $row->shipping_address; ?></small><br>
                                                        <small>Notes : <i><?php echo $row->notes; ?></i></small>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php if($row->item_image) : ?>
                                                <tr>
                                                    <th>BEFORE DELIVERY</th>
                                                    <td colspan="3">
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
                                                    <th>AFTER DELIVERY</th>
                                                    <td colspan="3">
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
                                            <?php if($row->files) : ?>
                                            <tr>
                                                <th colspan="4">Other Files</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <?php foreach($row->files as $r) : ?>
                                                        <a href="<?php echo site_url("public/upload/files/".$r->file_path);?>"><?php echo $r->file_name;?></a>
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>    
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr class="customer-row">
                            <td colspan="6" class="text-center"><span>No Result</span></td>
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

<div class="modal fade" id="invoice_pdf_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Invoice Information</h4>
            </div>
            <div class="modal-body" id="_pdfViewer">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_pay" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Invoice Information</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url("app/invoice/pay_invoice"); ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="invoice_id" id="_invoice_id">
                    <input type="hidden" name="invoice_no" id="_invoice_no">
                    <div class="form-group">
                       <label>Payment Method</label>
                       <select class="form-control" name="payment_method" id="_paymethod" readonly="true" disabled="true">
                           <option value="COD">Cash on Delivery</option>
                           <option value="CHEQUE">Pay by Cheque</option>
                       </select>
                    </div>
                    <div class="form-group _cheque hide">
                       <label>Cheque No</label>
                       <input type="text" name="cheque_no" class="form-control" placeholder="xxx-xxxxxx-xx" required="false">
                    </div>
                    <div class="form-group">
                       <label>Paid Date</label>
                       <input type="text" name="paid_date" class="form-control datepicker">
                    </div>

                    <div class="form-group">
                       <label>Notes ( Optional )</label>
                       <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                       <label>Files ( Optional )</label>
                       <input type="file" name="file[]" class="form-control" multiple="">
                       <p class="help-block">Images , Pdf or Excel</p>
                    </div>
               </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                <a href="javascript:void(0);" class="btn btn-primary" id="submitForm">Submit</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_logs" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Invoice Logs</h4>
            </div>
            <div class="modal-body" id="invoice-content">
               <table id="invoice-logs-table" class="table table-bordered">
                   <thead>
                       <tr>
                        <td>Payment Method</td>
                        <td>Notes</td>
                        <td>Paid Date</td>
                        <td>Cheque No</td>
                        <td>Updated By</td>
                        <td>Date Updated</td>
                       </tr>
                   </thead>
                   <tbody>
                       <tr>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                       </tr>
                   </tbody>
               </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
