<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <div class="icon fa fa-paper-plane"></div>
                    <div class="title"><?php echo $application_name; ?></div>
                </a>
                <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                    <i class="fa fa-times icon"></i>
                </button>
            </div>
            <ul class="nav navbar-nav">
                <li class="<?php echo ($this->uri->segment(2) == "dashboard") ? "active" : ""; ?>">
                    <a href="<?php echo site_url('app/dashboard'); ?>">
                        <span class="icon fa fa-tachometer"></span><span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="panel panel-default dropdown <?php echo ($this->uri->segment(2) == "invoice") ? "active" : ""; ?>">
                    <a data-toggle="collapse" href="#dropdown-element">
                        <span class="icon fa fa-clipboard"></span><span class="title">Invoice</span>
                    </a>
                    <!-- Dropdown level 1 -->
                    <div id="dropdown-element" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo site_url('app/invoice/'); ?>">Billing Statement</a></li>
                                <li><a href="<?php echo site_url('app/invoice/order'); ?>">Order List</a></li>                               
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == "products") ? "active" : ""; ?>">
                    <a href="<?php echo site_url('app/products'); ?>">
                        <span class="icon fa fa-tags"></span><span class="title">Products</span>
                    </a>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == "categories") ? "active" : ""; ?>">
                    <a href="<?php echo site_url('app/categories'); ?>">
                        <span class="icon fa fa-list"></span><span class="title">Categories</span>
                    </a>
                </li>
                <li class="panel panel-default dropdown <?php echo ($this->uri->segment(2) == "users") ? "active" : ""; ?>">
                    <a data-toggle="collapse" href="#dropdown-form">
                        <span class="icon fa fa-users"></span><span class="title">Accounts</span>
                    </a>
                    <!-- Dropdown level 1 -->
                    <div id="dropdown-form" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo site_url('app/users'); ?>">Users</a></li>
                                <li><a href="<?php echo site_url('app/users/customer'); ?>">Customers</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                
            
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
</div>