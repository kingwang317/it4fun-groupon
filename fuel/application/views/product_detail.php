        <script type="text/javascript" src="<?=site_url();?>templates/Scripts/prod.js" ></script> 
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1&appId=181555878720296";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        jQuery(document).ready(function($) {
          $("#fb_share").click(function(){
            var url = $(this).attr('url');
            var caption = $(this).attr('caption');
            FB.ui({
              method: 'feed',
              display: 'popup',
              link: url,
              caption: caption,
            }, function(response){});
          });
        });

        </script>
        <div id="navigation">
          <div class="bar">
            <a class='logo' href="<?php echo site_url();?>"></a>
            <!--<div class="menu1"><a href="<?php echo $pro_cate_1?>" ></a></div>-->
            <div class="menu2"><a href="<?php echo $pro_cate_2?>" ></a></div>
          </div>
        </div>
              
        <div id="prod">
          <div class="prod_1">
            <div class="photo"><img src="<?php echo $base_url.$pro_photo_data->ga_url?>" width="640" height="480"></div>
            <div class="info">
              <div class="info_1">
                <div class="block">
                  <p class="prodname"><?php echo $pro_detail_results->pro_name?></p>
                  <p class="proddesc">
                    <?php echo $pro_detail_results->pro_summary?>
                  </p>
                </div>
              </div>
              <div class="info_2" startDate="<?php echo $system_time;?>" endDate="<?php echo str_replace("-","/",$pro_detail_results->pro_off_time)?>" >
                <div class="block">
                  <ul>
                    <li class="l_1">限時販售
                      <span class="day"></span>天
                      <span class="hour"></span>時
                      <span class="min"></span>分
                      <span class="sec"></span>秒
                      <span class="msec"></span>
                    </li>
                    <li class="l_2"><S>原價：NT$<?php echo $pro_detail_results->pro_original_price?>元</S></li>
                    <li class="l_3">團購價：NT$<?php echo $pro_detail_results->pro_group_price?>元</li>
                    <li class="l_4">現省：NT$<?php echo $pro_detail_results->pro_original_price-$pro_detail_results->pro_group_price?>元</li>
                    <li class="l_5">已售出 <?php echo $pro_selled_cnt?>份</li>
                  </ul>
                </div>
              </div>
              <?php
                if($pro_offed === false)
                {
              ?>
                <a class="buy_btn" href="<?php echo $go_cart_url?>"></a>
              <?php
                }
                else
                {
              ?>
                <a class="buy_btn" href="javascript: void(0); alert('團購已過期')"></a>
              <?php
                }
              ?>
              </a>
            </div>
          </div>
          <div class="prod_2">
            <div class="fb-like" data-href="<?php echo current_url();?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
          </div>
          <div class="prod_3">
            <div class="left">
              <div class="abgne_tab">
                <ul class="tabs">
                  <li><a href="#tab1">商品說明</a></li>
                  <li><a href="#tab2">商品規格</a></li>
                  <li><a href="#tab3">購買需知</a></li>
                </ul>

                <div class="tab_container">
                  <div id="tab1" class="tab_content">
                    <div id="tab_1_content">
                      <?php echo htmlspecialchars_decode($pro_detail_results->pro_desc)?>
                    </div>
                  </div>
                  <div id="tab2" class="tab_content">
                    <div id="tab_2_content">
                    <?php echo htmlspecialchars_decode($pro_detail_results->pro_format)?>
                    </div>
                  </div>
                  <div id="tab3" class="tab_content">
                    <div id="tab_3_content">
                    <?php echo htmlspecialchars_decode($pro_detail_results->pro_ship_note)?>
                    </div>       
                  </div>
                </div>
              </div>
              <?php
                if($pro_offed === false)
                {
              ?>
                <a class="buy_btn" href="<?php echo $go_cart_url?>"></a>
              <?php
                }
                else
                {
              ?>
                <a class="buy_btn" href="javascript: void(0); alert('團購已過期')"></a>
              <?php
                }
              ?>
              
            </div>
            <div class="right">
              <?php
                if(isset($pro_top_result))
                {
                  foreach($pro_top_result as $row)
                  {
              ?>
                    <div class="subprod" startDate="<?php echo $system_time?>" endDate="<?php echo $row->pro_off_time?>" link="<?php echo $prod_detail_url.$row->pro_id?>">
                      <div class="img"><img src="<?php echo $base_url.$row->photo->ga_url?>" title="<?php echo $row->pro_name?>" width="240" height="240"></div>
                      <div class="black">
                        <div class='blackbg'></div>
                        <div class='txt'>
                          <span class="day"></span>天
                          <span class="hour"></span>時
                          <span class="min"></span>分
                          <span class="sec"></span>秒
                        </div>
                      </div>
                    </div>  
              <?php
                  }
                }
              ?>


            </div>
          </div>          
        </div>        
        <br class="clear"/>
        <br/>
        <br/>
