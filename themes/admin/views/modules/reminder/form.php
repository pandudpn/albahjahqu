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
          <form method="post" action="<?php echo site_url('reminder/save'); ?>" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $data->id; ?>" name="id">

            <div class="form-group row">
              <label for="" class="col-3 col-form-label">Judul Notifikasi</label>
              <div class="col-9">
                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>" placeholder="Judul Notifikasi" required>
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="col-3 col-form-label">Pesan Notifikasi</label>
              <div class="col-9">
                <textarea name="quote" id="quote" cols="10" rows="10" class="form-control"><?php echo $data->quote; ?></textarea>
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="col-3 col-form-label">Notifikasi</label>
              <div class="col-9">
                <select name="alarm" id="alarm" class="form-control">
                  <option value="on" <?= ($data->alarm == 'on') ? 'selected' : null ?>>Aktif</option>
                  <option value="off" <?= ($data->alarm == 'off') ? 'selected' : null ?>>Tidak Aktif</option>
                </select>
              </div>
            </div>

            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
            <a href="<?php echo site_url('islami/ibadah'); ?>" class="btn btn-danger waves-effect waves-light">
                Batal 
            </a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
