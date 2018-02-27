<script type="text/javascript">
    $(document).on("click" , ".btn-add-more" , function(){
        var clone = $(this).parent().prev().clone();
        clone.find("input").val("");
        $(this).parent().before(clone);
        $(this).parent().prev().find("input").trigger("click");
    });
    $(document).on("click" , ".btn-remove-attachment" , function(){
        var count = $(this).closest("dd").find(".form-group").length;

        if(count != 1){
            $(this).closest(".form-group").remove();
        }else{
            $(this).closest(".form-group").find("input").val("");
        }
    });

    $(document).ready(function(){
        var c = $(".thumbnail-list-item").length;

        if(c == 1){
            $('div.thumbnail-list-item').find(".btn-remove-image").remove();
        }

    });
    $(document).on("click" , ".btn-remove-image" , function(){
        var image_id = $(this).data("id");
        var url = '<?php echo site_url('app/products/delete_product_image/');?>'+image_id;
        
        var $me = $(this);
        var c= confirm("Are you sure?");
        if(c == true){
            $.ajax({
                url : url ,
                method : "GET" ,
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    if(json.status){
                        $me.closest("div.thumbnail-list-item").remove();
                        $.notify("Successfully deleted image" , { className:  "success" , position : "top center"});
                        var c = $(".thumbnail-list-item").length;
                       
                        if(c == 1){
                            $('div.thumbnail-list-item').find(".btn-remove-image").remove();
                        }
                    }
                }
            });
        }
    });


    $(document).on("click" , ".btn-set-primary" , function(){
        var image_id = $(this).data("id");
        var url = '<?php echo site_url('app/products/set_primary_image/');?>'+image_id;
        
        var $me = $(this);
        var c= confirm("Are you sure?");
        if(c == true){
            $.ajax({
                url : url ,
                method : "GET" ,
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    if(json.status){
                        var c = $(".thumbnail-list-item").length;
                       
                        if(c == 1){
                            $('div.thumbnail-list-item').find(".btn-set-primary").remove();
                        }
                        location.reload();
                    }
                }
            });
        }
    });

    $(document).on("click" , ".btn-delete-product" , function(){

        var c = confirm("Are you sure?");

        if(c == true){
            window.location.href = $(this).data("href");
        }
    });

    $(document).on("click" , "#save" , function(){
        var form = $(this).closest(".modal").find("form");
        var modal = $(this).closest(".modal");
        $.ajax({
            url : form.attr("action"),
            method : form.attr("method"),
            data : form.serialize() ,
            success : function(response){
                var json = jQuery.parseJSON(response);

                $('#category_select').append($('<option>', {
                    value: json.id,
                    text: json.name,
                    selected : "selected"
                }));


                modal.modal("hide");
            }
        });
    });


</script>
<style type="text/css">
    img {
       
    }
</style>
<div class="container margin-bottom">

    <div class="side-body padding-top">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('app/products'); ?>">Product</a></li>
            <li class="active">New Product</li>
        </ol>   
        <h3>Update Product</h3>
        
        <form class="form-horizontal" action="<?php echo site_url("app/products/update_product"); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $result->product_id ?>">
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Details</div>
                    </div>
                    <a href="javascript:void(0);" data-href="<?php echo site_url('app/products/delete_product/'.$result->product_id);?>" class="btn btn-danger btn-sm btn-delete-product pull-right">Delete Product</a>
                </div>
                <div class="card-body">
                    <dl class="dl-horizontal text-left">
                        <dt>Product Name</dt>
                        <dd>
                            <div class="form-group">
                                <input type="text" name="product_name" class="form-control" value="<?php echo $result->product_name ?>">
                            </div>
                        </dd>
                        
                        <dt>Price</dt>
                        <dd>
                            <div class="form-group">
                                <input type="number" name="product_price" class="form-control" placeholder="0.00" value="<?php echo number_format($result->price , 2) ?>">
                            </div>
                        </dd>
                        <dt>Position</dt>
                        <dd>
                            <div class="form-group">
                                <input type="number" name="product_position" value="1" class="form-control">
                            </div>
                        </dd>
                        <dt>Category</dt>
                        <dd>
                            <div class="form-group">
                                <div class="input-group">
                                  <select class="form-control" name="category" id="category_select">
                                    <option value="">Select Category</option>
                                    <?php foreach($category_list as $row) : ?>
                                        <option value="<?php echo $row->category_id; ?>" <?php echo ($this->hash->decrypt($row->category_id) == $result->category_id ) ? ' selected="selected"' : '';?> ><?php echo $row->category_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" style="margin:0px;" type="button" data-toggle="modal" data-target="#myModal">+</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                        </dd>
                        <dt>Status</dt>
                        <dd>
                            <div class="form-group">
                                <select class="form-control" name="productstatus">
                                    <option  <?php echo ($result->status == 1) ? "selected" : "" ; ?> value="ACTIVE">Active</option>
                                    <option <?php echo ($result->status == 0) ? "selected" : "" ; ?> value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                        </dd>
                        <dt>Short Description</dt>
                        <dd>
                            <div class="form-group">
                                <input type="text" name="short_description" class="form-control" value="<?php echo $result->short_description ?>">
                            </div>
                        </dd>
                        <dt>Description</dt>
                        <dd>
                            <div class="form-group">
                                <textarea class="textarea" name="description"> <?php echo $result->product_description ?></textarea>
                            </div>
                        </dd>
                        <dt>Images</dt>
                        <dd>
                            <div class="row text-center">
                                <?php foreach($result->images as $key => $value):?>
                                <div class="col-lg-3 thumbnail-list-item" style="background-image: url('<?php echo site_url("thumbs/images/product/".$value->image_path."/80/80/".$value->image_name); ?>'); background-repeat: no-repeat; background-position: center; position: relative; background-size: contain; height: 100%;    width: auto; min-height: 200px; min-width: 200px; margin-right: 20px;">
                                    
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-remove-image" data-id="<?=$value->image_id;?>"style="margin-top: 90px;">Delete</a>
                                    <?php if($value->primary_image != 1):?>
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm btn-set-primary" data-id="<?=$value->image_id;?>"style="margin-top: 90px;">Make Primary</a>
                                    <?php endif;?>
                                
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </dd>
                        <dd>
                            <div class="form-group">
                                <div class="input-group">
                                  <input type="file" name="other_file[]" class="form-control">
                                  <span class="input-group-btn">
                                    <button class="btn btn-default btn-remove-attachment" type="button" style="margin:0px;">x</button>
                                  </span>
                                </div>
                            </div>
                            <div class="row text-right">
                                <a href="javascript:void(0);" class="btn btn-primary btn-add-more">Add More</a>
                            </div>
                        </dd>

                </dl>

            </div>
            <div class="text-right margin-bottom">
                <a href="javascript:void(0);" class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Update" class="btn btn-success">
            </div>
        </div>


    </form>
</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Category</h4>
    </div>
    <div class="modal-body">
        <form action="<?php echo site_url("app/products/add_category"); ?>" method="POST">
            <div class="form-group">
                <label for="category">Product Category</label>
                <input type="text" class="form-control" name="product_category">
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save">Save</button>
    </div>
</div>
</div>
</div>