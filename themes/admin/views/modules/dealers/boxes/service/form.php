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
                    <form method="post" action="<?php echo site_url('dealers/boxes/service/save'); ?>">
                        
                        <input type="hidden" value="<?php echo $boxes->id; ?>" name="box_id">
                        <input type="hidden" value="<?php echo $service_boxes->id; ?>" name="id">

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer Name</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="dealer_name" value="<?php echo $boxes->dealer_name; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">IP Box</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="ipbox" value="<?php echo $boxes->ipbox; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Type</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="type" value="<?php echo $boxes->type; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Operator</label>
                            <div class="col-9">
                                <select class="form-control select2" name="operator">
                                    <?php foreach($provider as $prov){ 
                                        if($prov->alias == $service_boxes->operator){
                                            echo "<option selected='selected' value='$prov->alias'>$prov->name ($prov->alias)</option>";
                                        }else{
                                            echo "<option value='$prov->alias'>$prov->name ($prov->alias)</option>";
                                        }
                                        } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Service Type</label>
                            <div class="col-9">
                                <select class="form-control" name="service_type">
                                    <option <?php if($service_boxes->category == 'REG'){ echo 'selected'; } ?> value='REG'>REG</option>
                                    <option <?php if($service_boxes->category == 'DAT'){ echo 'selected'; } ?> value='DAT'>DAT</option>
                                    <option <?php if($service_boxes->category == 'PKD'){ echo 'selected'; } ?> value='PKD'>PKD</option>
                                    <option <?php if($service_boxes->category == 'PKT'){ echo 'selected'; } ?> value='PKT'>PKT</option>
                                    <option <?php if($service_boxes->category == 'BLK'){ echo 'selected'; } ?> value='BLK'>BLK</option>
                                    <option <?php if($service_boxes->category == 'DLK'){ echo 'selected'; } ?> value='DLK'>DLK</option>
                                    <option <?php if($service_boxes->category == 'TLK'){ echo 'selected'; } ?> value='TLK'>TLK</option>
                                    <option <?php if($service_boxes->category == 'NAP'){ echo 'selected'; } ?> value='NAP'>NAP</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Coverage</label>
                            <div class="col-9">
                                <select class="form-control" name="service_coverage">
                                    <option <?php if($service_boxes->category == 'inner'){ echo 'selected'; } ?> value='inner'>Inner</option>
                                    <option <?php if($service_boxes->category == 'outer'){ echo 'selected'; } ?> value='outer'>Outer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">MSISDN</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="msisdn" value="<?php echo $service_boxes->msisdn; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">PIN SIM</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="pinsim" value="<?php echo $service_boxes->pinsim; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Status</label>
                            <div class="col-7">
                                <select class="form-control select2" name="status">
                                    <option <?php if($service_boxes->status == 'active'){ echo 'selected'; } ?> value='active'>Active</option>
                                    <option <?php if($service_boxes->status == 'inactive'){ echo 'selected'; } ?> value='inactive'>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('dealers/boxes/'.$boxes->id.'/service'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->