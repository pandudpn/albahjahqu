<script src="<?php echo $this->template->get_theme_path();?>assets/js/renderjson.js"></script>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Log</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        
        <div class="card-box table-responsive">
        	<form method="get">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-12">Filter : </div>
                    <div class="col-3">
                        <select class="form-control" name="service" id="service">
                            <option value="">Choose Services</option>
                            <option value="billing">Billing</option>
                            <option value="credit">Credit</option>
                            <option value="electricity">Electricity</option>
                            <option value="user">User</option>
                            <option value="user_migration">User Migration</option>
                            <option value="voucher">Voucher</option>
                        </select>
                    </div>
                    <div class="col-3">
                    	<input type="text" class="form-control" name="trx_code" id="trx_code" placeholder="Transaction Code">
                    </div>
                    <div class="col-2">
                    	<a href="javascript:;" class="btn btn-primary"><i class="fa fa-search"></i> Go</a> 
                    	<a href="<?php echo site_url('log'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
            </form>
            <table class="table">
            <thead>
	            <tr>
	                <th>#</th>
	                <th>User</th>
	                <th>Payload</th>
	                <th>Reference</th>
	                <th>Type</th>
	                <th>Partner</th>
	                <th>Remarks</th>
	                <th>Created On</th>
	            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">1</th>
                <td>
                	<div id="user"></div>
                </td>
                <td>
                	<div id="payload"></div>
                </td>
                <td>@mdo</td>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                <td>@mdo</td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                <td>@mdo</td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                <td>@mdo</td>
            </tr>
            </tbody>
        </table>

        </div>
    </div>
</div> <!-- end row -->
<script>
    document.getElementById("user").appendChild(
        renderjson(
        	{
		        "id" : "51",
		        "account" : "6287823095790"
		    }
        )
    );

    document.getElementById("payload").appendChild(
        renderjson(
        	{
		        "mitracomm" : {
		            "transaction" : {
		                "destination_no" : "0201020006"
		            },
		            "request" : {
		                "partner_id" : "DEKAPE",
		                "terminal_type" : "6012",
		                "product_code" : "2029",
		                "date_time" : "20190116050341",
		                "trx_id" : "001547632917",
		                "terminal_id" : "00013512",
		                "signature_id" : "f4ec3c5946769cfa386d76a1023f44d60e3bd58a",
		                "data" : {
		                    "cust_id" : "0201020006",
		                    "cust_name" : "ISRUN",
		                    "repeat" : "01",
		                    "detail" : {
		                        "period" : "201812",
		                        "amount" : "28500",
		                        "penalty" : "0"
		                    }
		                }
		            }
		        }
		    }
        )
    );
</script>
