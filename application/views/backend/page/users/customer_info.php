<style type="text/css">
    .detail-content{
        margin-top: 25px;
        text-align: left;
        margin-bottom: 25px;
    }
</style>
<div class="container-fluid margin-bottom">
   
    <div class="side-body padding-top">
        <div class="container" >
            <a href="<?php echo site_url('app/users/customer'); ?>" style="display:inline-block;position: relative;left: -10px;"><i class="fa fa-arrow-left fa-3x"  aria-hidden="true"></i> </a> <h1 style="display:inline-block;"> <?php echo ($customer_info->account_type == "COMPANY") ? $customer_info->company_name : $customer_info->display_name; ?></h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/users/customer"); ?>" class="btn btn-success btn-same-size submit-form" >Back to Customers List</a>
                    </div>
                </div>
            </div>
        </div>
        <section class="container">
                
            <div class="card margin-bottom">
                <div class="card-body">
                    <div class="card-content">
                        <div class="panel-group detail-content">
                            <div class="panel panel-default">
                                <div class="panel-heading">Details</div>
                                <div class="panel-body">
                                    <?php if($customer_info->account_type == "COMPANY") :?>
                                    <p><label>Company Name: </label> <?php echo $customer_info->company_name; ?></p>
                                    <p><label>Manager Name: </label> <?php echo $customer_info->display_name; ?></p>
                                    <?php else : ?>
                                    <p><label>Full Name: </label> <?php echo $customer_info->display_name; ?></p>
                                    <?php endif; ?>
                                    <p><label>Email Address: </label> <?php echo $customer_info->email; ?></p>
                                    <p><label>Status: </label> <?php echo convert_status($customer_info->status); ?></p>
                                </div>
                                <div class="panel-heading">Address</div>
                                <div class="panel-body">
                                    <p><label>Street 1: </label> <?php echo $customer_address->street1; ?>
                                        <p><label>Street 2: </label> <?php echo $customer_address->street2; ?></p>
                                        <p><label>Suburb: </label> <?php echo $customer_address->suburb; ?></p>
                                        <p><label>City: </label> <?php echo $customer_address->city; ?></p>
                                        <p><label>Post Code: </label> <?php echo $customer_address->postcode; ?></p>
                                        <p><label>State: </label> <?php echo $customer_address->state; ?></p>
                                </div>
                                
                                <div class="panel-heading">Orders</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                            <a href="#">
                                                <div class="card red summary-inline">
                                                    <div class="card-body">
                                                        <i class="icon fa fa-tags fa-4x"></i>
                                                        <div class="content">
                                                            <div class="title"><?php echo $customer_order["total_orders"]; ?></div>
                                                            <div class="sub-title">Total Orders</div>
                                                        </div>
                                                        <div class="clear-both"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                            <a href="#">
                                                <div class="card yellow summary-inline">
                                                    <div class="card-body">
                                                        <i class="icon fa fa-truck fa-4x" style="transform: scaleX(-1);"></i>
                                                        <div class="content">
                                                            <div class="title"><?php echo $customer_order["on_delivery"]; ?></div>
                                                            <div class="sub-title">On-Delivery</div>
                                                        </div>
                                                        <div class="clear-both"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                            <a href="#">
                                                <div class="card green summary-inline">
                                                    <div class="card-body">
                                                        <i class="icon fa fa-check-circle fa-4x"></i>
                                                        <div class="content">
                                                            <div class="title"><?php echo $customer_order["delivered"]; ?></div>
                                                            <div class="sub-title">Delivered</div>
                                                        </div>
                                                        <div class="clear-both"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </div>
</div>