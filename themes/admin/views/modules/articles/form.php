<script src="<?php echo $this->template->get_theme_path();?>assets/tinymce/js/tinymce/tinymce.min.js"></script>

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left"><?php echo $title; ?> Article</h4>

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
                    <form method="post" action="<?php echo site_url($url_save); ?>" enctype="multipart/form-data">
                    
                    <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <?php if($this->session->userdata('user')->app_id == 'com.dekape.okbabe') { ?>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Type</label>
                            <div class="col-9">
                                <select class="form-control" name="for" id="for" onchange="type_change()">
                                    <option value="">Pilih Tipe Artikel</option>
                                    <option <?php if($data->for == 'okbabe'){ echo 'selected'; } ?> value='okbabe'>OKBABE</option>
                                    <option <?php if($data->for == 'dealer'){ echo 'selected'; } ?> value='dealer'>DEALER</option>
                                    <?php if(strtolower($title) == 'add' && $this->session->userdata('user')->role =='dekape'){ ?>
                                    <option value='all_apps'>ALL APPS</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                            <input type="hidden" name="for" value="dealer">
                        <?php } ?>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer name</label>
                            <div class="col-9">
                                <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                <select class="form-control select2" name="for_dealer" id="for_dealer">
                                    <option value="">Pilih Dealer</option>
                                    <?php foreach($dealer as $deal){
                                        if($deal->id == $data->for_dealer){
                                            echo "<option selected value='$deal->id'> $deal->name </option>";
                                        }else{
                                            echo "<option value='$deal->id'> $deal->name </option>";
                                        }
                                    } ?>
                                </select>
                                <?php }else{ ?>
                                    <select class="form-control select2" disabled>
                                        <option value="">Pilih Dealer</option>
                                        <?php foreach($dealer as $deal){
                                            if($deal->id == $this->session->userdata('user')->dealer_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                    </select>
                                    <input type="hidden" name="for_dealer" value="<?php echo $this->session->userdata('user')->dealer_id; ?>">
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Title</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Content</label>
                            <div class="col-9">
                                <textarea class="form-control editor" id="editor1" name="content" rows="15"><?php echo $data->content; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Cover Image</label>
                            <div class="col-9">
                                <?php if(!empty($data)) { ?>
                                <img src="<?php echo site_url('data/images/'.$data->cover_image); ?>" height="200" style="margin-bottom: 5px;">
                                <?php } ?>
                                <input class="form-control" type="file" name="cover_image">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Status</label>
                            <div class="col-9">
                                <select class="form-control" name="status" id="status">
                                    <option <?php if($data->status == 'enabled'){ echo 'selected'; } ?> value='enabled'>enabled</option>
                                    <option <?php if($data->status == 'disabled'){ echo 'selected'; } ?> value='disabled'>disabled</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('articles'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> 
<script type="text/javascript">
    $(document).ready(function() {

        type_change()
        
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

    } );

    function type_change()
    {
        let type = $("#for").val();

        if(type == 'okbabe' || type = 'all_apps')
        {
            $("#for_dealer").attr('disabled', 'disabled');
        }
        else
        {
            $("#for_dealer").attr('disabled', false);
        }
        
    }
</script>