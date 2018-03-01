<script type="text/javascript">
    $(document).ready(function(){
            $("div.thumbnail-list-item").click(function(){
            var url = $(this).css("background-image");
            var previous = $("div#primary-image").css("background-image");
            $("div#primary-image").css("background-image",url);
            $(this).css("background-image",previous);
        });
    });
    
</script>

<style type="text/css">
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
                        <?php foreach($result->images as $key => $value):?>
                            <?php if($value->primary_image == 1) {?>
                            <div class="thumbnail-list-item" id="primary-image" style="background-image: url('<?php echo site_url("thumbs/images/product/".$value->image_path."/150/150/".$value->image_name); ?>'); background-repeat: no-repeat; background-position: center; position: relative; background-size: contain; height: 100%;    width: auto; min-height: 200px; min-width: 200px; margin-right: 20px;margin-bottom: 20px;">
                            </div>
                            <?php } else{?>
                            <div class="thumbnail-list-item" style="background-image: url('<?php echo site_url("thumbs/images/product/".$value->image_path."/150/150/".$value->image_name); ?>'); background-repeat: no-repeat; background-position: center; position: relative; background-size: contain; height: 100%;    width: auto; min-height: 65px; min-width: 65px; margin-right: 15px; display: inline-block;cursor: pointer;">
                            </div>
                            <?php }?>
                        <?php endforeach; ?>
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
            <div class="text-right margin-bottom">
                <a href="<?php echo site_url("app/products"); ?>" class="btn btn-primary">Back to Products List</a>
            </div>
        </section>
    </div>
</div>