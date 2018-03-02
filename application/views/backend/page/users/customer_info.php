<style type="text/css">
    .detail-content{
        margin-top: 25px;
        text-align: left;
        margin-bottom: 25px;
    }
</style>
<div class="container-fluid margin-bottom">
   
    <div class="side-body padding-top">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url('app/products'); ?>">Customers</a></li>
                <li class="active">Customer Details</li>
            </ol>
        </div>
        
        <section class="container">
                
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <h3><?php echo $customer_info->display_name; ?></h3>
                </div>
                <div class="col-lg">
                    <div class="panel-group detail-content">
                        <div class="panel panel-default">
                            <div class="panel-heading">Details</div>
                            <div class="panel-body">
                                <p><label>Company Name: </label> <?php echo $customer_info->company_name; ?></p>
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
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                        <a href="#">
                                            <div class="card blue summary-inline">
                                                <div class="card-body">
                                                    <div class="content">
                                                        <div class="title"><?php echo $customer_order["total_price"]; ?></div>
                                                        <div class="sub-title">Total Price</div>
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
            <div class="text-right margin-bottom">
                <a href="<?php echo site_url("app/users"); ?>" class="btn btn-primary">Back to Users List</a>
            </div>
        </section>
    </div>
</div>