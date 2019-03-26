<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Topups</h4>

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
                    <div class="col-2"><input type="text" name="from" class="form-control datepicker" placeholder="From" value="<?php echo $from; ?>"></div>
                    <div class="col-2"><input type="text" name="to" class="form-control datepicker" placeholder="To" value="<?php echo $to; ?>"></div>
                    <div class="col-2">
                        <select class="form-control" name="status" style="height: 40.74px;">
                            <option value="">Choose Status</option>
                            <option value="dispute" <?php if($status == 'dispute') { echo "selected"; } ?>>Dispute / Pending</option>
                            <option value="approved" <?php if($status == 'approved') { echo "selected"; } ?>>Approved</option>
                            <option value="rejected" <?php if($status == 'rejected') { echo "selected"; } ?>>Rejected</option>
                        </select>
                    </div>
                    <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                    <div class="col-2">
                    	<select class="form-control" name="dealer" style="height: 40.74px;">
                    		<option value="">Choose Dealer</option>
                    		<?php foreach ($dealers as $key => $d) { if($d->id == $dealer) { $selected = 'selected'; }else{ $selected = ''; } ?>
                    		<option value="<?php echo $d->id; ?>" <?php echo $selected; ?>><?php echo $d->name; ?></option>
                    		<?php } ?>
                    	</select>
                    </div>
                    <?php } ?>
                    <div class="col-2"><button class="btn btn-primary">Go</button> <a href="<?php echo site_url('topups'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
                <div class="row" style="margin-bottom: 15px; margin-left: 5px;">
                    <div class="col-12">
                        <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                        <a href="<?php echo site_url('topups/user'); ?>" class="btn btn-success"><i class="fa fa-upload"></i> Simulate VA Callback / Topup Manual</a>
                    <?php } ?>
                        <a href="<?php echo site_url('topups/download?from='.$from.'&to='.$to); ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                    </div>
                </div>
            </form>

            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Dealer</th>
                    <th>Topup</th>
                    <th>Note</th>
                    <th>Image</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
		            <tr>
		                <th colspan="4" style="text-align:right">Total:</th>
		                <th colspan="4"></th>
		            </tr>
		        </tfoot>
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
<input type="hidden" name="total_sum" id="total_sum" value="<?php echo $total_sum; ?>">

<!-- Modal Alert Reject-->
<div class="modal fade bs-example-modal-sm" id="modal-alert-approve" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Approve Topup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="" id="form-approve">
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Nominal</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value="" name="base_price" id="base_price">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Approve</button>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
            
                </form>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" id="modal-show-image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Payment proof</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" class="img-fluid" id="payment-proof-img" style="margin-bottom: 10px;"><br>
                image url <input type="text" name="" id="payment-url" onfocus="this.select();">
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	var datatable = "";
    $(document).ready(function() {
        datatable = $('#datatable').DataTable({ 
            // "scrollX": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('topups/datatables?from='.$from.'&to='.$to.'&dealer='.$dealer.'&status='.$status)?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 8 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                <?php if($this->session->userdata('user')->role != 'dekape') { ?>


                { 
                "targets": [ 8 ], //first column / numbering column
                "visible": false, //set not visible
                }
                <?php } ?>
            ],
            "scrollX": false,
            "footerCallback": function ( row, data, start, end, display ) {
	            var api = this.api(), data;
	            console.log(display);
	 
	            // Remove the formatting to get integer data for summation
	            var intVal = function ( i ) {
	                return typeof i === 'string' ?
	                    i.replace(/[\Rp. ,]/g, '')*1 :
	                    typeof i === 'number' ?
	                        i : 0;
	            };
	 
	            // Total over all pages
	            total = api
	                .column( 4 )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                }, 0 );
	 
	            // Total over this page
	            pageTotal = api
	                .column( 4, { page: 'current'} )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                }, 0 );
	 
	            // Update footer
	            $( api.column( 4 ).footer() ).html(
	                'Rp. '+ pageTotal +' ( Rp. '+($("#total_sum").val())+' total)'
	            );
	        }
        });

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });

    function show_image(src)
    {
        $("#payment-proof-img").attr('src', src)
        $("#payment-url").attr('value', src)
        $("#modal-show-image").modal('show')
    }

    function approve_topup(url, base_price)
    {
    	// $("#btn-"+id).html('<i class="fa fa-spinner"></i>');

    	// $.ajax("<?php echo site_url('topups/set') ?>/"+id).done(function(data){
    	// 	console.log(data)
    	// 	datatable.ajax.reload();
    	// });

        $("#base_price").val(base_price)

        $("#form-approve").attr('action', url)
        $("#modal-alert-approve").modal('show')
    }

    function rollback_topup(id)
    {
    	// $("#btn-"+id).html('<i class="fa fa-spinner"></i>');

    	// $.ajax("<?php echo site_url('topups/rollback') ?>/"+id).done(function(data){
    	// 	console.log(data)
    	// 	datatable.ajax.reload();
    	// });
    }
</script>
