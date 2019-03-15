<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Denom Price</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="p-20">
            <a href="<?php echo site_url('prices/denom/add?'.$_SERVER["QUERY_STRING"]); ?>"><button class="btn btn-sm btn-primary waves-effect waves-light">
                <i class="zmdi zmdi-collection-plus"></i> Add Denom Price</button>
            </a>
            <div class="col-3 pull-right text-right">
                <a href="<?php echo site_url('prices/denom/download?'.$_SERVER["QUERY_STRING"]); ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download</a>
            </div>
        </div>
        <div class="card-box table-responsive" style="overflow-x: auto; zoom: 0.8;">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?> 

            <form method="get">
                <div class="row" style="margin-bottom: 15px; margin-left: 5px;">
                    <div class="col-12">Filter : </div>
                    <div class="col-2">
                        <select class="form-control" name="provider" style="height: 40.74px;">
                            <option value="">Choose Provider</option>
                            <?php foreach ($provider_lists as $key => $pl) { if($pl->operator == $provider) { $selected = 'selected'; }else{ $selected = ''; } ?>
                            <option value="<?php echo $pl->operator; ?>" <?php echo $selected; ?>><?php echo $pl->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-2">
                        <select class="form-control" name="category" style="height: 40.74px;">
                            <option value="">Choose Category</option>
                            <option <?php if($category == 'REG'){ echo 'selected'; } ?> value='REG'>REG</option>
                            <option <?php if($category == 'DAT'){ echo 'selected'; } ?> value='DAT'>DAT</option>
                            <option <?php if($category == 'PKD'){ echo 'selected'; } ?> value='PKD'>PKD</option>
                            <option <?php if($category == 'PKT'){ echo 'selected'; } ?> value='PKT'>PKT</option>
                            <option <?php if($category == 'BLK'){ echo 'selected'; } ?> value='BLK'>BLK</option>
                            <option <?php if($category == 'DLK'){ echo 'selected'; } ?> value='DLK'>DLK</option>
                            <option <?php if($category == 'TLK'){ echo 'selected'; } ?> value='TLK'>TLK</option>
                            <option <?php if($category == 'NAP'){ echo 'selected'; } ?> value='NAP'>NAP</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select class="form-control" name="type" style="height: 40.74px;">
                            <option value="">Choose Type</option>
                            <option value="inner" <?php if($type == 'inner') { echo 'selected'; } ?>>Inner</option>
                            <option value="outer" <?php if($type == 'outer') { echo 'selected'; } ?>>Outer</option>
                        </select>
                    </div>
                    <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                    <div class="col-2">
                        <select class="form-control" name="dealer" style="height: 40.74px;">
                            <option value="">Choose Dealer</option>
                            <?php foreach ($dealers as $key => $d) { if($d->id == $dealer) { $selected = 'selected'; }else{ $selected = ''; } ?>
                            <option value="<?php echo $d->id; ?>" <?php echo $selected; ?>><?php echo $d->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php } ?>
                    <div class="col-2">
                        <select class="form-control" name="biller" style="height: 40.74px;">
                            <option value="">Choose Biller</option>
                            <?php foreach ($billers as $key => $d) { if($d->id == $biller) { $selected = 'selected'; }else{ $selected = ''; } ?>
                            <option value="<?php echo $d->id; ?>" <?php echo $selected; ?>><?php echo $d->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-2"><button class="btn btn-primary">Go</button> <a href="<?php echo site_url('prices/denom'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
            </form>

            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Action</th>
                    <th>No</th>
                    <th>Provider</th>
                    <th>Description</th>
                    <th>Quota</th>
                    <th>Category</th>
                    <th>Dealer</th>
                    <th>Biller</th>
                    <th>Type</th>
                    <th>Denom</th>
                    <th>Selling Price</th>
                    <th>Base Price</th>
                    <th>Dealer Fee</th>
                    <th>Dekape Fee</th>
                    <th>Biller Fee</th>
                    <th>User Fee</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end row -->

<!-- Modal Alert Delete-->
<div class="modal fade bs-example-modal-sm" id="modal-alert" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <a id="confirm" href="javascript:;" class="btn btn-danger">Yes, Delete it</a>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({ 
            // "scrollX": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('prices/denom/datatables?dealer='.$dealer.'&biller='.$biller.'&provider='.$provider.'&type='.$type.'&category='.$category)?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 6 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 7 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 8 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 9 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 10 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 11 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 12 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 13 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 14 ], //last column / new item column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 15 ], //last column / new item column
                "orderable": false, //set not orderable
                }
            ],
            "scrollX": true,
            initComplete: function(settings, json){
                setTimeout(function(){
                    $('.dataTables_scrollBody table thead tr th.sorting').css('visibility', 'hidden');  
                }, 300);
            }
        });
    });

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
