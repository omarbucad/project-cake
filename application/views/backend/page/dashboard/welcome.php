<link rel="stylesheet" type="text/css" href="<?php echo 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css' ?>">
<script type="text/javascript" src="<?php echo 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo 'https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js' ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".table").DataTable( {
            "order": [],
            "columnDefs": [ {
              "targets"  : [0],
              "orderable": false,
            }]
        });

	});

	$(document).on("click" , ".pay_invoice" , function(){
        var invoice_id = $(this).data("id");
        var invoice_no = $(this).data("invoiceno");
        var modal = $('#invoice_pay').modal("show");
        modal.find(".modal-title").html("Invoice #"+invoice_no);
        modal.find('#_invoice_id').val(invoice_id);
        modal.find('#_invoice_no').val(invoice_no);
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

    $(document).on("click" , "#pending-order" , function(){
    	$(".panel-pending").toggle();

        $(".panel-confirmed").attr("style", "display: none");
        $(".panel-month").attr("style", "display: none");
        $(".panel-week").attr("style", "display: none");
        $(".panel-today").attr("style", "display: none");

        $(".panel-confirmed").parent().addClass("hidden");
        $(".panel-month").parent().addClass("hidden");
        $(".panel-week").parent().addClass("hidden");
        $(".panel-today").parent().addClass("hidden");

        if($(".panel-pending").parent().hasClass("hidden")){
            $(".panel-pending").parent().removeClass("hidden");
        }

    });

    $(document).on("click" , "#confirmed-order" , function(){
    	$(".panel-confirmed").toggle();
        $(".panel-month").attr("style", "display: none");
        $(".panel-pending").attr("style", "display: none");
        $(".panel-week").attr("style", "display: none");
        $(".panel-today").attr("style", "display: none");

        $(".panel-month").parent().addClass("hidden");
        $(".panel-pending").parent().addClass("hidden");
        $(".panel-week").parent().addClass("hidden");
        $(".panel-today").parent().addClass("hidden");

        if($(".panel-confirmed").parent().hasClass("hidden")){
            $(".panel-confirmed").parent().removeClass("hidden");
        }  
    });

    $(document).on("click" , "#panel-month" , function(){
        $(".panel-month").toggle();
        $(".panel-confirmed").attr("style", "display: none");
        $(".panel-pending").attr("style", "display: none");
        $(".panel-week").attr("style", "display: none");
        $(".panel-today").attr("style", "display: none");

        $(".panel-confirmed").parent().addClass("hidden");
        $(".panel-pending").parent().addClass("hidden");
        $(".panel-week").parent().addClass("hidden");
        $(".panel-today").parent().addClass("hidden");


        if($(".panel-month").parent().hasClass("hidden")){
            $(".panel-month").parent().removeClass("hidden");
        }
    });

    $(document).on("click" , "#panel-week" , function(){
        $(".panel-week").toggle();
        $(".panel-confirmed").attr("style", "display: none");
        $(".panel-pending").attr("style", "display: none");
        $(".panel-month").attr("style", "display: none");
        $(".panel-today").attr("style", "display: none");

        $(".panel-confirmed").parent().addClass("hidden");
        $(".panel-pending").parent().addClass("hidden");
        $(".panel-month").parent().addClass("hidden");
        $(".panel-today").parent().addClass("hidden");

        if($(".panel-week").parent().hasClass("hidden")){
            $(".panel-week").parent().removeClass("hidden");
        }
    });

    $(document).on("click" , "#panel-today" , function(){
        $(".panel-today").toggle();
        $(".panel-confirmed").attr("style", "display: none");
        $(".panel-pending").attr("style", "display: none");
        $(".panel-month").attr("style", "display: none");
        $(".panel-week").attr("style", "display: none");

        $(".panel-confirmed").parent().addClass("hidden");
        $(".panel-pending").parent().addClass("hidden");
        $(".panel-month").parent().addClass("hidden");
        $(".panel-week").parent().addClass("hidden");

        if($(".panel-today").parent().hasClass("hidden")){
            $(".panel-today").parent().removeClass("hidden");
        }
    });
     
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
    	<div class="card">
    		<div class="card-body">

                <h2>Welcome <?php echo $session_data->name; ?></h2>

    			<?php $this->load->view("backend/common/sales_box"); ?>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                   <a href="javascript:void(0);" id="pending-order">
                    <div class="card blue summary-inline">
                        <div class="card-body">
                            <i class="icon fa fa-shopping-cart fa-4x"></i>
                            <div class="content">
                                <div class="title"><?php echo $total_pending_order; ?></div>
                                <div class="sub-title">Total Pending Orders</div>
                                
                                <span class="pull-right sub"><small>Click to View List</small></span>
                            </div>
                            <div class="clear-both"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                   <a href="javascript:void(0);" id="confirmed-order">
                    <div class="card red summary-inline">
                        <div class="card-body">
                            <i class="icon fa fa-thumbs-up fa-4x"></i>
                            <div class="content">
                                <div class="title"><?php echo $total_confirmed_order; ?></div>
                                <div class="sub-title">Total Confirmed Orders</div>
                                <span class="pull-right sub"><small>Click to View List</small></span>
                            </div>
                            <div class="clear-both"></div>
                        </div>
                    </div>
                </a>
            </div>
          </div>

    			<div class="row">
    				<div class="col-lg-12 col-xs-12">
	    				<div class="panel panel-info panel-pending" style="display: none;">
						  	<div class="panel-heading">New Orders</div>
						  	<div class="panel-body">
						  		<table class="table">
						   			<thead>
						   				<tr>
						   					<th>Order Number</th>
						   					<th>Item</th>
						   					<th>Price</th>
						   					<th>Price w/ GST</th>
						   					<th></th>
						   				</tr>
						   			</thead>
						   			<tbody>
						   				<?php foreach($new_order as $row) : ?>
						   					<tr class="customer-row">
						   						<td>
						   							<a href="javascript:void(0);"><?php echo $row->order_number; ?> ( <?php echo $row->display_name; ?> )</a><br>
						   							<small class="help-block"><?php echo $row->email; ?></small>
						   						</td>
						   						<td>
						   							<span><?php echo $row->items; ?></span>
						   						</td>
						   						<td>
						   							<span><?php echo $row->total_price; ?></span>
						   						</td>
						   						<td>
						   							<span><?php echo $row->total_price_with_gst; ?> <br><small><?php echo $row->gst_price; ?> @6%</small></span>
						   						</td>
						   						<td>
						   							<span>
						   								<a href="<?php echo site_url("app/invoice/order?name=$row->order_number"); ?>" class="btn btn-primary btn-xs">Go to Order</a><br>
						   								<small><?php echo $row->created; ?></small>
						   							</span>
						   						</td>
						   					</tr>
						   				<?php endforeach; ?>
						   			</tbody>	
						   	  	</table>
						  	</div>
						</div>
	    			</div>
	    			<div class="col-lg-12 col-xs-12">
	    				<div class="panel panel-danger panel-confirmed" style="display: none;">
						   	<div class="panel-heading">Unpaid Invoices</div>
						   	<div class="panel-body">
						   		<table class="table">
						   			<thead>
						   				<tr>
						   					<th>Invoice #</th>
						   					<th>Price</th>
						   					<th>Date Invoice</th>
						   					<th></th>
						   				</tr>
						   			</thead>
						   			<tbody>
										<?php foreach($unpaid_invoice as $row) : ?>
						   					<tr>
							   					<td><span><?php echo $row->invoice_no; ?></span></td>
							   					<td><span><?php echo $row->total_price; ?></span></td>
							   					<td><span><?php echo $row->invoice_date; ?></span></td>
							   					<td>
							   						<a href="javascript:void(0);" class="btn btn-primary btn-xs pay_invoice" data-id="<?php echo $row->invoice_id; ?>" data-invoiceno="<?php echo $row->invoice_no; ?>">Invoice Update</a>
							   					</td>
							   				</tr>
						   				<?php endforeach; ?>
						   			</tbody>	
					   			</table>
						   	</div>
						</div>
	    			</div>
                    <div class="col-lg-12 col-xs-12">
                        <div class="panel panel-success panel-month" style="display: none;">
                            <div class="panel-heading">Monthly Sales <span class="pull-right"><?php echo $card_info["month"]["current"]["date"]; ?></span></div>
                            <div class="panel-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Price w/ GST</th>
                                            <th>Payment Method</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($sales_data['month'] as $row) : ?>
                                            <tr>
                                                <td><span><?php echo $row->invoice_no; ?></span></td>
                                                <td><span><?php echo $row->total_price; ?></span></td>
                                                <td><span><?php echo $row->payment_method; ?></span></td>
                                                <td>
                                                    <span>
                                                        <a href="<?php echo site_url("app/invoice/order?name=$row->invoice_no"); ?>" class="btn btn-primary btn-xs">Go to Order</a><br>
                                                        <small><?php echo $row->invoice_date; ?></small>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xs-12">
                        <div class="panel panel-warning panel-week" style="display: none;">
                            <div class="panel-heading">Weekly Sales <span class="pull-right"><?php echo $card_info["week"]["current"]["date"]; ?></span></div>
                            <div class="panel-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Price w/ GST</th>
                                            <th>Payment Method</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($sales_data['week'] as $row) : ?>
                                            <tr>
                                                <td><span><?php echo $row->invoice_no; ?></span></td>
                                                <td><span><?php echo $row->total_price; ?></span></td>
                                                <td><span><?php echo $row->payment_method; ?></span></td>
                                                <td>
                                                    <span>
                                                        <a href="<?php echo site_url("app/invoice/order?name=$row->invoice_no"); ?>" class="btn btn-primary btn-xs">Go to Order</a><br>
                                                        <small><?php echo $row->invoice_date; ?></small>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xs-12">
                        <div class="panel panel-danger panel-today" style="display: none;">
                            <div class="panel-heading">Today Sales <span class="pull-right"><?php echo $card_info["day"]["current"]["date"]; ?></span></div>
                            <div class="panel-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Price w/ GST</th>
                                            <th>Payment Method</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($sales_data['today'] as $row) : ?>
                                            <tr>
                                                <td><span><?php echo $row->invoice_no; ?></span></td>
                                                <td><span><?php echo $row->total_price; ?></span></td>
                                                <td><span><?php echo $row->payment_method; ?></span></td>
                                                <td>
                                                    <span>
                                                        <a href="<?php echo site_url("app/invoice/order?name=$row->invoice_no"); ?>" class="btn btn-primary btn-xs">Go to Order</a><br>
                                                        <small><?php echo $row->invoice_date; ?></small>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>
    			</div>
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