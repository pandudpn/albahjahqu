<!DOCTYPE html>
<html lang="id">
  <head>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta charset="utf-8">
    <meta content="text/html" http-equiv="Content-Type">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

    <title>E - Sinergi46 - <?php echo $data->title;?> </title>
    <meta name="description" content="<?php echo $data->intro;?>">

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->template->get_theme_path();?>images/favicon.ico" />
    <link rel="shortcut icon" type="image/png" href="<?php echo $this->template->get_theme_path();?>images/favicon.png" />

    <!-- SEO OG -->
    <meta charset="utf-8">
    <meta property="og:type" content="article" />
    <meta property="og:site_name" content="Sinergy46" />
    <meta property="og:title" content="<?php echo $data->title;?>" />
    <meta property="og:image" content="<?php echo site_url('data/images');?>/<?php echo $data->featured_image;?>" />
    <meta property="og:description" content="<?php echo $data->intro;?>" />
    <meta property="og:url" content="<?php echo site_url();?><?php echo $data->slug ?>" />
    <!-- <meta property="og:image:type" content="image/jpeg" /> -->
    <!-- <meta property="og:image:width" content="650" /> -->
    <!-- <meta property="og:image:height" content="366" /> -->

    <!-- FB APP ID -->
    <!-- <meta property="fb:app_id" content="example:187960271237149" />
    <meta property="fb:admins" content="example:100000607566694" /> -->

    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@bni" />
    <meta name="twitter:site:id" content="@bni" />
    <meta name="twitter:creator" content="@bni" />
    <meta name="twitter:description" content="<?php echo $data->intro;?>"/>
    <meta name="twitter:image:src" content="<?php echo site_url('data/images');?>/<?php echo $data->featured_image;?>" />

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link href="<?php echo $this->template->get_theme_path();?>css/main.css?ver=1.1" rel="stylesheet">
</head>
<body>
    <div class="header">
        <img class="main_logo" src="<?php echo $this->template->get_theme_path();?>images/icon_e-sinergi46.png">
    </div>
    <div class="wrapper-post">
        <?php echo $template['body']; ?>        
    </div>
    <div class="footer-detail">
        <div class="base-footer-detail">
            <img style="width: 200px;" src="<?php echo $this->template->get_theme_path();?>images/icon_e-sinergi46.png">
            <p>Download Official Apps of Sinergi46 News Magazine of PT Bank Negara Indonesia (Persero) Tbk.</p><br>
            <div style="display: flex;">
                <div style="flex:1">
                    <a href="https://play.google.com/store/apps/details?id=com.bilinedev.sinergi46"><img style="max-width: 80%;" src="<?php echo $this->template->get_theme_path();?>images/android-market.png"></a>
                </div>
                <div style="flex:1">
                    <a href="https://itunes.apple.com/id/app/sinergi46/id1404188589"><img style="max-width: 80%;" src="<?php echo $this->template->get_theme_path();?>images/app-store.png"></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
