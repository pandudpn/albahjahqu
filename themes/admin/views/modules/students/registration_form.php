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
                    <form method="post" action="<?php echo site_url('students/registrations/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        <input type="hidden" value="<?= $dad->id; ?>" name="dadid">
                        <input type="hidden" value="<?= $mom->id; ?>" name="momid">
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="partner" class="col-form-label col-3">Yayasan</label>
                                    <div class="col-9">
                                        <select name="partner" id="partner" class="form-control" required="required">
                                            <option value="" selected disabled>-</option>
                                            <?php foreach($branch AS $row) { ?>
                                                <option value="<?= $row->id; ?>" <?php echo ($data->partner_branch_id == $row->id) ? "selected" : null; ?>><?php echo $row->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="name" class="col-3 col-form-label">Nama</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="name" name="name" value="<?= $data->name; ?>" placeholder="Nama Siswa Baru" required="required">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="grade" class="col-3 col-form-label">Kelas</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="grade" name="grade" value="<?= $data->grade; ?>" required="required" placeholder="Kelas Santri">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="city" class="col-form-label col-3">Kota</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="city" name="city" value="<?= $address->city; ?>" placeholder="Kotamadya" required="required">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label for="village" class="col-form-label col-3">Kelurahan</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control" id="village" name="village" value="<?= $address->village; ?>" placeholder="Kelurahan" required="required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label for="rt" class="col-form-label col-3">RT</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control" id="rt" name="rt" value="<?= $address->neighborhood; ?>" placeholder="RT" required="required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label for="rw" class="col-form-label col-3">RW</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control" id="rw" name="rw" value="<?= $address->citizens; ?>" placeholder="RW" required="required">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="birth" class="col-3 col-form-label">TTL</label>
                                    <div class="col-5">
                                        <input type="text" class="form-control" id="birth" name="birthplace" value="<?= $data->birthplace; ?>" placeholder="Tempat Kelahiran">
                                    </div>
                                    <div class="col-4">
                                        <input type="text" class="form-control date" id="birthday" name="birthday" value="<?= $data->birthday; ?>" placeholder="Tanggal Lahir Siswa">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="district" class="col-form-label col-3">Kecamatan</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="district" name="district" value="<?= $address->district; ?>" placeholder="Kecamatan" required="required">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="address" class="col-form-label col-3">Alamat</label>
                                    <div class="col-9">
                                        <textarea name="address" id="address" cols="5" rows="5" class="form-control" placeholder="Alamat Tempat Tinggal Siswa" required="required"><?php echo $data->address; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="dadname" class="col-form-label col-3">Nama Ayah</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="dadname" name="dadname" value="<?= $dad->name; ?>" placeholder="Nama Ayah Siswa">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="dadnum" class="col-form-label col-3">Nomer Telp Ayah</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="dadnum" name="dadnum" value="<?= $dad->phone; ?>" placeholder="Nomer Telepon Ayah">
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <label for="dadwork" class="col-form-label col-3">Pekerjaan Ayah</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="dadwork" name="dadwork" value="<?= $dad->work; ?>" placeholder="Pekerjaan Ayah Siswa">
                                    </div>
                                </div>

                            </div>

                            
                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="momname" class="col-form-label col-3">Nama Ibu</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="momname" name="momname" value="<?= $mom->name; ?>" placeholder="Nama Ibu Siswa">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="momnum" class="col-form-label col-3">Nomer Telp Ibu</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="momnum" name="momnum" value="<?= $mom->phone; ?>" placeholder="Nomer Telepon Ibu">
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <label for="momwork" class="col-form-label col-3">Pekerjaan Ibu</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="momwork" name="momwork" value="<?= $mom->work; ?>" placeholder="Pekerjaan Ibu Siswa">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('students/registrations'); ?>" class="btn btn-danger waves-effect waves-light">
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