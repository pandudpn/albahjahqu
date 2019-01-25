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
                <div class="col-8">
                    <form method="post" action="<?php echo site_url('promos/save'); ?>">
                    
                    <input type="hidden" value="<?php echo $data->id; ?>" name="id">

                    	<div class="form-group row">
                            <label for="" class="col-3 col-form-label">For Dealer</label>
                            <div class="col-7">
                                <select class="form-control" name="dealer" id="dealer">
                                    <option value="">Pilih Dealer</option>
                                    <?php foreach($dealer as $deal){
                                        if($deal->id == $data->dealer){
                                            echo "<option selected value='$deal->id'> $deal->name </option>";
                                        }else{
                                            echo "<option value='$deal->id'> $deal->name </option>";
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>

                    	<div class="form-group row">
                            <label for="" class="col-3 col-form-label">Title</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Text</label>
                            <div class="col-7">
                                <textarea name="text" class="form-control" rows="5"><?php echo $data->text; ?></textarea>
                            </div>
                        </div>

                    	<div class="form-group row">
                            <label for="" class="col-3 col-form-label">Time</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="execute_time" value="<?php echo $data->execute_time; ?>">
                                <small class="text-muted">eg. 07:00, 12:00, 19:00 etc</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Date</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="execute_date" value="<?php echo $data->execute_date; ?>">
                                <small class="text-muted">eg. 01, 05, 31 etc</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Month</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="execute_month" value="<?php echo $data->execute_month; ?>">
                                <small class="text-muted">eg. 01, 08, 12 etc</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Year</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="execute_year" value="<?php echo $data->execute_year; ?>">
                                <small class="text-muted">eg. 2019, 2020, 2025 etc</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Period</label>
                            <div class="col-7">
                                <select class="form-control" name="execute_period" id="execute_period">
                                	<option value="">Choose Period</option>
                                	<option <?php if($data->execute_period == 'once'){ echo 'selected'; } ?> value='once'>Once</option>
                                    <option <?php if($data->execute_period == 'daily'){ echo 'selected'; } ?> value='daily'>Daily</option>
                                    <option <?php if($data->execute_period == 'weekly'){ echo 'selected'; } ?> value='weekly'>Weekly</option>
                                    <option <?php if($data->execute_period == 'monthly'){ echo 'selected'; } ?> value='monthly'>Monthly</option>
                                    <option <?php if($data->execute_period == 'yearly'){ echo 'selected'; } ?> value='yearly'>Yearly</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Category</label>
                            <div class="col-7">
                                <select class="form-control" name="category" id="category">
                                	<option value="">Choose Category</option>
                                    <option <?php if($data->category == 'billing'){ echo 'selected'; } ?> value='billing'>Billing</option>
                                    <option <?php if($data->category == 'electricity'){ echo 'selected'; } ?> value='electricity'>Electricity</option>
                                    <option <?php if($data->category == 'credit'){ echo 'selected'; } ?> value='credit'>Credit</option>
                                    <option <?php if($data->category == 'voucher'){ echo 'selected'; } ?> value='voucher'>Voucher</option>
                                    <option <?php if($data->category == 'topup'){ echo 'selected'; } ?> value='topup'>Topup</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Status</label>
                            <div class="col-7">
                                <select class="form-control" name="status" id="status">
                                    <option value="active" <?php if($data->status == 'active') { echo 'selected'; } ?>>Active</option>
                                    <option value="nonactive" <?php if($data->status == 'nonactive') { echo 'selected'; } ?>>Non active</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('promos'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->
