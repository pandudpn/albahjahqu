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
                <div class="col-6">
                    <form method="post" action="<?php echo site_url('islami/ibadah/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Tipe Ibadah</label>
                            <div class="col-9">
                                <select name="type" id="type" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <option value="wajib" <?= ($data->type == 'wajib') ? 'selected' : null ?>>Wajib</option>
                                    <option value="sunnah" <?= ($data->type == 'sunnah') ? 'selected' : null ?>>Sunnah</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Judul Ibadah</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>" placeholder="Judul atau Nama Ibadah" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Teks</label>
                            <div class="col-9">
                                <textarea name="text" id="text" cols="10" rows="10" class="form-control"><?php echo $data->text; ?></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('islami/ibadah'); ?>" class="btn btn-danger waves-effect waves-light">
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
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#province').change(function(){
            var prov = $(this).val();

            getCities(prov);
        });

        $('#city').change(function(){
            var city    = $(this).val();

            getDistrict(city);
        });

        $('#district').change(function(){
            var district= $(this).val();

            getVillage(district);
        });
    });

    function getCities(prov){
        $.ajax({
            url: '<?= site_url("school/cities"); ?>/' + prov,
            type: 'get',
            dataType: 'json',
            cache: false,
            success: function(result){
                var html = '<option value="" selected disabled>-</option>';
                if(result.status === 'success'){
                    $.map(result.data, (res, index) => {
                        html += '<option value="'+ res.id + '">' + res.name + '</option>'
                    });
                }

                $('#city').html(html);
            }
        });
    }

    function getDistrict(city){
        $.ajax({
            url: '<?= site_url("school/districts"); ?>/' + city,
            type: 'get',
            dataType: 'json',
            cache: false,
            success: function(result){
                console.log(result);
                var html = '<option value="" selected disabled>-</option>';
                if(result.status === 'success'){
                    $.map(result.data, (res, index) => {
                        html += '<option value="'+ res.id + '">' + res.name + '</option>'
                    });
                }

                $('#district').html(html);
            }
        });
    }

    function getVillage(district){
        $.ajax({
            url: '<?= site_url("school/villages"); ?>/' + district,
            type: 'get',
            dataType: 'json',
            cache: false,
            success: function(result){
                var html = '<option value="" selected disabled>-</option>';
                if(result.status === 'success'){
                    $.map(result.data, (res, index) => {
                        html += '<option value="'+ res.id + '">' + res.name + '</option>'
                    });
                }

                $('#village').html(html);
            }
        });
    }
</script>