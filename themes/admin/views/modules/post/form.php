<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left"><?php echo $title; ?></h4>

            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="<?php echo site_url('post'); ?>">Posts</a></li>
                <li class="breadcrumb-item active"><?php echo $title; ?></li>
            </ol>


            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->
<form method="post" enctype="multipart/form-data">
 <div class="row">
    <div class="col-lg-8">
        <div class="card-box">
            <?php if($alert){ ?>
            <div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <?php echo $alert['msg']; ?>
            </div>
            <?php } ?>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Title</label>
                <div class="col-lg-9">
                    <input class="form-control" id="title" type="text" value="<?php echo $d->title; ?>" name="title" required onkeyup="convertToSlug()">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Slug</label>
                <div class="col-lg-9">
                    <input class="form-control" id="slug" type="text" value="<?php echo $d->slug; ?>" name="slug" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Intro</label>
                <div class="col-lg-9">
                    <textarea class="form-control" name="intro" style="width: 100%; height: 100px;"><?php echo $d->intro; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-form-label">Content</label>
                <div class="col-12">
                    <textarea class="form-control editor" id="editor1" name="content" rows="15"><?php echo $d->content; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-box">
            <div class="card-body">
                <a href="javascript:void(0)" onclick="preview()" class="btn btn-outline-primary waves-effect waves-light pull-right btn-sm">Preview</a>
                <br>
                <br>
                <div class="checkbox checkbox-primary">
                    <input id="headline" type="checkbox" name="headline" <?php if($d->headline == 'yes') { echo "checked"; } ?> onchange="headline_click()">
                    <label for="headline">
                        Set as headline
                    </label>
                </div>
                <div class="checkbox checkbox-primary">
                    <input id="featured" type="checkbox" name="featured" <?php if($d->featured == 'yes') { echo "checked"; } ?>>
                    <label for="featured">
                        Set as featured
                    </label>
                </div>
                <div class="checkbox checkbox-primary">
                    <input id="pinned" type="checkbox" name="pinned" <?php if($d->pinned == 'yes') { echo "checked"; } ?>>
                    <label for="pinned">
                        Pin this post
                    </label>
                </div>
            </div>
            <div class="card-footer">
            <div>
                <div class="row">
                    <div class="col-lg-8">
                        <select class="form-control" name="status" id="status" onchange="change_status()">
                            <option value="published_now">Publish Now</option>
                            <option value="published_later" <?php if($d->status == 'published') { echo "selected"; } ?>>Publish Later</option>
                            <option value="draft" <?php if($d->status == 'draft') { echo "selected"; } ?>>Draft</option>
                        </select>
                    </div>
                    <div class="col-lg-4 text-right">
                        <button class="btn btn-primary">Save</button></div>
                    </div>
                    <?php if($d->status == 'draft' || empty($d)) { 
                        $display = 'style="display: none; margin-top: 5px;"';
                    }else{
                        $display = 'style="margin-top: 5px;"';
                    } ?>
                    <input data-mask="9999-99-99 99:99" type="text" <?php echo $display; ?> id="published_date" name="published_date" class="form-control" placeholder="yyyy-mm-dd hh:mm" value="<?php echo $d->published_date; ?>">
                    <span class="font-13 text-muted" <?php echo $display; ?> id="eg_published_date">e.g "2018-06-12 14:30"</span>
                </div>
            </div>
        </div>
        <div class="card-box">
            <div class="card-header">
                Categories
            </div>
            <div class="card-body" style="max-height: 210px; overflow: auto;">
                <?php foreach ($categories as $key => $c) { ?>
                    <div class="radiobox radiobox-primary">
                        <input id="radio<?php echo $key; ?>" name="category" value="<?php echo $c->id; ?>" type="radio" 
                        <?php if($d->category_id == $c->id) { echo "checked"; } ?>>
                        
                        <label for="radio<?php echo $key; ?>">
                            &nbsp; <?php echo $c->name; ?>
                        </label>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="card-box">
            <div class="card-header">
                Tags
            </div>
            <div class="card-body">
                <select name="tag_id[]" class="form-control select2tags" multiple="multiple" data-placeholder="Select tags">
                    <?php foreach ($tags as $key => $t) { ?>
                    <option value="<?php echo $t->id; ?>" <?php if(in_array($t->id, $post_tags_selected)) { echo "selected"; } ?>><?php echo $t->name; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="card-box">
            <div class="card-header">
                Media
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-12 col-form-label">Featured Image</label>
                    <div class="col-12">
                        <?php if(!empty($d)) { echo "<img class='img-fluid' style='margin-bottom:10px;' src='".site_url('data/images/'.$d->featured_image)."'>"; } ?>
                        <input type="file" name="featured_image" <?php if(empty($d)) { echo "required"; } ?>>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-form-label">Featured Video (link youtube)</label>
                    <div class="col-lg-12">
                        <input class="form-control" type="text" value="<?php echo $d->featured_video; ?>" name="featured_video" placeholder="https://www.youtube.com/watch?v=xxxxxxx">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
        $('.colorpicker').colorpicker({
        format: 'hex'
    });

    $('.select2tags').select2({
        placeholder: "Select",
        width: '100%', 
        allowClear: true,
        tags: true
    });

    tinymce.init({
        selector: "#editor1",
        plugins: [
             "advlist autolink lists link image charmap print preview hr anchor pagebreak",
             "searchreplace wordcount visualblocks visualchars code fullscreen",
             "insertdatetime nonbreaking save table directionality",
             "emoticons template paste textcolor colorpicker textpattern youtube"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image responsivefilemanager youtube",
        mobile: { theme: 'mobile' },
        extended_valid_elements: "+iframe[src|width|height|name|align|class]",
        automatic_uploads: true,
        image_advtab: true,
        images_upload_url: "<?php echo site_url('post/imgupload'); ?>",
        file_picker_types: 'image', 
        paste_data_images:true,
        relative_urls: false,
        remove_script_host: false,
            file_picker_callback: function(cb, value, meta) {
                 var input = document.createElement('input');
                 input.setAttribute('type', 'file');
                 input.setAttribute('accept', 'image/*');
                 input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                       var id = 'post-image-' + (new Date()).getTime();
                       var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                       var blobInfo = blobCache.create(id, file, reader.result);
                       blobCache.add(blobInfo);
                       cb(blobInfo.blobUri(), { title: file.name });
                    };
                 };
                 input.click();
            }
   });

    headline_click();
} );

function convertToSlug()
{
    var txt = $("#title").val()
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        ;

    $("#slug").val(txt);
}

function preview()
{
    tinymce.get('editor1').execCommand('mcePreview');
}

function change_status()
{
    var status = $("#status").val();

    if(status == 'published_later' || status == 'draft')
    {
        $("#headline").prop('checked', false);
        $("#headline").attr('disabled', 'disabled');
    }
    else
    {
        $("#headline").attr('disabled', false);
    }

    if(status == 'published_later')
    {
        $("#published_date").show();
        $("#eg_published_date").show();
    }
    else
    {
        $("#published_date").hide(); 
        $("#eg_published_date").hide();
    }
}

function headline_click()
{
    if($("#headline").prop('checked'))
    {
        $("#featured").prop('checked', false);
        $("#pinned").prop('checked', false);

        $("#featured").attr('disabled', 'disabled');
        $("#pinned").attr('disabled', 'disabled');
    }
    else
    {
        $("#featured").attr('disabled', false);
        $("#pinned").attr('disabled', false);
    }
}

</script>