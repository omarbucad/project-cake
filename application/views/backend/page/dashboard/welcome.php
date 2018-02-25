<link rel="stylesheet" type="text/css" href="<?php echo 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css' ?>">
<script type="text/javascript" src="<?php echo 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo 'https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js' ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".table").DataTable();
	});
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
    	<div class="card">
    		<div class="card-body">
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
						   							<span>
						   								<a href="javascript:void(0);" class="btn btn-primary btn-xs">Go to Order</a><br>
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
							   					<td><span><?php echo $row->price; ?></span></td>
							   					<td><span><?php echo $row->invoice_date; ?></span></td>
							   					<td><a href="javascript:void(0);" class="btn btn-primary btn-xs">Update</a></td>
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