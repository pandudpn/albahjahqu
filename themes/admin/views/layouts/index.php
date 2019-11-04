<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="<?php echo $this->template->get_theme_path();?>assets/images/favicon.png">

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">

        <!-- Select2 -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->template->get_theme_path();?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap CSS -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css">

        <!-- App CSS -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <!-- dropzone -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />

        <!-- mdi icons -->
        <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.5.95/css/materialdesignicons.min.css">

        <!-- Modernizr js -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/modernizr.min.js"></script>

        <!-- PUSH NOTIF -->

        <script src="https://okbabe-production-cluster.firebaseapp.com/__/firebase/5.7.2/firebase-app.js"></script>
        <script src="https://okbabe-production-cluster.firebaseapp.com/__/firebase/5.7.2/firebase-messaging.js"></script>
        <script src="https://okbabe-production-cluster.firebaseapp.com/__/firebase/init.js"></script>

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

        <!-- dropzone -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/dropzone/dist/dropzone.js"></script>


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
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>

        <!-- Select2 --> 

        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js" type="text/javascript"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/multiselect/js/jquery.multi-select.js" type="text/javascript"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

        <!-- bootstrap -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

        <!-- Validation js (Parsleyjs) -->
        <script type="text/javascript" src="<?php echo $this->template->get_theme_path();?>assets/plugins/parsleyjs/parsley.min.js"></script>

        <!--CKEDITOR-->
        <script src="<?php echo $this->template->get_theme_path();?>assets/tinymce/js/tinymce/tinymce.min.js"></script>
        <style type="text/css">
            .navbar-custom {
                background-color: #00b169;
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
                        <!-- <img src="<?php echo $this->template->get_theme_path();?>assets/images/logo_okbabe_purple.png" style="width: 70%;"> -->
                        <?php echo strtoupper(substr($this->session->userdata('user')->app_id, (strrpos($this->session->userdata('user')->app_id, '.') + 1), strlen($this->session->userdata('user')->app_id))); ?>
                    </a>
                </div>

                <nav class="navbar-custom">

                    <ul class="list-inline float-right mb-0">

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect notif-icon" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <i class="zmdi zmdi-notifications-none noti-icon"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg" aria-labelledby="Preview">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5><small><span class="badge badge-danger mr-2" id="countNotif"></span>Pemberitahuan</small></h5>
                                </div>
                                <div id="resultNotif"></div>

                                <!-- item-->
                                <!-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-success"><i class="icon-bubble"></i></div>
                                    <p class="notify-details">Robert S. Taylor commented on Admin<small class="text-muted">1min ago</small></p>
                                </a> -->

                                <!-- All-->
                                <!-- <a href="javascript:void(0);" class="dropdown-item notify-item notify-all">
                                    View All
                                </a> -->
                            </div>
                        </li>

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link waves-effect" href="javascript:void(0);">
                                <?php echo $this->session->userdata('user')->name; ?>
                            </a>
                        </li>

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo $this->template->get_theme_path();?>assets/images/users/avatar.png" alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5><small><?php echo $this->session->userdata('user')->name; ?></small> </h5>
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
                            <?php if(strpos($this->session->userdata('user')->email, 'tahfidz') === false){ ?>
                                <li class="text-muted menu-title">Umum</li>
                                <li>
                                    <a href="<?php echo site_url(); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-view-dashboard"></i><span> Halaman Utama </span> 
                                    </a>
                                </li>
                                <?php if($this->session->userdata('user')->app_id == 'com.dekape.okbabe'){ ?>
                                    <li class="text-muted menu-title">Menu</li>
                                    <li>
                                        <a href="<?php echo site_url('/user/admin'); ?>" class="waves-effect">
                                            <i class="zmdi zmdi-account-circle"></i><span> Admin </span> 
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <!-- <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect">
                                    <i class="zmdi zmdi-notifications-active"></i><span> Pengumuman</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo site_url('announcements/pushnotif'); ?>">Kirim Notifikasi</a></li>
                                </ul>
                            </li> -->
                            <li class="text-muted menu-title">Laporan</li>
                            <?php if(strpos($this->session->userdata('user')->email, 'tahfidz') === false){ ?>
                                <li>
                                    <a href="<?php echo site_url('reporting/zakat'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-file-text"></i><span> Zakat </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('reporting/infaq'); ?>" class="waves-effect">
                                        <i class="mdi mdi-account-cash"></i><span> Infaq </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('reporting/sadaqah'); ?>" class="waves-effect">
                                        <i class="mdi mdi-cash"></i><span> Sadaqah </span> 
                                    </a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="<?php echo site_url('reporting/iuran'); ?>" class="waves-effect">
                                    <i class="mdi mdi-credit-card"></i><span> Iuran SPP </span> 
                                </a>
                            </li>
                            <?php if(strpos($this->session->userdata('user')->email, 'tahfidz') === false){ ?>
                                <li>
                                    <a href="<?php echo site_url('reporting/balance'); ?>" class="waves-effect">
                                        <i class="mdi mdi-wallet-outline"></i><span> Saldo </span> 
                                    </a>
                                </li>
                                <li class="text-muted menu-title">Konten</li>
                                <!-- <li>
                                    <a href="<?php echo site_url('/topics'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-lamp"></i><span> Topik </span> 
                                    </a>
                                </li> -->
                                <li>
                                    <a href="<?php echo site_url('/albums'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-image-o"></i><span> Album </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('/articles'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-border-color"></i><span> Artikel </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('/news'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-comment-alert"></i><span> Berita </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('/videos'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-youtube-play"></i><span> Video </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('/donations'); ?>" class="waves-effect">
                                        <i class="mdi mdi-charity"></i><span> Donasi </span> 
                                    </a>
                                </li>
                                <!-- <li class="text-muted menu-title">Management</li>
                                <li>
                                    <a href="<?php echo site_url('zakat'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-assignment-o"></i><span> Zakat </span> 
                                    </a>
                                </li> -->
                                <li class="text-muted menu-title">Islami</li>
                                <li>
                                    <a href="<?php echo site_url('/islami/doa'); ?>" class="waves-effect">
                                        <i class="mdi mdi-book-open-page-variant"></i><span> Doa </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('/islami/hadits_daily'); ?>" class="waves-effect">
                                        <i class="mdi mdi-calendar-today"></i><span> Hadits Harian </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('/islami/dzikir'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-label-heart"></i><span> Dzikir Harian </span> 
                                    </a>
                                </li>
                                <li class="has_sub">
                                    <a href="javascript:void(0);" class="waves-effect">
                                        <i class="mdi mdi-islam"></i><span> Panduan Ibadah </span> 
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo site_url('islami/category_ibadah'); ?>">Kategori Ibadah</a></li>
                                        <li><a href="<?php echo site_url('islami/ibadah'); ?>">List Panduan Ibadah</a></li>
                                    </ul>
                                </li>
                                <li class="text-muted menu-title">Manajemen</li>
                                <li>
                                    <a href="<?php echo site_url('/events'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-calendar-note"></i><span> Event </span> 
                                    </a>
                                </li>
                                <?php if($this->session->userdata('user')->dealer_id != 41){ ?>
                                    <li>
                                        <a href="<?php echo site_url('/streaming'); ?>" class="waves-effect">
                                            <i class="mdi mdi-television"></i><span> Link Streaming </span> 
                                        </a>
                                    </li>
                                <?php } ?>
                                <li>
                                    <a href="<?php echo site_url('/reminder'); ?>" class="waves-effect">
                                        <i class="mdi mdi-alarm"></i><span> Notifikasi Pengingat </span> 
                                    </a>
                                </li>
                                <li class="text-muted menu-title">Transaksi</li>
                                <li>
                                    <a href="<?php echo site_url('/bills'); ?>" class="waves-effect">
                                        <i class="mdi mdi-credit-card-outline"></i><span> Iuran </span> 
                                    </a>
                                </li>
                                <li class="text-muted menu-title">Manajemen Pendidikan</li>
                                <!-- <li>
                                    <a href="<?php echo site_url('/community/units'); ?>" class="waves-effect">
                                        <i class="mdi mdi-domain"></i><span> Sekolah </span> 
                                    </a>
                                </li> -->
                                <!-- <li>
                                    <a href="<?php echo site_url('/community/staff'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-accounts-list"></i><span> Guru </span> 
                                    </a>
                                </li> -->
                                <li>
                                    <a href="<?php echo site_url('/community/students'); ?>" class="waves-effect">
                                        <i class="mdi mdi-account-group"></i><span> Siswa </span> 
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="<?php echo site_url('/community/graduate'); ?>" class="waves-effect">
                                        <i class="mdi mdi-school"></i><span> Alumni </span> 
                                    </a>
                                </li> -->
                                <!-- <li class="has_sub">
                                    <a href="javascript:void(0);" class="waves-effect">
                                        <i class="mdi mdi-trophy"></i><span> Penghargaan </span> 
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo site_url('community/achievement'); ?>">Sekolah</a></li>
                                        <li><a href="<?php echo site_url('community/achievement_student'); ?>">Siswa</a></li>
                                        <li><a href="<?php echo site_url('community/achievement_staff'); ?>">Guru</a></li>
                                    </ul>
                                </li> -->
                            <?php } ?>
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
                <?php echo date('Y') ?>  © okbabe admin.
            </footer>

            <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.core.js"></script>
            <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.app.js"></script>

            <!-- request notification -->
            <script>
                notif();

                $(document).ready(function(){
                    setInterval(() => {
                        notif();
                    }, 30000);
                });

                $(document).on('click', '.notify-item', function(){
                    var id  = $(this).data('id');

                    $.ajax({
                        url: '<?= site_url("notifications/update"); ?>',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        cache: false,
                        dataType: 'json',
                        success: function(result){
                            console.log('berhasil');
                        }
                    })
                });

                $(document).on('click', '#updateAll', function(e) {
                    e.preventDefault();

                    updateAll();
                });

                function notif(){
                    var URL = '<?= site_url("notifications/data"); ?>';

                    $.ajax({
                        url: URL,
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        success: function(result){
                            console.log(result);
                            if(result.status == 'success'){
                                var html = "";
                                var count;
                                if(result.data.length > 0){
                                    count   = result.data.length;
                                    $('.notif-icon').append('<span class="noti-icon-badge"></span>');

                                    for(var i = 0; i <= 5; i++) {
                                        html += '<a href="' + result.data[i].url + '" class="dropdown-item notify-item" data-id="' + result.data[i].id + '">';
                                        html += '<div class="notify-icon bg-success"><i class="icon-bubble"></i></div>';
                                        html += '<p class="notify-details" title="' + result.data[i].text + '">' + result.data[i].msg + '<small class="text-muted">' + result.data[i].created + '</small></p>';
                                        html += '</a>';
                                    }

                                    if(result.data.length > 1) {
                                        html += '<div class="dropdown-divider"></div>';
                                        html += '<a href="#" class="dropdown-item notify-item text-center" id="updateAll">';
                                        html += '<p class="notify-details">Tandai sudah dibaca</p>';
                                        html += '</a>';
                                    }
                                }else{
                                    count = 0;
                                    html = '<div class="text-center text-secondary">Tidak ada pemberitahuan.</div>';

                                    $('.noti-icon-badge').remove();
                                }

                                $('#countNotif').html(count);
                                $('#resultNotif').html(html);
                            }
                        }
                    })
                }

                function updateAll() {
                    $.ajax({
                        url: '<?php echo site_url("notifications/updateAll"); ?>',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        success: function(result) {
                            notif();
                        }
                    })
                }
            </script>
        </div>
    </body>
</html>