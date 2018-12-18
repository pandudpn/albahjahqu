<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Dashboard: <?php echo date('F'); ?></h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
    	<div class="card-box">
	        <div class="row">
	            <div class="col-sm-12 col-xs-12 col-md-12">
	                <h4 class="header-title m-t-0">Jumlah Transaksi</h4>
	                <p class="text-muted font-13 m-b-30"> </p>
	                <div id="trx-chart" style="height: 400px;"></div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="col-12">
    	<div class="card-box">
	        <div class="row">
	            <div class="col-sm-12 col-xs-12 col-md-12">
	                <h4 class="header-title m-t-0">Total Transaksi</h4>
	                <p class="text-muted font-13 m-b-30"> </p>
	                <div id="trx-total-chart" style="height: 400px;"></div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="col-6">
	    <div class="card-box">
	    	<div class="col-sm-12 col-xs-12 col-md-12">
            	<h4 class="header-title m-t-0">Dealer Sales</h4>
                <p class="text-muted font-13 m-b-30"> </p>
            	<div id="dealer-chart" style="height: 400px;"></div>
            </div>
	    </div>
    </div>
    <div class="col-6">
	    <div class="card-box">
	    	<div class="col-sm-12 col-xs-12 col-md-12">
            	<h4 class="header-title m-t-0">Topup</h4>
                <p class="text-muted font-13 m-b-30"> </p>
            	<div id="topup-chart" style="height: 400px;"></div>
            </div>
	    </div>
    </div>
    <div class="col-12">
	    <div class="card-box">
	    	<div class="col-sm-12 col-xs-12 col-md-12">
            	<h4 class="header-title m-t-0">Produk Sales</h4>
                <p class="text-muted font-13 m-b-30"> </p>
            	<div id="product-sales-chart" style="height: 450px;"></div>
            </div>
	    </div>
    </div>
</div>


<script type="text/javascript">
	
$(document).ready(function(){

	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawtrxchart);

	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawtrxtotalchart);

	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawdealerchart);

	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawtopupchart);

	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawproductsales);

	function drawtrxchart() {

		var jsonData = $.ajax({
			url: "<?php echo site_url('home/trx_chart'); ?>",
			dataType: "json",
			async: false
        }).responseText;
          
      	var data = new google.visualization.DataTable(jsonData);
		
		var options = {
		title: 'Jumlah Transaksi Bulan <?php echo date('F'); ?>',
			vAxis: {
			  title: ''
			},
			hAxis:{
				format: 'decimal',
			}
		};

		var chart = new google.visualization.ColumnChart(
		document.getElementById('trx-chart'));

		chart.draw(data, options);
    }

    function drawtrxtotalchart() {

		var jsonData = $.ajax({
			url: "<?php echo site_url('home/trx_total_chart'); ?>",
			dataType: "json",
			async: false
        }).responseText;
          
      	var data = new google.visualization.DataTable(jsonData);

		var options = {
		title: 'Total Transaksi Bulan <?php echo date('F'); ?>',
			vAxis: {
			  title: ''
			},
			hAxis:{
				format: 'decimal',
			}
		};

		var chart = new google.visualization.ColumnChart(
		document.getElementById('trx-total-chart'));

		chart.draw(data, options);
    }

    function drawdealerchart() {
      	var jsonData = $.ajax({
			url: "<?php echo site_url('home/dealer_chart'); ?>",
			dataType: "json",
			async: false
        }).responseText;
          
      	var data = new google.visualization.DataTable(jsonData);

      var options = {
        title: 'Dealer Sales',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total Sales',
          minValue: 0
        },
        vAxis: {
          title: 'Dealer'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('dealer-chart'));
      chart.draw(data, options);
    }

    function drawtopupchart() {

		var jsonData = $.ajax({
			url: "<?php echo site_url('home/topup_chart'); ?>",
			dataType: "json",
			async: false
        }).responseText;
          
      	var data = new google.visualization.DataTable(jsonData);

		var options = {
		title: 'Topup dan Sales',
			vAxis: {
			  title: 'Topup dan Sales'
			},
			hAxis:{
				format: 'decimal',
			}
		};

		var chart = new google.visualization.ColumnChart(
		document.getElementById('topup-chart'));

		chart.draw(data, options);
    }

    function drawproductsales() {

        var jsonData = $.ajax({
			url: "<?php echo site_url('home/product_sales_chart'); ?>",
			dataType: "json",
			async: false
        }).responseText;
         
      	var data = new google.visualization.DataTable(jsonData);

        var options = {
          title: 'Product Sales'
        };

        var chart = new google.visualization.PieChart(document.getElementById('product-sales-chart'));
        chart.draw(data, options);
    }

});

</script>