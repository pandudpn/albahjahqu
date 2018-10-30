<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Add New Post</h4>

            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="<?php echo site_url('banner'); ?>">Posts</a></li>
                <li class="breadcrumb-item active">Add New Post</li>
            </ol>


            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

 <div class="row">
    <div class="col-8">
        <div class="card-box">
            <div class="form-group row">
                <label class="col-3 col-form-label">Title</label>
                <div class="col-9">
                    <input class="form-control" type="text" value="" name="title" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Slug</label>
                <div class="col-9">
                    <input class="form-control" type="text" value="" name="title" required>
                </div>
            </div>
            <div class="form-group row">
            	<label class="col-12 col-form-label">Content</label>
            	<div class="col-12">
            		<textarea class="form-control editor" id="editor" name="description" ></textarea>
            	</div>
            </div>
        </div>
    </div>
    <div class="col-4">
    	<div class="card-box">
    		<div class="card-footer">
            <div><a href="#" class="card-link" style="color: red;">Move to trash</a> <a href="#" class="btn btn-primary btn-sm" style="margin-left: 70px;">Publish</a></div>
            </div>
    	</div>
    	<div class="card-box">
    		<div class="card-header">
    			Categories
    		</div>
    		<div class="card-body">
    			<div class="checkbox checkbox-primary">
                    <input id="checkbox1" type="checkbox">
                    <label for="checkbox1">
                        Lorem Ipsum
                    </label>
                </div>

                <div class="checkbox checkbox-primary">
                    <input id="checkbox2" type="checkbox">
                    <label for="checkbox2">
                        Dolor Sit
                    </label>
                </div>

                <div class="checkbox checkbox-primary">
                    <input id="checkbox3" type="checkbox">
                    <label for="checkbox3">
                        Lorem
                    </label>
                </div>

                <div class="checkbox checkbox-primary">
                    <input id="checkbox4" type="checkbox">
                    <label for="checkbox4">
                        Dolor
                    </label>
                </div>
    		</div>
    		<div class="card-footer">
    			<a href="">+ Add New Category</a>
    		</div>
    	</div>

    	<div class="card-box">
    		<div class="card-header">
    			Featured image
    		</div>
    		<div class="card-body">
    			<img src="<?php echo site_url('assets/upload.png');?>" class="img-fluid">
    		</div>
    	</div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
        $('.colorpicker').colorpicker({
        format: 'hex'
    });

        CKEDITOR.replace( 'editor' );
        CKEDITOR.config.width = '100%';   // CSS unit (percent).
        CKEDITOR.config.height = '250px';   // CSS unit (percent).
} );

</script>