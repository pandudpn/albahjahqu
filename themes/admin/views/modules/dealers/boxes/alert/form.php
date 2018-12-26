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
                    <form method="post" action="<?php echo site_url('dealers/boxes/alert/save'); ?>">
                    
                    <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Slotbox</label>
                            <div class="col-7">
                                <select class="form-control select2" name="slotbox" id="slotbox">
                                	<option value="">Dealer | IPbox | Type | Slot | Service | Coverage</option>
                                    <?php foreach($box_services as $b){ 
                                            if($b->id == $data->slotbox){
                                                echo "<option selected value='$b->id'> $b->dealer_name | $b->ipbox | $b->type | $b->slot | $b->service_type | $b->service_coverage </option>";
                                            }else{
                                                echo "<option value='$b->id'> $b->dealer_name | $b->ipbox | $b->type | $b->slot | $b->service_type | $b->service_coverage </option>";
                                            }
                                        } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Denom</label>
                            <div class="col-7">
                                <select class="form-control select2" name="denom" id="denom">
                                	<option value="">Pilih Denom</option>
                                    <option value="v1" <?php if($data->denom == 'v1') { echo 'selected'; } ?>>v1</option>
                                    <option value="v5" <?php if($data->denom == 'v5') { echo 'selected'; } ?>>v5</option>
                                    <option value="v10" <?php if($data->denom == 'v10') { echo 'selected'; } ?>>v10</option>
                                    <option value="v15" <?php if($data->denom == 'v15') { echo 'selected'; } ?>>v15</option>
                                    <option value="v20" <?php if($data->denom == 'v20') { echo 'selected'; } ?>>v20</option>
                                    <option value="v25" <?php if($data->denom == 'v25') { echo 'selected'; } ?>>v25</option>
                                    <option value="v40" <?php if($data->denom == 'v40') { echo 'selected'; } ?>>v40</option>
                                    <option value="v50" <?php if($data->denom == 'v50') { echo 'selected'; } ?>>v50</option>
                                    <option value="v80" <?php if($data->denom == 'v80') { echo 'selected'; } ?>>v80</option>
                                    <option value="v100" <?php if($data->denom == 'v100') { echo 'selected'; } ?>>v100</option>
                                    <option value="v200" <?php if($data->denom == 'v200') { echo 'selected'; } ?>>v200</option>
                                    <option value="v300" <?php if($data->denom == 'v300') { echo 'selected'; } ?>>v300</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">First Alert</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="first_alert" value="<?php echo $data->first_alert; ?>" placeholder="50">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Second Alert</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="second_alert" value="<?php echo $data->second_alert; ?>" placeholder="25">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Third Alert</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="third_alert" value="<?php echo $data->third_alert; ?>" placeholder="10">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Status</label>
                            <div class="col-7">
                                <select name="status" class="form-control">
                                	<option value="active" <?php if($data->status == 'active') { echo 'selected'; } ?>>Active</option>
                                	<option value="inactive" <?php if($data->status == 'inactive') { echo 'selected'; } ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('dealers/boxes/alert'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->