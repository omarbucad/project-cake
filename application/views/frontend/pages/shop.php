<script type="text/javascript">
  $(document).on("click" , ".add-cart" , function(){
    var id = $(this).data("id");
    var url = "<?php echo site_url("product/add_cart"); ?>";
    var qty = $('#_qty').val();

    $(this).closest(".modal").modal("hide");

    if(qty == 0){
        alert("Please enter a quantity.");
    }
    else{
      $.ajax({
        url : url ,
        data : {id : id , qty : qty },
        method : "POST" ,
        success : function(response){
          var json = jQuery.parseJSON(response);

          if(json.status){
            var modal = $('#myModal').modal("show");
            modal.find(".total_items").html(json.data.items);
            modal.find(".total_price").html(json.data.price);
            modal.find(".total_gst").html("RM "+parseFloat(json.data.price_raw * 0.06).toFixed(2));
            modal.find(".total_price_with_gst").html("RM " +parseFloat((json.data.price_raw * 0.06) + json.data.price_raw).toFixed(2));
          }else{
            alert(json.message);
          }

        }
      });
    }
    
  });

  $(document).on("click" , ".add-cart-show" , function(){
      var product_id = $(this).data("id");
      var url = "<?php echo site_url("product/check_cart"); ?>";  

      $.ajax({
        url : url ,
        data : {id : product_id },
        method : "POST" ,
        success : function(response){
          
          var json = jQuery.parseJSON(response);
          
          if(json.status){

            var modal = $("#myModal2").modal("show");
            modal.find(".add-cart").data("id" , product_id);

          }else{
            alert(json.message);
          }
        }
      });

      
  });

  $(document).on("keypress keyup blur", "#_qty", function(e) {
      $(this).val($(this).val().replace(/[^\d].+/, ""));
      if ((e.which < 48 || e.which > 57)) {
          e.preventDefault();
      }
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

  .cropper:hover .product-image{
  /*  -webkit-filter: grayscale(50%); /* Safari 6.0 - 9.0 */
      /*filter: grayscale(50%);
    transition: .5s ease-in;
    transition: .5s ease-out;*/
  }
  .cropper:hover .product-shortdesc{
    opacity: 1;
  }
  .product-shortdesc{
    transition: .5s ease-in-out;
    opacity: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    text-align: center;
    color: #fff;
    background: rgba(0, 0, 0, 0.5);
    padding: 25px 10px;
    width: 100%;
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
                  <div class="cropper text-center">
                    <img class="card-img-top product-image" src="<?php echo site_url("thumbs/images/product/".$row->images[0]->image_path."/350/250/".$row->images[0]->image_name); ?>" alt="" >
                    <div class="product-shortdesc">
                      <p class="text"><?php echo $row->short_description; ?></p>
                    </div>
                  </div>
                </a>
                <div class="card-body">
                  <h4 class="card-title" style="min-height: 50px;">
                    <a href="<?php echo site_url("product/?id=$row->product_id"); ?>"><?php echo $row->product_name; ?></a>
                  </h4>
                  <h5><?php echo $row->price; ?></h5>
                </div>
                <div class="card-footer text-right">
                   <button class="btn btn-success add-cart-show" style="width: 100%" data-id="<?php echo $row->product_id; ?>" type="button">Add to Cart</button>
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
            <td >Price</td>
            <td class="text-right"><span class="total_price">1</span></td>
          </tr>
          <tr>
            <td >GST @6%</td>
            <td class="text-right"><span class="total_gst">1</span></td>
          </tr>
          <tr>
            <td >Total price w/ GST</td>
            <td class="text-right"><span class="total_price_with_gst">1</span></td>
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



<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Item Quantity</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="qty" id="_qty" placeholder="Quantity" class="form-control" value="1" min="1" step="1" pattern="[0-9]">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a href="javascript:void(0);" class="btn btn-primary add-cart">Confirm</a>
      </div>
    </div>
  </div>
</div>