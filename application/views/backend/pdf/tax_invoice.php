<!DOCTYPE>
<html>
<head>
	<title></title>
	<style type="text/css">
		.invoice-box{
			max-width:100%;
			margin-top:20px;
		}
		table {
			border-collapse: collapse;
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
				<td colspan="2" style="text-align: center;"> <h2>TAX INVOICE</h2> </td>
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
				<td colspan="2"></td>
				<td>P/O NO:</td>
				<td><?php echo $invoice_no; ?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td>TERMS:</td>
				<td><?php echo $payment_method; ?></td>
			</tr>
			<tr>
				<td colspan="2">Attn:</td>
				<td>D/O No:</td>
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
				<td style="width:50%"> &nbsp;</td>
				<td> &nbsp;</td>
			</tr>


		</table>

		

	</div>

</body>
</html>
