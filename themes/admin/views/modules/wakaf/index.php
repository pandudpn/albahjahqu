<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left"><?= $title; ?></h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="p-20">
            <a href="<?php echo site_url('wakaf/add'); ?>"><button class="btn btn-sm btn-primary waves-effect waves-light">
                <i class="zmdi zmdi-collection-plus"></i> Buat Wakaf Baru </button>
            </a>
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
            <table id="datatable" class="table table-striped table-bordered table-responsive" style="min-width: 1500px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Atas Nama</th>
                        <th>Judul</th>
                        <th>Jenis Wakaf</th>
                        <th>Lokasi Wakaf</th>
                        <th>Cabang</th>
                        <th>Tanggal Wakaf</th>
                        <th>Status</th>
                        <th>Petugas</th>
                        <th></th>
                        <th width="8%"></th>
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
            "scrollX": true,

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('wakaf/datatables')?>",
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                    "data": "no"
                },
                {
                    "targets": [1],
                    "orderable": false,
                    "data": "give"
                },
                {
                    "targets": [2],
                    "orderable": false,
                    "data": "title"
                },
                {
                    "targets": [3],
                    "orderable": false,
                    "data": "type"
                },
                {
                    "targets": [4],
                    "orderable": false,
                    "data": "loc_wakaf"
                },
                {
                    "targets": [5],
                    "orderable": false,
                    "data": "branch"
                },
                {
                    "targets": [6],
                    "orderable": false,
                    "data": "date"
                },
                {
                    "targets": [7],
                    "data": "status",
                    "render": function(data, type, row, meta) {
                        var html
                        if(data === "request") {
                            html = "<span class='badge badge-dark'>Pengajuan</span>"
                        } else if(data === "process") {
                            html = "<span class='badge badge-warning'>Sedang Proses</span>"
                        } else if(data === "verified") {
                            html = "<span class='badge badge-primary'>Menunggu Verifikasi</span>"
                        } else if(data === "approve") {
                            html = "<span class='badge badge-success'>Diterima</span>"
                        } else if(data === "reject") {
                            html = "<span class='badge badge-danger'>Ditolak</span>"
                        }
                        return html
                    }
                },
                {
                    "targets": [8],
                    "orderable": false,
                    "data": "officer"
                },
                {
                    "targets": [9],
                    "orderable": false,
                    "data": "assign",
                    "render": function(data, type, row, meta) {
                        return "<a href='"+data+"' class='btn btn-primary'><i class='fa fa-fa-pencil-square-o'></i> Pilih Petugas</a>"
                    }
                },
                {
                    "targets": [10],
                    "orderable": false,
                    "data": {
                        "edit": "edit",
                        "delete": "delete"
                    },
                    "render": function(data, type, row, meta) {
                        return '<a href="'+data.edit+'" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a> &nbsp; <a href="javascript:void(0)" onclick="alert_delete(\''+data.delete+'\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>'
                    }
                }
            ]
        });
    });

    function alert_delete(url)
    {
        $('.modal-dialog').removeClass('modal-lg');
        $('.modal-title').html('Alert');
        $('.modal-body').html('Apakah kamu yakin ingin menghapus data ini?');
        $('.modal-footer').html('<a id="confirm" href="javascript:;" class="btn btn-danger">Yes, Delete it</a> <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>')
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
