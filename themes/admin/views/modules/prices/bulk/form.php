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
                    <form method="post" action="<?php echo site_url('prices/bulk/save?'.$_SERVER["QUERY_STRING"]); ?>">
                        <input type="hidden" value="<?php echo $bulk->id; ?>" name="id">

                        <div class="col-6">
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Operator</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="operator" id="operator">
                                        <option value="">-- Operator --</option>
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
                                    <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                    <select class="form-control select2" name="dealer">
                                        <?php foreach($dealer as $deal){
                                            if($deal->id == $bulk->dealer_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                    </select>
                                    <?php }else{ ?>
                                        <select class="form-control select2" disabled>
                                            <?php foreach($dealer as $deal){
                                                if($deal->id == 1){
                                                    continue;
                                                }else {
                                                    if($deal->id == $this->session->userdata('user')->dealer_id){
                                                        echo "<option selected value='$deal->id'> $deal->name </option>";
                                                    }else{
                                                        echo "<option value='$deal->id'> $deal->name </option>";
                                                    }
                                                }
                                            } ?>
                                        </select>
                                        <input type="hidden" name="dealer" value="<?php echo $this->session->userdata('user')->dealer_id; ?>">
                                    <?php } ?>
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
                                <label for="" class="col-3 col-form-label">Category</label>
                                <div class="col-9">
                                    <select class="form-control" name="category" id="category">
                                        <option <?php if($bulk->category == 'REG'){ echo 'selected'; } ?> value='REG'>REG</option>
                                        <option <?php if($bulk->category == 'DAT'){ echo 'selected'; } ?> value='DAT'>DAT</option>
                                        <option <?php if($bulk->category == 'PKD'){ echo 'selected'; } ?> value='PKD'>PKD</option>
                                        <option <?php if($bulk->category == 'PKT'){ echo 'selected'; } ?> value='PKT'>PKT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Service</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="service" id="service">
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
                                <label for="" class="col-3 col-form-label">Type</label>
                                <div class="col-9">
                                    <select class="form-control" name="type">
                                        <option <?php if($bulk->type == NULL){ echo 'selected'; } ?> value=''>-</option>
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
                                    <input class="form-control" type="number" id="margin_dealer" name="margin_dealer" value="<?php echo $bulk->margin_dealer; ?>" required min="0" max="10" step="0.01">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Discount reseller user (%)</label>
                                <div class="col-7">
                                    <input class="form-control" type="number" id="margin_reseller_user" name="margin_reseller_user" value="<?php echo $bulk->margin_reseller_user; ?>" required min="0" max="10" step="0.01">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Margin end-user (%)</label>
                                <div class="col-7">
                                    <input class="form-control" type="number" name="margin_end_user" value="<?php echo floatval($bulk->margin_end_user); ?>" required min="0" max="10" step="0.01">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dekape fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="number" name="dekape_fee" value="<?php echo intval($bulk->dekape_fee); ?>" required <?php if($this->session->userdata('user')->role != 'dekape') { echo 'readonly'; }?> min="0" max="1000000000" step="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Biller fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="number" name="biller_fee" value="<?php echo intval($bulk->biller_fee); ?>" required <?php if($this->session->userdata('user')->role != 'dekape') { echo 'readonly'; }?> min="0" max="1000000000" step="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Partner fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="number" name="partner_fee" value="<?php echo intval($bulk->partner_fee); ?>" required <?php if($this->session->userdata('user')->role != 'dekape') { echo 'readonly'; }?> min="0" max="1000000000" step="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Description</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" name="description" value="<?php echo $bulk->description; ?>" required>
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
                            <a href="<?php echo site_url('prices/bulk?'.$_SERVER["QUERY_STRING"].''); ?>" class="btn btn-danger waves-effect waves-light">
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

    $("#operator").on('change', function(){
        var source = $('#operator').val();
        var service = $("#service");
        var denom = $("#denom");

        var service_id = "<?php echo $bulk->service_id; ?>";
        var denom_id = "<?php echo $bulk->denom_id; ?>";

        $.get("<?php echo site_url().'references/data/service_code/'; ?>" + source +'/BLK', function (data, status) {
            service.html('');
            var obj = JSON.parse(data);
            
            $.each(obj, function (idx, val) {
                if(service_id == obj[idx].id)
                {
                    service.append("<option selected value="+obj[idx].id+">" + obj[idx].remarks + "</option>");
                }
                else
                {
                    service.append("<option value="+obj[idx].id+">" + obj[idx].remarks + "</option>");
                }
                
            });
        });
        
        // $.get("<?php echo site_url().'references/data/denom/'; ?>" + source, function (data, status) {
        //     denom.html('');
        //     var obj = JSON.parse(data);
        //     $.each(obj, function (idx, val) {

        //         if(denom_id == obj[idx].id)
        //         {
        //             denom.append("<option selected value="+obj[idx].id+">" + obj[idx].service.toUpperCase() + " | " + obj[idx].provider + " | " + obj[idx].type + " | " + obj[idx].value + "</li>");
        //         }
        //         else
        //         {
        //             denom.append("<option value="+obj[idx].id+">" + obj[idx].service.toUpperCase() + " | " + obj[idx].provider + " | " + obj[idx].type + " | " + obj[idx].value + "</li>");
        //         }
                
        //     });
        // });
    }).trigger('change');

    $("#category").on('change', function(){
        var source   = $('#category').val();
        var operator = $('#operator').val();
        var target   = $("#service");

        var service_id = "<?php echo $bulk->service_id; ?>";
        var denom_id = "<?php echo $bulk->denom_id; ?>";

        $.get("<?php echo site_url().'references/data/service_code/'; ?>" + operator +"/BLK", function (data, status) {
            target.html('');
            var obj = JSON.parse(data);
            $.each(obj, function (idx, val) {
                if(service_id == obj[idx].id)
                {
                    target.append("<option selected value="+obj[idx].id+">" + obj[idx].remarks + "</option>");
                }
                else
                {
                    target.append("<option value="+obj[idx].id+">" + obj[idx].remarks + "</option>");
                }
                
            });
        });
    });


    var margin_dealer = document.getElementById("margin_dealer")
      , margin_reseller_user = document.getElementById("margin_reseller_user");

    function validatePassword(){
      if(margin_dealer.value < margin_reseller_user.value) {
        margin_reseller_user.setCustomValidity("Margin dealer tidak boleh lebih kecil dari margin reseller");
      } else {
        margin_reseller_user.setCustomValidity('');
      }
    }

    margin_dealer.onchange = validatePassword;
    margin_reseller_user.onkeyup = validatePassword;
</script>