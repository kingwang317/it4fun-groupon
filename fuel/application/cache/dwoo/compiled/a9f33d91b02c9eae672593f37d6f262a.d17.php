<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Taste it 團購網!&nbsp;聯絡我們</title>
        <meta name="keywords" content="聯絡我們" />
    <meta name="description" content="聯絡我們" />
    <meta property="og:title" content=""/>
    <meta property="og:image" content=""/>
    <meta property="og:site_name" content="聯絡我們"/>
    <meta property="og:description" content=""/>
    <link href="http://taste-it.com.tw/templates/css/layout.css" rel="stylesheet">
    <link href="http://taste-it.com.tw/templates/css/menu2.css" rel="stylesheet">
    <script type="text/javascript" src="http://taste-it.com.tw/templates/Scripts/lib/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="http://taste-it.com.tw/templates/Scripts/lib/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="http://taste-it.com.tw/templates/Scripts/lib/jquery.timers.js"></script>
    <script type="text/javascript" src="http://taste-it.com.tw/templates/Scripts/lib/jquery.easing.min.js"></script>
    <script type="text/javascript" src="http://taste-it.com.tw/templates/Scripts/lib/RegCheck.js"></script>
    <script type="text/javascript" src="http://taste-it.com.tw/templates/Scripts/lib/ga.js"></script>
      </head>
  <body>
    <div id='main_container'>
      <div id='container'>
        <div id="header">
          <div id="headerblock">
            <a id='logo' href="http://taste-it.com.tw/"></a>
            <ul id="headerbtn">
              <!--<li><a href="order_memberedit.html" target="_self">訂單查詢</a></li> 
              <li>|</li>
              <li><a href="faq.html" target="_self">常見問題</a></li> 
              <li>|</li>
              <li><a href="#" target="_self">聯絡我們</a></li> 
              <li>|</li>
              <li><a href="oldprods.html" target="_self">過往好貨</a></li>-->
              
<li id="topmenu">
<a href="http://taste-it.com.tw/ordercheck" id="topmenu_7" title="訂單查詢">訂單查詢</a>&nbsp;|&nbsp;<a href="http://taste-it.com.tw/faq" id="topmenu_8" title="常見問題">常見問題</a>&nbsp;|&nbsp;<a href="http://taste-it.com.tw/contact_us" id="topmenu_9" title="聯絡我們">聯絡我們</a>&nbsp;|&nbsp;<a href="http://taste-it.com.tw/old/list" id="topmenu_10" title="過往好貨">過往好貨</a></li>
                              <li><a href="http://taste-it.com.tw//user/logout" id="topmenu_10" title="登出">登出</a></li>
                            
            </ul>

          </div>
        </div>	
	<!--<div id="main_inner">-->
		<div id="faqbanner">
          <div id="faqbannerbox"></div>
        </div>         
<div id="faq">
          <div class="title"></div>
          <div class="qua">
            <ul>
             意見/問題反應:<hr />
嚐試購物團希望您能滿意我們的產品及服務，對於產品的任何問題或意見，都歡迎您與我們聯繫。<hr />
客服信箱: service@taste-it.com.tw<hr />
嚐新鮮工作人員會於1個工作日內以Email回覆您。</div>
           <img src="<?php echo img_path('8b864-510x410.png');?>" alt="" /> </li>
            </ul>
          </div>
        </div>	<!--</div>-->
	
        <div id="footer">
          <div id="footerblock">
            <div id="footer_logo"></div>
            <ul>
              <!--<li><a href="#" target="_self">嚐新鮮</a></li> 
              <li>|</li>
              <li><a href="index.html" target="_self">嚐海鮮</a></li>  
              <li>|</li>
              <li><a href="#" target="_self">會員資訊</a></li>  
              <li>|</li>
              <li><a href="#" target="_self">最新消息</a></li>  
              <li>|</li>
              <li><a href="faq.html" target="_self">常見問題</a></li>  
              <li>|</li> 
              <li><a href="#" target="_self">關於我們</a></li> 
              <li>|</li>
              <li><a href="#" target="_self">聯絡我們</a></li>  
              <li>|</li> 
              <li><a href="#" target="_self">條款與政策</a></li> -->
              
<li id="topmenu">
<a href="http://taste-it.com.tw/prod/pro_list/pro_cate_0002" id="topmenu_1" title="嚐海鮮">嚐海鮮</a>&nbsp;|&nbsp;<a href="http://taste-it.com.tw/faq" id="topmenu_3" title="常見問題">常見問題</a>&nbsp;|&nbsp;<a href="http://taste-it.com.tw/about_us" id="topmenu_4" title="關於我們">關於我們</a>&nbsp;|&nbsp;<a href="http://taste-it.com.tw/contact_us" id="topmenu_5" title="聯絡我們">聯絡我們</a>&nbsp;|&nbsp;<a href="http://taste-it.com.tw/policy" id="topmenu_6" title="條款與政策">條款與政策</a></li>

            </ul>


            <p> Copyright @ 2013 嚐試 Teste It . All rights reserved </p>
          </div>
        </div>
      </div>
    </div>

</body>
</html><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>