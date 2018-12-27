<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Complaints Help</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive" style="overflow-x: auto; zoom: 0.8;">
            <?php if($alert){ ?>
            <div class="alert alert-<?php echo $alert['type']; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $alert['msg']; ?>
            </div>
            <?php } ?> 
            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Ticket</th>
                    <th>Customer</th>
                    <th>Dealer</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th>Last Updated</th>
                    <th></th>
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
                <p>Are you sure want to do this?</p>
            </div>
            <div class="modal-footer">
                <a id="confirm" href="javascript:;" class="btn btn-danger">Yes</a>
                <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alert Delete-->
<div class="modal fade bs-example-modal-lg" id="modal-information" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Ticket: <span class="data-ticket"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4"><b>Subject</b></div>
                    <div class="col-8 data-loading data-subject"></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4"><b>Customer</b></div>
                    <div class="col-8 data-loading data-customer"></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4"><b>Customer Contact</b></div>
                    <div class="col-8 data-loading data-customer-contact"></div>
                </div>
                <hr>
                <!-- <div class="row">
                    <div class="col-4"><b>Reference</b></div>
                    <div class="col-8 data-loading data-reference"></div>
                </div>
                <hr> -->
                <div class="row">
                    <div class="col-4"><b>Destination</b></div>
                    <div class="col-8 data-loading data-destination"></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4"><b>Message</b></div>
                    <div class="col-8 data-loading data-description"></div>
                </div>
            </div>
            <div class="modal-footer">
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
                "url": "<?php echo site_url('complaints/help/datatables')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
                },
                { 
                "targets": [ 8 ], //first column / numbering column
                "orderable": false, //set not orderable
                }
            ]
        });
    });

    function data(id)
    {
        $(".data-loading").html('loading...');
        $("#modal-information").modal('show')

        $.ajax("<?php echo site_url('complaints/help/data'); ?>/"+id)
        .done(function(r){
            $(".data-loading").html('');
            $(".data-ticket").html(r.ticket);
            $(".data-subject").html(r.subject);
            $(".data-customer").html(r.cus_name+' ('+r.dealer_name+')');
            $(".data-customer-contact").html(r.customer.phone+' / <a href="mailto:'+r.customer.email+'">'+r.customer.email+'</a>');
            // $(".data-reference").html(r.reference_code);
            $(".data-destination").html(r.destination_no+' / '+r.destination_holder);
            $(".data-description").html(r.description);
            console.log(r);
        });
    }

    function alert(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
