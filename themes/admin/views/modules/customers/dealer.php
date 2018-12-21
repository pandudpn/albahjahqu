<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-chained/1.0.1/jquery.chained.min.js"></script>
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left"><?php echo $title; ?></h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card-box" style="overflow-x: auto; zoom: 0.9;">
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
                    <form method="post">
                        <div class="col-6">
                        	<div class="form-group row">
                        		<small class="text-muted"> Geography </small>
                        	</div>

                        	<div class="form-group row">
                                <label for="" class="col-3 col-form-label">Province</label>
                                <div class="col-7">
                                    <select name="province" class="form-control" id="province" onchange="province_change(this.value)">
                                    	<option value="">Choose Province</option>
                                    	<?php foreach ($provinces as $key => $p) {
                                    		if($p->id == $city->province_id) { $selected = 'selected'; }else{ $selected = ''; }
                                    		echo '<option value="'.$p->id.'" '.$selected.'>'.$p->name.'</option>';
                                    	} ?>
                                    </select>
                                </div>
                            </div>

                        	<div class="form-group row">
                                <label for="" class="col-3 col-form-label">City</label>
                                <div class="col-7">
                                    <select name="city" class="form-control" id="city" onchange="city_change(this.value)">
                                    	<option value="">Choose City</option>
                                    	<?php foreach ($cities as $key => $c) {
                                    		if($c->id == $data->city) { $selected = 'selected'; }else{ $selected = ''; }
                                    		echo '<option value="'.$c->id.'" '.$selected.'>'.$c->name.'</option>';
                                    	} ?>
                                    </select>
                                </div>
                            </div>

                        	<div class="form-group row">
                                <label for="" class="col-3 col-form-label">District</label>
                                <div class="col-7">
                                    <select name="district" class="form-control" id="district" onchange="district_change(this.value)">
                                    	<option value="">Choose District</option>
                                    	<?php foreach ($districts as $key => $d) {
                                    		if($d->id == $data->district) { $selected = 'selected'; }else{ $selected = ''; }
                                    		echo '<option value="'.$d->id.'" '.$selected.'>'.$d->name.'</option>';
                                    	} ?>
                                    </select>
                                </div>
                            </div>

                        	<div class="form-group row">
                                <label for="" class="col-3 col-form-label">Village</label>
                                <div class="col-7">
                                    <select name="village" class="form-control" id="village">
                                    	<option value="">Choose Village</option>
                                    	<?php foreach ($villages as $key => $v) {
                                    		if($v->id == $data->village) { $selected = 'selected'; }else{ $selected = ''; }
                                    		echo '<option value="'.$v->id.'" '.$selected.'>'.$v->name.'</option>';
                                    	} ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                        		<small class="text-muted"> Dealer </small>
                        	</div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dealer</label>
                                <div class="col-7">
                                    <select name="dealer_id" class="form-control" id="dealer" onchange="dealer_change(this.value)">
                                    	<option value="">Choose Dealer</option>
                                    	<?php foreach ($dealers as $key => $d) {
                                    		if($d->id == $data->dealer_id) { $selected = 'selected'; }else{ $selected = ''; }
                                    		echo '<option value="'.$d->id.'" '.$selected.'>'.$d->name.'</option>';
                                    	} ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dealer Cluster</label>
                                <div class="col-7">
                                    <select name="cluster" class="form-control" id="dealer_cluster">
                                    	<option value="">Choose Dealer Cluster</option>
                                    	<?php foreach ($dealer_clusters as $key => $dc) {
                                    		if($dc->id == $data->cluster) { $selected = 'selected'; }else{ $selected = ''; }
                                    		echo '<option value="'.$dc->id.'" '.$selected.'>'.$dc->name.'</option>';
                                    	} ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-3 col-form-label">Dealership</label>
                                <div class="col-7">
                                    <select name="dealership" class="form-control">
                                    	<option value="wko" <?php if($data->dealership == 'wko') { echo 'selected'; } ?>>WKO</option>
                                    	<option value="wkt" <?php if($data->dealership == 'wkt') { echo 'selected'; } ?>>WKT</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            <a href="<?php echo site_url('customers'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                            </a>

                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> 

<script type="text/javascript">
	function province_change(id) 
	{
		$.ajax("<?php echo site_url('customers/lists_city'); ?>/"+id).done(function(data){
			$("#city").html("");

			for (var i = 0; i < data.length; i++) {
				$("#city").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
			}

            city_change($("province_id").val());
		});
	}

	function city_change(id) 
	{
		$.ajax("<?php echo site_url('customers/lists_district'); ?>/"+id).done(function(data){
			$("#district").html("");

			for (var i = 0; i < data.length; i++) {
				$("#district").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
			}
		});
	}

	function district_change(id) 
	{
		$.ajax("<?php echo site_url('customers/lists_village'); ?>/"+id).done(function(data){
			$("#village").html("");

			for (var i = 0; i < data.length; i++) {
				$("#village").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
			}
		});
	}

	function dealer_change(id) 
	{
		$.ajax("<?php echo site_url('customers/lists_cluster'); ?>/"+id).done(function(data){
			$("#dealer_cluster").html("");

			for (var i = 0; i < data.length; i++) {
				$("#dealer_cluster").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
			}
		});
	}
</script>
