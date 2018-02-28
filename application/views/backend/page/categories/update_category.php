<script type="text/javascript">
    $(document).on("click" , ".btn-delete-category" , function(){

        var c = confirm("Are you sure?");

        if(c == true){
            window.location.href = $(this).data("href");
        }
    });
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url('app/categories'); ?>">Category</a></li>
                <li class="active">Update Category</li>
            </ol>   
            <h3>Update Category</h3>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom text-left">
                        <a href="javascript:void(0);" data-href="<?php echo site_url('app/categories/delete_category/'.$result->category_id);?>" class="btn btn-danger btn-sm btn-delete-category">Delete Category</a>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/categories"); ?>" class="btn btn-primary">Cancel</a>
                        <input type="submit" name="submit" value="Update" class="btn btn-success">
                    </div>
                </div>
            </div>
        </div>

        <section class="container">
        <form class="form-horizontal" action="<?php echo site_url("app/categories/update_category/".$result->category_id); ?>" method="POST" enctype="multipart/form-data">
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
                                <input type="text" name="category_name" class="form-control" value="<?php echo $result->category_name;?>">
                            </div>
                        </dd>
                        <dt>Category Status</dt>
                        <dd>
                            <div class="form-group">
                                <select name="category_status" class="form-control">
                                    <option <?php echo ($result->status == 1) ? "selected" : ""; ?> value="1">Active</option>
                                    <option <?php echo ($result->status == 0) ? "selected" : ""; ?> value="0">Inactive</option>
                                </select>
                            </div>
                        </dd>
                    </dl>
    
                </div>
            </div>



            <div class="text-right margin-bottom">
                <a href="<?php echo site_url('app/categories');?>" class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Save" class="btn btn-success">
            </div>
        </form>
        </section>
    </div>
</div>