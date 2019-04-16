<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">KYCs</h4>

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
                    <div class="col-2"><input type="text" name="to" class="form-control datepicker" placeholder="To" value="<?php echo $to; ?>"></div>
                    <div class="col-3">
                        <select name="status" class="form-control" style="height: 40.74px;">
                            <option value="">Choose Status</option>
                            <option value="waiting" <?php if($status == 'waiting') { echo 'selected'; } ?>>Waiting</option>
                            <option value="approved" <?php if($status == 'approved') { echo 'selected'; } ?>>Approved</option>
                            <option value="rejected" <?php if($status == 'rejected') { echo 'selected'; } ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-2"><button class="btn btn-primary">Go</button> <a href="<?php echo site_url('customers/kycs'); ?>" class="btn btn-secondary">Reset</a></div>
                    <div class="col-2 pull-right text-right">
                        <a href="<?php echo site_url('customers/kycs/download?from='.$from.'&to='.$to.'&status='.$status); ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                    </div>
                </div>
            </form>
            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>KTP</th>
                    <th>Mother</th>
                    <th>Job</th>
                    <th>KTP Image</th>
                    <th>Selfie Image</th>
                    <th>Decision</th>
                    <th>Remarks</th>
                    <th>Date Submitted</th>
                    <th>Last Updated</th>
                    <th>Action</th>
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
                <h5 class="modal-title" id="mySmallModalLabel">Are you sure want to do this?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="kyc_id" name="kyc_id">
                <input type="hidden" id="kyc_status" name="kyc_status">
                <div class="form-group row">
                    <label for="media" class="col-3 col-form-label">Reason</label>
                    <div class="col-9">
                        <textarea class="form-control form-control-sm" rows="4" style="width:100%" name="kyc_remarks"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="confirm_status" href="javascript:;" class="btn btn-danger">Yes</a>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal IMG-->
<div class="modal fade bs-example-modal-sm" id="modal-img" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" class="img-fluid" id="image-src">
            </div>
            <div class="modal-footer">
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
                "url": "<?php echo site_url('customers/kycs/datatables?from='.$from.'&to='.$to.'&status='.$status)?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 12 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
            ],
            "scrollX": true,
            initComplete: function(settings, json){
                setTimeout(function(){
                    $('.dataTables_scrollBody table thead tr th.sorting').css('visibility', 'hidden');  
                }, 300);
            }
        });

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $('#datatable').css('min-height','400px');
    });

    function alert(status, id)
    {
        //$("#confirm").attr('href', url)
        $("#kyc_id").val(id);
        $("#kyc_status").val(status);
        $("#modal-alert").modal('show');

    }

    $("#confirm_status").on('click', function(e){
        var kyc_id      = $('[name="kyc_id"]').val();
        var kyc_remarks = $('[name="kyc_remarks"]').val();
        var kyc_status  = $('[name="kyc_status"]').val();

        $.ajax({
            type: "POST",
            url: '<?php echo site_url().'customers/kycs/status'; ?>',
            data: { kyc_id:kyc_id, kyc_remarks:kyc_remarks, kyc_status:kyc_status },
            success: function (res) {
                console.log(res);
                location.reload();
            }
        });
    });

    function showimg(url)
    {
        $("#image-src").attr('src', '');
        $("#image-src").attr('src', url);

        $("#modal-img").modal('show');
    }
</script>
