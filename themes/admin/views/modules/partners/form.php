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
                    <form method="post" action="<?php echo site_url('partners/save'); ?>">
                    
                        <input type="hidden" value="<?php echo $data->id; ?>" name="id">

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Type</label>
                            <div class="col-6">
                                <select class="form-control" name="type">
                                    <option <?php if($data->type == 'school'){ echo 'selected'; } ?> value='school'>School</option>
                                    <option <?php if($data->type == 'city'){ echo 'selected'; } ?> value='city'>City</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Name</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="name" value="<?php echo $data->name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Description</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="description" value="<?php echo $data->description; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Province</label>
                            <div class="col-6">
                                <select class="form-control select2" name="province" id="province">
                                    <?php foreach($province as $prov){ 
                                            if($prov->id == $data->province){
                                                echo "<option selected value='$prov->id'> $prov->name </option>";
                                            }else{
                                                echo "<option value='$prov->id'> $prov->name </option>";
                                            }
                                        } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">City</label>
                            <div class="col-6">
                                <select class="form-control select2" name="city" id="city">
                                    <?php foreach($cities as $city){ 
                                            if($city->id == $data->city){
                                                echo "<option selected value='$city->id'> $city->name </option>";
                                            }else{
                                                echo "<option value='$city->id'> $city->name </option>";
                                            }
                                        } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Address</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="address" value="<?php echo $data->address; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Zip Code</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="zipcode" value="<?php echo $data->zipcode; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Phone</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="phone" value="<?php echo $data->phone; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Email</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="email" value="<?php echo $data->email; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label">Fax</label>
                            <div class="col-7">
                                <input class="form-control" type="text" name="fax" value="<?php echo $data->fax; ?>">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <a href="<?php echo site_url('partners'); ?>" class="btn btn-danger waves-effect waves-light">
                             Cancel 
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> <!-- end row -->

<script>
    $("#province").on('change', function(){
        var source   = $(this).val();
        var target   = $("#city");

        $.get("<?php echo site_url().'references/geo/city/'; ?>" + source, function (data, status) {
            target.html('');
            var obj = JSON.parse(data);
            $.each(obj, function (idx, val) {
                target.append("<option value="+obj[idx].id+">" + obj[idx].name + "</li>");
            });
        });
    });

</script>