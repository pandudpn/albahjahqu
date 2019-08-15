<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Dashboard: <?php echo date('F Y'); ?></h4>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

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
		title: 'Jumlah Transaksi',
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
			title: 'Total Transaksi',
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