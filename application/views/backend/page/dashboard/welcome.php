<link rel="stylesheet" type="text/css" href="<?php echo 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css' ?>">
<script type="text/javascript" src="<?php echo 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo 'https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js' ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".table").DataTable();
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
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
    	<div class="card">
    		<div class="card-body">
    			<div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    	<span><h5><?php echo "March 3 2018"; ?></h5></span>
                        <a href="#">
                            <div class="card red summary-inline">
                                <div class="card-body">
                                    <i class="icon fa fa-tags fa-4x"></i>
                                    <div class="content">
                                        <div class="title">3</div>
                                        <div class="sub-title"></div>
                                        <span><small>Total Pending Orders Yesterday: <?php echo "5"; ?></small></span>
                                    </div>
                                    <div class="clear-both"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    	<span><h5>March 1 2018 - March 8 2018</h5></span>
                        <a href="#">
                            <div class="card yellow summary-inline">
                                <div class="card-body">
                                    <i class="icon fa fa-truck fa-4x" style="transform: scaleX(-1);"></i>
                                    <div class="content">
                                        <div class="title">4</div>
                                        <div class="sub-title"></div>
                                        <span><small>Total Pending Orders Last Week: <?php echo "5"; ?></small></span>
                                    </div>
                                    <div class="clear-both"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    	<span><h5>March 1 2017 - march 31 2017</h5></span>
                        <a href="#">
                            <div class="card green summary-inline">
                                <div class="card-body">
                                    <i class="icon fa fa-check-circle fa-4x"></i>
                                    <div class="content">
                                        <div class="title">1</div>
                                        <div class="sub-title"></div>
                                        <span><small>Total Pending Orders Last Month: <?php echo "5"; ?></small></span>
                                    </div>
                                    <div class="clear-both"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

    			<h2>Welcome <?php echo $session_data->name; ?></h2>

    			<div class="row">
    				<div class="col-lg-6 col-xs-12">
	    				<div class="panel panel-success">
						  	<div class="panel-heading">New Order</div>
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
	    			<div class="col-lg-6 col-xs-12">
	    				<div class="panel panel-danger">
						   	<div class="panel-heading">Unpaid Invoices</div>
						   	<div class="panel-body">
						   		<table class="table">
						   			<thead>
						   				<tr>
						   					<th>Invoice Number</th>
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