<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Push Notif </h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-7">
        <div class="card-box table-responsive">
            <div class="row">
                <div class="col-12">
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

    <div class="col-12">
        <div class="card-box table-responsive">
            <div class="row">
                <div class="col-12">
                    <table id="datatable" class="table table-striped table-bordered table-responsive" style="width: 100%;">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
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
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('announcements/pushnotif/datatables')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable,
                    "data": "no"
                },
                {
                    "targets": [1],
                    "data": "title"
                },
                {
                    "targets": [2],
                    "data": "msg",
                    "orderable": false
                },
                {
                    "targets": [3],
                    "data": "created",
                    "orderable": false
                },
                {
                    "targets": [4],
                    "orderable": false,
                    "data": "deleted",
                    "render": function(data, type, row, meta){
                        return '<a href="javascript:void(0)" onclick="alert_delete(\''+data+'\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                    }
                }
            ]
        });
    });

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>