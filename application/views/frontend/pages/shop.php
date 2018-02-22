<script type="text/javascript">
  $(document).on("click" , ".add-cart" , function(){
    var id = $(this).data("id");
    var url = "<?php echo site_url("product/add_cart"); ?>";
    
    $.ajax({
      url : url ,
      data : {id : id },
      method : "POST" ,
      success : function(response){
        var json = jQuery.parseJSON(response);

        if(json.status){
          var modal = $('#myModal').modal("show");
          modal.find(".total_items").html(json.data.items);
          modal.find(".total_price").html(json.data.price);
        }else{
          alert(json.message);
        }

      }
    });
    
  });

  /*function for adding wish */
  $(document).on('click' ,'.add-wish' , function () {
    var product_id = $(this).data('id');
    var url = "<?php echo site_url("product/add_wish"); ?>";

    $.ajax({
      url  : url ,
      data : {product_id : product_id } ,
      method : "POST" ,
      success : function (response) {
        var json = jQuery.parseJSON(response);
        alert(json.message);
      }
    });
  });
</script>
<style type="text/css">
  .cropper{
    width: 250px;
    height: 250px;
    overflow: hidden;
    position: relative;
  }
  .cropper > img{
    position: absolute;
    left: -1000%;
    right: -1000%;
    top: -1000%;
    bottom: -1000%;
    margin: auto;
    min-height: 100%;
    min-width: 100%;
  }
</style>
<div style="margin-top: 100px;"></div>
<div class="container">

  <div class="row">

    <div class="col-lg-3">

      <form action="<?php echo site_url("welcome"); ?>" method="GET">
        <div class="input-group">
          <input type="text" class="form-control" name="s" value="<?php echo $this->input->get("s"); ?>" placeholder="Search for...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
          </span>
        </div><!-- /input-group -->
      </form>
      <br>
      <div class="list-group">
        <a href="<?php echo site_url("welcome/?shop_list=all"); ?>" class="list-group-item <?php echo ($this->input->get("shop_list") == "all") ? "active" : ""; ?>">All</a>
        <?php foreach($shop_list as $key => $row) : ?>
          <a class="list-group-item <?php echo ($this->input->get("shop_list") == $row->category_id) ? "active" : ""; ?>" href="<?php echo site_url("welcome/?shop_list=$row->category_id"); ?>"><?php echo $row->category_name; ?></a>
        <?php endforeach; ?>
      </div>

    </div>
    <!-- /.col-lg-3 -->
    <div class="col-lg-9">
      <?php foreach($result as $key => $val) : ?>
        
        <div class="row" style="margin-bottom: 20px;">
          <?php foreach($val as $row) : ?>
            <div class="col-lg-4 col-md-6 mb-4 ">
              <div class="card h-100 thumbnail">
                <a href="<?php echo site_url("product/?id=$row->product_id"); ?>">
                  <div class="cropper">
                    <img class="card-img-top" src="<?php echo site_url("thumbs/images/product/".$row->images[0]->image_path."/250/250/".$row->images[0]->image_name); ?>" alt="" >
                  </div>
                </a>
                <div class="card-body">
                  <h4 class="card-title">
                    <a href="<?php echo site_url("product/?id=$row->product_id"); ?>"><?php echo $row->product_name; ?></a>
                  </h4>
                  <h5><?php echo $row->price; ?></h5>
                  <p class="card-text"><?php echo $row->short_description; ?></p>
                </div>
                <div class="card-footer text-right">
                  <div style="display: flex;">
                    <a href="javascript:void(0);" style="flex: 1;margin: 5px;" class="btn btn-success btn-xs add-cart" data-id="<?php echo $row->product_id; ?>">Add to Cart</a>
                    <a href="javascript:void(0);" style="flex: 1;margin: 5px;" class="btn btn-warning btn-xs add-wish" data-id="<?php echo $row->product_id; ?>"><i class="fa fa-star"></i > Add to Wish Lists</a>
                  </div>
                </div>

              </div>
            </div>
          <?php endforeach; ?> 
        </div>
       
      <?php endforeach; ?>
       <div class="text-center">
          <nav aria-label="Page navigation">
            <?php echo $links; ?>
          </nav>
        </div>
    </div> 
    <!-- /.col-lg-9 -->

  </div>
  <!-- /.row -->  

</div>  


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">1 new item has been added to your cart</h4>
      </div>
      <div class="modal-body">
        <legend>My Cart ( <small><span class="total_items">2</span> Items</small> )</legend>
        <table style="width: 100%;">
          <tr>
            <td>TOTAL</td>
            <td class="text-right"><span class="total_price">RM 1000</span></td>
          </tr>

        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Continue Shopping</button>
        <a href="<?php echo site_url("cart"); ?>" class="btn btn-primary">Proceed to Checkout</a>
      </div>
    </div>
  </div>
</div>