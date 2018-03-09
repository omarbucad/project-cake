
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
						<form action="<?php echo site_url("login/change_password/").$code; ?>" method="POST">
							<legend>CHANGE PASSWORD</legend>
							<div class="form-group">
								<label for="email">Email Address *</label>
								<input type="text" name="email" class="form-control" placeholder="Email Address" id="email" required="true" readonly="true" value="<?php echo $customer_email;?>">
							</div>
							<div class="form-group">
                                <label for="password">New Password *</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" id="password" required="true" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password *</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" id="confirm_password" required="true" autocomplete="off">
                            </div>
							<div class="text-left">
								<input type="submit" name="submit" class="btn btn-primary btn-block" value="SUBMIT">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>