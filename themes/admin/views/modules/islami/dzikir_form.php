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
                    <form method="post" id="form" action="<?php echo site_url('islami/dzikir/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Judul</label>
                            <div class="col-9">
                                <input class="form-control" id="title" type="text" name="title" value="<?php echo $data->title; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-3">
                                <label for="txt">
                                    <input class="checked" name="type" type="radio" id="txt" value="txt" checked>
                                    Teks
                                </label>
                            </div>
                            <div class="col-3">
                                <label for="import">
                                    <input class="checked" name="type" type="radio" id="import" value="import">
                                    PDF
                                </label>
                            </div>
                        </div>

                        <div id="text">
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Teks</label>
                                <div class="col-9">
                                    <textarea class="form-control editor" id="editor1" name="content" rows="10"><?php echo $data->content_dzikir; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div id="image" class="d-none">
                            <div class="form-group row">
                                <label for="photo" class="col-form-label col-3">File PDF</label>
                                <div class="col-9">
                                    <input type="file" class="form-control" name="pdf">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="Yes">Simpan</button>
                        <a href="<?php echo site_url('islami/dzikir'); ?>" class="btn btn-danger waves-effect waves-light">
                            Batal 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
    var radio   = $('.checked:checked').val();
    $(document).ready(function() {
        $('.checked').change(function(e) {
            e.preventDefault();

            radio = $(this).val();

            if(radio == "txt") {
                $('#text').removeClass('d-none');
                $('#image').addClass('d-none');
            }else if(radio == "import") {
                $('#text').addClass('d-none');
                $('#image').removeClass('d-none');
            }
        })
        tinymce.init({
            selector: "#editor1",
            plugins: [
                 "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                 "searchreplace wordcount visualblocks visualchars code fullscreen",
                 "insertdatetime nonbreaking save table directionality",
                 "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image responsivefilemanager",
            mobile: { theme: 'mobile' },
            extended_valid_elements: "+iframe[src|width|height|name|align|class]",
            automatic_uploads: true,
            image_advtab: true,
            images_upload_url: "<?php echo site_url('articles/imgupload'); ?>",
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

    });
</script>