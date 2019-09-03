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
                    <form method="post" action="<?php echo site_url('islami/hadits_daily/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Title</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Text Arab</label>
                            <div class="col-9">
                                <textarea class="form-control editor" id="editor1" name="text_ar" rows="15"><?php echo $data->text_ar; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Latin</label>
                            <div class="col-9">
                                <textarea class="form-control editor" name="latin" rows="15"><?php echo $data->latin; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Translate</label>
                            <div class="col-9">
                                <textarea class="form-control editor" name="translate" rows="15"><?php echo $data->translate; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Image</label>
                            <div class="col-9">
                                <?php if(!empty($data)) { ?>
                                <img src="<?php echo $data->image; ?>" height="200" style="margin-bottom: 5px;">
                                <?php } ?>
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('islami/hadits_daily'); ?>" class="btn btn-danger waves-effect waves-light">
                            Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>