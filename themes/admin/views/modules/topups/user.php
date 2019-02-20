<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Topup Customer</h4>

            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="<?php echo site_url('topups'); ?>">Topups</a></li>
                <li class="breadcrumb-item active">Customer</li>
            </ol>


            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

 <div class="row">
    <div class="col-8">
    	<form method="post" enctype="multipart/form-data">
        <div class="card-box">

        	<?php if($alert){ ?>
        	<div class="alert alert-<?php echo $alert['type']; ?>">
        		<?php echo $alert['msg']; ?>
        	</div>
        	<?php } ?>

            <div class="form-group row">
                <label class="col-3 col-form-label">Customer Phone</label>
                <div class="col-9">
                    <input class="form-control" type="text" value="" name="phone" placeholder="eg. 6285295703112" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-3 col-form-label">Bank</label>
                <div class="col-9">
                    <select name="bank" class="form-control" required>
                    	<option value="">Choose Bank</option>
                        <option value="mandiri_manual">Bank Mandiri Manual</option>
                        <option value="bri_manual">Bank BRI Manual</option>
                        <option value="bni_manual">Bank BNI Manual</option>
                        <option value="mandiri">Bank Mandiri VA</option>
                        <option value="bri">Bank BRI VA</option>
                        <option value="bni">Bank BNI VA</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-3 col-form-label">Amount</label>
                <div class="col-9">
                    <input class="form-control" type="text" value="" name="amount" required>
                </div>
            </div>

            <div class="form-group text-right m-b-0">
                <button class="btn btn-primary waves-effect waves-light" type="submit">
                    Submit
                </button>
                <a href="<?php echo site_url('topups'); ?>" class="btn btn-secondary waves-effect m-l-5">
                    Cancel
                </a>
            </div>
        </div>
        </form>
    </div>
</div>