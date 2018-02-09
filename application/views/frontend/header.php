<div class="navbar-wrapper">
  <div class="container">
    <nav class="navbar navbar-default navbar-static-top  text-uppercase">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo site_url("/"); ?>">
               <img alt="Brand" src="<?php echo site_url("public/img/GB.png"); ?>" width="150px;">
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse ">
          <ul class="nav navbar-nav navbar-left">
            <li class="dropdown">
              <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Shop <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo site_url("welcome/?shop_list=all"); ?>">All</a></li>
                <?php foreach($shop_list as $key => $row) : ?>
                  <li><a href="<?php echo site_url("welcome/?shop_list=$row->category_id"); ?>"><?php echo $row->category_name; ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if($this->session->userdata("customer")) : ?>
                <li><p class="navbar-text"></p></li>

                <li class="dropdown">
                  <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata("customer")->display_name; ?></a>
                  <ul class="dropdown-menu">
                    <li><a href="<?php echo site_url("profile"); ?>">My Account</a></li>
                    <li><a href="<?php echo site_url("order/"); ?>">My Orders</a></li>
                    <li><a href="<?php echo site_url("order/wishlist"); ?>">Wishlist</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo site_url("login/logout"); ?>">Logout</a></li>
                  </ul>
                </li>
            <?php else : ?>
                <li><a href="<?php echo site_url("login"); ?>">Login</a></li>
            <?php endif; ?>
            <li><a href="<?php echo site_url("cart"); ?>">Cart / RM 0.00 <span class="badge">0</span></a></li>
          </ul>
        </div>
      </div>
    </nav>

  </div>
</div>
