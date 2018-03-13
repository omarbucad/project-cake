<script type="text/javascript">
    $(document).ready(function(){
            $("div.thumbnail-list-item").click(function(){
            var url = $(this).css("background-image");
            var previous = $("div#primary-image").css("background-image");
            $("div#primary-image").css("background-image",url);
            $(this).css("background-image",previous);
        });
    });

    $(document).ready(function() {
        $('.animated-thumbnail').lightGallery({
            thumbnail:true
        });
    });
    
</script>

<style type="text/css">
    .daterangepicker.dropdown-menu {
        z-index: 100001 !important;
    }
    .lg-backdrop{
        z-index: 999999!important;
    }
    .lg-outer{
        z-index: 999999!important;
    }
    .product-detail, .detail-content{
        margin-top: 25px;
        text-align: left;
        margin-bottom: 25px;
    }
</style>
<div class="container-fluid margin-bottom">
   
    <div class="side-body padding-top">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url('app/products'); ?>">Product</a></li>
                <li class="active">Product Details</li>
            </ol>   
            <h3><?php echo $result->product_name?> <span class="pull-right"><?php echo $result->price; ?></span></h3>
        </div>
        
        <section class="container">
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    
                </div>
                <div class="product-detail text-left col-lg-3">
                    <div class="animated-thumbnail">
                        <?php foreach($result->images as $key => $value):?>
                            <?php if($value->primary_image == 1) {?>
                            <a href="<?php echo site_url("thumbs/images/product/".$value->image_path."/850/850/".$value->image_name); ?>">
                                <img src="<?php echo site_url("thumbs/images/product/".$value->image_path."/850/850/".$value->image_name); ?>" style="display: none">
                                <div class="thumbnail-list-item" id="primary-image" style="background-image: url('<?php echo site_url("thumbs/images/product/".$value->image_path."/850/850/".$value->image_name); ?>'); background-repeat: no-repeat; background-position: center; position: relative; background-size: contain; height: 100%;    width: auto; min-height: 200px; min-width: 200px; margin-right: 20px;margin-bottom: 20px;">
                                </div>
                            </a>
                            <?php } else{?>
                            <a href="<?php echo site_url("thumbs/images/product/".$value->image_path."/850/850/".$value->image_name); ?>">
                                <img src="<?php echo site_url("thumbs/images/product/".$value->image_path."/850/850/".$value->image_name); ?>" style="display: none">
                                <div class="thumbnail-list-item" style="background-image: url('<?php echo site_url("thumbs/images/product/".$value->image_path."/850/850/".$value->image_name); ?>'); background-repeat: no-repeat; background-position: center; position: relative; background-size: contain; height: 100%;    width: auto; min-height: 65px; min-width: 65px; margin-right: 15px; display: inline-block;cursor: pointer;">
                                </div>
                            </a>
                            <?php }?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-body col-lg-9">
                    <div class="product-detail">
                        <h4>Description</h4>
                        <h5>
                            <?php foreach($category_list as $row) : ?>
                                <?php if($this->hash->decrypt($row->category_id) == $result->category_id) :?>
                                    <span><?php echo $row->category_name; ?> - </span>
                                <?php endif;?>
                            <?php endforeach; ?>
                             <?php echo convert_status($result->status);?>
                        </h5>

                        <div class="detail-content">
                            <p><?php echo $result->product_description ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                   <span><h5><?php echo $card_info["day"]["current"]["date"]; ?></h5></span>
                       <a href="#">
                        <div class="card red summary-inline">
                            <div class="card-body">
                                <i class="icon fa fa-cube fa-4x"></i>
                                <div class="content">
                                    <div class="title"><?php echo $card_info["day"]["current"]["sales"]; ?></div>
                                    <div class="sub-title">Today's Item</div>
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
                       <a href="#">
                        <div class="card yellow summary-inline">
                            <div class="card-body">
                                <i class="icon fa fa-cube fa-4x"></i>
                                <div class="content">
                                    <div class="title"><?php echo $card_info["week"]["current"]["sales"]; ?></div>
                                    <div class="sub-title">Weekly Item</div>
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
                       <a href="#">
                        <div class="card green summary-inline">
                            <div class="card-body">
                                <i class="icon fa fa-cube fa-4x"></i>
                                <div class="content">
                                    <div class="title"><?php echo $card_info["month"]["current"]["sales"]; ?></div>
                                    <div class="sub-title">Monthly Item</div>
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
            <div class="text-right margin-bottom">
                <a href="<?php echo site_url("app/products"); ?>" class="btn btn-primary">Back to Products List</a>
            </div>
        </section>
    </div>
</div>