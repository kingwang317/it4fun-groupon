
        <div id="navigation">
          <div class="bar">
            <a class='logo' href="<?php echo site_url();?>"></a>
            <!--<div class="menu1"><a href="<?php echo $pro_cate_1?>" ></a></div>-->
            <div class="menu2"><a href="<?php echo $pro_cate_2?>" ></a></div>
          </div>
        </div>

        <div id="buystep2">
          <div class="txt"></div>
          <div class="line"><div></div></div>
        </div>        
        <div id="buystep2_info">
          <form action='<?php echo $gateway ?>' method='post' enctype="multipart/form-data">
              <input type='text' value='2000132' name='MerchantID'>
              <input type='text' value='CREDIT' name='PaymentType'>
              <input type='text' value='4311952222222222' name='CardNo'>
              <br />
              <textarea name="XMLData" style="width:500px;height:500px;"><?php echo $encode_data ?></textarea><br />
              <!--<input type='text' value='<?php echo $encode_data ?>' name='XMLData'>-->
              <input type='submit'>
          </form>
        </div>  
        <br class="clear">   