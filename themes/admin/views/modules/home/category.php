<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Categories</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-4">
        <div class="card-box">
            <div class="form-group row">
                <label class="col-3 col-form-label">Name</label>
                <div class="col-9">
                    <input class="form-control" type="text" value="" name="title" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12">
                    <div class="text-right">
                        <a href="#" class="btn btn-primary">Publish</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card-box table-responsive">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="80%">Name</th>
                    <th>
                    	
					</th>
                </tr>
                </thead>


                <tbody>
                    <tr>
                        <td>Lorem Ipsum dolor sit Amet</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>consectetur adipiscing elit</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Nam eget quam varius</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Lorem Ipsum 2</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Interdum et malesuada </td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
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
