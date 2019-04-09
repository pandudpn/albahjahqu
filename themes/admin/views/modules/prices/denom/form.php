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
                    <form method="post" action="<?php echo site_url('prices/denom/save?'.$_SERVER["QUERY_STRING"]); ?>">
                        <input type="hidden" value="<?php echo $denom->id; ?>" name="id">

                        <div class="col-6">
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Operator</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="operator" id="operator">
                                        <option value="">-- Operator --</option>
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
                                    <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                    <select class="form-control select2" name="dealership" id="dealership">
                                        <option <?php if($denom->dealership == 'dealer'){ echo 'selected'; } ?> value='dealer'>Dealer</option>
                                        <option <?php if($denom->dealership == 'biller'){ echo 'selected'; } ?> value='biller'>Biller</option>
                                    </select>
                                    <?php }else{ ?>
                                    <select class="form-control select2" disabled>
                                        <option <?php if($denom->dealership == 'dealer'){ echo 'selected'; } ?> value='dealer'>Dealer</option>
                                        <option <?php if($denom->dealership == 'biller'){ echo 'selected'; } ?> value='biller'>Biller</option>
                                    </select>
                                    <input type="hidden" name="dealership" value="dealer">
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group row" id="container-dealer" <?php if($denom->dealership != 'dealer'  && !empty($denom->dealership)){ echo 'style="display:none;"'; } ?>>
                                <label for="" class="col-3 col-form-label">Dealer name</label>
                                <div class="col-9">
                                    <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                    <select class="form-control select2" name="dealer">
                                        <?php foreach($dealer as $deal){
                                            if($deal->id == $denom->dealer_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                    </select>
                                    <?php }else{ ?>
                                        <select class="form-control select2" disabled>
                                            <?php foreach($dealer as $deal){
                                                if($deal->id == $this->session->userdata('user')->dealer_id){
                                                    echo "<option selected value='$deal->id'> $deal->name </option>";
                                                }else{
                                                    echo "<option value='$deal->id'> $deal->name </option>";
                                                }
                                            } ?>
                                        </select>
                                        <input type="hidden" name="dealer" value="<?php echo $this->session->userdata('user')->dealer_id; ?>">
                                    <?php } ?>
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
                                <label for="" class="col-3 col-form-label">Category</label>
                                <div class="col-9">
                                    <select class="form-control" name="category" id="category">
                                        <option <?php if($denom->category == 'REG'){ echo 'selected'; } ?> value='REG'>REG</option>
                                        <option <?php if($denom->category == 'DAT'){ echo 'selected'; } ?> value='DAT'>DAT</option>
                                        <option <?php if($denom->category == 'PKD'){ echo 'selected'; } ?> value='PKD'>PKD</option>
                                        <option <?php if($denom->category == 'PKT'){ echo 'selected'; } ?> value='PKT'>PKT</option>
                                        <option <?php if($denom->category == 'BLK'){ echo 'selected'; } ?> value='BLK'>BLK</option>
                                        <option <?php if($denom->category == 'DLK'){ echo 'selected'; } ?> value='DLK'>DLK</option>
                                        <option <?php if($denom->category == 'TLK'){ echo 'selected'; } ?> value='TLK'>TLK</option>
                                        <option <?php if($denom->category == 'NAP'){ echo 'selected'; } ?> value='NAP'>NAP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Service / Product</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="service" id="service">
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
                                <label for="" class="col-3 col-form-label">Type</label>
                                <div class="col-9">
                                    <select class="form-control" name="type">
                                        <option <?php if($denom->type == NULL){ echo 'selected'; } ?> value=''>-</option>
                                        <option <?php if($denom->type == 'inner'){ echo 'selected'; } ?> value='inner'>Inner</option>
                                        <option <?php if($denom->type == 'outer'){ echo 'selected'; } ?> value='outer'>Outer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Denom</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="denom" id="denom">
                                        <?php foreach($ref_denom as $refd){ 
                                            if($refd->id == $denom->denom_id){
                                                echo "<option selected value='$refd->id'> ".strtoupper($refd->service)." | $refd->provider | $refd->type | $refd->value </option>";
                                            }else{
                                                echo "<option value='$refd->id'> ".strtoupper($refd->service)." | $refd->provider | $refd->type | $refd->value </option>";
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
                                    <input class="form-control" type="text" name="base_price" value="<?php echo intval($denom->base_price); ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dealer Fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="dealer_fee" value="<?php echo intval($denom->dealer_fee); ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dekape Fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="dekape_fee" value="<?php echo intval($denom->dekape_fee); ?>" required <?php if($this->session->userdata('user')->role != 'dekape') { echo 'readonly'; }?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Biller Fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="biller_fee" value="<?php echo intval($denom->biller_fee); ?>" required <?php if($this->session->userdata('user')->role != 'dekape') { echo 'readonly'; }?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Partner fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="partner_fee" value="<?php echo intval($denom->partner_fee); ?>" required <?php if($this->session->userdata('user')->role != 'dekape') { echo 'readonly'; }?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">User fee</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="user_fee" value="<?php echo intval($denom->user_fee); ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Description</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" name="description" value="<?php echo $denom->description; ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Quota</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" name="quota" value="<?php echo $denom->quota; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Promo?</label>
                                <div class="col-9">
                                    <select class="form-control" name="promo">
                                        <option <?php if($denom->promo == 'yes'){ echo 'selected'; } ?> value='yes'>Yes</option>
                                        <option <?php if($denom->promo == 'no'){ echo 'selected'; } ?> value='no'>No</option>
                                    </select>
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
                            <a href="<?php echo site_url('prices/denom?'.$_SERVER["QUERY_STRING"]); ?>" class="btn btn-danger waves-effect waves-light">
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
    $(document).ready(function(){

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
            var category = $('#category').val();
            var service = $("#service");
            var denom = $("#denom");
            var service_id = "<?php echo $denom->service_id; ?>";
            var denom_id = "<?php echo $denom->denom_id; ?>";
            // console.log("<?php echo site_url().'references/data/service_code/'; ?>" + source)
            $.get("<?php echo site_url().'references/data/service_code/'; ?>" + source +"/"+ category, function (data, status) {
                service.html('');
                var obj = JSON.parse(data);
                $.each(obj, function (idx, val) {
                    // console.log(obj);
                    if(service_id == obj[idx].id)
                    {
                        service.append("<option selected value="+obj[idx].id+">" + obj[idx].remarks + obj[idx].biller.name + ' | ' + obj[idx].biller_code + "</option>");
                    }
                    else
                    {
                        service.append("<option value="+obj[idx].id+">" + obj[idx].remarks + obj[idx].biller.name + ' | ' + obj[idx].biller_code + "</option>");
                    }
                });
            });
            
            $.get("<?php echo site_url().'references/data/denom/'; ?>" + source + '/'+ category, function (data, status) {
                denom.html('');
                var obj = JSON.parse(data);
                $.each(obj, function (idx, val) {

                    if(denom_id == obj[idx].id)
                    {
                        denom.append("<option selected value="+obj[idx].id+">" + obj[idx].service.toUpperCase() + " | " + obj[idx].provider + " | " + obj[idx].type + " | " + obj[idx].value + " | " + obj[idx].supplier_code + obj[idx].biller.name +"</option>");
                    }
                    else
                    {
                        denom.append("<option value="+obj[idx].id+">" + obj[idx].service.toUpperCase() + " | " + obj[idx].provider + " | " + obj[idx].type + " | " + obj[idx].value + " | " + obj[idx].supplier_code + obj[idx].biller.name +"</option>");
                    }
                    
                });
            });
        }).trigger('change');

        $("#category").on('change', function(){
            var source   = $('#category').val();
            var operator = $('#operator').val();
            var target   = $("#service");
            var denom    = $("#denom");
            var service_id = "<?php echo $denom->service_id; ?>";
            console.log(service_id)
            var denom_id = "<?php echo $denom->denom_id; ?>";
            // console.log("<?php echo site_url().'references/data/service_code/'; ?>" + operator +"/"+ source)
            $.get("<?php echo site_url().'references/data/service_code/'; ?>" + operator +"/"+ source, function (data, status) {
                target.html('');
                var obj = JSON.parse(data);
                console.log(obj)
                $.each(obj, function (idx, val) {
                    if(service_id == obj[idx].id)
                    {
                        target.append("<option selected value="+obj[idx].id+">" + obj[idx].remarks + obj[idx].biller.name + ' | ' + obj[idx].biller_code + "</li>");
                    }
                    else
                    {
                        target.append("<option value="+obj[idx].id+">" + obj[idx].remarks + obj[idx].biller.name + ' | ' + obj[idx].biller_code +  "</li>");
                    }
                });
            });

            $.get("<?php echo site_url().'references/data/denom/'; ?>" + operator + '/'+ source, function (data, status) {
                denom.html('');
                var obj = JSON.parse(data);
                $.each(obj, function (idx, val) {
                    if(denom_id == obj[idx].id)
                    {
                        denom.append("<option selected value="+obj[idx].id+">" + obj[idx].service.toUpperCase() + " | " + obj[idx].provider + " | " + obj[idx].type + " | " + obj[idx].value + " | " + obj[idx].supplier_code + obj[idx].biller.name +"</li>");
                    }
                    else
                    {
                        denom.append("<option value="+obj[idx].id+">" + obj[idx].service.toUpperCase() + " | " + obj[idx].provider + " | " + obj[idx].type + " | " + obj[idx].value + " | " + obj[idx].supplier_code + obj[idx].biller.name +"</li>");
                    }
                    
                });
            });
        }).trigger('change');

    })

</script>