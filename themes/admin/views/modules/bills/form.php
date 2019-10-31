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
                    <form method="post" action="<?php echo site_url('bills/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <!-- <?php if(empty($data)){ ?>
                            <div class="form-group">
                                <label for="sekolah" class="col-form-label col-3">Sekolah</label>
                                <div class="col-9">
                                    <select name="school" id="sekolah" class="form-control">
                                        <option value="">-</option>
                                        <?php foreach($school AS $row){ ?>
                                            <option value="<?= $row->id; ?>"><?php echo $row->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?> -->

                        <div class="form-group row">
                            <label for="student" class="col-form-label col-3">Nama Siswa</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="student" value="<?= $student->name; ?>" name="student" placeholder="Nama Siswa" <?php echo (isset($data)) ? 'readonly' : null ?>>
                                <input type="text" class="form-control mt-2" id="nis" name="nis" value="<?= $data->student_number; ?>" readonly="readonly">
                                <input type="hidden" id="student_id" value="<?= $data->student_id; ?>" name="student_id">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="type" class="col-form-label col-3">Tipe Pembayaran</label>
                            <div class="col-9">
                                <select name="type" id="type" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <option value="registration" <?php echo ($data->bill_type == 'registration') ? 'selected' : null ?>>Pendaftaran</option>
                                    <option value="deposit" <?php echo ($data->bill_type == 'deposit') ? 'selected' : null ?>>Tabungan</option>
                                    <option value="tuition" <?php echo ($data->bill_type == 'tuition') ? 'selected' : null ?>>Uang Sekolah (SPP)</option>
                                    <option value="infra" <?php echo ($data->bill_type == 'infra') ? 'selected' : null ?>>Uang Gedung</option>
                                    <option value="accomodation" <?php echo ($data->bill_type == 'accomodation') ? 'selected' : null ?>>Akomodasi</option>
                                    <option value="meals" <?php echo ($data->bill_type == 'meals') ? 'selected' : null ?>>Makanan</option>
                                    <option value="stationary" <?php echo ($data->bill_type == 'stationary') ? 'selected' : null ?>>Alat Tulis</option>
                                    <option value="book" <?php echo ($data->bill_type == 'book') ? 'selected' : null ?>>Buku</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="period_type" class="col-form-label col-3">Periode Pembayaran</label>
                            <div class="col-9">
                                <select name="period_type" id="period_type" class="form-control">
                                    <option value="">-</option>
                                    <option value="annual" <?php echo ($data->bill_period_type == 'annual') ? 'selected': null ?>>Tahunan</option>
                                    <option value="semester" <?php echo ($data->bill_period_type == 'semester') ? 'selected': null ?>>Semester</option>
                                    <option value="trimester" <?php echo ($data->bill_period_type == 'trimester') ? 'selected': null ?>>Trimester</option>
                                    <option value="quarterly" <?php echo ($data->bill_period_type == 'quarterly') ? 'selected': null ?>>Triwulan</option>
                                    <option value="monthly" <?php echo ($data->bill_period_type == 'monthly') ? 'selected': null ?>>Bulanan</option>
                                    <option value="weekly" <?php echo ($data->bill_period_type == 'weekly') ? 'selected': null ?>>Mingguan</option>
                                    <option value="daily" <?php echo ($data->bill_period_type == 'daily') ? 'selected': null ?>>Harian</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="period" class="col-form-label col-3">Periode</label>
                            <div class="col-9">
                                <input type="number" class="form-control" id="period" value="<?php echo $data->bill_period; ?>" name="period" placeholder="3 (Tahunan)">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="year" class="col-form-label col-3">Tahun</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="year" value="<?php echo $data->bill_year; ?>" name="bill_year" placeholder="2019">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amount" class="col-form-label col-3">Jumlah</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="amount" value="<?php echo $data->bill_amount; ?>" name="amount" placeholder="999.999">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="col-form-label col-3">Batas Waktu</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="date" value="<?php echo $data->bill_due_date; ?>" name="due_date" placeholder="2019-10-24">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="<?php echo site_url('bills'); ?>" class="btn btn-danger waves-effect waves-light">
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
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
<!-- jquery ui -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
        $('#student').autocomplete({
            source: "<?php echo base_url('bills/autoComplete'); ?>",
            focus: function(event, ui){
                $('#student').val(ui.item.student);
                return false;
            },
            select: function(event, ui){
                $('#student').val(ui.item.student);
                $('#nis').val(ui.item.nis);
                $('#student_id').val(ui.item.id);
                return false;
            }
        }).autocomplete("instance")._renderItem = function(ul, item){
            return $("<li>")
                    .append("<div>" + item.student + " - " + item.nis + "</div><small>" + item.unit + "</small>")
                    .appendTo(ul);
        };
    })
</script>