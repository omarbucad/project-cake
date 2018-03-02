<style type="text/css">
	
</style>
<div class="container margin-bottom">
    <div class="side-body padding-top">
		<section class="content">
			<div class="row">
				<div class="col-xs-12 table-responsive">
					<div class="panel panel-default">
						<div class="panel-heading">Notifications List</div>
						<div class="panel-body">
							<table class="table">
								<tbody>
									<?php //print_r_die($this->notification->notify_list(true));?>
									<?php foreach($result as $key => $value) :?>
									<?php //print_r_die($value);?>
										<tr class="bg bg-warning">
											<td colspan="2">
												<a href="<?php echo $value->url;?>"><b><?php echo $value->sender->name;?></b> <?php echo $value->reference; ?> </a>
											</td>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>