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
                            <option value="open" <?php if($status == 'open') { echo "selected"; } ?>>Open</option>
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
            </form>

            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Coordinate</th>
                    <th>Dealer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end row -->

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
                "url": "<?php echo site_url('topups/pickup/datatables?from='.$from.'&to='.$to.'&dealer='.$dealer.'&status='.$status)?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                }
            ],
            "scrollX": false
        });

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });
</script>
