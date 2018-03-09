
<div style="margin-top: 100px;"></div>
<div class="container">
	<?php if(validation_errors()) : ?>
		<div id="alert_container_remove">
			<div class="side-body padding-top">
				<div class="alert alert-danger alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closed_alert"><span aria-hidden="true">×</span></button>
					<?php echo validation_errors(); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<h2 class="page-header">MY ACCOUNT</h2>
	<div class="row">
		<div class="col-lg-6 col-xs-12 col-lg-offset-3 ">
			<div class="panel-group">
				<div class="panel panel-default">
					<div class="panel-body">
						<form action="<?php echo site_url("login/resend_activation_email"); ?>" method="POST">
							<legend>ACTIVATION</legend>
							<span><p>Please check your email for the activation link.</p></span>
							<span><p>Didn't receive email?</p></span>
							<div class="form-group">
								<label for="email">Email Address *</label>
								<input type="text" name="email" class="form-control" placeholder="Email Address" id="email" required="true" value="<?php echo $customer_email;?>" readonly="true">
							</div>
							<div class="text-left">
								<input type="submit" name="submit" class="btn btn-primary btn-block" value="Resend Activation Link">
							</div>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>