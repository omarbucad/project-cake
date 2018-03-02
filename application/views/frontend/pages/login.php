
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
					<?php if($this->input->get("error")) :?>
						<div class="alert alert-danger alert-dismissible">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Wrong Password!</div>
					<?php endif; ?>
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
				<div>
				<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">Personal Information</div>
						<div class="panel-body">
							<div class="form-group">
								<label for="name">Manager Name *</label>
								<input type="text" name="name" class="form-control" placeholder="Manager Name" id="name" required="true" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="company_name">Company Name *</label>
								<input type="text" name="company_name" class="form-control" placeholder="Company Name" id="company_name" required="true" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="username">Email Address *</label>
								<input type="email" name="username" class="form-control" placeholder="Email Address" id="username" required="true" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="phone_number">Phone number *</label>
								<input type="text" name="phone_number" class="form-control" placeholder="Phone Number" id="phone_number" required="true" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="password">Password *</label>
								<input type="password" name="password" class="form-control" placeholder="Password" id="username" required="true" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="confirm_password">Confirm Password *</label>
								<input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" id="confirm_password" required="true" autocomplete="off">
							</div>
						</div>
					</div>
				</div>
				<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">Address *
							<a href="#adress-div" data-toggle="collapse" class="pull-right">
								<i class="fa fa-caret-down" aria-hidden="true"></i>
							</a>
						</div>
						<div class="panel-body collapse" id="adress-div">
							<div class="form-group">
			                    <label for="street1">Street 1 *</label>
			                    <input type="text" name="street1" class="form-control" id="street1" placeholder="Street 1" autocomplete="off" required="true">
			                </div>
			                <div class="form-group">
			                    <label for="street2">Street 2 *</label>
			                    <input type="text" name="street2" class="form-control" id="street2" placeholder="Street 2" autocomplete="off" required="true">
			                </div>
			                <div class="form-group">
			                    <label for="suburb">Suburb *</label>
			                    <input type="text" name="suburb" class="form-control" id="suburb" placeholder="Suburb" autocomplete="off" required="true">
			                </div>
			                <div class="form-group">
			                    <label for="city">City *</label>
			                    <input type="text" name="city" class="form-control" id="city" placeholder="City" autocomplete="off" required="true">
			                </div>
			                <div class="form-group">
			                    <label for="postcode">Post Code *</label>
			                    <input type="text" name="postcode" class="form-control" id="postcode" placeholder="Post Code" autocomplete="off"  required="true">
			                </div>
			                <div class="form-group">
			                    <label for="state">State *</label>
			                    <input type="text" name="state" class="form-control" id="state" placeholder="State" autocomplete="off"  required="true">
			                </div>
						</div>
					</div>
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