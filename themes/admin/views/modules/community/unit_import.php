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
                    <form method="post" action="<?php echo site_url('community/units/import_save'); ?>" enctype="multipart/form-data">
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
                            <label for="" class="col-form-label col-3">File Excel</label>
                            <div class="col-9">
                                <input type="file" class="form-control" name="excel" required>
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