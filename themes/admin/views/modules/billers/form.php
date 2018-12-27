<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left"><?php echo $title; ?></h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive" style="overflow-x: auto; zoom: 0.9;">
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
                    <form method="post" action="<?php echo site_url('billers/save'); ?>">
                    
                    <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Name</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Code</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="code" value="<?php echo $data->code; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">PIC Name</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="pic" value="<?php echo $data->pic; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">PIC Phone</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="pic_phone" value="<?php echo $data->pic_phone; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">PIC Email</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="pic_email" value="<?php echo $data->pic_email; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Date Joined</label>
                            <div class="col-7">
                                <input class="form-control datepicker" type="text" name="date_joined" value="<?php echo $data->date_joined; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Note</label>
                            <div class="col-7">
                                <textarea name="note" class="form-control"><?php echo $data->note; ?></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('billers'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
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