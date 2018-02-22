<script type="text/javascript">
    $(document).on('click' , '#search' , function(){
        $('#search_form').submit();
    });
    $(document).ready(function(){
       setTimeout(function(){ $('.alert-success').fadeOut() }, 1000);
    });
</script>

<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <!-- session -->
        <?php if ($this->session->flashdata('message_name')) { ?>
            <div class="alert alert-success">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Success!</strong> Product Updated!
            </div>
        <?php  } ?>
        <!-- session -->
        <div class="container">
        	<h1>Products </h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/products/add"); ?>" class="btn btn-success ">Add Product</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="card-body no-padding-left no-padding-right">
               <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="" method="GET" id="search_form">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Name</label>
                                    <input type="text" name="product_name" <?php echo (!empty($_GET['product_name'])) ? 'value = '.$_GET['product_name'].'  ' : '' ?>  placeholder="Product Name" class="form-control " />
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Roles</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">--</option>
                                        <?php foreach($category_list as $row) : ?>
                                            <option value="<?php echo $this->hash->decrypt($row->category_id);?>" <?php echo (!empty($_GET['product_category']) ) ? ' selected="selected"' : '';?> ><?php echo $row->category_name;?></option>
                                        <?php endforeach; ?>    
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 col-lg-offset-3 text-right">
                                <a href="javascript:void(0);" class="btn btn-primary btn-vertical-center btn-same-size" id="search">Search</a>
                            </div>
                        </div>
                    </div>
                    <div class="container ">
                        <table class="table my-table">
                            <thead>
                                <tr>
                                    <th width="25%">Name</th>
                                    <th width="20%">Short Description</th>
                                    <th width="20%">Description</th>
                                    <th width="10%">Position</th>
                                    <th width="10%">Category</th>
                                    <th width="10%">Status</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($result as $key => $row) : ?>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-6 col-lg-4 no-margin-bottom">
                                                    <img src="<?php echo site_url("thumbs/images/product/".$row->images->image_path."/80/80/".$row->images->image_name); ?>" class="img img-responsive thumbnail no-margin-bottom">
                                                </div>
                                                <div class="col-xs-6 col-lg-8 no-margin-bottom">
                                                    <a href="1"><?php echo $row->product_name; ?></a><br>
                                                    <small><strong><?php echo $row->price; ?></strong> </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td ><span ><?php echo $row->short_description; ?></span></td>
                                        <td ><span ><?php echo $row->product_description; ?></span></td>
                                        <td ><span ><?php echo $row->product_position; ?></span></td>
                                        <td ><span ><?php echo $row->category_name; ?></span></td>
                                        <td ><span ><?php echo $row->status; ?></span></td>
                                        <td ><span><a href="<?php echo site_url("app/products/view_productsbyid/".$row->product_id); ?>" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
