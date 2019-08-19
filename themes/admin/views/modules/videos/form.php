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
                <?php if(!isset($data)){ ?>
                <div class="col-8 mx-auto">
                    <form action="<?= site_url('youtube/add'); ?>" method="get" id="sChannel">
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">URL Youtube</label>
                            <div class="col-8">
                                <input type="text" class="form-control" id="video" name="video" placeholder="e.g: https://www.youtube.com/watch?v=5W4OsfnGGmc">
                                <small class="text-secondary">Example: https://www.youtube.com/watch?v=5W4OsfnGGmc</small>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php } ?>
                <div class="col-12 m-t-30">
                    <div id="result"></div>
                    <form method="post" action="<?php echo site_url('videos/save'); ?>" enctype="multipart/form-data">
                        <input type="hidden" name="created" id="created" value="<?= $data->created_on; ?>">
                        <input type="hidden" name="photo" id="photo" value="<?= $data->thumbnail; ?>">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Youtube ID</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="id" name="id" value="<?php echo $data->id; ?>" placeholder="Youtube Video ID" readonly>
                            </div>
                        </div>

                        <!-- title -->
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Title</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $data->title; ?>" placeholder="Title Video">
                            </div>
                        </div>

                        <!-- desc -->
                        <div class="form-group row">
                            <label for="" class="col-form-label col-3">Description</label>
                            <div class="col-9">
                                <textarea name="desc" id="desc" cols="10" rows="10" class="form-control"><?php echo $data->description; ?></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" id="save" <?php (!isset($data)) ? 'disabled' : null ?>>Save</button>
                        <a href="<?= site_url('/youtube'); ?>" class="btn btn-danger waves-effect waves-light">Cancel</a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#sChannel').submit(function(e){
            e.preventDefault();

            var video = $('#video').val();

            Youtube(video);
        });
    });

    function Youtube(video){
        $.ajax({
            url: '<?= site_url("videos/data"); ?>',
            type: 'get',
            cache: false,
            data: {
                'video': video
            },
            success: function(result){
                var html = '<div class="row mb-5">';
                    html += '<div class="col-3 mt-3 mx-auto">';
                    html += '<a href="https://www.youtube.com/watch?v='+result.data.items[0].id+'" target="_blank" style="color:black;">';
                    html += '<div height="'+result.data.items[0].snippet.thumbnails.high.height+'" width="'+result.data.items[0].snippet.thumbnails.high.width+'">';
                    html += '<img src="'+result.data.items[0].snippet.thumbnails.high.url+'" class="display-block mx-auto" style="height: 100%; width: 100%; object-fit: contain;">';
                    html += '</div>';
                    html += '<p>'+result.data.items[0].snippet.title+'</p>';
                    html += '</a>';
                    html += '</div>';
                    html += '</div>';

                $('#result').html(html);
                $('#id').val(result.data.items[0].id);
                $('#title').val(result.data.items[0].snippet.title);
                $('#desc').val(result.data.items[0].snippet.description);
                $('#created').val(result.data.items[0].snippet.publishedAt);
                $('#photo').val(result.data.items[0].snippet.thumbnails.medium.url);
                $('#save').removeAttr('disabled');
            }
        })
    }
</script>