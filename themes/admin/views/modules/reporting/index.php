<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Generate Reporting</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?> 
            
            <div class="row">
                <div class="col-6">
                    <form method="post" action="<?php echo site_url('reporting/generate'); ?>" target="_blank">
                    
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">From</label>
                            <div class="col-7">
                                <input class="form-control datepicker" type="text" name="from" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">To</label>
                            <div class="col-7">
                                <input class="form-control datepicker" type="text" name="to" value="" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Generate</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> 
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    })
</script>