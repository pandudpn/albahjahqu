<meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<link href="styles.css" media="all" rel="stylesheet" type="text/css" />
<table style="background-color: #f6f6f6;width: 100%;">
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;" width="600">
			<div style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
			<table cellpadding="0" cellspacing="0" class="main" style="background: #fff;border: 1px solid #e9e9e9;border-radius: 3px;" width="100%">
				<tbody>
					<tr>
						<td style="padding: 20px;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td style="content-block">

    <div class="content">
        {content}
<p>Terima kasih, transaksi anda berhasil, anda telah melakukan pembelian paket dengan detail sebagai berikut: </p>

<strong>Customer</strong><br />
<?php echo $customer_fullname;?><br />
<?php echo $customer_email;?><br />
<?php echo $customer_phone;?><br /><br />

<strong>Order</strong><br />
<span class="grey">Branch Name</span><br />
<?php echo $order_branchname;?><br /><br />

<span class="grey">Payment Method</span><br />
<?php echo $order_paymentmethod;?><br /><br />

<span class="grey">Transaction Status</span><br />
<?php echo $order_transactionstatus;?><br /><br />

<span class="grey">Transaction Time</span><br />
<?php echo date('d M Y - H:i:s', strtotime($order_transactiontime));?><br /><br />

<span class="grey">Amount</span><br />
Rp. <?php echo number_format($order_amount);?><br /><br />

<hr>

<p><strong>Informasi paket anda sebagai berikut</strong></p>

<table width="100%" border="0" class="details">
	<thead>
		<td width="50">Id</td>
		<td width="200">Name</td>
		<td width="80" align="right">Price</td>
		<td width="80" align="right">Amount</td>
		<td width="80" align="right">Subtotal</td>
	</thead>
	<tr>
		<td>1</td>
		<td>First Trial</td>
		<td align="right">250.000</td>
		<td align="right">1</td>
		<td align="right">250.000</td>
	</tr>
</table>

<br />
<hr>

<strong>Transfer Guide</strong><br />
Silahkan transfer sebesar <strong><?php echo number_format($order_amount);?></strong> ke rekening:<br />
<?php echo $branch_accountbank;?><br />
<?php echo $branch_accountnumber;?><br />
a/n PT Duapuluh Fit<br /><br />
    </div>
                    </td>
								</tr>
								<tr>
									<td align="center" class="content-block social-block" style="background: #efefef;border: 1px solid #cecece;padding: 10px 10px 5px 10px;">
									<table align="center">
										<tbody align="center">
											<tr align="center">
												<td align="center" style="padding-right: 10px;"><a href="http://www.facebook.com/duapuluhfit" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="facebook" class="social" src="http://20fit.co.id/files/facebook-4-48.png"/><br />
												Facebook </a></td>
												<td align="center" style="padding-right: 10px;"><a href="http://www.twitter.com/20_fit" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="twitter" class="social" src="http://20fit.co.id/files/twitter-4-48.png"/><br />
												Twitter </a></td>
												<td align="center" style="padding-right: 10px;"><a href="http://instagram.com/20_fit" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="instagram" class="social" src="http://20fit.co.id/files/instagram-4-48.png"/><br />
												Instagram </a></td>
												<td align="center" style="padding-right: 10px;"><a href="http://youtube.com/20fitID" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="youtube" class="social" src="http://20fit.co.id/files/youtube-4-48.png"/><br />
												Youtube </a></td>
												<td align="center" style="padding-right: 10px;"><a href="http://www.20fit.co.id" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="website" class="social" src="http://20fit.co.id/files/website-optimization-2-48.png"/><br />
												Website </a></td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
								<tr>
									<td class="content-block">
									<p>&nbsp;</p>

									<p align="center" style="margin-bottom: 10px;"><em>Copyright &copy; 2015 PT. Dua Puluh Fit, All rights reserved.</em></p>

									<p align="center" style="margin-bottom: 10px;"><a href="http://www.20fit.co.id" target="_blank"><em><img alt="" src="http://20fit.co.id/assets/images/mail/20fit_logo.png" style="width: 90px; height: 90px;" /></em></a></p>

									<p align="center">
										20FIT is the first micro-gym in Indonesia that uses Electro Muscle Stimulation (EMS) technology and Miha Bodytec equipment for personal training experience. Implementing modern and minimalist concept, the name ‘20FIT’ itself derived from the concept that fitness can be attained within 20 minutes.
									</p>
									<p align="center">
										Call our <a href="http://www.20fit.co.id/branch">STUDIO</a> now!<br /><br />
									</p>
									<p align="center">
										<strong>Head Office:</strong><br />
										Grand ITC Permata Hijau, Blok Ruby No. 8<br />
										Jl. Letjen Soepono (Arteri Permata Hijau)<br />
										Kebayoran Lama, Jakarta Selatan 12210<br />
										Ph. 021-53668573 /74<br />
										<a href="mailto:info@20fit.co.id">info@20fit.co.id</a><br />
										Operational hours: 8.00 AM - 5.00 PM (Mon - Fri)<br />
									</p>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
			</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
<style type="text/css">*{
  margin: 0;
  padding: 0;
  font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
  box-sizing: border-box;
  font-size: 12px;
}

img {
  max-width: 100%;
}

body {
  -webkit-font-smoothing: antialiased;
  -webkit-text-size-adjust: none;
  width: 100% !important;
  height: 100%;
  line-height: 1.6;
  	font-size: 14px;
	line-height: 24px;
}
strong, span{
	font-size: 14px;
}
a{
	color: #00F;
	font-weight: bold;
	text-decoration: underline;
}
hr{
	border: none;
	border-top:1px solid #e9e9e9;
	padding-top: 10px;
	margin-left: -20px;
	margin-right: -20px;
}
.content{
	margin: 10px auto;
	font-size: 14px;
	line-height: 24px;
}
	.content p{
		margin-bottom: 20px;
		font-size: 14px;
	}
.grey{
	color: #bebebe;
}
table.details thead td{
	border-bottom: 1px dotted #363636;
	padding-bottom: 5px;
	font-weight: bold;
	color: #bebebe;
	font-size: 14px;
}
table.details tr td{
	font-weight: bold;
	font-size: 14px;
	border-bottom: 1px dotted #363636;
	padding: 5px 0;
}
@media only screen and (max-width: 640px) {
  .images{
    width: 100%;
    height: auto;
  }
}
</style>

