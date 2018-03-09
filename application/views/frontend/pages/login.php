
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
	<div class="row">
		<div class="col-lg-4 col-xs-12 col-lg-offset-4">
			<form action="<?php echo site_url("login/?action=login"); ?>" method="POST">
				<div class="panel-group" style="margin-top: 20px">
					<div class="panel panel-default">
						<div class="panel-heading text-center">LOGIN</div>
						<div class="panel-body">
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
								<a href="<?php echo site_url('login/forgot_password');?>">Lost your Password?</a>
							</div>
							<div class="checkbox">
							    <label>
							      <input type="checkbox" name="remember_me"> Remember Me
							    </label>
							</div>
							<hr>
							<div class="text-center">
								<small><p class="help-block">Not Registered? <a href="<?php echo site_url('login/register');?>">Click Here</a>.</p></small>
								
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>