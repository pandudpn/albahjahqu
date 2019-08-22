<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Dashboard: <?php echo date('F Y'); ?></h4>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-12">
	    <div class="card-box">
	    	<div class="col-sm-12 col-xs-12 col-md-12">
            	<h4 class="header-title m-t-0">Jumlah Transaksi</h4>
                <p class="text-muted font-13 m-b-30"> </p>
            	<div id="zakat-chart" style="height: 550px;"></div>
            </div>
	    </div>
    </div>
</div>
<!-- end row -->

<script type="text/javascript">
	
$(document).ready(function(){
	const bulan	= ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
					'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
	var date	= new Date();
	var month	= date.getMonth();
	var year	= date.getFullYear();

	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(chart_zakat);

	function chart_zakat() {

		var jsonData = $.ajax({
			url: "<?php echo site_url('home/chart_zakat'); ?>",
			dataType: "json",
			async: false
        }).responseText;
          
      	var data = new google.visualization.DataTable(jsonData);
		
		var options = {
		title: 'Jumlah Transaksi Zakat - ' + bulan[month] + ' ' + year,
			vAxis: {
			  title: ''
			},
			hAxis:{
				format: 'decimal',
			},
			yAxis: {
				title: ''
			}
		};

		var chart = new google.visualization.ColumnChart(
		document.getElementById('zakat-chart'));

		chart.draw(data, options);
    }

});

</script>