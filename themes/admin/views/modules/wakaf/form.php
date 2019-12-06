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
                    <form method="post" action="<?php echo site_url('wakaf/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="type" class="col-form-label col-3">Jenis Wakaf</label>
                            <div class="col-9">
                                <select name="type" id="type" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <?php foreach($type AS $row){ ?>
                                        <option value="<?= $row->id; ?>" <?php echo ($data->wakaf_type_id == $row->id) ? "selected" : null ?>><?php echo $row->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="partner" class="col-form-label col-3">Cabang</label>
                            <div class="col-9">
                                <select name="partner" id="partner" class="form-control">
                                    <option value="" selected disabled>-</option>
                                    <?php foreach($branch AS $row){ ?>
                                        <option value="<?= $row->id; ?>" <?php echo ($data->partner_branch_id == $row->id) ? "selected" : null ?>><?php echo $row->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="give" class="col-form-label col-3">Atas Nama</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="give" name="give" value="<?= $data->give_name; ?>" placeholder="Atas Nama Pewakaf" required="required">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ktp" class="col-form-label col-3">KTP</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="ktp" name="ktp" value="<?= $data->id_card_number; ?>" placeholder="KTP Pewakaf" required="required">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="npwp" class="col-form-label col-3">NPWP</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="npwp" name="npwp" value="<?= $data->npwp_number; ?>" placeholder="NPWP Pewakaf">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="title" class="col-form-label col-3">Judul</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="title" name="title" value="<?= $data->title; ?>" placeholder="Judul Wakaf">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="desc" class="col-form-label col-3">Deskripsi</label>
                            <div class="col-9">
                                <textarea name="desc" id="desc" cols="5" rows="5" class="form-control" placeholder="Deskripsi Wakaf"><?php echo $data->description; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="address" class="col-form-label col-3">Alamat</label>
                            <div class="col-9">
                                <textarea name="address" id="address" cols="5" rows="5" class="form-control" placeholder="Alamat Tempat Tinggal"><?php echo $data->address; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="address_wakaf" class="col-form-label col-3">Lokasi Wakaf</label>
                            <div class="col-9">
                                <textarea name="address_wakaf" id="address_wakaf" cols="5" rows="5" class="form-control" placeholder="Lokasi Tempat Wakaf"><?php echo $data->address_wakaf; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="date" class="col-form-label col-3">Tanggal Wakaf</label>
                            <div class="col-9">
                                <input type="text" class="form-control date" id="date" name="date" value="<?= $data->date_wakaf; ?>" placeholder="YYYY-MM-DD">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="status" class="col-form-label col-3">Status</label>
                            <div class="col-9">
                                <select name="status" id="status" class="form-control" required="required">
                                    <option value="request" <?php echo ($data->status == "request") ? "selected" : null ?>>Pengajuan</option>
                                    <option value="verified" <?php echo ($data->status == "verified") ? "selected" : null ?>>Verifikasi</option>
                                    <option value="process" <?php echo ($data->status == "process") ? "selected" : null ?>>Proses</option>
                                    <option value="approve" <?php echo ($data->status == "approve") ? "selected" : null ?>>Diterima</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <h4>Dokumen - dokumen</h4>
                        </div>
                        <div class="row mb-5">
                            <?php if(!empty($docs)){
                            foreach($docs AS $doc){ ?>
                                <div class="col-6">
                                    <h6><?php echo strtoupper(str_replace("_", " ", $doc->type)); ?></h6>
                                    <div style="width: 150px; height: 150px;">
                                        <a href="<?= $doc->photo; ?>" target="_blank"><img src="<?= $doc->photo; ?>" style="width: 100%; height: 100%; object-fit: contain;"></a>
                                    </div>
                                </div>
                            <?php }
                            } else { ?>
                                <p>Tidak ada dokumen</p>
                            <?php } ?>
                        </div>

                        <div class="row">
                            <h4>Foto - foto</h4>
                        </div>
                        <div class="row mb-5">
                            <?php if(!empty($photo)){
                            foreach($photo AS $p){ ?>
                                <div class="col-6">
                                    <h6><?php echo strtoupper(str_replace("_", " ", $p->type)); ?></h6>
                                    <div style="width: 150px; height: 150px;">
                                        <a href="<?= $p->photo; ?>" target="_blank"><img src="<?= $p->photo; ?>" style="width: 100%; height: 100%; object-fit: contain;"></a>
                                    </div>
                                </div>
                            <?php }
                            } else { ?>
                                <p>Tidak ada foto.</p>
                            <?php } ?>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('wakaf'); ?>" class="btn btn-danger waves-effect waves-light">
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
        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        })
    });
</script>