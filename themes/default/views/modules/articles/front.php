<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $data->title; ?> | OKBABE+</title>
        <meta data-n-head="true" charset="utf-8">
        <meta data-n-head="true" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" name="viewport">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->template->get_theme_path();?>css/style.css">

        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $this->template->get_theme_path();?>images/icons/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $this->template->get_theme_path();?>images/icons/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $this->template->get_theme_path();?>images/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $this->template->get_theme_path();?>images/icons/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $this->template->get_theme_path();?>images/icons/favicon-16x16.png">
        <link rel="manifest" href="<?php echo $this->template->get_theme_path();?>images/icons/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?php echo $this->template->get_theme_path();?>images/icons/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- METADATA -->
         <meta name="original-source" content="<?php echo site_url(); ?>">
         <link rel="canonical" href="<?php echo site_url(); ?>">
         <meta property="og:locale" content="id_ID">
         <meta property="og:type" content="article">
         <meta property="og:title" content="<?php echo $data->title; ?> | OKBABE+">
         <meta property="og:description" content="<?php echo $data->headlines; ?>">
         <meta property="og:url" content="<?php echo current_url(); ?>">
         <meta property="og:site_name" content="OKBABE+">
         <meta property="og:image" content="<?php echo site_url('data/images/'.$data->cover_image); ?>">

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132342627-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-132342627-1');
        </script>

    </head>
    <body>
        <div class="new-container">
            <div class="header header-home">
                <div style="text-align: center;">
                    <img style="max-width: 210px;margin-top: 20px; margin-bottom: 20px;" src="<?php echo $this->template->get_theme_path();?>images/logo_okbabe_white.png">
                </div>
            </div>
        </div>
     
        <div class="new-container">
            <div>
                <img style="width: 100%;border-radius: 10px;box-shadow: 12px 12px 37px 0 rgba(0,0,0,0.12), 7px 7px 20px 0 rgba(0,0,0,0.30);" src="<?php echo site_url('data/images/'.$data->cover_image); ?>">
            </div>
            <div class="base base-body">
                <div class="content_area">
                    <div class="title_header">
                        <h6 style="margin-bottom: 0px;"><?php echo date('j F, Y H:i', strtotime($data->created_on)); ?></h6>
                            <h2 style="color: #731A91;"><?php echo $data->title; ?></h2>
                            <hr style="border: .3px solid #e3e3e3;">
                    </div>
                    <!--CONTENT-->
                    <?php echo $data->content; ?>
                    <!--CONTENT-->
                </div>

            </div>
        </div>
        <div class="new-container">
            <div class="footer_area" style="margin-top:20px;padding-top: 20px;padding-bottom: 20px;text-align: center;">
                <h2 style="color: #fff;font-weight: 300;">Download Sekarang</h2>
                    <img style="max-width: 210px;margin-bottom: 40px;" src="<?php echo $this->template->get_theme_path();?>images/logo_okbabe_white.png">
                    <br>
                    <a href="javascript:;"><img style="display: inline-block;width: 35%;margin-right: 10px;" src="<?php echo $this->template->get_theme_path();?>images/playstore.png"></a>
                <!-- <a href="javascript:;"><img style="display: inline-block;width: 35%;margin-left: 10px;" src="<?php echo $this->template->get_theme_path();?>images/appstore.png"></a> -->
            </div>
        </div>
    </body>
    <script type="text/javascript" src="<?php echo $this->template->get_theme_path();?>js/my_script.js"></script>
</html>