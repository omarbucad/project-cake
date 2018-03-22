<script type="text/javascript">
	$(document).on("click" , ".read-notif" , function(){

        var href = $(this).data("href");
        var url = '<?php echo site_url("app/dashboard/read_notif"); ?>';
        var id = $(this).data("id");
	        $.ajax({
	            url : url ,
	            data : {id : id},
	            method : "POST",
	            success : function(){
	                window.location.href = href;
	            }
	        });
	});
	$(document).on("click" , ".set-read" , function(){
        var url = $(this).data("href");
        var c = confirm("Are you sure?");
        if(c == true){
        	$.ajax({
	            url : url ,
	            method : "POST",
	            success : function(){
	            }
	        });
        }        
    });
</script>
<div class="container margin-bottom">
    <div class="side-body padding-top">
		<section class="content">
			<div class="row">
				<div class="col-xs-12 table-responsive">
					<div class="panel-group">
						<div class="panel panel-default">
							<div class="panel-heading">Notifications List <span class="pull-right"><a href="<?php echo site_url('app/notifications/mark_all_read'); ?>" class="btn btn-sm btn-info set-read" style="display: inline;">Mark All as Read</a></span></div>
							<div class="panel-body">
								<table class="table">
									<tbody>
										<?php foreach($result as $key => $value) :?>
											<tr class="bg <?php echo ($value->unread == '1') ? 'bg-default' : 'bg-warning'; ?>">
												<td colspan="2">
													<a href="javascript:void(0);" data-href="<?php echo $value->url;?>" class=" read-notif" data-id="<?php echo $value->id; ?>"><b><?php echo $value->sender->name;?></b> <?php echo $value->reference; ?> </a>
												</td>
											</tr>
										<?php endforeach;?>
									</tbody>
								</table>
								<div class="row"><span class="pull-right" style="padding-right: 10px;"><a href="<?php echo site_url('app/notifications/mark_all_read'); ?>" class="btn btn-sm btn-info set-read" style="display: inline;">Mark All as Read</a></span></div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>