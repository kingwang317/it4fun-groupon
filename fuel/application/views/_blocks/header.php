<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php if (!empty($is_blog)) : ?>
    <title><?php echo $CI->fuel_blog->page_title($page_title, ' : ', 'right')?></title>
    <?php else : ?>
    <title>Taste it 團購網!&nbsp;<?php echo fuel_var('page_title', '')?></title>
    <?php endif ?>
    <meta name="keywords" content="<?php echo fuel_var('meta_keywords')?>" />
    <meta name="description" content="<?php echo fuel_var('meta_description')?>" />
    <meta property="og:title" content="<?php echo fuel_var('og_title')?>"/>
    <meta property="og:image" content="<?php echo fuel_var('og_image')?>"/>
    <meta property="og:site_name" content="<?php echo fuel_var('page_title')?>"/>
    <meta property="og:description" content="<?php echo fuel_var('og_desc')?>"/>
    <link href="<?=site_url()?>templates/css/layout.css" rel="stylesheet">
    <link href="<?=site_url()?>templates/css/menu2.css" rel="stylesheet">
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/jquery.timers.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/jquery.easing.min.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/RegCheck.js"></script>
    <script type="text/javascript" src="<?=site_url()?>templates/Scripts/lib/ga.js"></script>
    <?php 
      define('FUELIFY', FALSE); //hide edit bar
    ?>
  </head>
  <body>
    <div id='main_container'>
      <div id='container'>
        <div id="header">
          <div id="headerblock">
            <a id='logo' href="<?php echo site_url();?>"></a>
            <ul id="headerbtn">
              <!--<li><a href="order_memberedit.html" target="_self">訂單查詢</a></li> 
              <li>|</li>
              <li><a href="faq.html" target="_self">常見問題</a></li> 
              <li>|</li>
              <li><a href="#" target="_self">聯絡我們</a></li> 
              <li>|</li>
              <li><a href="oldprods.html" target="_self">過往好貨</a></li>-->
              <?php echo fuel_nav(array('group_id' =>2, 'container_tag_id' => 'topmenu', 'item_id_prefix' => 'topmenu_','render_type' => 'delimited','delimiter' => '&nbsp;|&nbsp;','container_tag' => 'li')); ?>
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
        </div>