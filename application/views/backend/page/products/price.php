<script type="text/javascript">
    $(document).on('click' , '#search' , function(){
        $('#search_form').submit();
    });

    $(document).on("click" , ".remove-row" , function(){
        var c = confirm("Are you sure?");

        if(c == true){
            window.location.href = $(this).data("href");
        }
    });

    $(document).on("click" , '.view-list' , function(){
        var id = $(this).data("id");
        var name = $(this).data("name");
        var url = "<?php echo site_url("app/products/get_group_list/"); ?>"+id;

        $.ajax({
            url : url ,
            method : "GET" ,
            success : function(response){
                var json = jQuery.parseJSON(response);
                var modal = $('#view_list').modal("show");

                modal.find("tbody").html(" ");
                modal.find(".modal-title").html(name);
                $.each(json , function(k , v){
                    var tr = $("<tr>");

                    tr.append($("<td>").append(v.product_name));
                    tr.append($("<td>").append(v.price));
                    tr.append($("<td>").append(v.custom_price));

                    modal.find("tbody").append(tr);
                });

            }
        });
    });
</script>

<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container">
        	<h1>Price Group</h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/products/add_group"); ?>" class="btn btn-success ">Add Group</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="card-body no-padding-left no-padding-right">
                <div class="container">
                    <div class="card-body no-padding-left no-padding-right">
                        <form action="<?php echo site_url("app/products/price");?>" method="GET" id="search_form">
                            <div class="row">
                                <div class="col-xs-12 col-lg-3">
                                    <div class="form-group">
                                        <label for="s_name">Name</label>
                                        <input type="text" name="name" value="<?php echo $this->input->get("name")?>" placeholder="Name" class="form-control " />
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
                        <th width="25%">Status</th>
                        <th width="25%">Created</th>
                        <th width="25%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                
                                <td ><span ><a href="javascript:void(0);" class="view-list" data-id="<?php echo $row->price_book_id; ?>" data-name="<?php echo $row->group_name; ?>"><?php echo $row->group_name; ?></a></span></td>
                                <td ><span ><?php echo $row->status; ?></span></td>
                                <td ><span ><?php echo $row->created; ?></span></td>
                                
                                <td ><span>
                                    <a href="<?php echo site_url("app/products/update_group/".$row->price_book_id); ?>" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("app/products/remove_group/".$row->price_book_id); ?>" class="remove-row btn btn-xs btn-danger"><i class="fa fa-edit"></i> Remove</a>
                                </span></td>
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
    </div>
</div>

<div class="modal fade" id="view_list" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Group Products</h4>
            </div>
            <div class="modal-body">
               <table class="table table-bordered datatable">
                   <thead>
                       <tr>
                            <td>Name</td>
                            <td>Standard Price</td>
                            <td>Custom Price</td>
                       </tr>
                   </thead>
                   <tbody>

                   </tbody>
               </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
