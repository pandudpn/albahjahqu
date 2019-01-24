<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">TMS Callback Logs</h4>

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
            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Trx Code</th>
                    <th>IP Box</th>
                    <th>Slot</th>
                    <th>USSD Response</th>
                    <th>IP Address</th>
                    <th>Created On</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end row -->

<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({ 
            // "scrollX": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('log/tms_datatables')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                }
            ]
        });
    });

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
