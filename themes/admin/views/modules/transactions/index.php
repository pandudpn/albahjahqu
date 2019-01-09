<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Transactions</h4>

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
                    <div class="col-3"><button class="btn btn-primary">Go</button> <a href="<?php echo site_url('transactions'); ?>" class="btn btn-secondary">Reset</a></div>
                    <div class="col-3 pull-right text-right">
                        <a href="<?php echo site_url('transactions/download?from='.$from.'&to='.$to); ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                    </div>
                </div>
            </form>
            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>TRX Code</th>
                    <th>Product</th>
                    <th>Biller</th>
                    <th>SN / Token</th>
                    <th>Customer</th>
                    <th>Destination Number</th>
                    <th>Selling Price</th>
                    <th>Base Price</th>
                    <th>Dealer Fee</th>
                    <th>Biller Fee</th>
                    <th>Dekape Fee</th>
                    <th>Partner Fee</th>
                    <th>User Fee</th>
                    <th>User Cashback</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Action</th>
                    <!-- <th style="width: 120px;">
                    	<a href="<?php echo site_url('menu/create'); ?>" class="btn waves-effect btn-info btn-sm"> <i class="fa fa-plus"></i> </a>
					</th> -->
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>No</th>
                    <th>TRX Code</th>
                    <th>Product</th>
                    <th>Biller</th>
                    <th>SN / Token</th>
                    <th>Customer</th>
                    <th>Destination Number</th>
                    <th>Selling Price</th>
                    <th>Base Price</th>
                    <th>Dealer Fee</th>
                    <th>Biller Fee</th>
                    <th>Dekape Fee</th>
                    <th>Partner Fee</th>
                    <th>User Fee</th>
                    <th>User Cashback</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Action</th>
                    <!-- <th style="width: 120px;">
                        <a href="<?php echo site_url('menu/create'); ?>" class="btn waves-effect btn-info btn-sm"> <i class="fa fa-plus"></i> </a>
                    </th> -->
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div> <!-- end row -->

<!-- Modal Alert Reject-->
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
                <a id="confirm" href="javascript:;" class="btn btn-primary">Yes</a>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alert Approve-->
<div class="modal fade bs-example-modal-sm" id="modal-alert-approve" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Approve Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="" id="form-approve">
                    <div class="form-group row">
                        <label class="col-2 col-form-label">SN</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value="" name="ref_code">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Token</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value="" name="token_code">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Yes</button>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
            
                </form>
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
                "url": "<?php echo site_url('transactions/datatables?from='.$from.'&to='.$to)?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
            ],
            "scrollX": true
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

    function alert_approve(url)
    {
        $("#form-approve").attr('action', url)
        $("#modal-alert-approve").modal('show')
    }
</script>
