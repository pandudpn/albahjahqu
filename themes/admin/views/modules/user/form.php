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
        <div class="card-box table-responsive" style="overflow-x: auto; zoom: 0.9;">
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
                    <form method="post" action="<?php echo site_url('user/admin/save'); ?>">
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Name</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Email</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="email" value="<?php echo $data->email; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Phone</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="phone" value="<?php echo $data->phone; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Password</label>
                            <div class="col-7">
                                <input class="form-control" type="password" name="password">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Apps</label>
                            <div class="col-7">
                                <select name="app_id" class="form-control">
                                    <?php foreach ($apps as $key => $a) { ?>
                                        <option value="<?php echo $a->package_name; ?>" 
                                            <?php if($data->app_id == $a->package_name) { echo "selected"; } else { echo ""; }  ?>>
                                            <?php echo $a->name; ?>        
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('user/admin'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->