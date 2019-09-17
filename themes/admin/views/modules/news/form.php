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
                    <form method="post" action="<?php echo site_url('news/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Judul</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Konten</label>
                            <div class="col-9">
                                <textarea class="form-control editor" id="editor1" name="content" rows="15"><?php echo $data->description; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Foto</label>
                            <div class="col-9">
                                <?php if(!empty($data)) { ?>
                                <img src="<?php echo $data->image; ?>" height="200" style="margin-bottom: 5px;">
                                <?php } ?>
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Status</label>
                            <div class="col-9">
                                <select class="form-control" name="status" id="status">
                                    <option <?php if($data->status == 'no'){ echo 'selected'; } ?> value='no'>Tidak Headline</option>
                                    <option <?php if($data->status == 'headline'){ echo 'selected'; } ?> value='headline'>Headline</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('news'); ?>" class="btn btn-danger waves-effect waves-light">
                            Batal 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> 
<script type="text/javascript">
    $(document).ready(function() {
        tinymce.init({
            selector: "#editor1",
            plugins: [
                 "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                 "searchreplace wordcount visualblocks visualchars code fullscreen",
                 "insertdatetime nonbreaking save table directionality",
                 "emoticons template paste textcolor colorpicker textpattern youtube"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image responsivefilemanager youtube",
            mobile: { theme: 'mobile' },
            extended_valid_elements: "+iframe[src|width|height|name|align|class]",
            automatic_uploads: true,
            image_advtab: true,
            images_upload_url: "<?php echo site_url('news/imgupload'); ?>",
            file_picker_types: 'image', 
            paste_data_images:true,
            relative_urls: false,
            remove_script_host: false,
                file_picker_callback: function(cb, value, meta) {
                     var input = document.createElement('input');
                     input.setAttribute('type', 'file');
                     input.setAttribute('accept', 'image/*');
                     input.onchange = function() {
                        var file = this.files[0];
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = function () {
                           var id = 'post-image-' + (new Date()).getTime();
                           var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                           var blobInfo = blobCache.create(id, file, reader.result);
                           blobCache.add(blobInfo);
                           cb(blobInfo.blobUri(), { title: file.name });
                        };
                     };
                     input.click();
                }
       });

    } );
</script>