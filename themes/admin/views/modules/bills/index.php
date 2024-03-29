<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Iuran</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="p-20">
            <a href="<?php echo site_url('bills/add'); ?>"><button class="btn btn-sm btn-primary waves-effect waves-light">
                <i class="zmdi zmdi-collection-plus"></i> Buat Iuran Baru </button>
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
                    <div class="col-2"><input type="text" name="from" id="from" class="form-control datepicker" placeholder="From" value="<?php echo $from; ?>"></div>
                    <div class="col-2"><input type="text" name="to" id="to" class="form-control datepicker" placeholder="To" value="<?php echo $to; ?>"></div>
                    <div class="col-2"><button class="btn btn-primary">Go</button> <a href="<?php echo site_url('reporting/iuran'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
            </form>
            <table id="datatable" class="table table-striped table-bordered table-responsive" style="min-width: 1300px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Sekolah</th>
                        <th>Siswa</th>
                        <th>Tipe Pembayaran</th>
                        <th>Periode</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                        <th width="10%"></th>
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
        });

    });

    function Data(from, to){
        var url = '<?= site_url("bills/datatables"); ?>?from='+from+'&to='+to;
        $('#datatable').DataTable({ 
            // "scrollX": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "destroy": true,
            "scrollX": true,

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url,
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                    "targets": [0], //first column / numbering column
                    "data": "no",
                    "orderable": false //set not orderable
                },
                {
                    "targets": [2],
                    "data": "student"
                },
                {
                    "targets": [3],
                    "data": "bil_type",
                    "orderable": false
                },
                {
                    "targets": [4],
                    "data": "bil_period_t",
                    "orderable": false
                },
                {
                    "targets": [5],
                    "data": "bil_date"
                },
                {
                    "targets": [6],
                    "data": "status",
                    "render": function(data, type, row, meta) {
                        let html
                        if(data === "unpaid"){
                            html = '<span class="badge badge-danger">Belum Bayar</span>'
                        }else if(data === "paid"){
                            html = '<span class="badge badge-success">Sudah Bayar</span>'
                        }
                        return html
                    }
                },
                {
                    "targets": [7],
                    "data": {
                        "edit": "edit",
                        "delete": "delete"
                    },
                    "orderable": false,
                    "render": function(data, type, row, meta) {
                        return '<a href="'+data.edit+'" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a> &nbsp; <a href="javascript:void(0)" onclick="alert_delete(\''+data.delete+'\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                    }
                },
                {
                    "targets": [1],
                    "data": "school"
                }
            ]
        });
    }

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
