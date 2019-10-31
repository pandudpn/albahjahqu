<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Donasi</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="p-20">
            <a href="<?php echo site_url('donations/add'); ?>"><button class="btn btn-sm btn-primary waves-effect waves-light">
                <i class="zmdi zmdi-collection-plus"></i> Buat Donasi Baru </button>
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
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Tipe Donasi</th>
                    <th>Kategori</th>
                    <th>Tipe Bayar</th>
                    <th>Terkumpul</th>
                    <th>Target</th>
                    <th>Batas Waktu</th>
                    <th>Kabar Terbaru</th>
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
                "url": "<?php echo site_url('donations/datatables')?>",
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
                    "data": {
                        "title": "title",
                        "status": "status"
                    },
                    "render": function(data, type, row, meta) {
                        var html = '';
                        if(data.status == 'headline') {
                            html = '<span class="badge badge-danger">Headline</span>';
                        }

                        return data.title + " " + html
                    }
                },
                {
                    "targets": [2],
                    "data": "desc",
                    "orderable": false
                },
                {
                    "targets": [3],
                    "data": "type",
                    "orderable": false,
                    "render": function (data, type, row, meta) {
                        var html
                        if(data == 'specific') {
                            html = 'Spesifik';
                        }else {
                            html = 'Reguler'
                        }
                        return html
                    }
                },
                {
                    "targets": [4],
                    "data": "category",
                    "orderable": false
                },
                {
                    "targets": [5],
                    "data": "nom_type"
                },
                {
                    "targets": [6],
                    "data": "amount",
                    "render": function(data, type, row, meta) {
                        return 'Rp '+data
                    }
                },
                {
                    "targets": [7],
                    "data": "target",
                    "render": function(data, type, row, meta) {
                        return 'Rp '+data
                    }
                },
                {
                    "targets": [8],
                    "data": "due_date"
                },
                {
                    "targets": [9],
                    "orderable": false,
                    "data": {
                        "tracking": "tracking",
                        "track_add": "track_add",
                        "track_edit": "track_edit",
                        "track_list": "track_list",
                        "title": "title"
                    },
                    "render": function(data, type, row, meta) {
                        var html = '<a href="'+data.track_add+'" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Buat Kabar Terbaru</a>';

                        if(data.tracking != 0) {
                            html += ' &nbsp; <a href="'+data.track_list+'" class="btn btn-warning btn-sm view" data-title="'+data.title+'"><i class="fa fa-eye"></i> Lihat Kabar</a>';
                        }
                        return html;
                    }
                },
                { 
                    "targets": [10], //last column / numbering column
                    "orderable": false, //set not orderable
                    "data": {
                        "deleted": "deleted",
                        "edit": "edit"
                    },
                    "render": function(data, type, row, meta){
                        return '<a href="'+data.edit+'" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a> &nbsp; <a href="javascript:void(0)" onclick="alert_delete(\''+data.deleted+'\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                    }
                }
            ]
        });
    });

    $(document).on('click', '.view', function(e) {
        e.preventDefault();
        var title = $(this).data('title');

        var month = [
            'Januari', 'Febuari', 'Maret',
            'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ];

        $.ajax({
            url: $(this).attr('href'),
            type: 'get',
            dataType: 'json',
            success: function(result) {
                $('.modal-title').html(title);

                var html = '<table class="table table-bordered">'
                    html += '<thead>'
                    html += '<tr>'
                    html += '<th><center>Kabar Terbaru</center></th>'
                    html += '<th><center>Dibuat Tanggal</center></th>'
                    html += '<th></th>'
                    html += '</tr>'
                    html += '</thead>'
                    html += '<tbody>'

                    result.data.map((results) => {
                        let date = new Date(results.created_on)

                        html += '<tr>'
                        html += '<td>'+results.message+'</td>'
                        html += '<td><center>'+date.getDate()+' '+month[date.getMonth()]+' '+date.getFullYear()+'</center></td>'
                        html += '<td><a href="donations/track_edit/'+results.id+'" class="btn btn-success"><i class="fa fa-pencil"></i> Ubah Data</a></td>'
                        html += '</tr>'
                    })

                    html += '</tbody>'
                    html += '</table>'

                $('.modal-body').html(html);
                $('.modal-footer').html('<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>');
                
                $('.modal-dialog').addClass('modal-lg');
                $('#modal-alert').modal('show');
            }
        })
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
