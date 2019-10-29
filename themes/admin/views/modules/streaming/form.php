<script src="<?php echo $this->template->get_theme_path();?>assets/tinymce/js/tinymce/tinymce.min.js"></script>

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
        <div class="card-box table-responsive">
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
                    <form method="post" action="<?php echo site_url('streaming/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-3">
                                <label for="tv">
                                    <input class="checked" name="type" type="radio" id="tv" value="tv" checked>
                                    TV
                                </label>
                            </div>
                            <div class="col-3">
                                <label for="radio">
                                    <input class="checked" name="type" type="radio" id="radio" value="radio">
                                    RADIO
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Link Streaming (url)</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="url" value="<?php echo $data->url; ?>" required>
                            </div>
                        </div>

                        <div id="radiofield" class="d-none">
                            <div class="form-group row">
                                <label for="name" class="col-form-label col-3">Nama Saluran</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Saluran">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('streaming'); ?>" class="btn btn-danger waves-effect waves-light">
                            Batal 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.checked').change(function(e) {
            e.preventDefault();

            radio = $(this).val();

            if(radio == "tv") {
                $('#radiofield').addClass('d-none');
            }else if(radio == "radio") {
                $('#radiofield').removeClass('d-none');
            }
        })
    })
</script>