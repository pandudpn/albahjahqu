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
                        <div class="col-8">

                            <div class="form-group row">
                                <small class="text-muted"> Profile </small>
                            </div>

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
                                <label for="" class="col-3 col-form-label">Email</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="email" value="<?php echo $data->email; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Level</label>
                                <div class="col-7">
                                    <select name="level" class="form-control">
                                        <option value="">Choose Level</option>
                                        <option value="dealer" <?php if($data->level == 'dealer') { echo 'selected'; } ?>>Dealer</option>
                                        <option value="outlet" <?php if($data->level == 'outlet') { echo 'selected'; } ?>>Outlet</option>
                                        <option value="staff" <?php if($data->level == 'staff') { echo 'selected'; } ?>>Staff / Karyawan</option>
                                        <option value="agent" <?php if($data->level == 'agent') { echo 'selected'; } ?>>Agent</option>
                                        <option value="enduser" <?php if($data->level == 'enduser') { echo 'selected'; } ?>>End User / Customer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Referral Code</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="referral_code" value="<?php echo $data->referral_code; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <small class="text-muted"> Outlet </small>
                            </div>

                        	<div class="form-group row">
                                <label for="" class="col-3 col-form-label">Outlet Number</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="outlet_number" value="<?php echo $data->outlet_number; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Outlet Name</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="outlet_name" value="<?php echo $data->outlet_name; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <small class="text-muted"> Password </small>
                            </div>

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