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
                <div class="col-8">
                    <form method="post" action="<?php echo site_url('events/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        <input type="hidden" value="<?php echo $data->date; ?>" name="old_date">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Judul</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Pesan</label>
                            <div class="col-9">
                                <textarea class="form-control editor" id="editor1" name="message" rows="7"><?php echo $data->message; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Tanggal Kegiatan</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="date" name="date" placeholder="YYYY-mm-dd date for events" value="<?= $data->date; ?>" required>
                                <div id="result"></div>
                            </div>
                        </div>

                        <div class="mt-5"></div>
                        <hr>
                        <?php if(!isset($data)){ ?>
                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Judul Notifikasi</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" name="titlenotif" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Pesan Notifikasi</label>
                                <div class="col-9">
                                    <textarea class="form-control" name="notifmsg" rows="7"></textarea>
                                </div>
                            </div>
                        <?php } ?>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('events'); ?>" class="btn btn-danger waves-effect waves-light">
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
        $('#date').datetimepicker({
            format: 'Y-m-d H:i',
            step: 30
        });

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

    $(document).on('change', '#date', function(e){
        e.preventDefault();

        var val = $(this).val();

        var date = new Date(val);

        $.ajax({
            url: '<?= site_url("events/getcalendar"); ?>/'+date.getFullYear()+'-'+date.getMonth(),
            type: 'get',
            cache: false,
            dataType: 'json',
            success: function(result){
                if(result.status == 'success'){
                    var html = '<div class="row mt-3">';
                        html += '<div class="col-2 mb-3">Tanggal</div>';
                        html += '<div class="col-10 mb-3">Event</div>'
                        $.map(result.data, (res, index) => {
                            html += '<div class="col-2 text-center"><h5>' + res.date + '</h5></div>';
                            html += '<div class="col-10"><span>' + res.event + '</span></div>'
                        });
                        html += '</div>';
                }else if(result.stats == 'error'){
                    var html = '<span class="text-danger">Something wrong when checking calendar events</span>';
                }

                $('#result').html(html);
            }
        });
    });
</script>