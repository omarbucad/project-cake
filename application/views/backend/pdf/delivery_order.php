<!DOCTYPE>
<html>
<head>
	<title></title>
	<style type="text/css">
		.invoice-box{
			max-width:100%;
			margin-top:20px;
		}
		
	</style>
</head>
<body>

	<div class="invoice-box">
		<table style="width:100%;" border="0">

			<tr>
				<th colspan="4" style="text-align: center;">
					<img src="<?php echo base_url("public/img/GB2.png"); ?>" style="height: 50px;"><br>
					<small><strong>Gravybaby Sdn. Bhd.</strong> (1147708-X)</small><br>
					<small>M1-05-3A Menara 8trium, Level 5, Jalan Cempaka SD12/5,</small><br>
					<small>Bandar Sri Damansara 52200 Kuala Lumpur, Malaysia.</small><br>
					<small>Tel: +6012 212 2276 | Email: contact@travybaby.com</small><br>
					<small>GST no.: 001963732992</small><br>
				</th>
				
			</tr>	

			<tr>
				<td colspan="2"></td>
				<td colspan="2" style="text-align: center;"> <h2>DELIVERY ORDER</h2> </td>
			</tr>

			<tr>
				<td colspan="2"></td>
				<td><strong>NO:</strong></td>
				<td><?php echo $invoice_no; ?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td>DATE:</td>
				<td><?php echo date("d.m.Y"); ?></td>
			</tr>
			<tr>
				<td colspan="4">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="4">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2">Attn:</td>
				<td>invoice No:</td>
				<td><?php echo $invoice_no; ?></td>
			</tr>

			<tr>
				<td colspan="4">
					<strong><?php echo $display_name; ?></strong>
				</td>
			</tr>

			<tr>
				<td colspan="4" style="width:100%;"">
					Company:<br>
					<strong><?php echo $display_name; ?></strong><br>
					<span>
						<?php echo $street1.",<br>".$street2.",<br>".$suburb.",<br>".$city.",<br>".$state.",<br>".$postcode; ?>
					</span>
				</td>
			</tr>

			<tr>
				<td colspan="4" style="width:100%;">
					Hp no:<br>
					<strong><?php echo "01506251424"; ?></strong>
				</td>
			</tr>
			<tr>
				<td style="width:50%; border-bottom: 1px solid black;"> &nbsp;</td>
				<td style="border-bottom: 1px solid black;"> &nbsp;</td>
			</tr>

			<tr style="text-align: center; ">
				<th colspan="2" style="	border-left: 1px solid black; border-right: 1px solid black;
						border-bottom: 1px solid black;">
						<strong>ITEMS</strong>
				</th>
				<th colspan="2" style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;
						border-bottom: 1px solid black;">
						<strong>QTY</strong>
				</th>
			</tr>
			<tr>
				<td colspan="2"><span><?php echo "1ABC";?></span></td>
				<td colspan="2" style="text-align: center;"><span><?php echo "3";?></span></td>
			</tr>
			<tr>
				<td colspan="2"><span><?php echo "2";?></span></td>
				<td colspan="2" style="text-align: center;"><span><?php echo " ";?></span></td>
			</tr>
			<tr>
				<td colspan="2"><span><?php echo "3";?></span></td>
				<td colspan="2" style="text-align: center;"><span><?php echo " ";?></span></td>
			</tr>
			<tr>
				<td colspan="2" style="border-bottom: 1px solid black">&nbsp;</td>
				<td colspan="2" style="border-bottom: 1px solid black; text-align: center;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" style="border-right: 1px solid black;">&nbsp;</td>
				<td colspan="2" style="border: 1px solid black; text-align: center;"><span><?php echo "3";?></span></td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="4">
					<span>
						<p style="padding-right: 170px;">Payment can be made by cheque payable to GRAVYBABY SDN BHD, or arrange for online tranfer to PUBILC BANK 3199111705</p>
					</span>
				</td>
			</tr>

			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="2">
					Prepared by,
				</td>
				<td colspan="2">
					Acknowledged by,
				</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="1" style="border-bottom: 1px solid black;"></td>
				<td colspan="1"> </td>
				<td colspan="2" style="border-bottom: 1px solid black;"></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">(kindly sign, chop and return copy to us)</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">Name: <span><?php echo " ";?></span></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">Date: <span><?php echo " ";?></span></td>
			</tr>



		</table>

		

	</div>

</body>
</html>
