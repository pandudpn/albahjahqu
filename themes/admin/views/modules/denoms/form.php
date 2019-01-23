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
                    <form method="post" action="<?php echo site_url('denoms/save?'.$_SERVER["QUERY_STRING"]); ?>">
                        <input type="hidden" value="<?php echo $denom->id; ?>" name="id">

                        <div class="col-6">

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Service</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="service" id="service">
                                        <option <?php if($denom->service == 'celular'){ echo 'selected'; } ?> value='celular'>Celular</option>
                                        <option <?php if($denom->service == 'electricity'){ echo 'selected'; } ?> value='electricity'>Electricity</option>
                                        <option <?php if($denom->service == 'cabletv'){ echo 'selected'; } ?> value='cabletv'>Cable Tv</option>
                                        <option <?php if($denom->service == 'voucher'){ echo 'selected'; } ?> value='voucher'>Voucher</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Provider</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="provider" id="provider">
                                        <?php foreach($provider as $prov){ 
                                            if($prov->alias == $denom->provider){
                                                echo "<option selected='selected' value='$prov->alias'>$prov->name ($prov->alias)</option>";
                                            }else{
                                                echo "<option value='$prov->alias'>$prov->name ($prov->alias)</option>";
                                            }
                                         } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Supplier</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="supplier" id="supplier">
                                        <option <?php if($denom->supplier == 'dealer'){ echo 'selected'; } ?> value='dealer'>Dealer</option>
                                        <option <?php if($denom->supplier == 'biller'){ echo 'selected'; } ?> value='biller'>Biller</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="container-dealer" <?php if($denom->supplier != 'dealer'  && !empty($denom->supplier)){ echo 'style="display:none;"'; } ?>>
                                <label for="" class="col-3 col-form-label">Dealer name</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="dealer">
                                        <?php foreach($dealer as $deal){
                                            if($deal->id == $denom->supplier_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="container-biller" <?php if($denom->supplier != 'biller' || empty($denom->supplier)){ echo 'style="display:none;"'; } ?>>
                                <label for="" class="col-3 col-form-label">Biller name</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="biller">
                                        <?php foreach($biller as $bill){ 
                                            if($bill->id == $denom->supplier_id){
                                                echo "<option selected value='$bill->id'> $bill->name </option>";
                                            }else{
                                                echo "<option value='$bill->id'> $bill->name </option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Supplier Code</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="supplier_code" value="<?php echo $denom->supplier_code; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Type</label>
                                <div class="col-9">
                                    <select class="form-control" name="type" id="type">
                                        <option <?php if($denom->type == NULL){ echo 'selected'; } ?> value=''>-</option>
                                        <option <?php if($denom->type == 'REG'){ echo 'selected'; } ?> value='REG'>REG</option>
                                        <option <?php if($denom->type == 'DAT'){ echo 'selected'; } ?> value='DAT'>DAT</option>
                                        <option <?php if($denom->type == 'PKD'){ echo 'selected'; } ?> value='PKD'>PKD</option>
                                        <option <?php if($denom->type == 'PKT'){ echo 'selected'; } ?> value='PKT'>PKT</option>
                                        <option <?php if($denom->type == 'NAP'){ echo 'selected'; } ?> value='NAP'>NAP</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Code</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="code" value="<?php echo $denom->code; ?>" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Value</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="value" value="<?php echo $denom->value; ?>" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            <a href="<?php echo site_url('denoms?'.$_SERVER["QUERY_STRING"]); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                            </a>

                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->

<script>
    $("#supplier").on('change', function(){
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