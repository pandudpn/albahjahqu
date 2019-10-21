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
                    <form method="post" action="<?php echo site_url('community/units/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Tipe</label>
                            <div class="col-9">
                                <select name="type" id="type" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <!-- <option value="kantor" <?= ($data->type == 'kantor') ? 'selected' : null ?>>Kantor</option> -->
                                    <option value="sekolah" <?= ($data->type == 'sekolah') ? 'selected' : null ?>>Sekolah</option>
                                    <option value="pesantren" <?= ($data->type == 'pesantren') ? 'selected' : null ?>>Pondok Pesantren</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Nama Sekolah / Pesantren / Kantor</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>" placeholder="Nama Sekolah / Pesantren / Kantor" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Tingkat</label>
                            <div class="col-9">
                                <select name="level" id="level" class="form-control">
                                    <!-- <option value="kantor" <?= ($data->level == 'kantor') ? 'selected' : null; ?>>Kantor</option> -->
                                    <option value="paud" <?= ($data->level == 'paud') ? 'selected' : null; ?>>Pendidikan Anak Usia Dini</option>
                                    <option value="tk" <?= ($data->level == 'tk') ? 'selected' : null; ?>>TK (Taman Kanak-kanak)</option>
                                    <option value="sd" <?= ($data->level == 'sd') ? 'selected' : null; ?>>SD (Sekolah Dasar)</option>
                                    <option value="mi" <?= ($data->level == 'mi') ? 'selected' : null; ?>>Madrasah Ibtidaiyah</option>
                                    <option value="smp" <?= ($data->level == 'smp') ? 'selected' : null; ?>>SMP (Sekolah Menengah Pertama)</option>
                                    <option value="mts" <?= ($data->level == 'mts') ? 'selected' : null; ?>>Madrasah Tsanawiyah</option>
                                    <option value="sma" <?= ($data->level == 'sma') ? 'selected' : null; ?>>SMA (Sekolah Menengah Atas)</option>
                                    <option value="smk" <?= ($data->level == 'smk') ? 'selected' : null; ?>>SMK (Sekolah Menengah Kejuruan)</option>
                                    <option value="ma" <?= ($data->level == 'ma') ? 'selected' : null; ?>>Madrasah Aliyah</option>
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
                            <label for="" class="col-3 col-form-label">Alamat Sekolah / Pesantren / Kantor</label>
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

                        <div class="form-group row" id="photo">
                            <label for="foto" class="col-form-label col-3">Foto Bangunan</label>
                            <div class="col-9">
                                <input type="file" class="form-control" name="image[]" multiple="multiple">
                                <small style="color: #aaa">Foto bisa lebih dari 1.</small>
                            </div>
                        </div>

                        <div class="form-group row" id="photo">
                            <label for="foto" class="col-form-label col-3">Fasilitas Sekolah</label>
                            <div class="col-9" id="faci">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" class="form-control" id="faci_name" name="faci_name[]" placeholder="Nama Fasilitas">
                                    </div>
                                    <div class="col-5">
                                        <input type="file" class="form-control" name="faci_image[]" multiple="multiple">
                                    </div>
                                    <div class="col-1">
                                        <button class="btn btn-success btn-sm" id="add"><i class="mdi mdi-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('community/units'); ?>" class="btn btn-danger waves-effect waves-light">
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
            url: '<?= site_url("community/units/cities"); ?>/' + prov,
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
            url: '<?= site_url("community/units/districts"); ?>/' + city,
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
            url: '<?= site_url("community/units/villages"); ?>/' + district,
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