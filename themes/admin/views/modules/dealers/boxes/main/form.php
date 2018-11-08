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
                    <form method="post" action="<?php echo site_url('dealers/boxes/save'); ?>">
                        
                        <input type="hidden" value="<?php echo $boxes->id; ?>" name="id">

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer</label>
                            <div class="col-9">
                                <select class="form-control select2" name="dealer">
                                    <?php foreach($dealer as $deal){ 
                                            if($deal->id == $boxes->dealer_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">IP Box</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="ipbox" value="<?php echo $boxes->ipbox; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Type</label>
                            <div class="col-7">
                                <select class="form-control select2" name="type">
                                    <option <?php if($boxes->type == 'new'){ echo 'selected'; } ?> value='new'>New</option>
                                    <option <?php if($boxes->type == 'old'){ echo 'selected'; } ?> value='old'>Old</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Slot Max</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="slot_max" value="<?php echo $boxes->slot_max; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Service</label>
                            <div class="col-9">
                                <div class="checkbox checkbox-primary">
                                    <input id="serv_reg" name="serv_reg" type="checkbox">
                                    <label for="serv_reg">
                                        REG
                                    </label>
                                </div>
                                <div class="checkbox checkbox-primary">
                                    <input id="serv_dat" name="serv_dat" type="checkbox">
                                    <label for="serv_dat">
                                        DAT
                                    </label>
                                </div>
                                <div class="checkbox checkbox-primary">
                                    <input id="serv_pkd" name="serv_pkd" type="checkbox">
                                    <label for="serv_pkd">
                                        PKD
                                    </label>
                                </div>
                                <div class="checkbox checkbox-primary">
                                    <input id="serv_pkt" name="serv_pkt" type="checkbox">
                                    <label for="serv_pkt">
                                        PKT
                                    </label>
                                </div>
                                <div class="checkbox checkbox-primary">
                                    <input id="serv_blk" name="serv_blk" type="checkbox">
                                    <label for="serv_blk">
                                        BLK
                                    </label>
                                </div>
                                <div class="checkbox checkbox-primary">
                                    <input id="serv_dlk" name="serv_dlk" type="checkbox">
                                    <label for="serv_dlk">
                                        DLK
                                    </label>
                                </div>
                                <div class="checkbox checkbox-primary">
                                    <input id="serv_tlk" name="serv_tlk" type="checkbox">
                                    <label for="serv_tlk">
                                        TLK
                                    </label>
                                </div>
                                <div class="checkbox checkbox-primary">
                                    <input id="serv_nap" name="serv_nap" type="checkbox">
                                    <label for="serv_nap">
                                        NAP
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Status</label>
                            <div class="col-7">
                                <select class="form-control select2" name="status">
                                    <option <?php if($boxes->status == 'active'){ echo 'selected'; } ?> value='active'>Active</option>
                                    <option <?php if($boxes->status == 'inactive'){ echo 'selected'; } ?> value='inactive'>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->