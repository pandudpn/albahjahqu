<script src="<?php echo $this->template->get_theme_path();?>assets/tinymce/js/tinymce/tinymce.min.js"></script>
<style>
    #_cover-img {
        width: 150px;
        height: 150px;
    }

    #_cover-img > img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>

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
                    <form method="post" action="<?php echo site_url('community/staff/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        <input type="hidden" value="<?php echo $data->photo; ?>" name="old_photo">
                        
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Sekolah / Kantor</label>
                            <div class="col-9">
                                <select name="unit" id="unit" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <?php foreach($unit AS $units){ ?>
                                        <option value="<?= $units->id; ?>" <?php echo ($data->unit_id == $units->id) ? 'selected' : null ?>><?php echo $units->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-form-label col-3">Nama</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="name" name="name" value="<?= $data->name; ?>" placeholder="Nama Pengurus / Guru" required="required">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-form-label col-3">Telepon</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= $data->phone; ?>" placeholder="Nomer Telepon" required="required">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Jenis Kelamin</label>
                            <div class="col-4">
                                <label for="laki">
                                    <input type="radio" name="gender" id="laki" value="Laki-laki" <?php echo ($data->gender == 'Laki-laki') ? 'checked' : 'checked' ?>>
                                    Laki-laki
                                </label>
                            </div>
                            <div class="col-4">
                                <label for="perempuan">
                                    <input type="radio" name="gender" id="perempuan" value="Perempuan" <?php echo ($data->gender == 'Perempuan') ? 'checked' : null ?>>
                                    Perempuan
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="place" class="col-form-label col-3">TTL</label>
                            <div class="col-5">
                                <input type="text" class="form-control" id="place" name="place" value="<?= $data->birthplace; ?>" placeholder="Tempat Kelahiran" required="required">
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" id="date" name="date" value="<?= $data->birthday; ?>" placeholder="Tanggal Lahir YYYY-MM-DD" required="required">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-form-label col-3">Alamat</label>
                            <div class="col-9">
                                <textarea name="address" id="address" cols="5" rows="5" class="form-control" placeholder="Alamat Rumah Pengurus / Guru" required="required"><?php echo $data->address; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="position" class="col-form-label col-3">Posisi / Jabatan</label>
                            <div class="col-9">
                                <select name="position" id="position" class="form-control" required="required">
                                    <option value="staff" <?php echo ($data->position == 'staff') ? 'selected' : null ?>>Pengurus</option>
                                    <option value="guru" <?php echo ($data->position == 'guru') ? 'selected' : null ?>>Guru</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="photo" class="col-form-label col-3">Foto</label>
                            <div class="col-9">
                                <?php if(isset($data)){ ?>
                                    <div id="_cover-img" class="mb-4">
                                        <img src="<?= $data->photo; ?>" alt="Foto Pengurus / Guru">
                                    </div>
                                <?php } ?>
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="save">Simpan</button>
                        <a href="<?php echo site_url('community/students'); ?>" class="btn btn-danger waves-effect waves-light">
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
    });
</script>