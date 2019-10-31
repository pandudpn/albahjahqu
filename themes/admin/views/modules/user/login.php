
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
        <title>Login </title>

        <!-- Bootstrap CSS -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- App CSS -->
        <link href="<?php echo $this->template->get_theme_path();?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <!-- Modernizr js -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/modernizr.min.js"></script>

        <style type="text/css">
            .account-pages {
                background-color: #00b169;
            }

            .wrapper-page .card-box {
                box-shadow: 0 0px 24px 0 rgba(0, 0, 0, 0.06), 0 1px 0px 0 rgba(0, 0, 0, 0.02);
                border: 5px solid #00b169;
            }
        </style>

    </head>


    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">

        	<div class="account-bg">
                <div class="card-box mb-0">
                    <div class="text-center m-t-20">
                        <a href="index.html" class="logo">
                            <!-- <i class="zmdi zmdi-group-work icon-c-logo"></i>
                            <span>Uplon</span> -->
                            <!-- <img src="<?php echo $this->template->get_theme_path();?>assets/images/icon/BNI_logo.svg" height="40"> -->
                        </a>
                    </div>
                    <div class="m-t-10 p-20">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h6 class="text-muted text-uppercase m-b-0 m-t-0">Sign In</h6>
                            </div>
                        </div>
                        <form class="m-t-20" method="post" action="<?php echo site_url('login'); ?>">

                            <div class="form-group row">
                                <div class="col-12">
                                    <?php if($alert){ ?>
                                    <div class="alert alert-<?php echo $alert['type']; ?>">
                                        <?php echo $alert['msg']; ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control" name="email" type="email" required="" placeholder="Email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control" name="password" type="password" required="" placeholder="Password">
                                </div>
                            </div>

                            <div class="form-group text-center row m-t-10">
                                <div class="col-12">
                                    <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                </div>
                            </div>

                        </form>

                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- end card-box-->

        </div>
        <!-- end wrapper page -->


        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.min.js"></script>
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

        <!-- App js -->
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.core.js"></script>
        <script src="<?php echo $this->template->get_theme_path();?>assets/js/jquery.app.js"></script>

    </body>
</html>