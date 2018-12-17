<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Dashboard: Desember</h4>

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
	                <h4 class="header-title m-t-0">Transaksi</h4>
	                <p class="text-muted font-13 m-b-30"> </p>
	                <div id="trx-chart" style="height: 400px;"></div>
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
	google.charts.setOnLoadCallback(drawdealerchart);

	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawtopupchart);

	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawproductsales);

	function drawtrxchart() {

		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Tanggal');
      	data.addColumn('number', 'Total Transaksi');

      	data.addRows([
			['1 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['2 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['3 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['4 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['5 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['6 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['7 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['8 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['9 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['10 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['11 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['12 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['13 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['14 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['15 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['16 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['17 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['18 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['19 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['20 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['21 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['22 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['23 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['24 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['25 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['26 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['27 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['28 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['29 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['30 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000],
			['31 Desember', Math.floor(Math.random() * (10000 - 1000 + 1)) + 1000]
		]);

		var options = {
		title: 'Transaksi Bulan Desember',
			vAxis: {
			  title: 'Total Transaksi'
			},
			hAxis:{
				format: 'decimal',
			}
		};

		var chart = new google.visualization.ColumnChart(
		document.getElementById('trx-chart'));

		chart.draw(data, options);
    }

    function drawdealerchart() {
      var data = google.visualization.arrayToDataTable([
			['Dealer', 'Sales'],
			['OKBABE', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000],
			['BANGGAI', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000],
			['ARDAN', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000],
			['SINARTELEKOM', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000],
			['GRATIKA', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000],
			['WIDODO', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000],
			['DISACITRA', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000],
			['CITRAINDAH', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000],
			['DEKAPE', Math.floor(Math.random() * (100000000 - 100000 + 1)) + 100000]
      ]);

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

		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Keterangan');
      	data.addColumn('number', 'Jumlah');

      	data.addRows([
			['Topup', Math.floor(Math.random() * (100000000 - 10000000 + 1)) + 10000000],
			['Sales', Math.floor(Math.random() * (100000000 - 10000000 + 1)) + 10000000]
		]);

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

        var data = google.visualization.arrayToDataTable([
          ['Product', 'Total'],
          ['PLN', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Cable Tv', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Insurance', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Pajak', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Multi Finance ', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Voucher Game', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Zakat', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Internet', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Pulsa', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Pulsa Postpaid', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['Fixedline', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['PDAM', Math.floor(Math.random() * (10000 - 1 + 1)) + 1],
          ['PBB', Math.floor(Math.random() * (10000 - 1 + 1)) + 1]
        ]);

        var options = {
          title: 'Product Sales'
        };

        var chart = new google.visualization.PieChart(document.getElementById('product-sales-chart'));
        chart.draw(data, options);
    }

});

</script>