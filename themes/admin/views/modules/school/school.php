<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Data Sekolah / Pesantren</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="p-20">
            <a href="<?php echo site_url('school/add'); ?>"><button class="btn btn-sm btn-primary waves-effect waves-light">
                <i class="zmdi zmdi-collection-plus"></i> Tambah Sekolah / Pesantren </button>
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

            <form method="get" id="formSearch">
                <div class="row" style="margin-bottom: 15px; margin-left: 5px;">
                    <div class="col-12">Filter : </div>
                    <div class="col-2">
                        <select class="form-control" name="tipe" id="tipe" style="height: 40.74px;">
                            <option value="">Semua</option>
                            <option value="sekolah">Sekolah</option>
                            <option value="pesantren">Pesantren</option>
                        </select>
                    </div>
                    <div class="col-2"><button class="btn btn-primary">Cari</button> <a href="<?= site_url('school'); ?>" class="btn btn-secondary">Reset</a></div>
                </div>
            </form>

            <table id="datatable" class="table table-striped table-bordered" style="min-width: 2000px">
                <thead>
                <tr>
                    <th width="4%">No</th>
                    <th>Tipe</th>
                    <th>Nama</th>
                    <th>Tanggal Berdiri</th>
                    <th>No SK Pendirian</th>
                    <th>No Induk Sekolah</th>
                    <th>Alamat</th>
                    <th>Maps Lokasi</th>
                    <th width="6%"></th>
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
        DataTable('');

        $('#formSearch').submit(function(e){
            e.preventDefault();

            var data    = $('#tipe').val();

            DataTable(data);
        });
    });

    function DataTable(filter){
        $('#datatable').DataTable({ 
            // "scrollX": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "destroy": true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('school/datatables')?>?tipe="+filter,
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [0],
                    "orderable": false,
                    "data": "no"
                },
                {
                    "targets": [1],
                    "data": "type"
                },
                {
                    "targets": [2],
                    "data": "name"
                },
                {
                    "targets": [3],
                    "data": "date"
                },
                {
                    "targets": [4],
                    "data": "sk"
                },
                {
                    "targets": [5],
                    "data": "induk"
                },
                {
                    "targets": [6],
                    "data": "address"
                },
                {
                    "targets": [7],
                    "data": {
                        "maps": "maps",
                        "type": "type"
                    },
                    "render": function(data, type, row, meta){
                        var html;
                        if(data.maps == null){
                            html = '-';
                        }else{
                            html = "<a href='https://www.google.com/maps/@"+data.maps+",17z' target='_blank'><i class='mdi mdi-map-marker'></i> Lokasi " + data.type + "</a>";
                        }
                        return html;
                    }
                },
                { 
                    "targets": [8], //last column / numbering column
                    "orderable": false, //set not orderable
                    "data": {
                        "delete": "delete",
                        "edit":"edit"
                    },
                    "render": function(data, type, row, meta){
                        return '<a href="'+data.edit+'" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a> &nbsp; <a href="javascript:void(0)" onclick="alert_delete(\''+data.delete+'\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                    }
                }
            ],
            "scrollX": true,
            initComplete: function(settings, json){
                setTimeout(function(){
                    $('.dataTables_scrollBody table thead tr th.sorting').css('visibility', 'hidden');  
                }, 300);
            }
        });
    }

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
