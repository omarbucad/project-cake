<script type="text/javascript">
	$(document).on("click" , ".save" , function(){
		var form = $(this).closest(".modal").find("form");
		form.submit();
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
				  <a href="javascript:void(0);" class="list-group-item active">
				    My Account
				  </a>
				  <a href="<?php echo site_url("order/"); ?>" class="list-group-item">My Order</a>
				  <a href="<?php echo site_url("order/wishlist"); ?>" class="list-group-item">Wishlist</a>
				</div>
			</section>
		</div>
		<div class="col-lg-8">
			<h2 class="page-header">MY ACCOUNT</h2>
			<p class="help-block">Hello <?php echo $this->session->userdata("customer")->display_name; ?> (not <?php echo $this->session->userdata("customer")->display_name; ?>? <a href="<?php echo site_url("login/logout");?>">Sign out</a>). From your account dashboard you can view your recent orders, manage your addresses and <a href="<?php echo site_url("profile/edit"); ?>">edit your password and account details</a>.</p>

			<legend>MY ADDRESS</legend>
			<p class="help-block">The following addresses will be used on the checkout page by default.</p>
			<button class="btn btn-link" data-toggle="modal" data-target="#myModal">Edit</button>
			<address>
				<?php echo $this->session->userdata("customer")->display_name; ?><br>
				<?php echo $profile_information->street1; ?> , <?php echo $profile_information->street2; ?>,<br>
				<?php echo $profile_information->suburb; ?>,<br>
				<?php echo $profile_information->city; ?>,<br>
				<?php echo $profile_information->state; ?>,<br>
				<?php echo $profile_information->postcode; ?>
			</address>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit your Address</h4>
      </div>
      <div class="modal-body">
      		<form action="<?php echo site_url("profile/update_address"); ?>" method="POST">
      			<input type="hidden" name="address_id" value="<?php echo $profile_information->address_id; ?>">
      			<dl class="dl-horizontal text-left">
		      		<dt>Street 1</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[street1]" class="form-control" value="<?php echo $profile_information->street1; ?>">
		      			</div>
		      		</dd>
		      		<dt>Street 2</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[street2]" class="form-control" value="<?php echo $profile_information->street2; ?>">
		      			</div>
		      		</dd>
		      		<dt>Suburb</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[suburb]" class="form-control" value="<?php echo $profile_information->suburb; ?>">
		      			</div>
		      		</dd>
		      		<dt>City</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[city]" class="form-control" value="<?php echo $profile_information->city; ?>">
		      			</div>
		      		</dd>
		      		<dt>Postcode</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[postcode]" class="form-control" value="<?php echo $profile_information->postcode; ?>">
		      			</div>
		      		</dd>
		      		<dt>State</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[state]" class="form-control" value="<?php echo $profile_information->state; ?>">
		      			</div>
		      		</dd>

		      	</dl>
      		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a href="javascript:void(0);" class="btn btn-primary save">Save</a>
      </div>
    </div>
  </div>
</div>