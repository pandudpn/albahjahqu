<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">User Setting</h4>

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
                <label class="col-3 col-form-label">Password</label>
                <div class="col-9">
                    <input class="form-control" id="password" type="password" value="" name="password" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-3 col-form-label">Retype Password</label>
                <div class="col-9">
                    <input class="form-control" id="confirm_password" type="password" value="" name="password" required>
                </div>
            </div>

            <div class="form-group text-right m-b-0">
            	<input type="hidden" value="<?php echo $this->session->userdata('user')->id; ?>" name="id" required>
                <button class="btn btn-primary waves-effect waves-light" type="submit">
                    Submit
                </button>
                <a href="<?php echo site_url(); ?>" class="btn btn-secondary waves-effect m-l-5">
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