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
                    <form method="post" action="<?php echo site_url('dealers/clusters/save'); ?>">
                    
                    <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer</label>
                            <div class="col-7">
                                <select class="form-control" name="dealer_id" id="dealer_id">
                                    <?php foreach($dealers as $d){ 
                                            if($d->id == $data->dealer_id){
                                                echo "<option selected value='$d->id'> $d->name </option>";
                                            }else{
                                                echo "<option value='$d->id'> $d->name </option>";
                                            }
                                        } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Name</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Alias</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="alias" value="<?php echo $data->alias; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealership</label>
                            <div class="col-7">
                                <select name="area" class="form-control">
                                	<option value="wko" <?php if($data->area == 'wko') { echo 'selected'; } ?>>WKO</option>
                                	<option value="wkt" <?php if($data->area == 'wkt') { echo 'selected'; } ?>>WKT</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('dealers/clusters'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->