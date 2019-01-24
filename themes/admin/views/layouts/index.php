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
                        <img src="<?php echo $this->template->get_theme_path();?>assets/images/logo_okbabe_purple.png" style="width: 70%;">
                    </a>
                </div>

                <nav class="navbar-custom">

                    <ul class="list-inline float-right mb-0">


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
                            <li class="text-muted menu-title">General</li>

                            <li>
                                <a href="<?php echo site_url(); ?>" class="waves-effect">
                                    <i class="zmdi zmdi-view-dashboard"></i><span> Dashboard </span> 
                                </a>
                            </li>
                            <?php if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dekape') { ?>
                                <li class="has_sub">
                                    <a href="javascript:void(0);" class="waves-effect">
                                        <i class="zmdi zmdi-email-open"></i><span> Complaints <?php echo $unread; ?></span> 
                                        <span class="menu-arrow"></span>
                                    </a>

                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo site_url('complaints/report'); ?>">Report <?php echo $unread; ?></a></li>
                                        <li><a href="<?php echo site_url('complaints/help'); ?>">Help</a></li>
                                    </ul>
                                </li>   
                                <li class="text-muted menu-title">Transaction</li>

                                <li>
                                    <a href="<?php echo site_url('transactions'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-shopping-cart"></i><span> Transactions </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('transactions/pending'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-shopping-basket"></i><span> Pending Transactions </span> 
                                    </a>
                                </li>
                                <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                <li>
                                    <a href="<?php echo site_url('topups'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-upload"></i><span> Topups </span> 
                                    </a>
                                </li>
                                <?php } ?> 
                                <li>
                                    <a href="<?php echo site_url('transactions/logs'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-calendar-note"></i><span> Transaction Logs </span> 
                                    </a>
                                </li>

                                <?php if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dekape') { ?>

                                <li class="text-muted menu-title">Prices</li>    

                                <li>
                                    <a href="<?php echo site_url('services'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-card-sd"></i><span> Services / Products</span> 
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('denoms'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-dns"></i><span> Denoms</span> 
                                    </a>
                                </li>

                                <li class="has_sub">
                                    <a href="javascript:void(0);" class="waves-effect">
                                        <i class="zmdi zmdi-money"></i><span> Prices </span> 
                                        <span class="menu-arrow"></span>
                                    </a>

                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo site_url('prices/denom'); ?>">Denom</a></li>
                                        <li><a href="<?php echo site_url('prices/bulk'); ?>">Bulk</a></li>
                                        <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                        <li><a href="<?php echo site_url('prices/biller'); ?>">Biller</a></li>
                                        <?php } ?>
                                        <li><a href="<?php echo site_url('prices/logs'); ?>">Logs</a></li>
                                    </ul>
                                </li>

                                <?php } ?>

                                <li class="text-muted menu-title">Users</li>
                            
                                <li class="has_sub">
                                    <a href="javascript:void(0);" class="waves-effect">
                                        <i class="zmdi zmdi-accounts"></i><span> Customers </span> 
                                        <span class="menu-arrow"></span>
                                    </a>

                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo site_url('customers'); ?>">Profile</a></li>
                                        <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                        <li><a href="<?php echo site_url('customers/kycs'); ?>">KYC</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('referrals'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-tag-more"></i><span> Referral Codes </span> 
                                    </a>
                                </li>
                            <?php if($this->session->userdata('user')->role == 'dekape') { ?>
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
                            <?php } ?>   
                            <?php } ?>

                            <?php if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dekape') { ?>

                                <li class="text-muted menu-title">Modem Pool</li>

                                <li>
                                    <a href="<?php echo site_url('dealers/boxes'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-store"></i><span> Dealer Boxes </span> 
                                    </a>
                                </li>
                            <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                <li>
                                    <a href="<?php echo site_url('dealers/usages'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-collection-text"></i><span> Box Usages </span> 
                                    </a>
                                </li>
                            <?php } ?>
                                <li>
                                    <a href="<?php echo site_url('dealers/boxes/alert'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-alert-octagon"></i><span> Alert Settings </span> 
                                    </a>
                                </li>
                                
                                <li class="text-muted menu-title">Clusters</li>

                                <li>
                                    <a href="<?php echo site_url('dealers/clusters'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-layers"></i><span> Clusters </span> 
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('dealers/clustermaps'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-map"></i><span> Cluster Maps </span> 
                                    </a>
                                </li>
                                <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                    <li class="text-muted menu-title">Management</li>

                                    <li>
                                        <a href="<?php echo site_url('user/admin'); ?>" class="waves-effect">
                                            <i class="zmdi zmdi-account"></i><span> Admin Users </span> 
                                        </a>
                                    </li>
                                <?php } ?>

                                <li>
                                    <a href="<?php echo site_url('articles'); ?>" class="waves-effect">
                                        <i class="zmdi zmdi-assignment-o"></i><span> Articles </span> 
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if($this->session->userdata('user')->role == 'dekape') { ?>
                                <li class="text-muted menu-title">Logs</li>
                            
                                <li class="has_sub">
                                    <a href="javascript:void(0);" class="waves-effect">
                                        <i class="zmdi zmdi-view-list-alt"></i><span> Logs </span> 
                                        <span class="menu-arrow"></span>
                                    </a>

                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo site_url('log'); ?>">Communication Logs</a></li>
                                        <li><a href="<?php echo site_url('log/request'); ?>">Request Logs</a></li>
                                        <li><a href="<?php echo site_url('log/migration'); ?>">User Migration Logs</a></li>
                                        <li><a href="<?php echo site_url('log/tms'); ?>">TMS Callback Logs</a></li>
                                    </ul>
                                </li>
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

            <script type="text/javascript">
                var firebase_token      = "No Token";
                var firebase_permission = false;
                var adminId             = <?php echo $this->session->userdata('user')->id; ?>;
                var adminWeb            = "other";

                document.addEventListener('DOMContentLoaded', function() {
                    // // The Firebase SDK is initialized and available here!
                    // firebase.auth().onAuthStateChanged(user => { });
                    // firebase.database().ref('/path/to/ref').on('value', snapshot => { });
                    // firebase.storage().ref('/path/to/ref').getDownloadURL().then(() => { });
                    adminWeb = setUserBrowser();
                    //A. ASK PERMISSION FOR THE 1ST TIME
                    firebase.messaging().requestPermission().then(() => {
                      console.log('Notification permission granted.');
                        requestToken();
                    });

                    console.log(Notification.permission);

                    //B. SET LISTENER TO FOREGROUND MESSAGE
                    if (Notification.permission === 'granted') {
                        firebase.messaging().onMessage(function(payload) {
                            console.log('Received foreground message ', payload);

                            var title = 'OKBABE+ Customer Services';
                            var options = {
                                body: payload.notification.title + ' ' + payload.notification.body,
                                icon: '/okbabe-192x192.png',
                                image:'/okbabe-192x192.png',
                                requireInteraction: true
                            };

                            let notification = new Notification(title, options);
                            notification.onclick = function(event) {
                                //event.preventDefault();
                                //window.open(payload.notification.click_action , '_self');
                                window.open('<?php echo site_url(); ?>/complaints/report' , '_self', '', '');
                                //notification.close();
                            }

                        });
                    }else{ requestPermission(); }

                    //C. SET LISTENER TO REFRESHED TOKEN
                    firebase.messaging().onTokenRefresh(function () {
                        firebase.messaging().getToken()
                            .then(function (refreshedToken) {
                                firebase_token = refreshedToken;
                                sendTokenToServer(firebase_token);
                            })
                            .catch(function (err) {
                                console.log('Unable to retrieve refreshed token ', err);
                            });
                    });

                    //D. INIT THE APPS
                    try {
                      let app      = firebase.app();
                      let features = ['messaging'].filter(feature => typeof app[feature] === 'function');
                      console.log(`Firebase SDK loaded with ${features.join(', ')}`);
                    } catch (e) {
                      console.error(e);
                      console.log('Error loading the Firebase SDK, check the console.');
                    }

                    /**** ALL FUNCTIONS  ****/
                    function requestPermission() {
                        firebase.messaging().requestPermission()
                            .then(function () {
                                firebase_permission = true;
                                requestToken();
                            })
                            .catch(function (err) {
                                firebase_permission = false;
                                console.log('Unable to get permission to notify.', err);
                            });
                    }
                
                    function requestToken(){
                      console.log('Since permission is granted, retrieving token...');
                      firebase.messaging().getToken().then(function(currentToken) {
                        if (currentToken) {
                          firebase_token    = currentToken;
                          console.log(currentToken);
                          sendTokenToServer(currentToken);
                          setTokenValueSentToServer(currentToken, adminId);
                        } else {
                          console.log('No Instance ID token available. Request permission to generate one.');
                        }
                      }).catch(function(err) {
                           console.log('An error occurred while retrieving token. ', err);
                      });
                    }

                    function sendTokenToServer(currentToken) {
                        if (!isSameTokenSentToServer(currentToken)) {
                            var http   = new XMLHttpRequest();
                            var url    = "<?php echo site_url() ?>/" + "complaints/engine/fcm";
                            var params = "web_fcm=" + currentToken + "&id=" + adminId;
                            http.open("POST", url, true);
                            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            http.onreadystatechange = function () {
                                if (http.readyState == 4 && http.status == 200) { console.log(http.responseText); }
                            }
                            http.send(params);
                        } else {
                            console.log('Same token was already sent to server, unless it changes, no need to resend.');
                        }
                    }

                    function setTokenValueSentToServer(token, adminId) {
                        window.localStorage.setItem('tokenToServer', token);
                        window.localStorage.setItem('adminId', adminId);
                        window.localStorage.setItem('sentToServer', 1);
                    }

                    function setTokenSentToServer(sent) {
                        window.localStorage.setItem('sentToServer', sent ? 1 : 0);
                    }

                    function isTokenSentToServer() {
                        return window.localStorage.getItem('sentToServer') == 1;
                    }

                    function isSameTokenSentToServer(currentToken) {
                        var oldToken = window.localStorage.getItem('tokenToServer');
                        if (oldToken == currentToken) {
                            window.localStorage.setItem('sentToServer', 1);
                            return true;
                        } else {
                            window.localStorage.setItem('sentToServer', 0);
                            return false;
                        }
                    }

                    function setUserBrowser() {
                        var isChrome = Boolean(window.chrome);
                        console.log(isChrome);
                        if (isChrome) { return "chrome"; } 
                        else { 
                          alert("Please use Google Chrome before using This Site."); 
                          return "other"; 
                        }
                    }

                  });
            </script>

        </div>

    </body>
</html>