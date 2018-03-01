<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container" >
        	<a href="<?php echo site_url('app/users/customer'); ?>" style="display:inline-block;position: relative;left: -10px;"><i class="fa fa-arrow-left fa-3x"  aria-hidden="true"></i> </a> <h1 style="display:inline-block;"> Update Customer</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <a href="javascript:void(0);" class="btn btn-success btn-same-size submit-form" data-form="#form_users">Save</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container ">
            <form action="<?php echo site_url("app/users/edit/customer/".$customer_info->customer_id);?>" method="post" enctype="multipart/form-data" id="form_users">
                <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
                <section class="sec_border_bottom">
                    <h3>Profile</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>Personal and contact information for this user.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
                                <label for="display_name">Manager Name</label>
                                <input type="text" name="display_name" id="display_name" value="<?php echo $customer_info->display_name; ?>" class="form-control" placeholder="Manager Name">
                            </div>
                            <div class="form-group">
                                <label for="company_name">Company Name</label>
                                <input type="text" name="company_name" id="company_name" value="<?php echo $customer_info->company_name; ?>"  class="form-control" placeholder="Company Name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="<?php echo $customer_info->email; ?>" class="form-control" placeholder="name@email.com" readonly>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control"> value="<?php echo $customer_info->status; ?>">
                                	<option <?php echo ($customer_info->status == 1) ? "selected" : "" ; ?> value="1">Active</option>
                                	<option <?php echo ($customer_info->status == 0) ? "selected" : "" ; ?> value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="sec_border_bottom">
                    <h3>Address</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>A customer address defines where we will deliver goods.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                        	<input type="hidden" name="physical_address_id" value="<?php echo $customer_info->physical_address_id; ?>">
                            <div class="form-group">
                                <label>Street 1</label>
                                <input type="text" name="physical[street1]" value="<?php echo $customer_address->street1; ?>" class="form-control" required="true">
                            </div>
                            <div class="form-group">
                                <label>Street 2</label>
                                <input type="text" name="physical[street2]" value="<?php echo $customer_address->street2; ?>" class="form-control" required="true">
                            </div>
                            <div class="form-group">
                                <label>Suburb</label>
                                <input type="text" name="physical[suburb]" value="<?php echo $customer_address->suburb; ?>" class="form-control" required="true">
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="physical[city]" value="<?php echo $customer_address->city; ?>" class="form-control" required="true">
                            </div>
                            <div class="form-group">
                                <label>Post Code</label>
                                <input type="text" name="physical[postcode]" value="<?php echo $customer_address->postcode; ?>" class="form-control" required="true">
                            </div>
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" name="physical[state]" value="<?php echo $customer_address->state; ?>" class="form-control" required="true">
                            </div>
                        </div>
                    </div>
                </section>
                <section class="sec_border_bottom">
                    <div class="text-right margin-bottom">
                        <a href="javascript:void(0);" class="btn btn-success btn-same-size submit-form" data-form="#form_users">Save</a>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>