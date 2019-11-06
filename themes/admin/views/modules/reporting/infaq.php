<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Laporan Infaq</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="p-20">
            <a href="<?php echo site_url('reporting/infaq/export?from='.$from.'&to='.$to); ?>" id="excel"><button class="btn btn-sm btn-success waves-effect waves-light">
                <i class="fa fa-file-excel-o"></i> Export ke Excel </button>
            </a>
        </div>

        <div class="card-box table-responsive" style="overflow-x: auto; zoom: 0.8;">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?> 
            <form method="get" id="form">
                <div class="row" style="margin-bottom: 15px; margin-left: 5px;">
                    <div class="col-12">Filter : </div>
                    <div class="col-2"><input type="text" name="from" id="from" class="form-control datepicker" placeholder="Dari tanggal" value="<?php echo $from; ?>"></div>
                    <div class="col-2"><input type="text" name="to" id="to" class="form-control datepicker" placeholder="Hingga Tanggal" value="<?php echo $to; ?>"></div>
                    <div class="col-2"><button class="btn btn-primary">Go</button> <a href="<?php echo site_url('reporting/infaq'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
            </form>
            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end row -->

<!-- Modal Alert Delete-->
<div class="modal fade bs-example-modal-sm" id="modal-alert" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <a id="confirm" href="javascript:;" class="btn btn-danger">Yes, Delete it</a>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        Data('','');

        $('#form').submit(function(e){
            e.preventDefault();

            var from    = $('#from').val();
            var to      = $('#to').val();

            Data(from, to);

            var base_url    = '<?php echo site_url("reporting/infaq/export"); ?>';

            $('#excel').attr('href', base_url+'?from='+from+'&to='+to);
        });

    });

    function Data(from, to){
        var url = '<?= site_url("reporting/infaq/datatables"); ?>?from='+from+'&to='+to;
        $('#datatable').DataTable({ 
            // "scrollX": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "destroy": true,

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url,
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                    "targets": [0],
                    "orderable": false,
                    "data": "no"
                },
                {
                    "targets": [1],
                    "orderable": false,
                    "data": "name"
                },
                {
                    "targets": [2],
                    "orderable": false,
                    "data": "amount",
                    "render": function(data, type, row, meta) {
                        return "Rp " + data
                    }
                },
                {
                    "targets": [3],
                    "orderable": false,
                    "data": "date"
                }
            ]
        });
    }
</script>
