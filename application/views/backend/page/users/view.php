<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container">
        	<h1>Users</h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/users/add"); ?>" class="btn btn-success ">Add User</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("app/users/"); ?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get("name")?>" id="s_name" placeholder="Search by username or name">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Roles</label>
                                    <select class="form-control" id="s_roles" name="roles">
                                        <option value="">All Roles</option>
                                        <option value="DRIVER" <?php echo ($this->input->get("roles") == "DRIVER") ? "selected" : ""; ?>>Driver</option>
                                        <option value="ADMIN" <?php echo ($this->input->get("roles") == "ADMIN") ? "selected" : ""; ?>>Admin</option>
                                    </select>
                                </div>
                            </div>
                             <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Status</label>
                                    <select class="form-control" id="s_roles" name="status">
                                        <option value="">- Select Status-</option>
                                        <option value="ACTIVE" <?php echo ($this->input->get("status") == "ACTIVE") ? "selected" : ""; ?>>Active</option>
                                        <option value="INACTIVE" <?php echo ($this->input->get("status") == "INACTIVE") ? "selected" : ""; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 text-right">
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
                        <th width="10%">Role</th>
                        <th width="10%">Status</th>
                        <th width="55%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-xs-6 col-lg-4 no-margin-bottom">
                                            <img src="<?php echo site_url("thumbs/images/user/$row->image_path/80/80/$row->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom">
                                        </div>
                                        <div class="col-xs-6 col-lg-8 no-margin-bottom">
                                            <a href="<?php echo site_url("app/users/view_user_info/$row->user_id");?>"><?php echo $row->username; ?> ( <?php echo $row->name; ?> )</a><br>
                                            <small class="help-block"><?php echo $row->email; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td ><span ><?php echo $row->account_type; ?></span></td>
                                <td ><span ><?php echo $row->status; ?></span></td>
                                <td class="text-right"><span><a href="<?php echo site_url("app/users/edit/user/$row->user_id"); ?>">Edit</a></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="customer-row">
                            <td colspan="4" class="text-center"><span>No Result</span></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="pull-right">
                <nav aria-label="Page navigation">
                  <?php echo $links; ?>
              </nav>
          </div>
        </div>
    </div>
</div>
