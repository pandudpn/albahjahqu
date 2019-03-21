<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Generate Reporting</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
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
                    <form method="post" action="<?php echo site_url('reporting/generate'); ?>" target="_blank">
                        <div class="form-group row">
                            <small class="text-muted"> Dealer Reporting </small>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">From</label>
                            <div class="col-9">
                                <input class="form-control datepicker" type="text" name="from" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">To</label>
                            <div class="col-9">
                                <input class="form-control datepicker" type="text" name="to" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Type</label>
                            <div class="col-9">
                                <select class="form-control select2" name="type" id="type">
                                    <option value="omzet">Omzet</option>
                                    <option value="revenue">Revenue</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer</label>
                            <div class="col-9">
                                <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                <select class="form-control select2" name="dealer_id" id="dealer_id">
                                    <option value="">All Dealer</option>
                                    <?php foreach($dealer as $deal)
                                    {
                                        echo "<option value='$deal->id'> $deal->name </option>";
                                    } 
                                    ?>
                                </select>
                                <?php }else{ ?>
                                    <select class="form-control select2" disabled>
                                        <option value="">Semua Dealer</option>
                                        <?php foreach($dealer as $deal){
                                            if($deal->id == $this->session->userdata('user')->dealer_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                    </select>
                                    <input type="hidden" name="dealer_id" value="<?php echo $this->session->userdata('user')->dealer_id; ?>">
                                <?php } ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Generate</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="col-6">
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
                    <form method="post" action="<?php echo site_url('reporting/balance'); ?>" target="_blank">
                        <div class="form-group row">
                            <small class="text-muted"> Balance Reporting </small>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">From</label>
                            <div class="col-9">
                                <input class="form-control datepicker" type="text" name="from" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">To</label>
                            <div class="col-9">
                                <input class="form-control datepicker" type="text" name="to" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer</label>
                            <div class="col-9">
                                <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                <select class="form-control select2" name="dealer_id" id="dealer_id">
                                    <option value="">All Dealer</option>
                                    <?php foreach($dealer as $deal)
                                    {
                                        echo "<option value='$deal->id'> $deal->name </option>";
                                    } 
                                    ?>
                                </select>
                                <?php }else{ ?>
                                    <select class="form-control select2" disabled>
                                        <option value="">Semua Dealer</option>
                                        <?php foreach($dealer as $deal){
                                            if($deal->id == $this->session->userdata('user')->dealer_id){
                                                echo "<option selected value='$deal->id'> $deal->name </option>";
                                            }else{
                                                echo "<option value='$deal->id'> $deal->name </option>";
                                            }
                                        } ?>
                                    </select>
                                    <input type="hidden" name="dealer_id" value="<?php echo $this->session->userdata('user')->dealer_id; ?>">
                                <?php } ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Generate</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div> 
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    })
</script>