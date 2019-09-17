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
                    <form method="post" action="<?php echo site_url('alumni/save'); ?>" enctype="multipart/form-data">
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
                            <label for="" class="col-form-label col-3">Nomor Induk Siswa</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="nis" name="nis" value="<?php echo $data->nis; ?>" placeholder="NIS 19xxxxx" required="required" readonly>
                                <div id="cek_nis"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Nama Siswa / Santri</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>" placeholder="Nama Sekolah" required>
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
                            <label for="" class="col-3 col-form-label">Tempat, Tanggal Lahir</label>
                            <div class="col-5">
                                <input type="text" class="form-control" id="place" name="place" value="<?= $data->birth_place; ?>" placeholder="Tempat Kelahiran" required="required">
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control date" id="date" name="date" value="<?= $data->birthday; ?>" placeholder="Tanggal Lahir yyyy-mm-dd" required="required">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Alamat</label>
                            <div class="col-9">
                                <textarea class="form-control" name="address" placeholder="Jl. xxxxxx" rows="5" cols="5" required="required"><?= $data->address; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Tahun Kelulusan</label>
                            <div class="col-9">
                                <input type="text" class="form-control date" id="graduate" name="graduate" value="<?= $data->graduate_date; ?>" placeholder="YYYY-mm-dd" required="required">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="save">Save</button>
                        <a href="<?php echo site_url('school/students'); ?>" class="btn btn-danger waves-effect waves-light">
                            Cancel 
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#nis').change(function(e){
            e.preventDefault();

            var nis = $(this).val();

            $.ajax({
                url: '<?= site_url("school/students/checknis"); ?>',
                type: 'post',
                data: {
                    'nis': nis
                },
                dataType: 'json',
                success: function(result){
                    var html = '';
                    if(result.status === 'error'){
                        html = '<small class="text-danger">' + result.data + '</small>';
                        $('#save').attr('disabled', 'disabled');
                    }else{
                        $('#save').removeAttr('disabled');
                    }
                    $('#cek_nis').html(html);
                }
            });
        });

        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>