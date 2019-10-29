<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Notifikasi Pengingat</h4>
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
            <th>Waktu</th>
            <th>Judul Notifikasi</th>
            <th>Pesan Notifikasi</th>
            <th>Notifikasi</th>
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
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      "scrollX": true,
      "destroy": true,

      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": "<?php echo site_url('reminder/datatables')?>",
        "type": "POST"
      },

      //Set column definition initialisation properties.
      "columnDefs": [
        { 
          "targets": [0], //first column / numbering column
          "orderable": false, //set not orderable
          "data": "no"
        },
        {
          "targets": [1],
          "data": "name"
        },
        {
          "targets": [2],
          "data": "title"
        },
        {
          "targets": [3],
          "data": "quote",
          "orderable": false
        },
        {
          "targets": [4],
          "data": "alarm",
          "orderable": false,
          "render": function(data, type, row, meta){
            var html;
            if(data == 'on'){
              html = '<span class="badge badge-success">Aktif</span>';
            }else{
              html = '<span class="badge badge-danger">Tidak Aktif</span>';
            }
            return html;
          }
        },
        { 
          "targets": [5], //first column / numbering column
          "orderable": false, //set not orderable
          "data": {
            "edit": "edit",
            "delete": "delete"
          },
          "render": function(data, type, row, meta){
            return '<a href="'+data.edit+'" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a> &nbsp;';
          }
        },
      ]
    });
  });

  function alert_delete(url)
  {
    $("#confirm").attr('href', url)
    $("#modal-alert").modal('show')
  }
</script>
