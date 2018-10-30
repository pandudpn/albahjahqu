<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">User Admins</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-lg-4">
        <div class="card-box">
            <form id="form" method="post" enctype="multipart/form-data">
                <strong><?php echo $title; ?></strong><br><br>
                <div class="form-group row">
                    <label class="col-12 col-form-label">Name</label>
                    <div class="col-12">
                        <input class="form-control" type="text" value="<?php echo $d->name; ?>" name="name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-form-label">Username</label>
                    <div class="col-12">
                        <input class="form-control" type="text" value="<?php echo $d->username; ?>" name="username" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-form-label">Email</label>
                    <div class="col-12">
                        <input class="form-control" type="email" value="<?php echo $d->email; ?>" name="email" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-form-label">Password</label>
                    <div class="col-12">
                        <input class="form-control" id="password" type="password" value="" name="password" <?php if(empty($d)) { echo 'required'; } ?>>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-form-label">Confirm Password</label>
                    <div class="col-12">
                        <input class="form-control" type="password" value="" name="password_retype" data-parsley-equalto="#password" <?php if(empty($d)) { echo 'required'; } ?>>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <div class="text-right">
                            <a href="<?php echo site_url('user/admin'); ?>" class="btn btn-secondary">Reset</a>
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-box table-responsive">
            <?php if($alert){ ?>
            <div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <?php echo $alert['msg']; ?>
            </div>
            <?php } ?>

            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th width="20%"></th>
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
                <a id="confirm" href="javascript:;" class="btn btn-primary">Yes, Delete it</a>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({ 

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('user/admin/datatables')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 4 ], //first column / numbering column
                "orderable": false, //set not orderable
                }
            ]
        });
        

        $('#form').parsley();

    } );

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>