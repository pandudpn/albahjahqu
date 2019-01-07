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
                    <form method="post" action="<?php echo site_url('referrals/save'); ?>">
                    
                    <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Dealer</label>
                            <div class="col-7">
                                <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                <select class="form-control" name="dealer_id" id="dealer_id" onchange="dealer_change(this.value)">
                                    <option value="">Choose Dealer</option>
                                    <?php foreach($dealers as $d){ 
                                            if($d->id == $data->dealer_id){
                                                echo "<option selected value='$d->id'> $d->name </option>";
                                            }else{
                                                echo "<option value='$d->id'> $d->name </option>";
                                            }
                                        } ?>
                                </select>
                                <?php }else{ ?>
                                <select class="form-control" disabled>
                                    <?php foreach($dealers as $d){ 
                                            if($d->id == $this->session->userdata('user')->dealer_id){
                                                echo "<option selected value='$d->id'> $d->name </option>";
                                            }else{
                                                echo "<option value='$d->id'> $d->name </option>";
                                            }
                                        } ?>
                                </select>
                                <input type="hidden" name="dealer_id" value="<?php echo $this->session->userdata('user')->dealer_id; ?>">
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Cluster</label>
                            <div class="col-7">
                                <select name="cluster_id" class="form-control" id="dealer_cluster" onchange="cluster_change(this.value)">
                                    <option value="">Choose Cluster</option>
                                    <?php foreach ($dealer_clusters as $key => $dc) {
                                        if($dc->id == $data->cluster_id) { $selected = 'selected'; }else{ $selected = ''; }
                                        echo '<option value="'.$dc->id.'" '.$selected.'>'.$dc->name.'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Province</label>
                            <div class="col-7">
                                <select name="province_id" class="form-control" id="province" onchange="province_change(this.value)">
                                    <option value="">Choose Province</option>
                                    <?php foreach ($provinces as $key => $p) {
                                        if($p->id == $data->province_id) { $selected = 'selected'; }else{ $selected = ''; }
                                        echo '<option value="'.$p->id.'" '.$selected.'>'.$p->name.'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">City</label>
                            <div class="col-7">
                                <select name="city_id" class="form-control" id="city" onchange="city_change(this.value)">
                                    <option value="">Choose City</option>
                                    <?php foreach ($cities as $key => $c) {
                                        if($c->id == $data->city_id) { $selected = 'selected'; }else{ $selected = ''; }
                                        echo '<option value="'.$c->id.'" '.$selected.'>'.$c->name.'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">District</label>
                            <div class="col-7">
                                <select name="district_id" class="form-control" id="district" onchange="district_change(this.value)">
                                    <option value="">Choose District</option>
                                    <?php foreach ($districts as $key => $d) {
                                        if($d->id == $data->district_id) { $selected = 'selected'; }else{ $selected = ''; }
                                        echo '<option value="'.$d->id.'" '.$selected.'>'.$d->name.'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Code</label>
                            <div class="col-7">
                                <input class="form-control" id="code" type="text" name="referral_code" value="<?php echo $data->referral_code; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Phone</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="referral_phone" value="<?php echo $data->referral_phone; ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('referrals'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> 

<script type="text/javascript">
    first_char = '';
    second_char = '';
    third_char = '';

    function province_change(id) 
    {
        $.ajax("<?php echo site_url('customers/lists_city'); ?>/"+id).done(function(data){
            $("#city").html("");

            for (var i = 0; i < data.length; i++) {
                $("#city").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
            }

            city_change($("#province").val());
        });
    }

    function city_change(id) 
    {
        $.ajax("<?php echo site_url('customers/lists_district'); ?>/"+id).done(function(data){
            $("#district").html("");

            for (var i = 0; i < data.length; i++) {
                $("#district").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
            }

            district_change($("#city").val());

        });
    }

    function district_change(id) 
    {
        <?php if(!$data) { ?>
        third_char = id;
        $("#code").val(first_char+''+second_char+''+third_char);
        <?php } ?>

        $.ajax("<?php echo site_url('customers/lists_village'); ?>/"+id).done(function(data){
            $("#village").html("");

            for (var i = 0; i < data.length; i++) {
                $("#village").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
            }
        });
    }

    function dealer_change(id) 
    {
        <?php if(!$data) { ?>
        first_char = String.fromCharCode((64 + parseInt(id)));
        $("#code").val(first_char);
        <?php } ?>

        $.ajax("<?php echo site_url('customers/lists_cluster'); ?>/"+id).done(function(data){
            $("#dealer_cluster").html("");

            for (var i = 0; i < data.length; i++) {
                $("#dealer_cluster").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
            }

            cluster_change($("#dealer_cluster").val());
        });
    }

    function cluster_change(id)
    {
        if(id != null)
        {
            second_char = id;    
        }
        
        <?php if(!$data) { ?>
        $("#code").val(first_char+''+second_char);
        <?php } ?>
    }

    $(document).ready(function(){
        dealer_change($("#dealer_id").val());
        cluster_change($("#dealer_cluster").val());
        province_change($("#province").val());
        city_change($("#city").val());
        district_change($("#district").val());
        // dealer_change('<?php echo $this->session->userdata('user')->dealer_id; ?>');
    })
</script>