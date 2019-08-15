<style type="text/css">
*{
	margin: 0; padding: 0;
}
body{
	font-family: 'Helvetica', 'Arial', 'Sans';
	font-size: 12px;
	padding: 10px;
}
h1, h2, h3, h4, h5, h6{
	margin-bottom: 10px;
}
h2{
	font-size: 18px;
	font-weight: normal;
}
h3{

}

table.width100, table.width50, table.width1000{
	width: 100%;
	margin: 0px;
	border-collapse: collapse;
	margin-bottom: 40px;
}
table.width50{
	width: 50%;
}
table.width1000{
	width: 200%;
}
table.width100 thead, table.width50 thead, table.width1000 thead{
	background: #c82a27;
}
table.width100 thead td, table.width50 thead td, table.width1000 thead td{
	font-size: 12px;
	padding: 5px;
	color: #ffffff;
}
table.width100 tr, table.width50 tr, table.width1000 tr{
	border-left: 1px solid #111111;
	border-right: 1px solid #111111;
}
table.width100 tr td, table.width50 tr td, table.width1000 tr td{
	font-size: 12px;
	border-bottom: 1px solid #111111;
	padding: 5px;
}
table.noborder tr, table.noborder tr td{
	border: none;
}
/*table thead{*/
	/*
red 		#c82a27
black 		#111111
white 		#ffffff
light grey	#f2f3f5
*/
	/*background: #c82a27 !important;*/
	/*color: #ffffff;*/
	/*border: 0px solid #ffffff !important;*/
/*}*/
.wrapper{
	width: 100%;
	background: #efefef;
}
.column4{
	float: left;
	width: 30%;
	/*border: 1px solid #f00;*/
	margin-right: 10px;
}
	.column4 h3{
		background: #c82a27;
		color: #ffffff;
		font-weight: normal;
		font-size: 12px;
		padding: 10px;
		text-align: center;
		margin-bottom: 0px;
	}
.row{
	background: #efefef;
	margin-bottom: 20px;
}
	.row p{
		padding: 5px;
		border-bottom: 1px solid #ffffff;
	}
	.row p .right{
		text-align: right;
		float: right;
	}
/*@media only screen and (max-width: 768px) {
    .column4{
    	width: 100%;
    	display: block;
    }
}*/
</style>

<body>
<h2>Daily Summary of {branch_name}</h2>

<div class="wrapper">
	<div class="column4">
		<h3>SALES</h3>
		<div class="row">
			<p>Services <span class="right">{sales_services} ({sales_servicescount})</span></p>
			<p>Products <span class="right">{sales_products} ({sales_productscount})</span></p>
			<p>Memberships <span class="right">{sales_memberships} (0)</span></p>
			<p>Packages <span class="right">{sales_packages} ({sales_packagescount})</span></p>
			<p>Gift Cards <span class="right">{sales_giftcards} (0)</span></p>
			<!-- <p>Pre-paid Cards <span class="right">{sales_prepaidcards} (0)</span></p> -->
			<p>Total <span class="right">{sales_total}</span></p>

			<p>&nbsp;</p>

			<p>Product to Service % <span class="right">{sales_producttoservices}</span></p>
			<p>Product to Total % <span class="right">{sales_producttototal}</span></p>
			<p>Average Service Value <span class="right">{sales_averageservicevalue}</span></p>
			<p>Average Product Value <span class="right">{sales_averageproductvalue}</span></p>
			<p>Average Invoice Value <span class="right">{sales_averageinvoicevalue}</span></p>
		</div>
	</div>
	<div class="column4">
		<h3>COLLECTIONS</h3>
		<div class="row">
			<p>Cash <span class="right">{collection_cash}</span></p>
			<p>Card <span class="right">{collection_card}</span></p>
			<p>Transfer <span class="right">{collection_transfer}</span></p>
			<p>TOTAL <span class="right">{collection_total}</span></p>
			
			<p>&nbsp;</p>

			<p>Memberships <span class="right">{collection_memberships}</span></p>
			<p>Gift Cards <span class="right">{collection_giftcards}</span></p>
			<p>Pre-paid Cards <span class="right">{collection_prepaidcards}</span></p>
			<p>Loyality Points <span class="right">{collection_loyalitypoints}</span></p>

			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			
		</div>
	</div>
	<div class="column4">
		<h3>REGISTER CLOSURE</h3>
		<div class="row">
			<p>Closure Time <span class="right">{closure_time}</span></p>
			<p>Cash <span class="right">{closure_cash}</span></p>
			<p>Card <span class="right">{closure_card}</span></p>
			<p>Check <span class="right">{closure_check}</span></p>
			<!-- <p>Transfer <span class="right">{closure_transfer}</span></p> -->
			<p>Bank Deposit <span class="right">{closure_bankdeposit}</span></p>
			
			<p>&nbsp;</p>

			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			
		</div>
	</div>
