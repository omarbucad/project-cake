
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
							<div class="col-lg-12"><i class="fa fa-check-circle fa-4x" style="color: #80b542"></i></div>
							<div class="col-lg-12">
								<span style="margin-top: 20px"><h4>Registration Successful</h4></span>
								<span><p>Please check your email for the activation link.</p></span>
								<span><small>Didn't receive email? <a href="<?php echo site_url('login/resend_activation_email/?email=').$email;?>" >Click Here.</a></small></span>
							</div>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>

	<hr class="featurette-divider">
	<!-- FOOTER -->
    <footer>
        <p>&copy; <?php echo $year; ?> Copyright. <?php echo $company_name; ?> &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>

</div>