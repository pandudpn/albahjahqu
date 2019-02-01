<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Push Notif </h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <div class="row">
                <div class="col-7">
                    <?php if($alert){ ?>
                    <div class="alert alert-<?php echo $alert['type']; ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?php echo $alert['msg']; ?>
                    </div>
                    <?php } ?> 

                    <form method="post" action="">
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">To</label>
                            <?php if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') { ?>
                            <div class="col-9">
                                <input class="form-control" type="text" value="<?php echo $dealer->name; ?>" readonly>
                                <input type="hidden" name="dealer" value="<?php echo $dealer->id; ?>">
                            </div>
                            <?php } else { ?>
                            <div class="col-9">
                                <select name="dealer" class="form-control">
                                    <option value="">All Users</option>
                                    <?php foreach($dealers as $deal){
                                        echo "<option value='$deal->id'> Users of $deal->name </option>";
                                    } ?>
                                </select>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Title</label>
                            <div class="col-9">
                                <input class="form-control" type="text" name="title">
                            </div>
                        </div>

                         <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Message</label>
                            <div class="col-9">
                                <textarea class="form-control" name="message" rows="5"></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light pull-right">
                            Send Push Notification <i class="fa fa-bullhorn"></i>
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->