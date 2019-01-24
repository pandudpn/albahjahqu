<script src="<?php echo $this->template->get_theme_path();?>assets/js/renderjson.js"></script>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Log Request</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        
        <div class="card-box" style="overflow-x: auto; zoom: 0.9;">
        	<form method="get">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-12">Filter : </div>
                    <div class="col-3">
                        <select class="form-control" name="service" id="service">
                            <option value="">Choose Services</option>
                            <option value="billing_request">Billing</option>
                            <option value="credit_request">Credit</option>
                            <option value="electricity_request">Electricity</option>
                            <option value="voucher_request">Voucher</option>
                        </select>
                    </div>
                    <!-- <div class="col-3">
                    	<input type="text" class="form-control" name="trx_code" id="trx_code" placeholder="Transaction Code">
                    </div> -->
                    <div class="col-2">
                    	<a href="javascript:;" class="btn btn-primary" onclick="showdata()"><i class="fa fa-search"></i> Go</a> 
                    	<a href="<?php echo site_url('log/request'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
            </form>
            <table class="table" style="width: 1500px;">
	            <thead>
		            <tr>
		                <th>#</th>
		                <th>User</th>
		                <th>Transaction</th>
		                <th>Price</th>
		                <th>Remarks</th>
		                <th>Created On</th>
		            </tr>
	            </thead>
	            <tbody id="body-table">
	            	<tr><td colspan="8">no data found</td></tr>
	            </tbody>
	        </table>

        </div>
    </div>
</div> <!-- end row -->
<script>

	function showdata()
	{
		$("#body-table").html('')
		$("#body-table").html('loading... ')

		var service 	= $("#service").val()
		var trx_code 	= $("#trx_code").val()

		if(service == '')
		{
			$("#body-table").html('<tr><td colspan="8">no data found</td></tr>')
			return false
		}

		$.post("https://admin.okbabe.id/log/get_data/"+ service, { trx_code: trx_code})
		 .done(function(data){
			var data = data.data
			var num  = 1

			$("#body-table").html('')

			if(data.length > 0)
			{
				for (var i = data.length - 1; i >= 0; i--) {
					// console.log(data[i])

					var table = '<tr>'+
					                '<th scope="row">'+num+'</th>'+
					                '<td>'+
					                	'<div id="user_'+data[i]._id.$id+'"></div>'+
					                '</td>'+
					                '<td>'+
					                	'<div id="transaction_'+data[i]._id.$id+'"></div>'+
					                '</td>'+
					                '<td>'+
					                	'<div id="price_'+data[i]._id.$id+'"></div>'+
					                '</td>'+
					                '<td>'+data[i].remarks+'</td>'+
					                '<td>'+data[i].created_on+'</td>'+
					            '</tr>';

					$("#body-table").append(table)

					document.getElementById('user_'+data[i]._id.$id).appendChild(
				        renderjson(
				        	data[i].user
				        )
				    )

				    document.getElementById('transaction_'+data[i]._id.$id).appendChild(
				        renderjson(
				        	data[i].transaction
				        )
				    )

				    document.getElementById('price_'+data[i]._id.$id).appendChild(
				        renderjson(
				        	data[i].price
				        )
				    )

					num++
				}
			}
			else
			{
				$("#body-table").html('<tr><td colspan="8">no data found</td></tr>')
			}
		})
	}
</script>
