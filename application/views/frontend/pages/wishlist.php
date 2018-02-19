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
					<a href="<?php echo site_url("order/"); ?>" class="list-group-item ">My Order</a>
					<a href="<?php echo site_url("order/wishlist"); ?>" class="list-group-item active">Wishlist</a>
				</div>
			</section>
		</div>
		
		<div class="col-lg-8">
			<h2 class="page-header">MY ORDERS</h2>
					<?php if($wishlist) : ?>
				<form action="<?php echo site_url("cart/checkout"); ?>" method="POST">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="35%">Name</th>
								<th width="30%">Price</th>
								<th width="15%"></th>
								
							</tr>
						</thead>
						<tbody>
							<?php foreach($wishlist as $row)  : ?>
								<tr class="only-me">
									<td style="overflow: hidden;">
										<div style="float:left;width: 25%;">
											<a href="javascript:void(0);">
												<div >
													<img src="<?php echo site_url("thumbs/images/product/".$row->image_path."/60/60/".$row->image_name); ?>">
												</div>
											</a>
										</div>
										<div style="float:left;width: 75%;">
											<span><?php echo $row->name; ?></span>
										</div>
									</td>
									<td data-price="<?php echo $row->price; ?>">
										<span><?php echo number_format($row->price , 2)?></span>
									</td>
									
									<td>
										<a href="<?php echo site_url("cart/remove_items/$row->product_id"); ?>" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
									</td>
								</tr>
							<?php endforeach; ?>

						</tbody>
					</table>
					<div class="text-right">
						<input type="submit" class="btn btn-primary" value="Check Out">
					</div>
				</form>
			<?php else : ?>
				<div class="text-center">
					<h4>No Wishlist</h4>
					<a href="<?php echo site_url("welcome/?shop_list=all"); ?>" class="btn btn-success">Continue Buying</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>