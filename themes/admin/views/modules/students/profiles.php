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
            <a href="<?php echo site_url('students/registrations'); ?>" class="btn btn-secondary mt-3 mb-5 btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>

            <div class="row mb-3">
                <?php foreach($profiles AS $s){ ?>
                <div class="col-6">
                    <h6><?php echo ($s->role == 'father') ? "Data Ayah" : "Data Ibu"; ?></h6>
                    <div class="row">
                        <!-- nama -->
                        <div class="col-4">Nama</div>
                        <div class="col-1">:</div>
                        <div class="col-7"><?php echo $s->name; ?></div>
                        <!-- phone -->
                        <div class="col-4">Nomer Telepon</div>
                        <div class="col-1">:</div>
                        <div class="col-7"><?php echo ($s->phone == NULL) ? "" : $s->phone; ?></div>
                        <!-- pekerjaan -->
                        <div class="col-4">Pekerjaan</div>
                        <div class="col-1">:</div>
                        <div class="col-7"><?php echo ($s->work == NULL) ? "" : $s->work; ?></div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <hr>
            <div class="row mt-3">
                <div class="col-6">
                    <h6>Data Anak</h6>
                    <div class="row">
                        <!-- Nama -->
                        <div class="col-4">Nama</div>
                        <div class="col-1">:</div>
                        <div class="col-7"><?php echo $student->name; ?></div>
                        <!-- ttl -->
                        <div class="col-4">TTL</div>
                        <div class="col-1">:</div>
                        <div class="col-7"><?php echo $student->birthplace.", ".$student->birthday; ?></div>
                        <!-- kelas -->
                        <div class="col-4">Kelas</div>
                        <div class="col-1">:</div>
                        <div class="col-7"><?php echo $student->grade; ?></div>
                        <!-- jenis kelamin -->
                        <div class="col-4">Jenis Kelamin</div>
                        <div class="col-1">:</div>
                        <div class="col-7"><?php echo ($student->gender == "L") ? "Laki-laki" : "Perempuan"; ?></div>
                    </div>
                </div>

                <div class="col-6">
                    <h6>Dokumen-dokumen</h6>
                    <?php foreach($documents AS $doc) { ?>
                    <div class="mt-2">
                        <p>Dokumen <?php echo $doc->type; ?></p>
                        <a href="<?php echo $doc->url; ?>" style="width: 200px; height: 200px;" target="_blank">
                            <img src="<?php echo $doc->url; ?>" style="width: 100%; height: 100%; object-fit: contain;">
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</div>