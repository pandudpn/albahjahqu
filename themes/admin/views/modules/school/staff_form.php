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
                    <form method="post" action="<?php echo site_url('school/staff/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">

                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Sekolah / Pondok Pesantren</label>
                            <div class="col-9">
                                <select name="school" id="school" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <?php foreach($school AS $row){ ?>
                                    <option value="<?= $row->id; ?>" <?= ($row->id == $data->school_id) ? 'selected' : null ?>><?php echo $row->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Nama</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>" placeholder="Nama" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Posisi / Jabatan</label>
                            <div class="col-9">
                                <select name="position" id="position" class="form-control">
                                    <option value="staff" <?php echo ($data->position == 'staff') ? 'selected' : null ?>>Staff</option>
                                    <option value="guru" <?php echo ($data->position == 'guru') ? 'selected' : null ?>>Guru</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Nomer Telepon</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $data->phone; ?>" placeholder="Nomer Telp 08xxxxxxxx" required="required">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Alamat</label>
                            <div class="col-9">
                                <textarea class="form-control" name="address" placeholder="Jl. xxxxxx" rows="5" cols="5" required="required"><?= $data->address; ?></textarea>
                            </div>
                        </div>

                        <?php if(isset($data)){ ?>
                        <div class="mt-5" style="width: 120px; height: 120px;">
                            <img src="<?= $data->photo; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <?php } ?>

                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Foto</label>
                            <div class="col-9">
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="save">Simpan</button>
                        <a href="<?php echo site_url('school/staff'); ?>" class="btn btn-danger waves-effect waves-light">
                            Batal 
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>