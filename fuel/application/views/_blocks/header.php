<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php if (!empty($is_blog)) : ?>
    <title><?php echo $CI->fuel_blog->page_title($page_title, ' : ', 'right')?></title>
    <?php else : ?>
    <title>崇文冷凍食品股份有限公司-海鮮宅配鮮到你家&nbsp;<?php echo fuel_var('page_title', '')?></title>
    <?php endif ?>
    <meta name="keywords" content="<?php echo fuel_var('meta_keywords')?>" />
    <meta name="description" content="<?php echo fuel_var('meta_description')?>" />
    <meta property="og:title" content="<?php echo fuel_var('og_title')?>"/>
    <meta property="og:image" content="<?php echo fuel_var('og_image')?>"/>
    <meta property="og:site_name" content="<?php echo fuel_var('page_title')?>"/>
    <meta property="og:description" content="<?php echo fuel_var('og_desc')?>"/>
<!--     <link href="<?=site_url()?>templates/css/layout.css" rel="stylesheet">
    <link href="<?=site_url()?>templates/css/menu2.css" rel="stylesheet"> -->
    <link href="<?=site_url()?>templates/css/css.css" rel="stylesheet">
    <link href="<?=site_url()?>templates/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="<?=site_url()?>templates/css/jquery.bxslider.css" rel="stylesheet" />
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/jquery.timers.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/jquery.easing.min.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/RegCheck.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/ga.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/js/bootstrap.min.js"></script> 
    <script type="text/javascript" src="<?=site_url()?>templates/js/jquery.bxslider.min.js"></script> 

<script src="<?=site_url()?>templates/js/jquery.countdown.min.js"></script>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '721731694566645',
      xfbml      : true,
      version    : 'v2.1'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

    <?php 
      define('FUELIFY', FALSE); //hide edit bar
    ?>
  </head>
  <body>
    <div id="fb-root"></div>
    <div id="header">
      <a href="<?php echo site_url() ?>"><img src="<?=site_url()?>templates/images/logo.jpg" id="logo"></a>
      <ul>
        <li>
          <a href="<?php echo site_url() ?>cart"><img src="<?=site_url()?>templates/images/cart_icon.png"> 購物車</a>
        </li>
        <li>
          <a href="<?php echo site_url() ?>about">關於我們</a>
        </li>
        <li>
          <a href="<?php echo site_url() ?>news">最新消息</a>
        </li>
        <li>
          <a href="<?php echo site_url() ?>category">產品資訊</a>
        </li>
        <li>
          <a href="<?php echo site_url() ?>directions">購物說明</a>
        </li>
        <li>
          <a href="<?php echo site_url() ?>orders">訂單查詢</a>
        </li>
         <?php
            if(isset($this->fuel_auth))
            {
              $session_key = $this->fuel_auth->get_session_namespace();
              $user_data = $this->session->userdata($session_key);
            }
            else if(isset($CI->fuel_auth))
            {
              $session_key = $CI->fuel_auth->get_session_namespace();
              $user_data = $CI->session->userdata($session_key);
            }
            
            $member_id = isset($user_data['member_id'])?$user_data['member_id']:"";
            if($member_id)
            {
          ?>
            <li><a href="<?php echo site_url()?>/user/logout" id="topmenu_10" title="登出">登出</a></li>
          <?php
            }
          ?>
      </ul>
    </div>
    