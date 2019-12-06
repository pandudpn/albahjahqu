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
                    <form method="post" action="<?php echo site_url('wakaf/assignment_save/'.$data->id); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="officer" class="col-form-label col-3">Petugas</label>
                            <div class="col-9">
                                <select name="officer" id="officer" class="form-control" required="required">
                                    <option value="" selected disabled>-</option>
                                    <?php foreach($officer AS $officers){ ?>
                                        <option value="<?php echo $officers['id']; ?>" <?php $data->officer_id == $officers['id'] ? "selected" : null ?>><?php echo $officers['name']. " - (".$officers['total']." Penugasan)"; ?></option>
                                    <?php } ?>
                                </select>
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