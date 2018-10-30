<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Edit User</h4>

            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="<?php echo site_url('user'); ?>">User</a></li>
                <li class="breadcrumb-item active">Edit User</li>
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
                <label class="col-3 col-form-label">Name</label>
                <div class="col-9">
                    <input class="form-control" type="text" value="<?php echo $data->name; ?>" name="name" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-3 col-form-label">Phone</label>
                <div class="col-9">
                    <input class="form-control" type="text" value="<?php echo $data->phone; ?>" name="phone" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-3 col-form-label">Email</label>
                <div class="col-9">
                    <input class="form-control" type="text" value="<?php echo $data->email; ?>" name="email" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-3 col-form-label">Password</label>
                <div class="col-9">
                    <input class="form-control" id="password" type="password" value="" name="password">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-3 col-form-label">Retype Password</label>
                <div class="col-9">
                    <input class="form-control" id="confirm_password" type="password" value="" name="password">
                </div>
            </div>

            <div class="form-group text-right m-b-0">
                <button class="btn btn-primary waves-effect waves-light" type="submit">
                    Submit
                </button>
                <a href="<?php echo site_url('user'); ?>" class="btn btn-secondary waves-effect m-l-5">
                    Cancel
                </a>
            </div>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
	var password = document.getElementById("password")
	  , confirm_password = document.getElementById("confirm_password");

	function validatePassword(){
	  if(password.value != confirm_password.value) {
	    confirm_password.setCustomValidity("Passwords Don't Match");
	  } else {
	    confirm_password.setCustomValidity('');
	  }
	}

	password.onchange = validatePassword;
	confirm_password.onkeyup = validatePassword;
</script>