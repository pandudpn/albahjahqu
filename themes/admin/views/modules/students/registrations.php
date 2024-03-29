<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Pendaftaraan Santri</h4>

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
            <table id="datatable" class="table table-striped table-bordered table-responsive" style="min-width: 1500px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Ponpes</th>
                        <th>Umur</th>
                        <th>Ayah</th>
                        <th>Ibu</th>
                        <th>Alamat</th>
                        <th>Detil</th>
                        <th>Terima</th>
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
                "url": "<?php echo site_url('students/registrations/datatables')?>",
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
                    "data": "name"
                },
                {
                    "targets": [2],
                    "orderable": false,
                    "data": "partner"
                },
                {
                    "targets": [3],
                    "orderable": false,
                    "data": "age"
                },
                {
                    "targets": [4],
                    "orderable": false,
                    "data": "dad"
                },
                {
                    "targets": [5],
                    "orderable": false,
                    "data": "mom"
                },
                {
                    "targets": [6],
                    "orderable": false,
                    "data": "address"
                },
                {
                    "targets": [7],
                    "orderable": false,
                    "data": "details",
                    "render": function(data, type, row, meta) {
                        return '<a href="'+data+'" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></a>'
                    }
                },
                {
                    "targets": [8],
                    "orderable": false,
                    "data": {
                        "approve": "approve",
                        "branch": "branch"
                    },
                    "render": function(data, type, row, meta) {
                        return '<a href="'+data.approve+'" class="btn btn-success btn-sm" id="approve" data-branch="'+data.branch+'"><i class="fa fa-check"></i></a>'
                    }
                },
                {
                    "targets": [9],
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

    $(document).on('click', '#approve', function(e) {
        e.preventDefault();
        var url     = $(this).attr('href');
        var branch  = $(this).data('branch');

        var html = "<div class='form-group row'>";
            html += "<label>Nomer Induk Siswa</label>";
            html += "<input type='hidden' id='branch' value='"+branch+"' />";
            html += "<input class='form-control' name='nis' id='nis' type='text' placeholder='Masukan nomer induk siswa baru' />";
            html += "<div id='res'></div>"
            html += "</div>";

        $('.modal-dialog').removeClass('modal-lg');
        $('.modal-title').html('Penerimaan Siswa Baru');
        $('.modal-body').html(html);
        $('.modal-footer').html('<button type="button" class="btn btn-primary" data-url="'+url+'" id="simpan" disabled="disabled">Simpan</button> <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>');
        $('#modal-alert').modal('show');
    });

    $(document).on('click', '#simpan', function(e){
        e.preventDefault();

        var nis = $('#nis').val();
        var url = $(this).data('url');

        $.ajax({
            url: url,
            type: 'post',
            data: {
                'nis': nis
            },
            dataType: 'json',
            success: function(results) {
                if(results.status) {
                    window.location.href    = results.data
                } else {
                    var html    = '<small class="text text-danger">' + results.data + '</small>'
                }
            }
        });
    });

    $(document).on('keyup', '#nis', function(e) {
        e.preventDefault();

        var nis     = $(this).val();
        var branch  = $('#branch').val();

        console.log(branch);

        $.ajax({
            url: "<?= site_url('students/registrations/check_student_number'); ?>",
            type: 'post',
            data: {
                'nis': nis,
                'branch': branch
            },
            dataType: 'json',
            success: function(result) {
                var html = '';
                if(result.status) {
                    html    = '<small class="text-danger">'+result.data+'</small>';

                    $('#simpan').attr('disabled', 'disabled');
                } else {
                    $('#simpan').removeAttr('disabled');
                }
                $('#res').html(html);
            }
        })
    })

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
