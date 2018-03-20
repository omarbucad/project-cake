<script type="text/javascript">
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
<div class="container margin-bottom">
    <div class="side-body padding-top">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('app/products'); ?>">Products</a></li>
            <li><a href="<?php echo site_url('app/products/price'); ?>">Price Group</a></li>
            <li class="active">Update Group</li>
        </ol>   
        <h3>Update Price Group</h3>
        <form class="form-horizontal" action="<?php echo site_url("app/products/update_group/").$this->hash->encrypt($info->price_book_id); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Details</div>
                    </div>
                </div>
                <div class="card-body ">
                    <dl class="dl-horizontal text-left col-lg-8">
                        <dt>Group Name</dt>
                        <dd>
                            <div class="form-group">
                                <input type="text" name="category_name" value="<?php echo $info->group_name; ?>" class="form-control">
                            </div>
                        </dd>
                        <dt>Status</dt>
                        <dd>
                            <div class="form-group">
                                <select name="category_status" class="form-control">
                                    <option value="1" <?php echo ($info->status == 1) ? "active" : "" ;?>>Active</option>
                                    <option value="0" <?php echo ($info->status == 0) ? "active" : "" ;?>>Inactive</option>
                                </select>
                            </div>
                        </dd>
                    </dl>
                    <table class="table my-table">
                        <thead>
                            <tr>
                                <th width="35%">Name</th>
                                <th width="35%">Standard Price</th>
                                <th width="30%">Custom Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($info->products) : ?>
                                <?php foreach($info->products as $key => $row) : ?>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-6 col-lg-4 no-margin-bottom">
                                                    <img src="<?php echo site_url("thumbs/images/product/".$row->product_id->images[0]->image_path."/80/80/".$row->product_id->images[0]->image_name); ?>" class="img img-responsive thumbnail no-margin-bottom">
                                                </div>
                                                <div class="col-xs-6 col-lg-8 no-margin-bottom">
                                                    <a href="<?php echo site_url("app/products/product_info/".$row->product_id->product_id);?>"><?php echo $row->product_id->product_name; ?></a><br>
                                                </div>
                                            </div>
                                        </td>
                                        <td ><span ><?php echo $row->product_id->price; ?></span></td>
                                        <td >
                                            <div class="input-group">
                                              <span class="input-group-addon" id="basic-addon1">RM</span>
                                              <input type="text" class="form-control" name="product_id[<?php echo $row->product_id->product_id; ?>]" placeholder="Price" step="0.01" value="<?php echo $row->price; ?>" aria-describedby="basic-addon1">
                                            </div>
                                        </td>
                                                                        
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr class="customer-row">
                                    <td colspan="4" class="text-center"><span>No Result</span></td>
                                </tr>
                            <?php endif; ?>
                           
                        </tbody>
                    </table>

                </div>
            </div>


            

            <div class="text-right margin-bottom">
                <a href="<?php echo site_url('app/products/price');?>" class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Save" class="btn btn-success">
            </div>
        </form>
    </div>
</div>