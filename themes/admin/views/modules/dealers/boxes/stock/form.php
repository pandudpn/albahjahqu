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
                <div class="col-7">
                    <form method="post" action="<?php echo site_url('dealers/boxes/stock/save'); ?>">
                        
                        <input type="hidden" value="<?php echo $boxes->id; ?>" name="box_id">
                        <input type="hidden" value="<?php echo $stock->id; ?>" name="id">

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer Name</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="dealer_name" value="<?php echo $boxes->dealer_name; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">IP Box</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="ipbox" value="<?php echo $boxes->ipbox; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Type</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="type" value="<?php echo $boxes->type; ?>" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V1</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v1" value="<?php echo $stock->v1; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V5</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v5" value="<?php echo $stock->v5; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V10</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v10" value="<?php echo $stock->v10; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V15</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v15" value="<?php echo $stock->v15; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V20</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v20" value="<?php echo $stock->v20; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V25</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v25" value="<?php echo $stock->v25; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V40</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v40" value="<?php echo $stock->v40; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V50</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v50" value="<?php echo $stock->v50; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V80</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v80" value="<?php echo $stock->v80; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V100</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v100" value="<?php echo $stock->v100; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V200</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v200" value="<?php echo $stock->v200; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">V300</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" name="v300" value="<?php echo $stock->v300; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('dealers/boxes/'.$boxes->id.'/stock'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->