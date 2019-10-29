

<style type="text/css">
body{
	background: #ebebeb;
	font-family: "arial","helvetica","sans-serif";
	font-size: 14px;
}
h1, p{
	margin: 0; padding: 0;
}
.container{
	width: 600px;
	margin: 0 auto;
	background: #ffffff;
	border-radius: 5px;
}
	.header{
		background: #c82a27;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		text-align: center;
		color: #ffffff;
	}
		.header h1{
			font-size: 18px;
			padding: 20px 0 0 0;
		}
		.header p{
			padding-bottom: 20px;
		}
	.footer{
		text-align: center;
		padding: 20px;
		background: #c82a27;
		border-bottom-left-radius: 5px;
		border-bottom-right-radius: 5px;
		margin-top: 20px;
		color: #ffffff;
	}
table{
	width: 50%;
	margin: 20px auto 0 auto;
}
table tr.blue{
	background: #e9f4ff;
}
table tr td{
	padding: 5px;
	font-size: 14px;
}
table tr td:first-child{
	width: 70%;
}
table tr td:last-child{
	width: 30%;
	text-align: right;
}

@media(max-width:768px){
	.container{
		width: 100%;
	}
	.header{
		width: 100%;
	}
	.content table{
		width: 100%;
	}
}
</style>
<div class="container">
	<div class="header">
		<h1>REGISTER CLOSURE REPORT {branch_name}</h1>
		<p>close on {closing_date} by {close_admin_name}</p>
	</div>

	<div class="content">
<table>
	<tr>
		<td>Opening Balance</td>
		<td>{opening_balance}</td>
	</tr>
	<tr class="blue">
		<td><strong>Cash Collection</strong></td>
		<td><strong>{cash_collection}</strong></td>
	</tr>
	<tr>
		<td>To Petty Cash</td>
		<td>{to_pettycash}</td>
	</tr>
	<tr class="blue">
		<td>Cash Adjustment</td>
		<td>{cash_adjustment}</td>
	</tr>
	<tr>
		<td>Cash Deposit</td>
		<td>{cash_deposit}</td>
	</tr>
	<tr class="blue">
		<td><strong>Closing Balance</strong></td>
		<td>{closing_balance}</td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<tr class="blue">
		<td>Cards Collection</td>
		<td>{cards_collection}</td>
	</tr>
	<tr>
		<td>Cards Adjustment</td>
		<td>{cards_adjustment}</td>
	</tr>
	<tr class="blue">
		<td>Checks Collection</td>
		<td>{check_collection}</td>
	</tr>
	<tr>
		<td><strong>Business Breakdown</strong></td>
		<td></td>
	</tr>
	<tr class="blue">
		<td>Services</td>
		<td>{services}</td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td>Products</td>
		<td>{products}</td>
	</tr>
	<tr class="blue">
		<td>Memberships</td>
		<td>{memberships}</td>
	</tr>
	<tr>
		<td>Packages</td>
		<td>{packages}</td>
	</tr>
	<tr class="blue">
		<td>Gift Cards</td>
		<td>{gift_cards}</td>
	</tr>
	<tr>
		<td>Pre-paid Cards</td>
		<td>{prepaid_cards}</td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<tr class="blue">
		<td><strong>Appointments Booked</strong></td>
		<td></td>
	</tr>
	<tr>
		<td>Tomorow</td>
		<td>{appointment_tomorow}</td>
	</tr>
	<tr class="blue">
		<td>Day After Tomorow</td>
		<td>{appointment_day_after_tomorow}</td>
	</tr>
</table>
	</div>

	<div class="footer">
		<p>this email was sent by 20fit system</p>
	</div>
</div>