<script type="text/javascript">
	$(document).on('click' , '.img-list' , function(){
		var img = $(this);

		$('#img-parent').attr('src' , img.data('src'));
	});

	$(document).on("click" , ".add-cart" , function(){
	    var id = $(this).data("id");
	    var url = "<?php echo site_url("product/add_cart"); ?>";
	    var qty = $('#_qty').val();
	    
	    $(this).closest(".modal").modal("hide");

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
</script>
<style type="text/css">
	.card {
		position: relative;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-ms-flex-direction: column;
		flex-direction: column;
		min-width: 0;
		word-wrap: break-word;
		background-color: #fff;
		background-clip: border-box;
		border: 1px solid rgba(0,0,0,.125);
		border-radius: .25rem;
	}

	.card-body{
		-webkit-box-flex: 1;
		-ms-flex: 1 1 auto;
		flex: 1 1 auto;
		padding: 1.25rem;
	}

	.cropper{
		width: 100%;
		height: 350px;
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
	.img-list{
		display: block;
		margin: 5px 0px;
	}
	.card-header {
		padding: .75rem 1.25rem;
		margin-bottom: 0;
		background-color: rgba(0,0,0,.03);
		border-bottom: 1px solid rgba(0,0,0,.125);
	}
	.img-container{
		overflow: hidden;
	}

	.img-container > .img-list{
		display: inline-block;
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
		<div class="col-lg-9">
			<div class="card">
				<div class="row">
					<div class="col-lg-7 col-xs-12">
						<div class="cropper">
							<img class="card-img-top img-fluid" id="img-parent" src="<?php echo site_url("thumbs/images/product/".$result->images[0]->image_path."/400/400/".$result->images[0]->image_name); ?>" alt="">
						</div>
						<?php if(count($result->images) != 1) : ?>
						<div class="img-container" style="margin-top: 20px;">
							<?php foreach($result->images as $row) : ?>
								<img class="img-thumbnail img-list" style="width: calc((100% / 3) - 3px);height: 120px;" data-src="<?php echo site_url("thumbs/images/product/".$row->image_path."/400/400/".$row->image_name); ?>" src="<?php echo site_url("thumbs/images/product/".$row->image_path."/250/250/".$row->image_name); ?>" alt="">
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
					</div>
					<div class="col-lg-5 col-xs-12">
						<div class="card-body">
							<h3 class="card-title" style="margin-top: 0px;"><?php echo $result->product_name; ?></h3>
							<h4><?php echo $result->price; ?></h4>
							<p class="card-text"><?php echo $result->product_description; ?></p>
							<button class="btn btn-success add-cart-show btn-block"  data-id="<?php echo $result->product_id; ?>" type="button">Add to Cart</button>
						</div>
					</div>
				</div>
			</div><!-- card -->
		</div><!-- col-9 -->
	</div><!-- row -->

</div><!-- end container -->


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
            <input type="number" name="qty" id="_qty" placeholder="Quantity" class="form-control">
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