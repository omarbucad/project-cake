
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
		<div class="col-lg-4 col-xs-12 col-lg-offset-4 ">
			<div class="panel-group">
				<div class="panel panel-default">
					<div class="panel-body text-center">
						<form action="<?php echo site_url("login/forgot_password"); ?>" method="POST">
							<legend>FORGOTTEN PASSWORD</legend>
							<div class="form-group">
								<p class="help-block text-center">Enter your email address that you used to register. We'll send you an email with your username and a link to reset your password.</p>
								<input type="text" name="email" class="form-control" placeholder="Email Address" id="email" required="true">
							</div>
							<div class="text-left">
								<input type="submit" name="submit" class="btn btn-primary btn-block" value="RESET MY PASSWORD">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>