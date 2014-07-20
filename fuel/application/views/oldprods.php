
        <script type="text/javascript" src="<?=site_url()?>templates/Scripts/oldprods.js" ></script> 
        <div id="navigation">
          <div class="bar">
            <a class='logo' href="<?php echo site_url();?>"></a>
            <!--<div class="menu1"><a href="<?php echo $pro_cate_1?>" ></a></div>-->
            <div class="menu2"><a href="<?php echo $pro_cate_2?>" ></a></div>
          </div>
        </div>
        <div id="oldprods">
          <div class="title"></div>

          <?php
            if(isset($old_prod_result))
            {
              foreach($old_prod_result as $row)
              {
          ?>
                <div class="prod" link="<?php echo $prod_detail_url.$row->pro_id?>">
                  <div class="img"><img src="<?php echo $base_url.$row->photo->ga_url?>" title="<?php echo $row->pro_name?>" width="240" height="194"></div>
                </div>        
          <?php
              }
            }
          ?>

        </div>   
        <br class="clear"/>
        <br/>     