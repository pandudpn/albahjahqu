<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <!-- <link rel="shortcut icon" href="<?php echo $this->template->get_theme_path();?>assets/images/favicon_bni.ico"> -->

        <!-- App title -->
        <title> Admin Panel </title>

        <!-- Switchery css -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/switchery/switchery.min.css" rel="stylesheet" />

        <!-- DataTables -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Color Picker -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">

        <!-- Datepicker -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">

        <!-- Select2 -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap CSS -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- App CSS -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <!-- Modernizr js -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/modernizr.min.js"></script>

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.chained.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/popper.min.js"></script><!-- Tether for Bootstrap -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/detect.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/fastclick.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/waves.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/switchery/switchery.min.js"></script>


        <!-- Required datatable js -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Buttons examples -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/jszip.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/buttons.colVis.min.js"></script>
        
        <!-- Responsive examples -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

        <!-- Color Picker -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/mjolnic-bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

        <!-- Date Picker -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>

        <!-- Select2 --> 

        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js" type="text/javascript"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/multiselect/js/jquery.multi-select.js" type="text/javascript"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

        <!-- Validation js (Parsleyjs) -->
        <script type="text/javascript" src="<?php echo $this->template->get_theme_path();?>assets/plugins/parsleyjs/parsley.min.js"></script>


        <!--CKEDITOR-->
        <script src="<?php echo $this->template->get_theme_path();?>assets/tinymce/js/tinymce/tinymce.min.js"></script>
        <style type="text/css">
            .navbar-custom {
                background-color: #8d31a2;
            }
        </style>
    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="<?php echo site_url(); ?>" class="logo">
                        <!-- <i class="zmdi zmdi-group-work icon-c-logo"></i> -->
                        <!-- <span>AksenBNI</span></a> -->
                        <img src="<?php echo $this->template->get_theme_path();?>assets/images/logo.png" style="width: 70%;">
                    </a>
                </div>

                <nav class="navbar-custom">

                    <ul class="list-inline float-right mb-0">

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo $this->template->get_theme_path();?>assets/images/users/avatar.png" alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5 class="text-overflow"><small>Welcome ! Admin</small> </h5>
                                </div>

                                <!-- item-->
                                <a href="<?php echo site_url('user/setting'); ?>" class="dropdown-item notify-item">
                                    <i class="zmdi zmdi-settings"></i> <span>Settings</span>
                                </a>

                                <!-- item-->
                                <a href="<?php echo site_url('user/logout'); ?>" class="dropdown-item notify-item">
                                    <i class="zmdi zmdi-power"></i> <span>Logout</span>
                                </a>

                            </div>
                        </li>

                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="zmdi zmdi-menu"></i>
                            </button>
                        </li>
                    </ul>

                </nav>

            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <ul>
                            <li class="text-muted menu-title">General</li>

                            <li>
                                <a href="<?php echo site_url(); ?>" class="waves-effect">
                                    <i class="zmdi zmdi-view-dashboard"></i><span> Dashboard </span> 
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo site_url('services'); ?>" class="waves-effect">
                                    <i class="zmdi zmdi-card-sd"></i><span> Services </span> 
                                </a>
                            </li>

                            <li class="text-muted menu-title">Users</li>
                            
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect">
                                    <i class="zmdi zmdi-accounts"></i><span> Customers </span> 
                                    <span class="menu-arrow"></span>
                                </a>

                                <ul class="list-unstyled">
                                    <li><a href="<?php echo site_url('customers'); ?>">Customers</a></li>
                                    <li><a href="<?php echo site_url('customers/kycs'); ?>">KYC</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="<?php echo site_url('dealers'); ?>" class="waves-effect">
                                    <i class="zmdi zmdi-store"></i><span> Dealers </span> 
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo site_url('billers'); ?>" class="waves-effect">
                                    <i class="zmdi zmdi-library"></i><span> Billers </span> 
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo site_url('partners'); ?>" class="waves-effect">
                                    <i class="zmdi zmdi-label"></i><span> Partners </span> 
                                </a>
                            </li>

                            <li class="text-muted menu-title">Transaction</li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect">
                                    <i class="zmdi zmdi-money"></i><span> Prices </span> 
                                    <span class="menu-arrow"></span>
                                </a>

                                <ul class="list-unstyled">
                                    <li><a href="<?php echo site_url('prices/denom'); ?>">Denom</a></li>
                                    <li><a href="<?php echo site_url('prices/bulk'); ?>">Bulk</a></li>
                                    <li><a href="<?php echo site_url('prices/biller'); ?>">Biller</a></li>
                                </ul>
                            </li>
                            
                            <li>
                                <a href="<?php echo site_url('transactions'); ?>" class="waves-effect">
                                    <i class="zmdi zmdi-shopping-cart"></i><span> Transactions </span> 
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo site_url('dealers/boxes'); ?>" class="waves-effect">
                                    <i class="zmdi zmdi-store"></i><span> Dealer Boxes </span> 
                                </a>
                            </li>

                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>

                </div>

            </div>
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <?php echo $template['body']; ?>

                    </div> <!-- container -->

                </div> <!-- content -->



            </div>
            <!-- End content-page -->


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->

            <footer class="footer text-right">
                <?php echo date('Y') ?> © okbabe admin.
            </footer>


        </div>
        <!-- END wrapper -->

        <!-- App js -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.core.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.app.js"></script>

    </body>
</html>