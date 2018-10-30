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
                <label class="col-lg-3 col-form-label">Name</label>
                <div class="col-lg-9">
                    <input class="form-control" id="name" type="text" value="<?php echo $d->name; ?>" name="name" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">NPP</label>
                <div class="col-lg-9">
                    <input class="form-control" id="npp" type="text" value="<?php echo $d->npp; ?>" name="npp" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Group</label>
                <div class="col-lg-9">
                    <select class="form-control" id="work_group" name="work_group" required>
                        <option value="">Choose Work Group</option>
                        <?php foreach ($work_groups as $key => $g) {
                            if($d->group == $g->id)
                            {
                                $selected = 'selected';
                            }
                            else
                            {
                                $selected = '';
                            }

                            echo '<option value="'.$g->code.'" work_group_id="'.$g->id.'" '.$selected.'>'.$g->name.'</option>';
                        } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Work Unit</label>
                <div class="col-lg-9">
                    <select class="form-control" id="work_unit" name="uker" required>
                        <option value="">Choose Work Unit</option>
                        <?php foreach ($work_units as $key => $u) {
                            if($d->uker == $u->id)
                            {
                                $selected = 'selected';
                            }
                            else
                            {
                                $selected = '';
                            }
                            echo '<option value="'.$u->id.'" data-chained="'.$u->group.'" '.$selected.'>'.$u->unit.'</option>';
                        } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Email</label>
                <div class="col-lg-9">
                    <input class="form-control" id="email" type="text" value="<?php echo $d->email; ?>" name="email" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">Password</label>
                <div class="col-lg-9">
                    <input class="form-control" id="password" type="password" name="password">
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#work_unit").chained("#work_group"); 

        $("#work_group").on('change', function(e){
            var work_group_id = $("#work_group option:selected").attr("work_group_id");
            $("#work_group_id").val(work_group_id);
        });
    });
</script>