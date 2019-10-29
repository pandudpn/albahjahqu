<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left"><?php echo $title; ?></h4>

            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="<?php echo site_url('post'); ?>">Users</a></li>
                <li class="breadcrumb-item active"><?php echo $title; ?></li>
            </ol>


            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

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
                <label class="col-lg-3 col-form-label">Upload CSV</label>
                <div class="col-lg-9">
                    <input class="form-control" type="file" name="file" required>
                    <small class="text-muted">
                    	Example of csv file <a href="<?php echo site_url('assets/contoh.csv'); ?>">click here</a><br>
                    	Example list of work group and work unit <a href="<?php echo site_url('user/work_group_unit'); ?>">click here</a>
                    </small>

                </div>
            </div>
            <div class="form-group text-right m-b-0">
                <input class="form-control" id="work_group_id" type="hidden" name="group" value="<?php echo $d->group; ?>" required>
                <button class="btn btn-primary waves-effect waves-light" type="submit">
                    Submit
                </button>
                <a href="<?php echo site_url('user'); ?>" class="btn btn-secondary waves-effect m-l-5">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>