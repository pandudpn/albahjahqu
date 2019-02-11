<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Balance Manipulation for <font color="#8e2aa4"><?php echo $title; ?></font></h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card-box">
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
                    <form method="post">
                        <div class="col-8">

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Current Balance</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="balance" value="<?php echo 'Rp. '.number_format($eva_customer->account_balance); ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Action</label>
                                <div class="col-7">
                                    <select name="action" class="form-control" required>
                                        <option value="">Choose Action</option>
                                        <option value="credit">( + ) Credit </option>
                                        <option value="debit">( - ) Debit </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Amount</label>
                                <div class="col-7">
                                    <input class="form-control" type="number" min="0" name="amount" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Remarks</label>
                                <div class="col-7">
                                    <input class="form-control" type="text" name="remarks" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            <a href="<?php echo site_url('customers'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                            </a>

                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->