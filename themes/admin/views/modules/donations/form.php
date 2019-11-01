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
                    <form method="post" action="<?php echo site_url('donations/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">

                        <div class="form-group row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="type" class="col-form-label col-3">Tipe Donasi</label>
                                        <div class="col-9">
                                            <select name="type" id="type" class="form-control" required="required">
                                                <option value="reguler" <?php echo $data->type_donation == 'reguler' ? 'selected' : null ?>>Reguler</option>
                                                <option value="specific" <?php echo $data->type_donation == 'specific' ? 'selected' : null ?>>Spesifik</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="category" class="col-form-label col-3">Kategori Donasi</label>
                                        <div class="col-9">
                                            <select name="category" id="category" class="form-control" required="required">
                                                <option value="infaq" <?php echo $data->category_donation == 'infaq' ? 'selected' : null ?>>Infaq</option>
                                                <option value="shodaqoh" <?php echo $data->category_donation == 'shodaqoh' ? 'selected' : null ?>>Shodaqoh</option>
                                                <option value="zakat" <?php echo $data->category_donation == 'zakat' ? 'selected' : null ?>>Zakat</option>
                                                <option value="waqaf" <?php echo $data->category_donation == 'waqaf' ? 'selected' : null ?>>Waqaf</option>
                                                <option value="donation" <?php echo $data->category_donation == 'donation' ? 'selected' : null ?>>Donasi</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <small style="color: #a0a0a0;">
                                    *info <br>
                                    Reguler (Infaq)         => 7200420092 <br>
                                    Reguler (shadaqah)      => 7200420092 <br>
                                    Reguler (zakat)         => 7512227777 <br>
                                    Reguler (wakaf)         => 7512225556 <br>
                                    Reguler (donasi)        => 7510002223 <br>
                                    Spesifik (Infaq)        => 7722006668
                                </small>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Judul</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Deskripsi</label>
                            <div class="col-9">
                                <textarea class="form-control editor" id="editor1" name="description" rows="15"><?php echo $data->description; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Nominal Tipe Donasi</label>
                            <div class="col-3">
                                <label for="txt">
                                    <input class="checked" name="nom_type" type="radio" id="txt" value="freetext" <?php echo isset($data) ? $data->nominal_type == 'freetext' ? 'checked' : null : 'checked' ?>>
                                    Free Text
                                </label>
                            </div>
                            <div class="col-3">
                                <label for="slot">
                                    <input class="checked" name="nom_type" type="radio" id="slot" value="slot" <?php echo isset($data) ? $data->nominal_type == 'slot' ? 'checked' : null : null ?>>
                                    Slot
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="" class="col-6 col-form-label">Nominal /slot</label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" id="slot_amount" name="slot_amount" value="<?php echo $data->slot_amount; ?>" placeholder="Rp 100.000" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="" class="col-3 col-form-label">Satuan</label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" id="satuan" name="satuan" value="<?php echo $data->unit; ?>" placeholder="m2 atau liter" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Batas Waktu</label>
                            <div class="col-9">
                                <input class="form-control date" type="text" name="due_date" value="<?php echo $data->due_date; ?>" placeholder="2019-12-12" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Target Nominal</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="target" value="<?php echo $data->target_amount; ?>" required placeholder="Rp 10.000.000">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Headline</label>
                            <div class="col-9">
                                <select name="headline" id="headline" class="form-control">
                                    <option value="headline" <?php echo ($data->type == 'headline') ? 'selected' : null ?>>Ya</option>
                                    <option value="list" <?php echo ($data->type == 'list') ? 'selected' : null ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <?php if(isset($data)){ ?>
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-9">
                                <?php foreach($photos AS $photo) { ?>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <div style="width: 200px; height: 200px;" class="d-block mx-auto">
                                                <img src="<?php echo $photo->photo; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                        </div>
                                        <div class="col-1 pt-2">
                                            <a href="<?php echo site_url('donations/delete_photo/'.$data->id.'/'.$photo->id); ?>" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Foto</label>
                            <div class="col-9">
                                <input class="form-control" type="file" name="image[]" multiple <?php echo isset($data) ? null : 'required' ?>>
                                <small class="text-gray">* foto bisa lebih dari 1</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('donations'); ?>" class="btn btn-danger waves-effect waves-light">
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
        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        tinymce.init({
            selector: "#editor1",
            plugins: [
                 "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                 "searchreplace wordcount visualblocks visualchars code fullscreen",
                 "insertdatetime nonbreaking save table directionality",
                 "emoticons template paste textcolor colorpicker textpattern youtube"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image responsivefilemanager",
            mobile: { theme: 'mobile' },
            extended_valid_elements: "+iframe[src|width|height|name|align|class]",
            automatic_uploads: true,
            image_advtab: true,
            images_upload_url: "<?php echo site_url('donations/imgupload'); ?>",
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

       $('.checked').change(function(e) {
            e.preventDefault();

            radio = $(this).val();

            if(radio == "freetext") {
                $('#slot_amount').attr('readonly', 'readonly');
                $('#satuan').attr('readonly', 'readonly');
                $('#slot_amount').removeAttr('required');
                $('#satuan').removeAttr('required');
            }else if(radio == "slot") {
                $('#slot_amount').removeAttr('readonly');
                $('#satuan').removeAttr('readonly');
                $('#slot_amount').attr('required', 'required');
                $('#satuan').attr('required', 'required');
            }
        })
    });
</script>