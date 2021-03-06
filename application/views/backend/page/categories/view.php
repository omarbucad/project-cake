<script type="text/javascript">
    $(document).on('click' , '#search' , function(){
        $('#search_form').submit();
    });

</script>

<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container">
        	<h1>Categories </h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/categories/add_category"); ?>" class="btn btn-success ">Add Category</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="card-body no-padding-left no-padding-right">
                <div class="container">
                    <div class="card-body no-padding-left no-padding-right">
                        <form action="<?php echo site_url("app/categories");?>" method="GET" id="search_form">
                            <div class="row">
                                <div class="col-xs-12 col-lg-3">
                                    <div class="form-group">
                                        <label for="s_name">Name</label>
                                        <input type="text" name="name" value="<?php echo $this->input->get("name")?>" placeholder="Category Name" class="form-control " />
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
                                <div class="col-xs-12 col-lg-3 col-lg-offset-3  text-right">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-vertical-center btn-same-size" id="search">Search</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container ">

            <table class="table my-table">
                <thead>
                    <tr>
                        <th width="25%">Name</th>
                        <th width="10%">Status</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                
                                <td ><span ><?php echo $row->category_name; ?></span></td>
                                <td ><span ><?php echo $row->status; ?></span></td>
                                
                                <td ><span><a href="<?php echo site_url("app/categories/update_category/".$row->category_id); ?>" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="customer-row">
                            <td colspan="3" class="text-center"><span>No Result</span></td>
                        </tr>
                    <?php endif; ?>
                   
                </tbody>
            </table>
            <div class="customer-table-showing margin-bottom">
                <span class="pull-left">
                    <?php 
                        $x = 1;

                        if( $this->input->get("per_page") ){
                            $x = $this->input->get("per_page") + 1;
                        }

                    ?>
                    <small>Displaying <?php echo $x; ?> – <?php echo ($x-1) + count($result) ; ?> of <?php echo $config['total_rows']; ?></small>
                </span>
                <div class="pull-right">
                    <nav aria-label="Page navigation">
                      <?php echo $links; ?>
                    </nav>
                </div>
            </div>
        </div>
