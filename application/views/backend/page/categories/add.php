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
            <li><a href="<?php echo site_url('app/products'); ?>">Category</a></li>
            <li class="active">Add Category</li>
        </ol>   
        <h3>New Category</h3>
        <form class="form-horizontal" action="<?php echo site_url("app/categories/add_category"); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Details</div>
                    </div>
                </div>
                <div class="card-body col-lg-8">
                    <dl class="dl-horizontal text-left">
                        <dt>Category Name</dt>
                        <dd>
                            <div class="form-group">
                                <input type="text" name="category_name" class="form-control">
                            </div>
                        </dd>
                        <dt>Category Status</dt>
                        <dd>
                            <div class="form-group">
                                <select name="category_status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </dd>
                    </dl>
    
                </div>
            </div>



            <div class="text-right margin-bottom">
                <a href="javascript:void(0);" class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Save" class="btn btn-success">
            </div>
        </form>
    </div>
</div>