<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Price Logs</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive" style="overflow-x: auto; zoom: 0.8;">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?> 
            <form method="get">
                <div class="row" style="margin-bottom: 15px; margin-left: 5px;">
                    <div class="col-12">Filter : </div>
                    <div class="col-3"><input type="text" name="from" class="form-control datepicker" placeholder="From" value="<?php echo $from; ?>"></div>
                    <div class="col-3"><input type="text" name="to" class="form-control datepicker" placeholder="To" value="<?php echo $to; ?>"></div>
                    <div class="col-3"><button class="btn btn-primary">Go</button> <a href="<?php echo site_url('prices/logs'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
            </form>

            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Dealer</th>
                    <th>Type</th>
                    <th>Action</th>
                    <th>Remarks</th>
                    <th>Time</th>
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
                <p>Are you sure want to do this?</p>
            </div>
            <div class="modal-footer">
                <a id="confirm" href="javascript:;" class="btn btn-danger">Yes</a>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({ 
            // "scrollX": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('prices/logs/datatables?from='.$from.'&to='.$to)?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
            ]
        });

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });

    function alert(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
