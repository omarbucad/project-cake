<!DOCTYPE>
<html>
<head>
	<title></title>
	<style type="text/css">
		.invoice-box{
			max-width:100%;
			margin-top:5px;
		}
		td{
			padding:2px;
		}
	</style>
</head>
<body>

	<div class="invoice-box">
		<table style="width:97%;">

			<tr>
				<td colspan="4" style="text-align: center;">
					<img src="<?php echo base_url("public/img/GB2.png"); ?>" style="height: 50px;"><br>
					<small><strong>Gravybaby Sdn. Bhd.</strong> (1147708-X)</small><br>
					<small>M1-05-3A Menara 8trium, Level 5, Jalan Cempaka SD12/5,</small><br>
					<small>Bandar Sri Damansara 52200 Kuala Lumpur, Malaysia.</small><br>
					<small>Tel: +6012 212 2276 | Email: contact@travybaby.com</small><br>
					<small>GST no.: 001963732992</small><br>
				</td>
				
			</tr>	

			<tr>
				<td style="width:50%"></td>
				<td style="width:10%"></td>
				<td style="width:20%"></td>
				<td style="width:20%"></td>
			</tr>

			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2" style="text-align: center;"> <h2>DELIVERY ORDER</h2> </td>
			</tr>

			<tr>
				<td colspan="2">&nbsp;</td>
				<td><strong>NO:</strong></td>
				<td><?php echo $invoice_no; ?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td>DATE:</td>
				<td><?php echo date("d.m.Y"); ?></td>
			</tr>
			<tr>
				<td colspan="2">Attn:</td>
				<td>Invoice No:</td>
				<td><?php echo $invoice_no; ?></td>
			</tr>

			
			<tr>
				<td colspan="4" >
					<div style="padding:5px 0px">
						<strong><?php echo $display_name; ?></strong>
					</div>
				</td>
			</tr>

			<tr>
				<td colspan="4">
					Company:<br>
					<div style="padding:5px 0px;">
						<strong><?php echo $display_name; ?></strong><br>
						<?php echo $address; ?>
					</div>
				</td>
			</tr>

			<tr>
				<td colspan="4" style="">
					Hp no:<br>
					<div style="padding:5px 0px;">
						<strong><?php echo "01506251424"; ?></strong>
					</div>
				</td>
			</tr>

			<tr>
				<th style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0">ITEMS</th>
				<th style="text-align: center;padding:10px;border:1px solid #e0e0e0;" colspan="3">QTY</th>
				
			</tr>

			<?php foreach($items_list as $key => $i) : ?>
				<tr>
					<th style="padding: 5px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;"><?php echo $key+1; ?> <?php echo $i->product_name;?></th>
					<th style="padding: 5px;text-align: center;border-left:1px solid #e0e0e0;border-right:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;" colspan="3"><?php echo $i->quantity;?></th>
				</tr>
			<?php endforeach; ?>

			<tr>
				<td >&nbsp;</td>
				<td style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-right:1px solid #e0e0e0;padding: 5px;text-align: center;" colspan="3"><?php echo $items; ?></td>
			</tr>

			<tr>
				<td colspan="4" style="text-align: center;">
					<p>Payment can be made by cheque payable to GRAVYBABY SDN BHD <br>or arrange for online transfer to PUBLIC BANK 3199111705</p>
				</td>
			</tr>
			<tr>
				<td colspan="4"><br><br><br><br></td>
			</tr>
			<tr>
				<td>
					<p>Prepared by,</p>
				</td>
				<td>&nbsp;</td>
				<td colspan="2">Acknowledge by</td>
			</tr>
			<tr>
				<td colspan="4"><br><br><br><br></td>
			</tr>
			<tr>
				<td style="border-top:1px solid #e0e0e0;">&nbsp;</td>
				<td>&nbsp;</td>
				<td style="border-top:1px solid #e0e0e0;text-align: center;" colspan="2">(kindly sign, chop and return copy to us)</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="2">Name:</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="2">Date:</td>
			</tr>
		</table>

	</div>

</body>
</html>
