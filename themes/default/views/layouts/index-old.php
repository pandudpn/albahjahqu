<!DOCTYPE html>
<html lang="en">
  <head profile="http://www.w3.org/2005/10/profile">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" context="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

    <title>CI Front End System - Fundamental Template</title>
    <meta name="description" content="">

    <link href="<?php echo $this->template->get_theme_path();?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->template->get_theme_path();?>css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $this->template->get_theme_path();?>css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="<?php echo $this->template->get_theme_path();?>css/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?php echo $this->template->get_theme_path();?>css/introjs.css" rel="stylesheet" >

    <link href="<?php echo $this->template->get_theme_path();?>css/step-form-wizard.css" rel="stylesheet" type="text/css" media="screen, projection">
    <link href="<?php echo $this->template->get_theme_path();?>css/jquery.mCustomScrollbar.css" rel="stylesheet" >
    <link href="<?php echo $this->template->get_theme_path();?>css/parsley.css" rel="stylesheet" type="text/css" media="screen, projection">
    <link href="<?php echo $this->template->get_theme_path();?>css/jasny-bootstrap.min.css" rel="stylesheet" >

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url()."favicon.ico" ?>" />
    <link rel="shortcut icon" type="image/png" href="<?= base_url()."favicon.png" ?>" />

    <script src="<?php echo $this->template->get_theme_path();?>js/jquery/jquery.min.js"></script>
    <script src="<?php echo $this->template->get_theme_path();?>js/moment.min.js"></script>
    <script src="<?php echo $this->template->get_theme_path();?>js/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo $this->template->get_theme_path();?>js/bootstrap/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo $this->template->get_theme_path();?>js/bootstrap/bootstrap-select.min.js"></script>
    <script src="<?php echo $this->template->get_theme_path();?>js/introjs/intro.js"></script>

    <script src="<?php echo $this->template->get_theme_path();?>js/stepwizardjs/step-form-wizard.min.js"></script>
    <script src="<?php echo $this->template->get_theme_path();?>js/mcustom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?php echo $this->template->get_theme_path();?>js/parsley/parsley.min.js"></script>
    <!-- <script src="<?php echo $this->template->get_theme_path();?>js/parsley/lang/en.js"></script> -->
    <!-- <script src="<?php echo $this->template->get_theme_path();?>js/parsley/lang/en.extra.js"></script> -->
    <script src="<?php echo $this->template->get_theme_path();?>js/jasny/jasny-bootstrap.min.js"></script>
    <script src="<?php echo $this->template->get_theme_path();?>js/qrcode/qrcode.min.js"></script>
    <script type="text/javascript">
      
    </script>
</head>
<body>
    <div class="container clearfix">
        <div class="row">
          <div class="col-md-12">
            <h1 class="text-center"></h1>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6"></div>
          <div class="col-md-6"></div>
        </div>

        <?php echo $template['body']; ?>

        <div class="row">
          <div class="col-md-6"></div>
          <div class="col-md-6"></div>
        </div>

        <style type="text/css"></style>
    </div>
</body>
</html>
