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

    $(document).on("click" , ".pay_invoice" , function(){
        var invoice_id = $(this).data("id");
        var invoice_no = $(this).data("invoiceno");
        var modal = $('#invoice_pay').modal("show");
        modal.find(".modal-title").html("Invoice #"+invoice_no);
        modal.find('#_invoice_id').val(invoice_id);
        modal.find('#_invoice_no').val(invoice_no);
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
                    var tr = $("<tr>");
                $.each(json.data.customer_info , function (a , b){
                    $.each(json.data.invoice_logs , function(k , v){
                        var td = $("<td>").html(v.payment_method);
                        tr.append(td);
                        var td = $("<td>").html(v.notes);
                        tr.append(td);
                        var td = $("<td>").html(v.paid_date);
                        tr.append(td);
                        var td = $("<td>").html(v.cheque_no);
                        tr.append(td);
                        var td = $("<td>").html(b.display_name);
                        tr.append(td);
                        var td = $("<td>").html(v.created);
                        tr.append(td);
                    });
                });

                    $("#invoice-logs-table").find("tbody").html(tr);

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
            form.submit();
        }

    });
</script>
<style type="text/css">
    .daterangepicker.dropdown-menu {
        z-index: 100001 !important;
    }
</style>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

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
                                    <label for="s_name">Date period</label>
                                    <input type="text" name="date" class="form-control daterange" autocomplete="off" value="<?php echo $this->input->get("date"); ?>" id="s_name" placeholder="Search by date">
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
                                    <label for="s_brand">Payment Method</label>
                                     <select class="form-control" name="payment_method" id="s_brand">
                                        <option value="">All Payment Method</option>
                                        <option value="COD" <?php echo ($this->input->get("payment_method") == "COD") ? "selected" : "" ; ?>>Cash On Delivery</option>
                                        <option value="PAYCHEQUE" <?php echo ($this->input->get("payment_method") == "PAYCHEQUE") ? "selected" : "" ; ?>>Paycheque</option>
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
            </div>
        </div>
        <div class="container ">
            <div class="pull-right">
                <nav aria-label="Page navigation">
                  <?php echo $links; ?>
              </nav>
            </div>
            <table class="customer-table">
                <thead>
                    <tr>
                        <th width="25%">Invoice No</th>
                        <th width="10%">Total Price</th>
                        <th width="10%">Payment Method</th>
                        <th width="15%">Status</th>
                        <th width="20%">Invoice Date</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr class="customer-row">
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
                                            <li><a href="javascript:void(0);" class="pay_invoice" data-id="<?php echo $row->invoice_id; ?>" data-invoiceno="<?php echo $row->invoice_no; ?>">Pay Invoice</a></li>
                                        <?php else : ?>
                                             <li><a href="javascript:void(0);" class="view_logs" data-id="<?php echo $row->invoice_id; ?>" data-invoiceno="<?php echo $row->invoice_no; ?>">Invoice Logs</a></li>
                                        <?php endif; ?>
                                        <li role="separator" class="divider"></li>
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
                                                    <small class="help-block"><?php echo $row->email; ?></small>
                                                </td>
                                                <th width="20%;">Items</th>
                                                <td width="30%;"><?php echo $row->items; ?> </td>
                                            </tr>
                                            <tr>
                                                <th>Driver</th>
                                                <td><?php echo $row->name; ?> </td>
                                                <th>Customer Signature</th>
                                                <td>
                                                    <?php if($row->image) : ?>
                                                        <img src="<?php echo site_url("public/upload/signature/".$row->image); ?>" style="height: 100px;">
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Receiver Name</th>
                                                <td><?php echo $row->customer_name; ?></td>
                                                <th>Notes</th>
                                                <td><?php echo $row->notes; ?></td>
                                            </tr>
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
            <div class="pull-right">
                <nav aria-label="Page navigation">
                  <?php echo $links; ?>
              </nav>
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
                       <select class="form-control" name="payment_method" id="_paymethod">
                           <option value="COD">Cash on Delivery</option>
                           <option value="PAYCHEQUE">Paycheque</option>
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
                        <td>Customer Name</td>
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
