<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container">
        	<h1>Customer</h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/users/add_customer"); ?>" class="btn btn-success ">Add Customer</a>
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

                            <div class="col-xs-12 col-lg-3 col-lg-offset-6 text-right">
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
                        <th width="20%">Name</th>
                        <th width="20%">Email</th>
                        <th width="45%">Address</th>
                        <th width="10%">Status</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $key => $row) : ?>
                        <tr>
                            <td><span><?php echo $row->display_name; ?></span></td>
                            <td><span><?php echo $row->email; ?></span></td>
                            <td><span><?php echo $row->street1; ?></span></td>
                            <td><span><?php echo $row->status; ?></span></td>
                            <td><span><a href="#">Edit</a></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
