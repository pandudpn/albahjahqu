<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Posts</h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
        	<?php if($alert){ ?>
	    	<div class="alert alert-<?php echo $alert['type']; ?>">
	    		<?php echo $alert['msg']; ?>
	    	</div>
	    	<?php } ?>

            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Categories</th>
                    <th><i class="zmdi zmdi-comments"></i></th>
                    <th><i class="zmdi zmdi-eye"></i></th>
                    <th>Date</th>
                    <th>
                    	<a href="<?php echo site_url('home/post_new'); ?>" class="btn waves-effect btn-info"> Add New <i class="fa fa-plus"></i> </a>
					</th>
                </tr>
                </thead>


                <tbody>
                	<tr>
                        <td>Lorem Ipsum dolor sit Amet</td>
                        <td>Author</td>
                        <td><a href="">Lorem</a></td>
                        <td>3</td>
                        <td>321</td>
                		<td><i>Last Modified</i><br>8 May, 2018</td>
                		<td>
	                    	<a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
	                    	<a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
	                    </td>
	                </tr>
                    <tr>
                        <td>consectetur adipiscing elit</td>
                        <td>Author</td>
                        <td><a href="">Ipsum</a>, <a href="">Lorem</a></td>
                        <td>1</td>
                        <td>89</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Nam eget quam varius</td>
                        <td>Author</td>
                        <td><a href="">Dolor</a></td>
                        <td>0</td>
                        <td>143</td>
                        <td><i>Published</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Lorem Ipsum 2</td>
                        <td>Author</td>
                        <td><a href="">Lorem Ipsum</a></td>
                        <td>9</td>
                        <td>92</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Interdum et malesuada </td>
                        <td>Author</td>
                        <td><a href="">Dolor sit amet</a></td>
                        <td>10</td>
                        <td>112</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Lorem Ipsum dolor sit Amet</td>
                        <td>Author</td>
                        <td><a href="">Lorem</a></td>
                        <td>3</td>
                        <td>890</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>consectetur adipiscing elit</td>
                        <td>Author</td>
                        <td><a href="">Category</a></td>
                        <td>1</td>
                        <td>55</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Nam eget quam varius</td>
                        <td>Author</td>
                        <td><a href="">Category</a></td>
                        <td>0</td>
                        <td>21</td>
                        <td><i>Published</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Lorem Ipsum 2</td>
                        <td>Author</td>
                        <td><a href="">Category</a></td>
                        <td>9</td>
                        <td>285</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Interdum et malesuada </td>
                        <td>Author</td>
                        <td><a href="">Category</a></td>
                        <td>10</td>
                        <td>42</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Lorem Ipsum 2</td>
                        <td>Author</td>
                        <td><a href="">Category</a></td>
                        <td>9</td>
                        <td>71</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Interdum et malesuada </td>
                        <td>Author</td>
                        <td><a href="">Category</a></td>
                        <td>10</td>
                        <td>321</td>
                        <td><i>Last Modified</i><br>8 May, 2018</td>
                        <td>
                            <a href="" class="btn waves-effect btn-success"> <i class="fa fa-pencil"></i> </a>
                            <a href="javascript:;" class="btn waves-effect btn-danger" onclick=""> <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
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
        	ordering: false
        });
    } );

    function alert_delete(url)
    {
        $("#confirm").attr('href', url)
        $("#modal-alert").modal('show')
    }
</script>