</div>

<div class="wrapper">
	<div class="column4">
		<h3>REVENUE &amp; SERVICE SUMMARY</h3>
		<div class="row">
			<p>Projected Monthly Revenue <span class="right">N/A</span></p>
			<p>Total Collected <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;New Guests <span class="right">{revenue_newguests}</span></p>
			<p>&nbsp;&nbsp;&nbsp;Existing Guests <span class="right">{revenue_existing}</span></p>
			<p>Total Revenue <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;Collection <span class="right">N/A</span></p>
			<p>&nbsp;&nbsp;&nbsp;Redemption <span class="right">N/A</span></p>
			<p>Sales Modified <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;# Old Sales Modified <span class="right">0</span></p>
			<p>&nbsp;&nbsp;&nbsp;Old Balance Paid <span class="right">0</span></p>
			<p>&nbsp;&nbsp;&nbsp;Unpaid Balance for Month <span class="right">0</span></p>
			<p>Sales Totals <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;# Open Sales <span class="right">0</span></p>
			<p>Total Sales Value<span class="right">0</span></p>
			<p>Total Amount Paid<span class="right">0</span></p>
			<p>Total Due Amount<span class="right">0</span></p>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

		</div>
	</div>
	<div class="column4">
		<h3>GUEST &amp; SERVICE SUMMARY</h3>
		<div class="row">
			<p>Total # Guests<span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;New Guests<span class="right">{guests_new}</span></p>
			<p>&nbsp;&nbsp;&nbsp;Old Guests<span class="right">{guests_old}</span></p>
			<p>&nbsp;&nbsp;&nbsp;Rebooked Guests<span class="right">{guests_rebooked}</span></p>
			
			<p>Total # Services <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;New Guests <span class="right">{guests_new}</span></p>
			<p>&nbsp;&nbsp;&nbsp;Old Guests <span class="right">{guests_old}</span></p>
			
			<p>Appointment Status <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;Cancellations <span class="right">{guests_cancellations}</span></p>
			<p>&nbsp;&nbsp;&nbsp;No Shows <span class="right">{guests_noshows}</span></p>
			<p>&nbsp;&nbsp;&nbsp;Deleted <span class="right">{guests_deleted}</span></p>
			<p>&nbsp;&nbsp;&nbsp;Walkins <span class="right">{guests_walkins}</span></p>
			
			<p>Customer Satisfaction <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;# Guests Give Feedback <span class="right">{guests_givefeedback}</span></p>
			<p>&nbsp;&nbsp;&nbsp;# Guests Didn't Give Feedback <span class="right">{guests_didnotgivefeedback}</span></p>
			<p>&nbsp;&nbsp;&nbsp;# Below 3 Ratings <span class="right">{guests_below3ratings}</span></p>
			
			<p>Center Utilization (Employee) <span class="right">0 %</span></p>
			<p>Center Utilization (Room) <span class="right">0 %</span></p>
			
		</div>
	</div>
	<div class="column4">
		<h3>EMPLOYEE SUMMARY</h3>
		<div class="row">
			<p>Trainer Service Revenue (Month Till Day) <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;Highest <span class="right">{employeesummary_highest}</span></p>
			<p>&nbsp;&nbsp;&nbsp;Lowest <span class="right">{employeesummary_lowest}</span></p>
			
			<p>Trainer &amp; Targets <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;# On Target <span class="right">{closure_check}</span></p>
			<p>&nbsp;&nbsp;&nbsp;# Off Target <span class="right">{closure_check}</span></p>

			<p>Employee Attendance <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;# On Time <span class="right">{closure_check}</span></p>
			<p>&nbsp;&nbsp;&nbsp;# Late <span class="right">{closure_check}</span></p>
			<p>&nbsp;&nbsp;&nbsp;# No Show <span class="right">{closure_check}</span></p>

			<p>Tips <span class="right">0</span></p>

			<p>Petty Cash <span class="right"></span></p>
			<p>&nbsp;&nbsp;&nbsp;Receipts <span class="right">{pettycash_receipts}</span></p>
			<p>&nbsp;&nbsp;&nbsp;Expenses <span class="right">{pettycash_expenses}</span></p>
			
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			
		</div>
	</div>
