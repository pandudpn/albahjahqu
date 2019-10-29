<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Dzikir</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="p-20">
            <a href="<?php echo site_url('islami/dzikir/add'); ?>"><button class="btn btn-sm btn-primary waves-effect waves-light">
                <i class="zmdi zmdi-collection-plus"></i> Tambah Dzikir </button>
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
            <table id="datatable" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Teks Dzikir</th>
                    <th>Foto</th>
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
                "url": "<?php echo site_url('islami/dzikir/datatables')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                    "targets": [ 0 ], //first column / numbering column
                    "orderable": false, //set not orderable
                    "data": "no"
                },
                {
                    "targets": [1],
                    "data": "title"
                },
                { 
                    "targets": [2], //third column / numbering column
                    "orderable": false, //set not orderable
                    "data": "text"
                },
                {
                    "targets": [3],
                    "orderable": false,
                    "data": {
                        "image": "image",
                        "type": "type"
                    },
                    "render": function(data, type, row, meta) {
                        var html;
                        if(data.type === "txt") {
                            html = "-";
                        }else {
                            html = '<a href="' + data.image + '" id="detail">Lihat Foto</a>';
                        }
                        return html;
                    }
                },
                {
                    "targets": [4],
                    "orderable": false,
                    "data": {
                        "edit": "edit",
                        "delete": "delete"
                    },
                    "render": function(data, type, row, meta){
                        return '<a href="'+data.edit+'" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a> &nbsp; <a href="javascript:void(0)" onclick="alert_delete(\''+data.delete+'\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                    }
                }
            ]
        });
    });

    $(document).on('click', '#detail', function(e) {
        e.preventDefault();

        console.log(getImages($(this).attr('href')));
        $('.modal-title').html('Foto Dzikir');
        
        $('.modal-footer').html('<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">Close</a>');
        $('#modal-alert').modal('show');
    });

    function getImages(url) {
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(result) {
                var html = '<div class="row">'

                    result.map((results) => {
                        html += '<div class="col-12 text-center">'
                        html += '<div style="width: 100px; height: 200px;" class="d-block mx-auto">'
                        html += '<img src="' + results.photo + '" style="width:100%; height: 100px; object-fit: contain;"/>'
                        html += '</div>'
                        html += '</div>'
                    });
                    
                    html += '</div>';

                $('.modal-body').html(html);
            }
        })
    }

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
