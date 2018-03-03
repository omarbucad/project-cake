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

	$(document).on("click" , "#save" , function(){
		var form = $(this).closest(".modal").find("form");
		var modal = $(this).closest(".modal");
		$.ajax({
			url : form.attr("action"),
			method : form.attr("method"),
			data : form.serialize() ,
			success : function(response){
				var json = jQuery.parseJSON(response);

				$('#category_select').append($('<option>', {
				    value: json.id,
				    text: json.name,
				    selected : "selected"
				}));


				modal.modal("hide");
			}
		});
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
	    						<input type="number" name="product_price" step="0.01" class="form-control" placeholder="0.00">
	    					</div>
	    				</dd>
	    				<dt>Position</dt>
	    				<dd>
	    					<div class="form-group">
	    						<input type="number" name="product_position" value="1" class="form-control">
	    					</div>
	    				</dd>
	    				<dt>Category</dt>
	    				<dd>
	    					<div class="form-group">
	    						<div class="input-group">
							      <select class="form-control" name="category" id="category_select">
							      		<option value="">Select Category</option>
							      		<?php foreach($category_list as $row) : ?>
							      			<option value="<?php echo $row->category_id; ?>"><?php echo $row->category_name; ?></option>
							      		<?php endforeach; ?>
							      </select>
							      <span class="input-group-btn">
							        <button class="btn btn-default" style="margin:0px;" type="button" data-toggle="modal" data-target="#myModal">+</button>
							      </span>
							    </div><!-- /input-group -->
	    					</div>
	    				</dd>
	    				<dt>Short Description</dt>
	    				<dd>
	    					<div class="form-group">
	    						<input type="text" name="short_description" class="form-control">
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
	    		<a href="<?php echo site_url('app/products');?>"  class="btn btn-default">Cancel</a>
	    		<input type="submit" name="submit" value="Save" class="btn btn-success">
	    	</div>
    	</form>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Category</h4>
      </div>
      <div class="modal-body">
        	<form action="<?php echo site_url("app/products/add_category"); ?>" method="POST">
        		<div class="form-group">
        			<label for="category">Product Category</label>
        			<input type="text" class="form-control" name="product_category">
        		</div>
        	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save">Save</button>
      </div>
    </div>
  </div>
</div>