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
                    <form method="post" action="<?php echo site_url('prices/bulk/save'); ?>">
                        <input type="hidden" value="<?php echo $bulk->id; ?>" name="id">

                        <div class="col-6">
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Operator</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="operator">
                                        <?php foreach($provider as $prov){ 
                                            if($prov->alias == $bulk->operator){
                                                echo "<option selected='selected' value='$prov->alias'>$prov->name ($prov->alias)</option>";
                                            }else{
                                                echo "<option value='$prov->alias'>$prov->name ($prov->alias)</option>";
                                            }
                                         } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dealership</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="dealership" id="dealership">
                                        <option <?php if($bulk->dealership == 'dealer'){ echo 'selected'; } ?> value='dealer'>Dealer</option>
                                        <option <?php if($bulk->dealership == 'biller'){ echo 'selected'; } ?> value='biller'>Biller</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="container-dealer" <?php if($bulk->dealership != 'dealer'  && !empty($denom->supplier)){ echo 'style="display:none;"'; } ?>>
                                <label for="" class="col-3 col-form-label">Dealer name</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="dealer">
                                        <?php foreach($dealer as $deal){
                                            if($deal->id == $bulk->dealer_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="container-biller" <?php if($bulk->dealership != 'biller' || empty($bulk->dealership)){ echo 'style="display:none;"'; } ?>>
                                <label for="" class="col-3 col-form-label">Biller name</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="biller">
                                        <?php foreach($biller as $bill){ 
                                            if($bill->id == $bulk->biller_id){
                                                echo "<option selected value='$bill->id'> $bill->name </option>";
                                            }else{
                                                echo "<option value='$bill->id'> $bill->name </option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Service</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="service">
                                        <?php foreach($service_code as $service){ 
                                            if($service->id == $bulk->service_id){
                                                echo "<option selected value='$service->id'> $service->remarks </option>";
                                            }else{
                                                echo "<option value='$service->id'> $service->remarks </option>";
                                            }
                                        }  ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Category</label>
                                <div class="col-9">
                                    <select class="form-control" name="category">
                                        <option <?php if($bulk->category == 'REG'){ echo 'selected'; } ?> value='REG'>REG</option>
                                        <option <?php if($bulk->category == 'DAT'){ echo 'selected'; } ?> value='DAT'>DAT</option>
                                        <option <?php if($bulk->category == 'PKD'){ echo 'selected'; } ?> value='PKD'>PKD</option>
                                        <option <?php if($bulk->category == 'PKT'){ echo 'selected'; } ?> value='PKT'>PKT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Type</label>
                                <div class="col-9">
                                    <select class="form-control" name="type">
                                        <option <?php if($bulk->type == 'inner'){ echo 'selected'; } ?> value='inner'>Inner</option>
                                        <option <?php if($bulk->type == 'outer'){ echo 'selected'; } ?> value='outer'>Outer</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Margin dealer (%)</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="margin_dealer" value="<?php echo $bulk->margin_dealer; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Margin reseller user (%)</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="margin_reseller_user" value="<?php echo $bulk->margin_reseller_user; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Margin end-user (%)</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="margin_end_user" value="<?php echo $bulk->margin_end_user; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dekape fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="dekape_fee" value="<?php echo $bulk->dekape_fee; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Biller fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="biller_fee" value="<?php echo $bulk->biller_fee; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Partner fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="partner_fee" value="<?php echo $bulk->partner_fee; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Description</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" name="description" value="<?php echo $bulk->description; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Status</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="status">
                                        <option <?php if($bulk->status == 'active'){ echo 'selected'; } ?> value='active'>Active</option>
                                        <option <?php if($bulk->status == 'inactive'){ echo 'selected'; } ?> value='inactive'>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->

<script>
    $("#dealership").on('change', function(){
        var value = $(this).val();
        if(value == 'biller'){
            $("#container-biller").show();
            $("#container-dealer").hide();
        }else{
            $("#container-biller").hide();
            $("#container-dealer").show();
        }
    });
</script>