<script type="text/javascript">
	$(function() {
	  var a = $('input.daterange').daterangepicker({
	    autoUpdateInput: false
	  });

	  $('input.daterange').on('apply.daterangepicker', function(ev, picker) {
	      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	  });

	  $('input.daterange').on('cancel.daterangepicker', function(ev, picker) {
	      $(this).val('');
	  });

	  return a;
	});

	$(function() {
	  return $('input.datepicker').daterangepicker({
	        singleDatePicker: true,
	        showDropdowns: true,
	        locale: {
	            format: 'D MMM YYYY'
	        }
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
				  <a href="<?php echo site_url("order/"); ?>" class="list-group-item active">My Order</a>
				</div>
			</section>
		</div>
		<div class="col-lg-8">
			<h2 class="page-header">MY ORDERS</h2>

			<div class="card margin-bottom">
            <div class="card-body no-padding-left no-padding-right">
                <div class="container">
                    <div class="card-body no-padding-left no-padding-right">
                        <form action="<?php echo site_url("order"); ?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Order #</label>
                                    <input type="text" name="order_no" class="form-control" value="<?php echo $this->input->get("order_no"); ?>" id="s_name" placeholder="Order #">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Date Period</label>
                                    <input type="text" name="date" class="form-control daterange" value="<?php echo $this->input->get("date"); ?>" id="s_name" placeholder="Search by date">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-2  text-right">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

			<?php if($order_list) : ?>
				<table class="table table-border">
					<thead>
						<tr>
							<th>Order #</th>
							<th>Items</th>
							<th>Total Price</th>
							<th>Payment Method</th>
							<th>Date</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($order_list as $row) : ?>
							<tr>
								<td><a href="<?php echo site_url("order/view/$row->order_number"); ?>"><?php echo $row->order_number; ?></a></td>
								<td><?php echo $row->items; ?></td>
								<td><?php echo $row->total_price_with_gst; ?></td>
								<td><?php echo $row->pay_method; ?></td>
								<td><?php echo $row->created; ?></td>
								<td><?php echo $row->status; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<div class="pull-right">
                    <nav aria-label="Page navigation">
                      <?php echo $links; ?>
                    </nav>
                </div>
			<?php else : ?>
				<div class="text-center">
					<h4>There is no Order</h4>
					<a href="<?php echo site_url("welcome/?shop_list=all"); ?>" class="btn btn-success">Continue Buying</a>
				</div>
			<?php endif; ?>	
		</div>
	</div>
</div>