</div>
<table class="width100">
	<thead>
		<td colspan="7">BUSINESS UNIT SUMMARY</td>
	</thead>
	<tr>
		<td></td>
		<td>Services</td>
		<td>Products</td>
		<td>Memberships</td>
		<td>Packages</td>
		<td>Gift Cards</td>
		<!-- <td>Prepaid Cards</td> -->
		<td>Total</td>
	</tr>
	<tr>
		<td>Studio</td>
		<td>{sales_services}</td>
		<td>0</td>
		<td>0</td>
		<td>{sales_packages}</td>
		<td>0</td>
		<!-- <td>0</td> -->
		<td>{sales_total}</td>
	</tr>
</table>
<!-- daily summary eof -->
<h4>Center Sales</h4>
<table class="width100">
	<thead>
		<td>Date</td>
		<td>Services</td>
		<td>Products</td>
		<td>Packages</td>
		<td>Gift Cards</td>
		<td>Prepaid Cards</td>
		<td>Total</td>
	</thead>
	{center_sales}
</table>

<!-- center sales -->
<h4>Center Collection</h4>
<table class="width100">
	<thead>
		<td>Date</td>
		<td>Cash</td>
		<td>Card</td>
		<td>Transfer</td>
		<td>Total Collection</td>
	</thead>
	{center_collection}
</table>
<!-- center collection eof -->
<h4>Center Business</h4>
<table class="width1000">
	<thead>
		<td align="center">Date</td>
		<td align="center">Service Sale</td>
		<td align="center">Service - Taxes</td>
		<td align="center">Product Sale</td>
		<td align="center">Product - Taxes</td>
		<td align="center">Product Shipping Charge</td>
		<td align="center">Membership Sale</td>
		<td align="center">Membership - Taxes</td>
		<td align="center">Package Sale</td>
		<td align="center">Package Sale - Taxes</td>
		<td align="center">Prepaid Card Sale</td>
		<td align="center">Prepaid Card Taxes</td>
		<td align="center">TotalSale</td>
		<td align="center">Cash Payments</td>
		<td align="center">Card Payments</td>
		<td align="center">Membership Payments</td>
		<td align="center">Prepaid Card Payments</td>
		<td align="center">Transfer</td>
		<td align="center">TotalCollection</td>
		<td align="center">Projected Collection</td>
		<td align="center">TodayCollection</td>
		<td align="center">Unpaid amount as on date</td>
		<td align="center">PreviousDayCollection</td>
		<td align="center">Unpaid amount as on today</td>
		<td align="center">FutureCollection</td>
		<td align="center">COLLECTIONS (PREVIOUS + FUTURE)</td>
		<td align="center">Paid Till Date</td>
		<td align="center">Other Center Payments</td>
		<td align="center">Month Total(Cash/CC/Check Payments)</td>
		<td align="center">Bank Deposit Amount</td>
		<td align="center">Bank Name</td>
		<td align="center">Refunds</td>
	</thead>
	{center_business}
</table>
<!-- center business -->

<h4>Appointments - Deleted</h4>
<table class="width100">
	<thead>
		<td>Invoice No</td>
		<td>Kwitansi No</td>
		<td>Guest</td>
		<td>Trainer</td>
		<td>Service</td>
		<td>Promotion</td>
		<td>Price</td>
		<td>Discount</td>
		<td>Tax</td>
		<td>Price Paid</td>
		<td>First Visit</td>
		<td>Status</td>
		<td>Comments</td>
	</thead>
	<tr>
		<td>N/A</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
<!-- appointment deleted eof -->
<!-- appointment modified -->
<h4>Appointments - Modified</h4>
<table class="width100">
	<thead>
		<td>Invoice No</td>
		<td>Kwitansi No</td>
		<td>Appointment Date</td>
		<td>Guest</td>
		<td>Trainer</td>
		<td>Service</td>
		<td>Quantity</td>
		<td>Promotion</td>
		<td>Price</td>
		<td>Discount</td>
		<td>Tax</td>
		<td>Price Paid</td>
		<td>First Visit</td>
		<td>Payment Type</td>
		<td>Status</td>
		<td>modified by</td>
	</thead>
	<tr>
		<td>N/A</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
<!-- appointment modified end -->

<h4>Guest Feedback</h4>
<table class="width100">
	<thead>
		<td>Invoice No</td>
		<td>Kwitansi No</td>
		<td>Feedback</td>
		<td>Guest</td>
		<td>Trainer</td>
		<td>Training Session</td>
		<td>Ambience</td>
		<td>Trainer</td>
		<td>Cleanlines</td>
		<td>Recommendation</td>
		<td>Comment</td>
		<td>First Visit</td>
		<td>Issue</td>
	</thead>
	{guest_feedback}
</table>
</body>