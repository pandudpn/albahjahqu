<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Add Sub Category</h4>

            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="<?php echo site_url('subcategory'); ?>">Sub Category</a></li>
                <li class="breadcrumb-item active">Add Sub Category</li>
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
                <label class="col-2 col-form-label">Name</label>
                <div class="col-10">
                    <input class="form-control" type="text" value="" name="name" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 col-form-label">Type</label>
                <div class="col-10">
                    <input class="form-control" type="text" value="" name="type" required>
                </div>
            </div>

            <div class="form-group text-right m-b-0">
                <button class="btn btn-primary waves-effect waves-light" type="submit">
                    Submit
                </button>
                <a href="<?php echo site_url('subcategory'); ?>" class="btn btn-secondary waves-effect m-l-5">
                    Cancel
                </a>
            </div>
        </div>
        </form>
    </div>
</div>