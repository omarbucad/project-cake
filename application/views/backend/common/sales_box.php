<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
       <span><h5><?php echo $card_info["day"]["current"]["date"]; ?></h5></span>
           <a href="#" id="panel-today">
            <div class="card red summary-inline">
                <div class="card-body">
                    <i class="icon fa fa-tags fa-4x"></i>
                    <div class="content">
                        <div class="title"><?php echo $card_info["day"]["current"]["sales"]; ?></div>
                        <div class="sub-title">Today's Sale</div>
                        <span></span>
                    </div>
                    <div style="overflow: hidden;margin-top: 10px;">
                        <span class="pull-left"><?php echo $card_info["day"]["previous"]["sales"]; ?> ( Previous )</span>
                        <span class="pull-right"><?php echo $card_info["day"]["previous"]["date"]; ?></span>
                    </div>
                    <div class="clear-both"></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
       <span><h5><?php echo $card_info["week"]["current"]["date"]; ?></h5></span>
           <a href="#" id="panel-week">
            <div class="card yellow summary-inline">
                <div class="card-body">
                    <i class="icon fa fa-truck fa-4x"></i>
                    <div class="content">
                        <div class="title"><?php echo $card_info["week"]["current"]["sales"]; ?></div>
                        <div class="sub-title">Weekly Sales</div>
                    </div>
                    <div style="overflow: hidden;margin-top: 10px;">
                        <span class="pull-left"><?php echo $card_info["week"]["previous"]["sales"]; ?> ( Previous )</span>
                        <span class="pull-right"><?php echo $card_info["week"]["previous"]["date"]; ?></span>
                    </div>
                    <div class="clear-both"></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
       <span><h5><?php echo $card_info["month"]["current"]["date"]; ?></h5></span>
           <a href="#" id="panel-month">
            <div class="card green summary-inline">
                <div class="card-body">
                    <i class="icon fa fa-check-circle fa-4x"></i>
                    <div class="content">
                        <div class="title"><?php echo $card_info["month"]["current"]["sales"]; ?></div>
                        <div class="sub-title">Monthly Sales</div>
                    </div>
                    <div style="overflow: hidden;margin-top: 10px;">
                        <span class="pull-left"><?php echo $card_info["month"]["previous"]["sales"]; ?> ( Previous )</span>
                        <span class="pull-right"><?php echo $card_info["month"]["previous"]["date"]; ?></span>
                    </div>
                    <div class="clear-both"></div>
                </div>
            </div>
        </a>
    </div>
</div>