<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Sub Category</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>
                    	<a href="<?php echo site_url('subcategory/add'); ?>" class="btn waves-effect btn-info"> <i class="fa fa-plus"></i> </a>
					</th>
                </tr>
                </thead>


                <tbody>
                	<?php foreach ($lists as $key => $l) { ?>
                	<tr>
                		<td><?php echo $l->name; ?></td>
                		<td><?php echo $l->type; ?></td>
                		<td>
	                    	<a href="<?php echo site_url('subcategory/edit/'.$l->id); ?>" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
	                    	<a href="javascript:;" class="btn waves-effect btn-danger" onclick="alert_delete('<?php echo site_url('subcategory/delete/'.$l->id); ?>')"> <i class="fa fa-trash"></i> </a>
	                    </td>
	                </tr>
                	<?php } ?>
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
                <a id="confirm" href="javascript:;" class="btn btn-primary">Yes, Delete it</a>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({
        	ordering: false
        });
    } );

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
