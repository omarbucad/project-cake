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
				  <a href="javascript:void(0);" class="list-group-item disabled">
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
			<a href="#">Edit</a>
			<address>
				Romar Bucad<br>
				1851 Arthur Street Filhomes Subdivision Lemens Mabiga,<br>
				Mabalacat City<br>
				Pampanga<br>
				2010<br>
			</address>
		</div>
	</div>
</div>