<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
    	<div class="card">
    		<div class="card-body">
    			<h2>Welcome <?php echo $session_data->name; ?></h2>

    			<div class="row">
    				<div class="col-lg-6 col-xs-12">
	    				<div class="panel panel-success">
						  <div class="panel-heading">New Order</div>
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
					   				<?php if($new_order) : ?>
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
					   				<?php else : ?>
					   					<tr>
					   						<td colspan="4" class="text-center">
					   							<p>No Order Today</p>
					   						</td>
					   					</tr>
					   				<?php endif; ?>
					   			</tbody>	
					   		</table>
						</div>
	    			</div>
	    			<div class="col-lg-6 col-xs-12">
	    				<div class="panel panel-danger">
						   <div class="panel-heading">Unpaid Invoices</div>
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
					   				<?php if($unpaid_invoice) : ?>
					   					<?php foreach($unpaid_invoice as $row) : ?>
					   						<tr>
						   						<td><span><?php echo $row->invoice_no; ?></span></td>
						   						<td><span><?php echo $row->price; ?></span></td>
						   						<td><span><?php echo $row->invoice_date; ?></span></td>
						   						<td><a href="javascript:void(0);" class="btn btn-primary btn-xs">Pay</a></td>
						   					</tr>
					   					<?php endforeach; ?>
					   				<?php else : ?>
					   					<tr>
					   						<td colspan="4" class="text-center">
					   							<p>No Unpaid Invoice</p>
					   						</td>
					   					</tr>
					   				<?php endif; ?>
					   			</tbody>	
					   		</table>
						</div>
	    			</div>
    			</div>
    		</div>
    	</div>
    </div>
 </div>