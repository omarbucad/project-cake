<script type="text/javascript">
	$(document).on('click' , '.remove_wish' , function(){
		var url = "<?php echo site_url("Order/remove_wish"); ?>";
		var me = $(this);
		swal({
			title: "Are you sure?",
			text: "You will remove this product to you wishlists",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, delete it!",
			confirmButtonColor: "#ff7f7f",
			closeOnConfirm: false
		},
		function(){
			$.ajax({
				url: url,
				type: "POST",
				data: {
					product_id : me.data('product_id') 
				},
				dataType: "html",
				success: function (response) {
					swal("Done!", "It was succesfully deleted!", "success");
					console.log(response);
					me.closest('tr').remove();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					swal("Error deleting!", "Please try again", "error");
				}
			});
		});
	});

	$(document).on('click' , '.add_wish_to_cart' , function () {
		var url = "<?php echo site_url("Order/add_wish_to_cart"); ?>";
		var me = $(this);
		swal({
			title: "Are you sure?",
			text: "You will add this product to your cart ",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, delete it!",
			confirmButtonColor: "#ff7f7f",
			closeOnConfirm: false
		},
		function(){
			$.ajax({
				url: url,
				type: "POST",
				data: {
					product_id : me.data('product_id') 
				},
				dataType: "html",
				success: function (response) {
					swal("Done!", "It was succesfully deleted!", "success");
					console.log(response);
					me.closest('tr').remove();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					swal("Error deleting!", "Please try again", "error");
				}
			});
		});

	});
</script>

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
					<a href="<?php echo site_url("order/"); ?>" class=list-group-item ">My Order</a>
					<a href="<?php echo site_url("order/wishlist"); ?>" class="list-group-item active">My Wishlist</a>
				</div>
			</section>
		</div>
		
		<div class="col-lg-8">
			<h2 class="page-header">MY ORDERS</h2>
			<?php if($wishlist) : ?>
				<form action="#" method="POST">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="35%">Name</th>
								<th width="20%">Price</th>
								<th width="25%"></th>
								
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
											<span><?php echo $row->product_name; ?></span>
										</div>
									</td>
									<td data-price="<?php echo $row->price; ?>">
										<span><?php echo number_format($row->price , 2)?></span>
									</td>
									
									<td>
										<a href="javascript:void(0);" class="btn btn-link remove_wish" data-product_id="<?php echo $row->product_id ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

										<a href="javascript:void(0);" class="btn btn-link add_wish_to_cart" data-product_id="<?php echo $row->product_id ?>"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>Add to Cart</a>
						
									</td>
								</tr>
							<?php endforeach; ?>

						</tbody>
					</table>
				
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