<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container">
        	<h1>Products</h1>
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
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="#" method="POST">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Name</label>
                                    <input type="text" name="name" class="form-control" id="s_name" placeholder="Search by username or name">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Roles</label>
                                    <select class="form-control" id="s_roles">
                                        <option>All Roles</option>
                                        <option>Cashier</option>
                                        <option>Manager</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 col-lg-offset-3 text-right">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                            </div>
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
        <div class="container ">
            <table class="table my-table">
                <thead>
                    <tr>
                        <th width="25%">Name</th>
                        <th width="40%">Description</th>
                        <th width="10%">Position</th>
                        <th width="10%">Status</th>
                        <th width="15%"></th>
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
                                        <small><strong> RM <?php echo $row->price; ?></strong> </small>
                                    </div>
                                </div>
                            </td>
                            <td ><span ><?php echo $row->product_description; ?></span></td>
                            <td ><span ><?php echo $row->product_position; ?></span></td>
                            <td ><span ><?php echo $row->status; ?></span></td>
                            <td class="text-right"><span><a href="#">Edit</a></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
