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
                    <form method="post" action="<?php echo site_url('services/save'); ?>">
                    
                    <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Service</label>
                            <div class="col-6">
                                <select class="form-control" name="service" id="service">
                                    <option <?php if($data->service == 'PLS'){ echo 'selected'; } ?> value='PLS'>Pulsa</option>
                                    <option <?php if($data->service == 'PLN'){ echo 'selected'; } ?> value='PLN'>Listrik</option>
                                    <option <?php if($data->service == 'BIL'){ echo 'selected'; } ?> value='BIL'>Tagihan</option>
                                    <option <?php if($data->service == 'VCR'){ echo 'selected'; } ?> value='VCR'>Voucher</option>
                                    <option <?php if($data->service == 'PAM'){ echo 'selected'; } ?> value='PAM'>PDAM</option>
                                    <option <?php if($data->service == 'TOP'){ echo 'selected'; } ?> value='TOP'>Topup</option>
                                    <option <?php if($data->service == 'TRF'){ echo 'selected'; } ?> value='TRF'>Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Provider</label>
                            <div class="col-6">
                                <select class="form-control select2" name="provider">
                                    <?php foreach($provider as $prov){ 
                                            if($prov->alias == $data->provider){
                                                echo "<option selected value='$prov->alias'> $prov->name </option>";
                                            }else{
                                                echo "<option value='$prov->alias'> $prov->name </option>";
                                            }
                                        } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Category</label>
                            <div class="col-6">
                                <select class="form-control" name="prepaid" id="prepaid">
                                    <option <?php if($data->prepaid == '01'){ echo 'selected'; } ?> value='01'>Prepaid</option>
                                    <option <?php if($data->prepaid == '02'){ echo 'selected'; } ?> value='02'>Postpaid</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Type</label>
                            <div class="col-6">
                                <select class="form-control" name="type" id="type">
                                    <option <?php if($data->type == 'REG'){ echo 'selected'; } ?> value='REG'>REG</option>
                                    <option <?php if($data->type == 'DAT'){ echo 'selected'; } ?> value='DAT'>DAT</option>
                                    <option <?php if($data->type == 'PKD'){ echo 'selected'; } ?> value='PKD'>PKD</option>
                                    <option <?php if($data->type == 'PKT'){ echo 'selected'; } ?> value='PKT'>PKT</option>
                                    <option <?php if($data->type == 'BLK'){ echo 'selected'; } ?> value='BLK'>BLK</option>
                                    <option <?php if($data->type == 'DLK'){ echo 'selected'; } ?> value='DLK'>DLK</option>
                                    <option <?php if($data->type == 'TLK'){ echo 'selected'; } ?> value='TLK'>TLK</option>
                                    <option <?php if($data->type == 'NAP'){ echo 'selected'; } ?> value='NAP'>NAP</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Biller</label>
                            <div class="col-6">
                                <select class="form-control select2" name="by">
                                    <option value="">Choose Biller</option>
                                    <?php foreach($biller as $bill){ 
                                            if($bill->id == $data->by){
                                                echo "<option selected value='$bill->id'> $bill->name </option>";
                                            }else{
                                                echo "<option value='$bill->id'> $bill->name </option>";
                                            }
                                        } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Value / Amount</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="value" value="<?php echo $data->value; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Product Code</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="biller_code" value="<?php echo $data->biller_code; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Remarks</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="remarks" value="<?php echo $data->remarks; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Status</label>
                            <div class="col-7">
                                <select class="form-control" name="status" id="status">
                                    <option value="0" <?php if($data->deleted == '0') { echo 'selected'; } ?>>Active</option>
                                    <option value="1" <?php if($data->deleted == '1') { echo 'selected'; } ?>>Non active</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('services'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->
