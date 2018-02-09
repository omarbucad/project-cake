
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
		<div class="col-lg-6 col-xs-12">
			<form action="<?php echo site_url("login/?action=login"); ?>" method="POST">
				<legend>LOGIN</legend>
				<div class="form-group">
					<label for="username">Email Address *</label>
					<input type="text" name="username" class="form-control" placeholder="Email Address" id="username" required="true">
				</div>
				<div class="form-group">
					<label for="password">Password *</label>
					<input type="password" name="password" class="form-control" placeholder="Password" id="username" required="true">
				</div>
				<div class="text-left">
					<input type="submit" name="submit" class="btn btn-primary btn-block" value="Login">
					<a href="javascript:void(0);">Lost your Password?</a>
				</div>
				<div class="checkbox">
				    <label>
				      <input type="checkbox" name="remember_me"> Remember Me
				    </label>
				</div>
			</form>
		</div>
		<div class="col-lg-6 col-xs-12">
			<form action="<?php echo site_url("login/?action=register"); ?>" method="POST">
				<legend>REGISTER</legend>
				<div class="form-group">
					<label for="name">Full Name *</label>
					<input type="text" name="name" class="form-control" placeholder="Name" id="name" required="true">
				</div>
				<div class="form-group">
					<label for="username">Email Address *</label>
					<input type="email" name="username" class="form-control" placeholder="Email Address" id="username" required="true">
				</div>
				<div class="form-group">
					<label for="password">Password *</label>
					<input type="password" name="password" class="form-control" placeholder="Password" id="username" required="true">
				</div>
				<div class="form-group">
					<label for="confirm_password">Confirm Password *</label>
					<input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" id="confirm_password" required="true">
				</div>
				<div class="text-left">
					<input type="submit" name="submit" class="btn btn-primary btn-block" value="Register">
				</div>
			</form>
		</div>
	</div>

	<hr class="featurette-divider">
	<!-- FOOTER -->
    <footer>
        <p>&copy; <?php echo $year; ?> Copyright. <?php echo $company_name; ?> &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>

</div>