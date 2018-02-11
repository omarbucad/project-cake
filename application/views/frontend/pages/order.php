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
				  <a href="<?php echo site_url("profile"); ?>" class="list-group-item ">
				    My Account
				  </a>
				  <a href="<?php echo site_url("order/"); ?>" class="list-group-item disabled">My Order</a>
				  <a href="<?php echo site_url("order/wishlist"); ?>" class="list-group-item">Wishlist</a>
				</div>
			</section>
		</div>
		<div class="col-lg-8">
			<h2 class="page-header">MY ORDERS</h2>

			<?php if($order_list) : ?>
				<table class="table table-border">
					<thead>
						<tr>
							<th>Order #</th>
							<th>Items</th>
							<th>Total Price</th>
							<th>Date</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($order_list as $row) : ?>
							<tr>
								<td><a href="<?php echo site_url("order/view/$row->order_number"); ?>"><?php echo $row->order_number; ?></a></td>
								<td><?php echo $row->items; ?></td>
								<td><?php echo $row->total_price; ?></td>
								<td><?php echo $row->created; ?></td>
								<td><?php echo $row->status; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<div class="text-center">
					<h4>There is no Order</h4>
					<a href="<?php echo site_url("welcome/?shop_list=all"); ?>" class="btn btn-success">Continue Buying</a>
				</div>
			<?php endif; ?>
			

			
		</div>
	</div>
</div>