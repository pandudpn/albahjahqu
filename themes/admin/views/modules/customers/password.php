<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left"><?php echo $title; ?></h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card-box" style="overflow-x: auto; zoom: 0.9;">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?> 
            
            <div class="row">
                <div class="col-12">
                    <form method="post">
                        <div class="col-6">

                        	<div class="form-group row">
                                <label for="" class="col-3 col-form-label">PIN</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="pin">
                                    <span class="text-mute">Kosongkan jika tidak ingin diubah</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Password</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="password">
                                    <span class="text-mute">Kosongkan jika tidak ingin diubah</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            <a href="<?php echo site_url('customers'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                            </a>

                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->