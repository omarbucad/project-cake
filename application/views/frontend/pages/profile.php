<script type="text/javascript">
	$(document).on("click" , ".save" , function(){
		var form = $(this).closest(".modal").find("form");
		form.submit();
	});
	$(document).on("click" , "#edit-profile" , function(){
		var account_type = "<?php echo $profile_information->account_type; ?>";

		if($('div.account-details').hasClass("panel-default")){
			$('div.account-details').removeClass("panel-default");
			$('div.account-details').addClass("panel-primary");
			$(this).text("Submit");
			$(this).removeClass("btn-primary");
			$('#cancel-edit').removeClass("hidden");
			$(this).addClass("btn-success");


			if(account_type == "PERSONAL"){
				$('div.personal-inf input').attr("required", "true");
				$('div.info input').attr("required");

				$('div.personal-inf input').removeAttr("readonly");
				$('div.info input').removeAttr("readonly");
			}
			else{
				$('div.company-inf input').attr("required", "true");
				$('div.info input').attr("required", "true");

				$('div.company-inf input').removeAttr("readonly");
				$('div.info input').removeAttr("readonly");
			}
		}
		else{
			if($(this).hasClass('btn-success')){
				var c = confirm("Are you sure?");

		        if(c == true){
		            $('#form_details').submit();
		        }
			}
		}
	});

	$(document).on("click" , "#cancel-edit" , function(){
		var account_type = "<?php echo $profile_information->account_type; ?>";

		$('div.account-details').addClass("panel-default");
			$('div.account-details').removeClass("panel-primary");
			$('#edit-profile').text("Edit");
			$('#edit-profile').prepend("<i class='fa fa-edit'></i>");
			$('#edit-profile').removeClass("btn-success");
			$(this).addClass("hidden");
			$('#edit-profile').addClass("btn-primary");

			if(account_type == "PERSONAL"){
				$('div.personal-inf input').removeAttr("required");
				$('div.info input').removeAttr("required");

				$('div.personal-inf input').attr("readonly","true");
				$('div.info input').attr("readonly","true");
			}
			else{
				$('div.company-inf input').removeAttr("required");
				$('div.info input').removeAttr("required");

				$('div.company-inf input').attr("readonly","true");
				$('div.info input').attr("readonly","true");
			}
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
				</div>
			</section>
		</div>
		<div class="col-lg-8">
			<h2 class="page-header">MY ACCOUNT</h2>
			<p class="help-block hidden">Hello <?php echo $this->session->userdata("customer")->display_name; ?> (not <?php echo $this->session->userdata("customer")->display_name; ?>? <a href="<?php echo site_url("login/logout");?>">Sign out</a>). From your account dashboard you can view your recent orders, manage your addresses and <a href="<?php echo site_url("profile/edit"); ?>">edit your password and account details</a>.</p>

			<?php if(validation_errors()) : ?>
				<div id="alert_container_remove">
					<div class="side-body padding-top">
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closed_alert"><span aria-hidden="true">×</span></button>
							<?php echo validation_errors(); ?>
						</div>
					</div>
				</div>
			<?php else : ?>
				<div id="alert_container_remove">
					<div class="side-body padding-top">
						<?php if($this->session->userdata("status") == 'success') : ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closed_alert"><span aria-hidden="true">×</span></button>
							<?php echo $this->session->userdata("message"); ?>
						</div>
						<?php elseif($this->session->userdata("status") == 'error') : ?>
							<div class="alert alert-danger alert-dismissible fade in" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closed_alert"><span aria-hidden="true">×</span></button>
								<?php echo $this->session->userdata("message"); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="profile">
				<div class="panel-group">
					<div class="panel panel-default account-details">
						<div class="panel-heading">ACCOUNT DETAILS 
							<span class="pull-right"><a class="btn btn-xs btn-danger hidden" id="cancel-edit">Cancel</a> <a class="btn btn-xs btn-primary" id="edit-profile"><i class="fa fa-edit"></i> Edit</a></span></div>
						<div class="panel-body">
							<form action="<?php echo site_url('profile/edit_profile/').$this->session->userdata('customer')->customer_id;?>" method="POST" id="form_details">
								<?php if($profile_information->account_type == 'PERSONAL') : ?>
									<div class="form-group personal-inf">
										<label for="fullname">Full Name</label>
										<input type="text" class="form-control" name="fullname" value="<?php echo $profile_information->display_name; ?>" readonly="true">
									</div>
								<?php else : ?>
									<div class="form-group company-inf">
										<label for="manager_name">Manager Name</label>
										<input type="text" class="form-control" name="manager_name" value="<?php echo $profile_information->display_name; ?>" readonly="true">
									</div>
									<div class="form-group company-inf">
										<label for="company_name">Company Name</label>
										<input type="text" class="form-control" name="company_name" value="<?php echo $profile_information->company_name; ?>" readonly="true" >
									</div>
								<?php endif; ?>

								<div class="form-group">
									<label for="email">Email Address</label>
									<input type="email" class="form-control" name="email" value="<?php echo $profile_information->email; ?>" readonly="true">
								</div>
								<div class="form-group info">
									<label for="phone_number">Phone Number</label>
									<input type="text" class="form-control" name="phone_number" value="<?php echo $profile_information->phone_number; ?>" readonly="true">
								</div>
							</form>
						</div>
					</div>
				</div>
			<div class="row">
				<div class="panel-group col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">MY ADDRESS <span class="pull-right"><button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i> Edit</button></span></div>
						<div class="panel-body">
							<div class="address">
								<p class="help-block">The following addresses will be used on the checkout page by default.</p>
								
								<address>
									<?php echo $this->session->userdata("customer")->display_name; ?><br>
									<?php echo $profile_information->street1; ?>, <?php echo $profile_information->street2; ?>,<br>
									<?php echo $profile_information->suburb; ?>, <?php echo $profile_information->city; ?>,<br>
									<?php echo $profile_information->postcode; ?>, <?php echo $profile_information->state; ?>,
									
								</address>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-group col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">CHANGE PASSWORD</div>
						<div class="panel-body">
							<form action="<?php echo site_url('profile');?>" method="POST">
								<div class="form-group">
                                    <label for="password">New Password *</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password" id="password" required="true" autocomplete="off">
                                </div>
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label for="confirm_password">Confirm New Password *</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" id="confirm_password" required="true" autocomplete="off">
                                </div>
                                <div class="form-group text-center" style="margin-bottom: 8px;">
                                    <input type="submit" name="submit" class="btn btn-primary btn-block" value="SUBMIT" onclick="return confirm('Are you sure')">
                                </div>
							</form>
						</div>
					</div>
				</div>
			</div>
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
		      		<dt>Street 1 *</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[street1]" class="form-control" required="true" value="<?php echo $profile_information->street1; ?>">
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
		      		<dt>City *</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[city]" class="form-control"  required="true" value="<?php echo $profile_information->city; ?>">
		      			</div>
		      		</dd>
		      		<dt>Postcode</dt>
		      		<dd>
		      			<div class="form-group">
		      				<input type="text" name="physical[postcode]" class="form-control"  required="true" value="<?php echo $profile_information->postcode; ?>">
		      			</div>
		      		</dd>
		      		<dt>State *</dt>
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