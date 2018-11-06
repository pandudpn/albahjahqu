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
                    <form method="post" action="<?php echo site_url('prices/denom/save'); ?>">
                        <input type="hidden" value="<?php echo $denom->id; ?>" name="id">

                        <div class="col-6">
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Operator</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="operator">
                                        <?php foreach($provider as $prov){ 
                                            if($prov->alias == $denom->operator){
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
                                        <option <?php if($denom->dealership == 'dealer'){ echo 'selected'; } ?> value='dealer'>Dealer</option>
                                        <option <?php if($denom->dealership == 'biller'){ echo 'selected'; } ?> value='biller'>Biller</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="container-dealer" <?php if($denom->dealership != 'dealer'  && !empty($denom->dealership)){ echo 'style="display:none;"'; } ?>>
                                <label for="" class="col-3 col-form-label">Dealer name</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="dealer">
                                        <?php foreach($dealer as $deal){
                                            if($deal->id == $denom->dealer_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="container-biller" <?php if($denom->dealership != 'biller' || empty($denom->dealership)){ echo 'style="display:none;"'; } ?>>
                                <label for="" class="col-3 col-form-label">Biller name</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="biller">
                                        <?php foreach($biller as $bill){ 
                                            if($bill->id == $denom->biller_id){
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
                                            if($service->id == $denom->service_id){
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
                                        <option <?php if($denom->category == 'REG'){ echo 'selected'; } ?> value='REG'>REG</option>
                                        <option <?php if($denom->category == 'DAT'){ echo 'selected'; } ?> value='DAT'>DAT</option>
                                        <option <?php if($denom->category == 'PKD'){ echo 'selected'; } ?> value='PKD'>PKD</option>
                                        <option <?php if($denom->category == 'PKT'){ echo 'selected'; } ?> value='PKT'>PKT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Type</label>
                                <div class="col-9">
                                    <select class="form-control" name="type">
                                        <option <?php if($denom->type == 'inner'){ echo 'selected'; } ?> value='inner'>Inner</option>
                                        <option <?php if($denom->type == 'outer'){ echo 'selected'; } ?> value='outer'>Outer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Denom</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="denom">
                                        <?php foreach($ref_denom as $refd){ 
                                            if($refd->id == $denom->denom_id){
                                                echo "<option selected value='$refd->id'> $refd->service | $refd->provider | $refd->type | $refd->value </option>";
                                            }else{
                                                echo "<option value='$refd->id'> $refd->service | $refd->provider | $refd->type | $refd->value </option>";
                                            }
                                        }  ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Base Price</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="base_price" value="<?php echo $denom->base_price; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dealer Fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="dealer_fee" value="<?php echo $denom->dealer_fee; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dekape Fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="dekape_fee" value="<?php echo $denom->dekape_fee; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Biller Fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="biller_fee" value="<?php echo $denom->biller_fee; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Partner fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="partner_fee" value="<?php echo $denom->partner_fee; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">User fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="user_fee" value="<?php echo $denom->user_fee; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Description</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" name="description" value="<?php echo $denom->description; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Status</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="status">
                                        <option <?php if($denom->status == 'active'){ echo 'selected'; } ?> value='active'>Active</option>
                                        <option <?php if($denom->status == 'inactive'){ echo 'selected'; } ?> value='inactive'>Inactive</option>
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