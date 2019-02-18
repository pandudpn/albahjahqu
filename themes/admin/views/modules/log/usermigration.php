<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">User Migration Problems</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        
        <div class="card-box" style="overflow-x: auto; zoom: 0.9;">
        	<table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
	                <th>#</th>
	                <th>Name</th>
	                <th>EVA</th>
	                <th>Old Balance</th>
	                <th>Migration Balance</th>
	                <th>Migrated On</th>
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
                "url": "<?php echo site_url('log/usermigration/datatables')?>",
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
    });
</script>