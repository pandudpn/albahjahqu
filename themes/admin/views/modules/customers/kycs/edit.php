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
        <div class="card-box">
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
                                <label for="" class="col-3 col-form-label">Name</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">KTP</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="identity" value="<?php echo $data->identity; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Date of Birth</label>
                                <div class="col-7">
                                    <input type="text" name="dob" class="form-control" placeholder data-mask="9999-99-99" value="<?php echo $data->dob; ?>">
                                    <span class="font-13 text-muted">format: "yyyy-mm-dd"</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Gender</label>
                                <div class="col-7">
                                    <select class="form-control" name="gender">
                                    <option <?php if($data->gender == 'L'){ echo 'selected'; } ?> value='L'>Male</option>
                                    <option <?php if($data->gender == 'P'){ echo 'selected'; } ?> value='P'>Female</option>
                                </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            <a href="<?php echo site_url('customers/kycs'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                            </a>

                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->