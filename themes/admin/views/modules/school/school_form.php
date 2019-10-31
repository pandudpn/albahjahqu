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
                    <form method="post" action="<?php echo site_url('school/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Tipe</label>
                            <div class="col-9">
                                <select name="type" id="type" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <option value="sekolah" <?= ($data->type == 'sekolah') ? 'selected' : null ?>>Sekolah</option>
                                    <option value="pesantren" <?= ($data->type == 'pesantren') ? 'selected' : null ?>>Pondok Pesantren</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Nama Sekolah / Pesantren</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>" placeholder="Nama Sekolah" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Jenjang Sekolah / Pesantren</label>
                            <div class="col-9">
                                <select name="level" id="level" class="form-control">
                                    <option value="tk" <?= ($data->level == 'tk') ? 'selected' : null; ?>>TK (Taman Kanak-kanak)</option>
                                    <option value="sd" <?= ($data->level == 'sd') ? 'selected' : null; ?>>SD (Sekolah Dasar)</option>
                                    <option value="smp" <?= ($data->level == 'smp') ? 'selected' : null; ?>>SMP (Sekolah Menengah Pertama)</option>
                                    <option value="sma" <?= ($data->level == 'sma') ? 'selected' : null; ?>>SMA (Sekolah Menengah Atas)</option>
                                    <option value="smk" <?= ($data->level == 'smk') ? 'selected' : null; ?>>SMK (Sekolah Menengah Kejuruan)</option>
                                    <option value="universitas" <?= ($data->level == 'universitas') ? 'selected' : null; ?>>Universitas</option>
                                    <option value="tahfidz" <?= ($data->level == 'tahfidz') ? 'selected' : null; ?>>Tahfidz</option>
                                    <option value="tafaquh" <?= ($data->level == 'tafaquh') ? 'selected' : null; ?>>Tafaquh</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Tanggal Berdiri</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="date" id="date" value="<?= $data->date; ?>" placeholder="yyyy-mm-dd">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Nomer SK Pendirian</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="sk" value="<?= $data->no_sk; ?>" placeholder="xx/xx/xxx/xxx">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Nomer Induk Sekolah</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="induk" value="<?= $data->no_induk; ?>" placeholder="xx/xx/xxx/xxx">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Alamat Sekolah</label>
                            <div class="col-9">
                                <textarea class="form-control" name="address" placeholder="Jl. xxxxxx" rows="5" cols="5" required="required"><?= $data->address; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Provinsi</label>
                            <div class="col-9">
                                <select name="province" id="province" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <?php foreach($provinsi AS $row){ ?>
                                        <option value="<?= $row->id; ?>" <?= ($data->province == $row->id) ? 'selected' : null ?>><?= $row->name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Kota / Kabupaten</label>
                            <div class="col-9">
                                <select name="city" id="city" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <?php if(isset($kabupaten)){ 
                                        foreach($kabupaten AS $row){ ?>
                                        <option value="<?= $row->id; ?>" <?= ($data->city == $row->id) ? 'selected' : null ?>><?= $row->name; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Kecamatan</label>
                            <div class="col-9">
                                <select name="district" id="district" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <?php if(isset($kecamatan)){ 
                                        foreach($kecamatan AS $row){ ?>
                                        <option value="<?= $row->id; ?>" <?= ($data->district == $row->id) ? 'selected' : null ?>><?= $row->name; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Kelurahan</label>
                            <div class="col-9">
                                <select name="village" id="village" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <?php if(isset($kelurahan)){ 
                                        foreach($kelurahan AS $row){ ?>
                                        <option value="<?= $row->id; ?>" <?= ($data->village == $row->id) ? 'selected' : null ?>><?= $row->name; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="">Latitude</label>
                                    <input type="text" class="form-control" id="lat" name="lat" value="<?= $data->lat; ?>" placeholder="-6.3333333">
                                </div>
                                <div class="col-6">
                                    <label for="">Longitude</label>
                                    <input type="text" class="form-control" id="long" name="long" value="<?= $data->long; ?>" placeholder="103.3333333">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('school'); ?>" class="btn btn-danger waves-effect waves-light">
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