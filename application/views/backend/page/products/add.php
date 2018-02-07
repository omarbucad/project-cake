<script type="text/javascript">
	$(document).on("click" , ".btn-add-more" , function(){
		var clone = $(this).parent().prev().clone();
		clone.find("input").val("");
		$(this).parent().before(clone);
		$(this).parent().prev().find("input").trigger("click");
	});

	$(document).on("click" , ".btn-remove-image" , function(){
		var count = $(this).closest("dd").find(".form-group").length;

		if(count != 1){
			$(this).closest(".form-group").remove();
		}else{
			$(this).closest(".form-group").find("input").val("");
		}
	});
</script>
<div class="container margin-bottom">
    <div class="side-body padding-top">
    	<ol class="breadcrumb">
    		<li><a href="<?php echo site_url('app/products'); ?>">Product</a></li>
    		<li class="active">New Product</li>
    	</ol>	
    	<h3>New Product</h3>
    	<form class="form-horizontal" action="<?php echo site_url("app/products/add"); ?>" method="POST" enctype="multipart/form-data">
    		<input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
    		<!-- STORE SETTINGS -->
    		<div class="card margin-bottom">
	    		<div class="card-header">
	    			<div class="card-title">
	    				<div class="title">Details</div>
	    			</div>
	    		</div>
	    		<div class="card-body">
	    			<dl class="dl-horizontal text-left">
	    				<dt>Product Name</dt>
	    				<dd>
	    					<div class="form-group">
	    						<input type="text" name="product_name" class="form-control">
	    					</div>
	    				</dd>
	    				
	    				<dt>Price</dt>
	    				<dd>
	    					<div class="form-group">
	    						<input type="number" name="product_price" class="form-control" placeholder="0.00">
	    					</div>
	    				</dd>
	    				<dt>Position</dt>
	    				<dd>
	    					<div class="form-group">
	    						<input type="number" name="product_position" value="1" class="form-control">
	    					</div>
	    				</dd>
	    				<dt>Description</dt>
	    				<dd>
	    					<div class="form-group">
	    						<textarea class="textarea" name="description"></textarea>
	    					</div>
	    				</dd>
	    				<dt>Images</dt>
	    				<dd>
	    					<div class="form-group">
	    						<div class="input-group">
							      <input type="file" name="other_file[]" class="form-control">
							      <span class="input-group-btn">
							        <button class="btn btn-default btn-remove-image" type="button" style="margin:0px;">x</button>
							      </span>
							    </div>
	    					</div>
	    					<div class="row text-right">
	    						<a href="javascript:void(0);" class="btn btn-primary btn-add-more">Add More</a>
	    					</div>
	    				</dd>
	    			</dl>
	
	    		</div>
	    	</div>



	    	<div class="text-right margin-bottom">
	    		<a href="javascript:void(0);" class="btn btn-default">Cancel</a>
	    		<input type="submit" name="submit" value="Save" class="btn btn-success">
	    	</div>
    	</form>
    </div>
</div>


