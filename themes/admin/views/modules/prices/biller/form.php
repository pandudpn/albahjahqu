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
                    <form method="post" action="<?php echo site_url('prices/biller/save'); ?>">
                    <input type="hidden" value="<?php echo $biller->id; ?>" name="id">
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Biller</label>
                            <div class="col-9">
                                <select class="form-control select2" name="biller" id="biller">
                                    <?php foreach($ref_biller as $bill){ 
                                            if($bill->id == $biller->biller_id){
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
                                <select class="form-control select2" name="service" id="service">
                                    <?php foreach($service_code as $serv){ 
                                            if($serv->id == $biller->service_id){
                                                echo "<option selected value='$serv->id'> $serv->remarks </option>";
                                            }else{
                                                echo "<option value='$serv->id'> $serv->remarks </option>";
                                            }
                                        } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Provider Code</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="provider_code" value="<?php echo $biller->provider_code; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Base Price</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="base_price" value="<?php echo $biller->base_price; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Biller Fee</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="biller_fee" value="<?php echo $biller->biller_fee; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dekape Fee</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="dekape_fee" value="<?php echo $biller->dekape_fee; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer Fee</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="dealer_fee" value="<?php echo $biller->dealer_fee; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Partner Fee</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="partner_fee" value="<?php echo $biller->partner_fee; ?>" required>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="" class="col-3 col-form-label">User Fee</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="user_fee" value="<?php echo $biller->user_fee; ?>" required>
                            </div>
                        </div> -->
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('prices/biller'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->

<script>

    $("#biller").on('change', function(){
        var source   = $('#biller').val();
        var target   = $("#service");
        $.get("<?php echo site_url().'references/data/services/'; ?>" + source, function (data, status) {
            target.html('');
            var obj = JSON.parse(data);
            $.each(obj, function (idx, val) {
                target.append("<option value="+obj[idx].id+">" + obj[idx].remarks + "</li>");
            });
        });
    });

</script>