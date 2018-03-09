<script type="text/javascript">
	$(document).ready(function(){
		$("div.company").addClass("hidden");
		$('#fullname').attr("required", "true");
	});
	$(document).on("click","input#personal" , function(){
		$("div.company").addClass("hidden");
		$("div.personal").removeClass("hidden");
		$("#company_name").removeAttr("required");
        $("#manager_name").removeAttr("required");
        $("#fullname").attr("required","true");
	});
	$(document).on("click","input#company" , function(){
		$("div.company").removeClass("hidden");
		$("div.personal").addClass("hidden");
        $("#fullname").removeAttr("required");
		$("#company_name").attr("required","true");
        $("#manager_name").attr("required","true");
	});
	$(document).on("click","input#same_address" , function(){

	});
</script>
<style type="text/css">
	input[type=radio], input[type=checkbox]{
		cursor: pointer;
	}
	.radio-group input[type=radio]{
		margin-right: 5px;
	}
	.radio-group label{
		margin-right: 10px;
	}

</style>
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

	<h2 class="page-header">REGISTER</h2>
			
	<div class="row">
		<form action="<?php echo site_url("login/register"); ?>" method="POST">
		<div class="col-lg-12 form-group radio-group">
			<label class="group-label">Account type:</label>
			<input type="radio" name="account_type" id="personal" checked="checked" value="personal"><label>Personal</label>
			<input type="radio" name="account_type" id="company" value="company"><label>Company</label>
		</div>
		

			<div class="col-lg-6 col-xs-12">
				<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">Personal Information</div>
						<div class="panel-body" id="">
							<div class="form-group company">
								<label for="name">Manager Name *</label>
								<input type="text" name="manager_name" class="form-control" placeholder="Manager Name" id="manager_name" autocomplete="off">
							</div>
							<div class="form-group personal">
								<label for="name">Full Name *</label>
								<input type="text" name="fullname" class="form-control" placeholder="Full Name" id="fullname" autocomplete="off">
							</div>
							<div class="form-group company">
								<label for="company_name">Company Name *</label>
								<input type="text" name="company_name" class="form-control" placeholder="Company Name" id="company_name" autocomplete="off">
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
								<input type="password" name="password" class="form-control" placeholder="Password" id="password" required="true" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="confirm_password">Confirm Password *</label>
								<input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" id="confirm_password" required="true" autocomplete="off">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-xs-12">
				<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">Billing Address *</div>
						<div class="panel-body collapse in" id="billing-address-div">
							<div class="form-group">
			                    <label for="street1">Street 1 *</label>
			                    <input type="text" name="street1" class="form-control" id="billing_street1" placeholder="Street 1" autocomplete="off" required="true">
			                </div>
			                <div class="form-group">
			                    <label for="street2">Street 2 *</label>
			                    <input type="text" name="street2" class="form-control" id="billing_street2" placeholder="Street 2" autocomplete="off" required="true">
			                </div>
			                <div class="form-group">
			                    <label for="suburb">Suburb *</label>
			                    <input type="text" name="suburb" class="form-control" id="billing_suburb" placeholder="Suburb" autocomplete="off" required="true">
			                </div>
			                <div class="form-group">
			                    <label for="city">City *</label>
			                    <input type="text" name="city" class="form-control" id="billing_city" placeholder="City" autocomplete="off" required="true">
			                </div>
			                <div class="form-group">
			                    <label for="postcode">Post Code *</label>
			                    <input type="text" name="postcode" class="form-control" id="billing_postcode" placeholder="Post Code" autocomplete="off"  required="true">
			                </div>
			                <div class="form-group">
			                    <label for="state">State *</label>
			                    <input type="text" name="state" class="form-control" id="billing_state" placeholder="State" autocomplete="off"  required="true">
			                </div>
						</div>
					</div>
				</div>
			</div>
				
			<div class="col-lg-4 col-lg-offset-4 text-center">
				<input type="submit" name="submit" class="btn btn-primary" value="Submit">
			</div>
		</form>
	</div>
</div>