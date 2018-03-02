<script type="text/javascript">
    $(document).on('change' , '#profile_image' , function(){
        readURL(this , ".image-preview" , 'background');
    });
    $(document).on("click" , ".btn-delete-user" , function(){

        var c = confirm("Are you sure?");

        if(c == true){
            window.location.href = $(this).data("href");
        }
    });
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container" >
        	<a href="<?php echo site_url('app/users'); ?>" style="display:inline-block;position: relative;left: -10px;"><i class="fa fa-arrow-left fa-3x"  aria-hidden="true"></i> </a> <h1 style="display:inline-block;">Update User</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-8 no-margin-bottom text-left">
                        <a href="javascript:void(0);" data-href="<?php echo site_url('app/users/delete_user/'.$user_info->user_id);?>" class="btn btn-danger btn-sm btn-delete-user">Delete User</a>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <a href="javascript:void(0);" class="btn btn-success btn-same-size submit-form" data-form="#form_users">Save</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container ">
            <form action="<?php echo site_url("app/users/edit/user/".$user_info->user_id);?>" method="post" enctype="multipart/form-data" id="form_users">
                <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
                <section class="sec_border_bottom">
                    <h3>Profile</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>Personal and contact information for this user.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" value="<?php echo $user_info->username; ?>"  class="form-control" placeholder="Username" readonly>
                            </div>
                            <div class="form-group">
                                <label for="display_name">Display Name</label>
                                <input type="text" name="display_name" id="display_name" value="<?php echo $user_info->name; ?>"  class="form-control" placeholder="Display Name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email"  value="<?php echo $user_info->email; ?>"  class="form-control" placeholder="name@email.com" readonly>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Profile Image</label>
                                <div class="preview-image">
                                    <img src="<?php echo site_url("thumbs/images/user/$user_info->image_path/150/150/$user_info->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom">
                                </div>
                                <input type="file" name="file" id="profile_image" class="btn btn-default">
                            </div>
                        </div>
                    </div>
                </section>
                <section class="sec_border_bottom">
                    <h3>Role</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>A role defines what this user can see and do.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" name="role">
                                    <option  <?php echo ($user_info->account_type == "DRIVER") ? "selected" : "" ; ?> value="DRIVER">Driver</option>
                                    <option <?php echo ($user_info->account_type == "ADMIN") ? "selected" : "" ; ?> value="ADMIN">Admin</option>
                                </select>
                            </div>
                            
                        </div>
                    </div>
                </section>
                <section class="sec_border_bottom">
                    <div class="text-right margin-bottom">
                        <a href="javascript:void(0);" class="btn btn-success btn-same-size submit-form" data-form="#form_users">Save</a>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>