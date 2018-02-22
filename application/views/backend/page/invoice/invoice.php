<script type="text/javascript">
     $(document).on('click' , '.customer-row' , function(){
        if($(this).hasClass("active")){
            $(this).removeClass("active");
            $(this).next().addClass("hidden");
            $(this).next().removeClass("open");
        }else{
            $(this).addClass("active");
            $(this).next().removeClass("hidden");
            $(this).next().addClass("open");
        }
    });
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container">
        	<h1>Invoice</h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/invoice/order"); ?>" class="btn btn-success ">Go To Order</a>
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
                                    <label for="s_roles">Status</label>
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
            <table class="customer-table">
                <thead>
                    <tr>
                        <th width="25%">Invoice No</th>
                        <th width="20%">Total Price</th>
                        <th width="10%">Status</th>
                        <th width="20%">Invoice Date</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $key => $row) : ?>
                        <tr class="customer-row">
                            <td>
                                <span><?php echo $row->invoice_no; ?></span>
                            </td>
                            <td ><span ><?php echo $row->price; ?></span></td>
                            <td ><span><?php echo $row->payment_type; ?> <?php echo ($row->payment_type != "UNPAID") ? "<br><small>".$row->paid_date."</small>" : "" ; ?></span></td>
                            <td>
                                <span>
                                   <?php echo $row->invoice_date; ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#">Pay Invoice</a></li>
                                    <li><a href="#">View Invoice</a></li>
                                  </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="customer-info hidden">
                            <td colspan="6">
                                <table class="table table-bordered" style="width:80%;margin:10px auto;">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Order Information</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%;">Name</th>
                                            <td width="30%;">
                                                <a href="javascript:void(0);"><?php echo $row->order_number; ?> ( <?php echo $row->display_name; ?> )</a><br>
                                                <small class="help-block"><?php echo $row->email; ?></small>
                                            </td>
                                            <th width="20%;">Items</th>
                                            <td width="30%;"><?php echo $row->items; ?> </td>
                                        </tr>
                                        <tr>
                                            <th>Driver</th>
                                            <td><?php echo $row->name; ?> </td>
                                            <th>Customer Signature</th>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>    
                    <?php endforeach; ?>
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