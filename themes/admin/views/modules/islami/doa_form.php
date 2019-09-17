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
                    <form method="post" action="<?php echo site_url('islami/doa/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Judul</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>" required>
                            </div>
                        </div>

                        <div class="ayat">
                        <?php if(isset($ayat)){ 
                            foreach($ayat AS $k => $v){ ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="" class="col-form-label">Ayat</label>
                                        <textarea name="ayat[]" id="ayat" cols="2" rows="4" class="form-control"><?php echo $v->text_ar ?></textarea>
                                    </div>
                                    <div class="col-3">
                                        <label for="" class="col-form-label">Latin</label>
                                        <textarea name="latin[]" id="latin" cols="2" rows="4" class="form-control"><?php echo $v->latin ?></textarea>
                                    </div>
                                    <div class="col-4">
                                        <label for="" class="col-form-label">Translate</label>
                                        <textarea name="translate[]" id="translate" cols="2" rows="4" class="form-control"><?php echo $v->translate ?></textarea>
                                    </div>
                                    <div class="col-1 pt-5 text-center">
                                        <?php echo ($k == 0) ? '<a href="#" class="text-success" id="add"><i class="zmdi zmdi-plus-circle-o" style="font-size: 24px;"></i></a>' : '<a href="#" class="text-danger close" id="add"><i class="zmdi zmdi-close-circle-o" style="font-size: 24px;"></i></a>'; ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        <?php }else{ ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="" class="col-form-label">Ayat</label>
                                        <textarea name="ayat[]" id="ayat" cols="2" rows="4" class="form-control"></textarea>
                                    </div>
                                    <div class="col-3">
                                        <label for="" class="col-form-label">Latin</label>
                                        <textarea name="latin[]" id="latin" cols="2" rows="4" class="form-control"></textarea>
                                    </div>
                                    <div class="col-4">
                                        <label for="" class="col-form-label">Translate</label>
                                        <textarea name="translate[]" id="translate" cols="2" rows="4" class="form-control"></textarea>
                                    </div>
                                    <div class="col-1 pt-5 text-center">
                                        <a href="#" class="text-success" id="add"><i class="zmdi zmdi-plus-circle-o" style="font-size: 24px;"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                            <!-- <div id="result"></div> -->
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('islami/doa'); ?>" class="btn btn-danger waves-effect waves-light">
                            Batal 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#add', function(e){
        e.preventDefault();

        AddForm();
    });

    $(document).on('click', '.close', function(e){
        e.preventDefault();

        var abc = $(this).parent().parent();
        abc.remove();
    });

    function AddForm(){
        var length  = $('.ayat > .form-group').length;

        var html = '<div class="form-group">';
            html += '<div class="row">';
            html += '<div class="col-4">';
            html += '<label for="" class="col-form-label col-3">Ayat</label>';
            html += '<textarea class="form-control" name="ayat[]" rows="4" cols="2"></textarea>';
            html += '</div>';
            html += '<div class="col-3">';
            html += '<label for="" class="col-form-label col-3">Latin</label>';
            html += '<textarea class="form-control" name="latin[]" rows="4" cols="2"></textarea>';
            html += '</div>';
            html += '<div class="col-4">';
            html += '<label for="" class="col-form-label col-3">Translate</label>';
            html += '<textarea class="form-control" name="translate[]" rows="4" cols="2"></textarea>';
            html += '</div>';
            html += '<div class="col-1 pt-5 text-center">';
            html += '<a href="#" class="text-danger close"><i class="zmdi zmdi-close-circle-o" style="font-size: 24px;"></i></a>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

        $('.ayat > .form-group:last').after(html);
    }
</script>