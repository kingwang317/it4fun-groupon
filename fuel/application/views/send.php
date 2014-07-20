
  <div id="navigation">
    <div class="bar">
      <a class='logo' href="<?php echo site_url();?>"></a>
      <!--<div class="menu1"><a href="<?php echo $pro_cate_1?>" ></a></div>-->
      <div class="menu2"><a href="<?php echo $pro_cate_2?>" ></a></div>
    </div>
  </div>
<div id="buystep3_info">

      <div class="content1">              
          <div class="photo">
            <img src="<?php echo site_url()?>/templates/images/photo2.jpg" >
          </div>
          <div class="desc">
            <?php
              if($RtnCode === 1)
              {
            ?>
                <div class="str1">謝謝您的購買！ </div>
                <div class="str2">
                  訂單編號：<?php echo $order_id;?><br />
                  交易金額：<?php echo $trade_price;?><br />
                  <span><?php echo $order_name?></span> 
                  先生/小姐謝謝您的支持<br>您可以登入訂單查詢，查詢您的訂單狀況<br>
                  嚐試 Taste it再次謝謝您的購買
                </div>
            <?php
              }
              else
              {
            ?>
                <div class="str1">交易失敗！ </div>
                <div class="str2">
                  錯誤代碼：<?php echo $RtnCode;?><br />
                  訂單編號：<?php echo $order_id;?><br />
                  請恰客服人員!!
                </div>           
            <?php
              }
            ?>
          </div>                
      </div>
      
</div>  
<br class="clear">      
