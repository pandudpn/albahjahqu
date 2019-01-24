<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Log Migration</h4>

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
                    <!-- <div class="col-3">
                        <select class="form-control" name="service" id="service">
                            <option value="">Choose Services</option>
                            <option value="billing_request">Billing</option>
                            <option value="credit_request">Credit</option>
                            <option value="electricity_request">Electricity</option>
                            <option value="voucher_request">Voucher</option>
                        </select>
                    </div> -->
                    <div class="col-3">
                    	<input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
                    </div>
                    <div class="col-2">
                    	<a href="javascript:;" class="btn btn-primary" onclick="showdata()"><i class="fa fa-search"></i> Go</a> 
                    	<a href="<?php echo site_url('log/migration'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
            </form>
            <table class="table" style="width: 1500px;">
	            <thead>
		            <tr>
		                <th>#</th>
		                <th>Uid</th>
		                <th>Name</th>
		                <th>Phone</th>
		                <th>State</th>
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

		var phone 	= $("#phone").val()

		// if(phone == '')
		// {
		// 	$("#body-table").html('<tr><td colspan="8">no data found</td></tr>')
		// 	return false
		// }

		$.post("https://admin.okbabe.id/log/get_user_migration/user_migration", { phone: phone})
		 .done(function(data){
			var data = data.data
			var num  = data.length

			$("#body-table").html('')

			if(data.length > 0)
			{
				for (var i = data.length - 1; i >= 0; i--) {
					// console.log(data[i])

					var table = '<tr>'+
					                '<th scope="row">'+num+'</th>'+
					                '<td>'+data[i].uid+'</td>'+
					                '<td>'+data[i].name+'</td>'+
					                '<td>'+data[i].phone+'</td>'+
					                '<td>'+data[i].state+'</td>'+
					                '<td>'+timeConverter((data[i].created_on.$date.$numberLong).substring(0, 10))+'</td>'+
					            '</tr>';

					$("#body-table").prepend(table)

					num--
				}
			}
			else
			{
				$("#body-table").html('<tr><td colspan="8">no data found</td></tr>')
			}
		})
	}

	function timeConverter(UNIX_timestamp){
	  var a = new Date(UNIX_timestamp * 1000);
	  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	  var year = a.getFullYear();
	  var month = months[a.getMonth()];
	  var date = a.getDate();
	  var hour = a.getHours();
	  var min = a.getMinutes();
	  var sec = a.getSeconds();
	  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
	  return time;
	}

	$(document).ready(function(){
		showdata()
	})
</script>
