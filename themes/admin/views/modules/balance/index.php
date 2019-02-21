<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Balance VA</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive" style="overflow-x: auto; zoom: 0.8;">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?> 
            <table class="table table-bordered table-responsive">
                <thead>
	                <tr>
	                    <th>No</th>
	                    <th>Biller</th>
	                    <th>Balance</th>
	                    <th>Action</th>
	                </tr>
                </thead>
                <tbody>
                	 <tr>
	                    <td>1</td>
	                    <td>Xendit (PT Sinar Digital Terdepan)</td>
	                    <td><?php echo 'Rp. '.number_format($balance); ?></td>
	                    <td><a href="#modal-imburse" data-toggle="modal" data-target="#modal-imburse" class="btn btn-primary">
	                    	<i class="fa fa-arrow-down"></i> Imburse</a>
	                    </td>
	                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end row -->

<!-- Modal Alert Reject-->
<div class="modal fade" id="modal-imburse" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Imburse Balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="" id="form-reject">
                    <div class="form-group row">
                        <label class="col-3 col-form-label">Bank</label>
                        <div class="col-9">
                            <select name="bank_code" class="form-control">
                            	<option value="MANDIRI">MANDIRI</option>
                            	<option value="BCA">BCA</option>
                            	<option value="BRI">BRI</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label">Account Name</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="account_holder_name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label">Account Number</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="account_number" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label">Description</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="description" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Imburse Now</button>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
            
                </form>
        </div>
    </div>
</div>
