<script type="text/javascript">
	$(document).on("keyup" , ".quantity" , function(){
		compute_total();
	});
	$(document).on("change" , ".quantity" , function(){
		compute_total();
	});
	$(document).ready(function(){
		compute_total();
	});
	
	function compute_total(){
		var total_price = parseFloat(0);
		var table = $(".table");

		$.each(table.find("tbody > tr.only-me") , function(k , v){
			
			var quantity = parseInt($(v).find(".quantity").val());

			var price    = parseFloat($(v).find("td:eq(1)").data("price"));
			var tr_total = parseFloat(quantity * price).toFixed(2);

			total_price += parseFloat(tr_total);

			$(v).find(".total").html("RM "+tr_total);
			
		});

		var tr_gst =  total_price * 0.06;

		$(".total_gst").html("RM "+parseFloat(tr_gst).toFixed(2));
		$(".total_price").html("RM "+parseFloat(total_price + tr_gst).toFixed(2));
	}

	$(document).on("click" , "#form_submit" , function(){

		var form = $("#checkout_form");
		var payment_method = $("#payment_method").val();
		
		if(payment_method == ""){
			alert("Please select a payment method");
		}else{
			var c = confirm("Are you sure?");
			if(c == true){
				form.submit();
			}
		}
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
				  <a href="<?php echo site_url("profile/"); ?>" class="list-group-item ">
				    My Account
				  </a>
				  <a href="<?php echo site_url("order/"); ?>" class="list-group-item active">My Order</a>
				</div>
			</section>
		</div>
		<div class="col-lg-8">
			<h2 class="page-header">MY CART</h2>

			<?php if($cart_list["list"]) : ?>
				<form action="<?php echo site_url("cart/checkout"); ?>" method="POST" id="checkout_form">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="35%">Name</th>
								<th width="20%">Price</th>
								<th width="20%">Quantity</th>
								<th width="20%">Total</th>
								<th width="5%"></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($cart_list["list"] as $row)  : ?>
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
											<span style="padding-left: 5px;"><?php echo $row->product_name; ?></span>
										</div>
									</td>
									<td data-price="<?php echo $row->price_raw; ?>">
										<span><?php echo $row->price?></span>
									</td>
									<td>
										<input type="number" name="quantity[<?php echo $row->product_id; ?>]" step="1" min="1" value="1" class="form-control quantity">
									</td>
									<td>
										<span class="total"><?php echo $row->price; ?></span>
									</td>
									<td>
										<a href="<?php echo site_url("cart/remove_items/$row->product_id"); ?>" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
									</td>
								</tr>
							<?php endforeach; ?>
							<tr>
								<td class="text-right" colspan="3">GST 6%</td>
								<td>
									<span class="total_gst">0.00</span>
								</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" colspan="3">Total</td>
								<td>
									<span class="total_price">0.00</span>
								</td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<div class="form-group">
						<label for="payment_method">Payment Method *</label>
						<select class="form-control" name="payment_method" id="payment_method">
							<option value="">- Select Payment Method -</option>
							<option value="COD">Cash on Delivery</option>
							<option value="PAYCHEQUE">Paycheque</option>
						</select>
					</div>
					<div class="text-right">
						<button type="button" class="btn btn-primary" id="form_submit">Check Out</button>
					</div>
				</form>
			<?php else : ?>
				<div class="text-center">
					<h4>There is no Items in your cart</h4>
					<a href="<?php echo site_url("welcome/?shop_list=all"); ?>" class="btn btn-success">Continue Buying</a>
				</div>
			<?php endif; ?>
			
		</div>
	</div>
</div>