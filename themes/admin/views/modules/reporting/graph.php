<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Graph Reporting</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?> 
            
            <div class="row">
            	<div class="col-12">
                    <div class="row">
                    	<div class="col-4">
                    		<div class="form-group">
                    			<div class="col-12">
		                    		<select name="option" id="option" class="form-control">
		                    			<!-- <option value="">Choose Option</option> -->
		                    			<option value="daily">Daily</option>
		                    			<option value="weekly">Weekly</option>
		                    			<option value="monthly">Monthly</option>
		                    		</select>
	                    		</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
	                	<div class="col-4">
	                        <div class="form-group">
	                            <div class="col-12">
	                                <input class="form-control datepicker" type="text" id="from" value="" placeholder="From" required>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-4">
	                        <div class="form-group">
	                            <!-- <div class="col-12"> -->
	                                <input class="form-control datepicker" type="text" id="to" value="" placeholder="To" required>
	                            <!-- </div> -->
	                        </div>
	                    </div>
	                    <div class="col-4">
	                        <button type="submit" class="btn btn-primary waves-effect waves-light" onclick="reload_chart()">
	                        	<i class="fas fa-chart-pie m-r-5"></i>
	                        	View
	                    	</button>
	                    </div>
	                </div>
                </div>
            </div>

        </div>
    </div>
</div> 

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
        	<h4 class="header-title m-t-0">Revenue</h4>
            <p class="text-muted font-13 m-b-30"> </p>
            <div id="trx-chart-revenue" style="height: 400px;"></div>
            <p class="text-muted font-13 m-b-30"> </p>
            <h4 class="header-title m-t-0 text-right">Total Revenue: Rp. <span id="total_revenue">xxx.xxx.xxx</span></h4>
        </div>
    </div>
    <div class="col-12">
        <div class="card-box table-responsive">
        	<h4 class="header-title m-t-0">Revenue By Product</h4>
            <p class="text-muted font-13 m-b-30"> </p>
            <div id="trx-chart-product" style="height: 400px;"></div>
            <p class="text-muted font-13 m-b-30"> </p>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        var from 		= $("#from").val();
		var to 			= $("#to").val();
		var option 		= $("#option").val();

		google.charts.load('current', {packages: ['corechart', 'bar']});
		google.setOnLoadCallback(reload_chart);
    })

    function chart(from, to, option) {
		var jsonData = $.ajax({
			url: "<?php echo site_url('reporting/graph/revenue'); ?>?from="+from+"&to="+to+"&option="+option,
			dataType: "json",
			async: false
        }).responseText;
      
  		var data = new google.visualization.DataTable(jsonData);
  		var jsonParse = JSON.parse(jsonData);
  		// console.log(jsonParse.total);
	
		var options = {
			title: '',
			vAxis: {
			  title: ''
			},
			hAxis:{
				format: 'decimal',
			}
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('trx-chart-revenue'));

		chart.draw(data, options);

		var	number_string = jsonParse.total.toString(),
			sisa 	= number_string.length % 3,
			rupiah 	= number_string.substr(0, sisa),
			ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
				
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		$("#total_revenue").html(rupiah);

		var jsonData = $.ajax({
			url: "<?php echo site_url('reporting/graph/revenue_product'); ?>?from="+from+"&to="+to+"&option="+option,
			dataType: "json",
			async: false
        }).responseText;
      
  		var data = new google.visualization.DataTable(jsonData);
  		var jsonParse = JSON.parse(jsonData);
  		// console.log(jsonParse.total);
	
		var options = {
			title: '',
			vAxis: {
			  title: ''
			},
			hAxis:{
				format: 'decimal',
			}
		};

		var chart = new google.visualization.PieChart(document.getElementById('trx-chart-product'));

		chart.draw(data, options);
    }

	function reload_chart()
	{
		var from 		= $("#from").val();
		var to 			= $("#to").val();
		var option 		= $("#option").val();

		chart(from, to, option);
	}
</script>