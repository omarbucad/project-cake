
<style type="text/css">
	section{
		margin-top: 20px;
	}
</style>
<div style="margin-top: 100px;"></div>
<div class="container">
	<div class="row">
		<div class="col-lg-4">
			<section>
				<h3><?php echo $this->session->userdata("customer")->display_name; ?> <small># <?php echo $this->session->userdata("customer")->customer_id; ?></small></h3>
				<a href="<?php echo site_url("login/logout"); ?>" class="btn btn-primary btn-xs">Logout</a>
			</section>
			<section>
				<div class="list-group">
				  <a href="javascript:void(0);" class="list-group-item ">
				    My Account
				  </a>
				  <a href="<?php echo site_url("order/"); ?>" class="list-group-item disabled">My Order</a>
				  <a href="<?php echo site_url("order/wishlist"); ?>" class="list-group-item">Wishlist</a>
				</div>
			</section>
		</div>
		<div class="col-lg-8">
			<h2 class="page-header">ORDER # <?php echo $this->uri->segment(3); ?></h2>
			<table style="width:100%;margin: 20px 0px;">
				<tr>
					<th width="10%">Status</th>
					<td width="40%"><?php echo $order_data->status; ?></td>
					<th width="10%">Created</th>
					<td width="40%"><?php echo $order_data->created; ?></td>
				</tr>
				<tr>
					<td colspan="4" class="text-right">
						<?php if($order_data->status_raw == 1) : ?>
							<a href="<?php echo site_url("cart/cancel_order/".$order_data->order_number); ?>" class="btn btn-danger btn-xs">Cancel Order</a>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<table class="table table-bordered">
				<thead>
					<tr>
						<th width="35%">Name</th>
						<th width="35%">Price</th>
						<th width="15%">Quantity</th>
						<th width="15%">Total</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($order_data->product_list as $row)  : ?>
						<tr class="only-me">
							<td style="overflow: hidden;">
								<div style="float:left;width: 25%;">
									<a href="javascript:void(0);">
										<div >
											<img src="<?php echo site_url("thumbs/images/product/".$row->images[0]->image_path."/60/60/".$row->images[0]->image_name); ?>">
										</div>
									</a>
								</div>
								<div style="float:left;width: 75%;">
									<span><?php echo $row->product_name; ?></span>
								</div>
							</td>
							<td >
								<span><?php echo $row->product_price?></span>
							</td>
							<td>
								<span class="total"><?php echo $row->quantity; ?></span>
							</td>
							<td>
								<span class="total"><?php echo $row->total_price; ?></span>
							</td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<td class="text-right" colspan="3">Total</td>
						<td>
							<span class="total"><?php echo $order_data->total_price; ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>