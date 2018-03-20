<script type="text/javascript">
    $(document).on("click" , ".btn-delete-customer" , function(){

        var c = confirm("Are you sure?");

        if(c == true){
            window.location.href = $(this).data("href");
        }
    });

    $(document).ready(function(){
        var type = "<?php echo $customer_info->account_type; ?>";
        if(type == "PERSONAL"){
            $("div.company").addClass("hidden");
            $('#fullname').attr("required", "true");
        }
        else{
            $("div.personal").addClass("hidden");
            $('#company_name').attr("required", "true");
            $('#manager_name').attr("required", "true");
        }
        
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
                    <div class="col-xs-12 col-lg-8 no-margin-bottom text-left">
                        <a href="javascript:void(0);" data-href="<?php echo site_url('app/users/delete_customer/'.$customer_info->customer_id);?>" class="btn btn-danger btn-sm btn-delete-customer">Delete Customer</a>
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
                    <h3>Account Type</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>Customer Account Details.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group radio-group">
                                <input type="radio" name="account_type" id="personal" <?php echo ($customer_info->account_type == 'PERSONAL') ? "checked='checked'" : ""; ?> value="PERSONAL"><label>Personal</label>
                                <input type="radio" name="account_type" id="company" <?php echo ($customer_info->account_type == 'COMPANY') ? "checked='checked'" : ""; ?> value="COMPANY"><label>Company</label>
                            </div>
                            
                        </div>
                    </div>
                </section>
                <section class="sec_border_bottom">
                    <h3>Profile</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>Customer Account Details.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">

                            <div class="form-group company">
                                <label for="name">Manager Name *</label>
                                <input type="text" name="manager_name" class="form-control" placeholder="Manager Name" id="manager_name" autocomplete="off" value="<?php echo $customer_info->display_name;?>">
                            </div>
                            <div class="form-group personal">
                                <label for="name">Full Name *</label>
                                <input type="text" name="fullname" class="form-control" placeholder="Full Name" id="fullname" autocomplete="off" value="<?php echo $customer_info->display_name;?>">
                            </div>
                            <div class="form-group company">
                                <label for="company_name">Company Name *</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Company Name" id="company_name" autocomplete="off"  value="<?php echo $customer_info->company_name;?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="<?php echo $customer_info->email; ?>" class="form-control" placeholder="name@email.com" readonly>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone number *</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" id="phone_number" required="true" autocomplete="off" value="<?php echo $customer_info->phone_number;?>">
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
                    <h3>Price Group</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>Customer Account Details.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
 
                                <select class="form-control" name="price_group">
                                    <?php foreach($price_group_list as $key => $row) : ?>
                                            <option value="<?php echo $row->price_book_id; ?>" <?php echo ($row->price_book_id ==  $customer_info->price_book_id) ? "selected" : "" ;?>><?php echo $row->group_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="sec_border_bottom">
                    <h3>Status</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>Customer Account Details.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
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
                                <input type="text" name="physical[street2]" value="<?php echo $customer_address->street2; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Suburb</label>
                                <input type="text" name="physical[suburb]" value="<?php echo $customer_address->suburb; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="physical[city]" value="<?php echo $customer_address->city; ?>" class="form-control" required="true">
                            </div>
                            <div class="form-group">
                                <label>Post Code</label>
                                <input type="text" name="physical[postcode]" value="<?php echo $customer_address->postcode; ?>" class="form-control">
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