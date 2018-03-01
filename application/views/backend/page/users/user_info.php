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
                <li><a href="<?php echo site_url('app/products'); ?>">Users</a></li>
                <li class="active">User Details</li>
            </ol>
        </div>
        
        <section class="container">
                
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    
                </div>
                <div class="product-detail col-lg-3">
                    <div class="preview-image">
                        <img src="<?php echo site_url("thumbs/images/user/$user_info->image_path/150/150/$user_info->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom">
                    </div>
                    <p><small class="help-block"><?php echo $user_info->email;?></small></p>
                    <p><small class="help-block"><?php echo convert_timezone($user_info->created,true);?></small></p>

                    <p><?php echo convert_status($user_info->status);?></p>
                </div>
                <div class="col-lg-9">
                        <h3><?php echo $user_info->username; ?> (<?php echo $user_info->name;?>)</h3>
                        <div class="detail-content">
                            <legend><h5>Change Password </h5></legend>
                            <form  class="col-lg-6" action="<?php echo site_url("app/users/change_user_password/$user_info->user_id");?>" method="POST">
  
                                <div class="form-group">
                                    <label for="password">New Password *</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password" id="password" required="true" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password *</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" id="confirm_password" required="true" autocomplete="off">
                                </div>
                                <div class="text-left" style="margin-bottom: 20px;">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Confirm" onclick="return confirm('Are you sure')">
                                </div>
                            </form>
                        </div>
                </div>
            </div>
            <div class="text-right margin-bottom">
                <a href="<?php echo site_url("app/users"); ?>" class="btn btn-primary">Back to Users List</a>
            </div>
        </section>
    </div>
</div>