<script type="text/javascript">
	$(document).on('mouseover' , '.img-list' , function(){
		var img = $(this).find("img");

		$('#img-parent').attr('src' , img.data('src'));
	});

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
		height: 450px;
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
	.star-rating {
		line-height:32px;
		font-size:1.25em;
	}

	.star-rating .fa-star{color: yellow;}
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

				<?php if(count($result->images) == 1) : ?>
					<div class="cropper">
						<img class="card-img-top img-fluid" src="<?php echo site_url("thumbs/images/product/".$result->images[0]->image_path."/500/500/".$result->images[0]->image_name); ?>" alt="">
					</div>
				<?php else: ?>

					<div class="row">
						<div class="col-lg-8 col-xs-8">
							<div class="cropper">
								<img class="card-img-top img-fluid" id="img-parent" src="<?php echo site_url("thumbs/images/product/".$result->images[0]->image_path."/500/500/".$result->images[0]->image_name); ?>" alt="">
							</div>
						</div>
						<div class="col-lg-4 col-xs-4" style="max-height: 450px;overflow: auto;">
							<?php foreach($result->images as $row) : ?>
								<a href="javascript:void(0);" class="img-list">
									<div class="cropper" style="height: 150px;">
										<img class="card-img-top img-fluid" data-src="<?php echo site_url("thumbs/images/product/".$row->image_path."/500/500/".$row->image_name); ?>" src="<?php echo site_url("thumbs/images/product/".$row->image_path."/250/250/".$row->image_name); ?>" alt="">
									</div>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="card-body">
					<h3 class="card-title"><?php echo $result->product_name; ?></h3>
					<h4><?php echo $result->price; ?></h4>
					<p class="card-text"><?php echo $result->product_description; ?></p>
					<div class="text-right">
						<a href="javascript:void(0);" class="btn btn-success btn-xs add-cart" data-id="<?php echo $result->product_id; ?>">Add to Cart</a>
					</div>
					<div class="text-left">
						<div class="row">
							<div class="col-lg-12">
						      <div class="star-rating">
						        <span class="fa fa-star-o" data-rating="1"></span>
						        <span class="fa fa-star-o" data-rating="2"></span>
						        <span class="fa fa-star-o" data-rating="3"></span>
						        <span class="fa fa-star-o" data-rating="4"></span>
						        <span class="fa fa-star-o" data-rating="5"></span>
						        <input type="hidden" name="whatever1" class="rating-value" value="2.56">
						      </div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- card -->

			<div class="card card-outline-secondary my-4" style="margin-top: 5%">
				<div class="card-header">
					<h4>Product Review</h4>
				</div>
				<div class="card-body">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
					<small class="text-muted">Posted by Anonymous on 3/1/17</small>
					<hr>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
					<small class="text-muted">Posted by Anonymous on 3/1/17</small>
					<hr>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
					<small class="text-muted">Posted by Anonymous on 3/1/17</small>
					<hr>
					<a href="#" class="btn btn-success">Leave a Review</a>
				</div>
			</div>
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

<script type="text/javascript">
		/*star function*/
var $star_rating = $('.star-rating .fa');

var SetRatingStar = function() {
  return $star_rating.each(function() {
    if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
      return $(this).removeClass('fa-star-o').addClass('fa-star');
    } else {
      return $(this).removeClass('fa-star').addClass('fa-star-o');
    }
  });
};

$star_rating.on('click', function() {
  $star_rating.siblings('input.rating-value').val($(this).data('rating'));
  return SetRatingStar();
});

SetRatingStar();

</script>