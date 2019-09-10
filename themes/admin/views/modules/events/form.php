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
                    <form method="post" action="<?php echo site_url('events/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Title</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="title" value="<?php echo $data->title; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Message</label>
                            <div class="col-9">
                                <textarea class="form-control editor" id="editor1" name="message" rows="7"><?php echo $data->message; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Date</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="date" name="date" placeholder="YYYY-mm-dd date for events" value="<?= $data->date; ?>" required>
                                <div id="result"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('events'); ?>" class="btn btn-danger waves-effect waves-light">
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
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });

    $(document).on('change', '#date', function(e){
        e.preventDefault();

        var val = $(this).val();

        $.ajax({
            url: '<?= site_url("events/getcalendar"); ?>/'+val,
            type: 'get',
            cache: false,
            dataType: 'json',
            success: function(result){
                if(result.status == 'success'){
                    var html = '<div class="row mt-3">';
                        html += '<div class="col-1 mb-3">Tanggal</div>';
                        html += '<div class="col-11 mb-3">Event</div>'
                        $.map(result.data, (res, index) => {
                            html += '<div class="col-1 text-center"><h5>' + res.date + '</h5></div>';
                            html += '<div class="col-11"><span>' + res.event + '</span></div>'
                        });
                        html += '</div>';
                }else if(result.stats == 'error'){
                    var html = '<span class="text-danger">Something wrong when checking calendar events</span>';
                }

                $('#result').html(html);
            }
        });
    });
</script>