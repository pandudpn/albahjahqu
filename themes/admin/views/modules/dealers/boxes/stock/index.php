<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Box Stocks</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card-box">

        </div> 
    </div>
</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="p-20">
            <a href="<?php echo site_url('dealers/boxes/'.$box_id.'/stock/add'); ?>"><button class="btn btn-sm btn-primary waves-effect waves-light">
                <i class="zmdi zmdi-collection-plus"></i> Add Stock </button>
            </a>
        </div>
        <div class="card-box table-responsive" style="overflow-x: auto;">
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
                    <th>Dealer</th>
                    <th>IP Box</th>
                    <th>Slot</th>
                    <th>V1</th>
                    <th>V5</th>
                    <th>V10</th>
                    <th>V15</th>
                    <th>V20</th>
                    <th>V25</th>
                    <th>V40</th>
                    <th>V50</th>
                    <th>V80</th>
                    <th>V100</th>
                    <th>V200</th>
                    <th>V300</th>
                    <th width="90">Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div> 

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
        $('#datatable').DataTable({ 
            // "scrollX": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('dealers/boxes/'.$box_id.'/stock/datatables')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 1 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 2 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 3 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 4 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 5 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 6 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 7 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 8 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 9 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 10 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 11 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 12 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 13 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 14 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 15 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 16 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
            ]
        });
    });

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
