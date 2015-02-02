
<!--banner-->
<div id="banner">
  <ul class="bxslider">
   
<?php
      if(isset($ad_results))
      {
        foreach($ad_results as $ad_row)
        {

?> 
    <li>
      <div id="banner_img" style="background-image:url(<?php echo $base_url.$ad_row->ga_url?>);cursor:pointer;" onclick="location.href='<?php echo $prod_detail_url.$ad_row->pro_id?>';">
        <div id="banner_text" >
        <h1><?php echo $ad_row->pro_name?></h1>
        <p><?php echo mb_substr($ad_row->pro_summary,0,70,"utf-8");?></p>
        </div>
      </div>
    </li>
<?php
        }
      }
?>
      </ul>
  <script type="text/javascript">
  $(document).ready(function(){
    $('.bxslider').bxSlider();
  });
  </script>

</div>

<!--banner-->
<div id="category">
<ul>
  <li ><a href="category/0">全部商品</a></li>
  <?php if (isset($pro_cate_result)): ?>
    <?php foreach ($pro_cate_result as $key => $value): ?>
      <li ><a href="category/<?php echo $value->id ?>"><?php echo $value->code_name ?></a></li>
    <?php endforeach ?>
  <?php endif ?>

</ul>
<div id="clear"></div>
</div>
<!--product box-->
<div id="product_box">

<?php
      $i = 0;
      if(isset($pro_results))
      {
        foreach($pro_results as $row)
        {

?> 
        <!--box-->
        <div id="product_in">
        <div id="lable_new">
        </div>
          <a href="<?php echo $prod_detail_url.$row->pro_id.".php"?>"><img id="product_list_img" src="<?php echo $base_url.$row->photo->ga_url?>" title="<?php echo $row->pro_name?>" /></a>
          <p><?php echo $row->pro_summary?></p>
          <?php if ($row->always_available <> 1): ?>
              <div id='<?php echo "getting-started$i"; ?>'></div><script type="text/javascript">
                $('<?php echo "#getting-started$i"; ?>')
                .countdown('<?php echo str_replace("-","/",$row->pro_off_time)?>', function(event) {
                $(this).html(event.strftime('%D 天 %H時%M分%S秒'));
              });
              </script>
          <?php endif ?>
             <?php if ($row->always_available == 0): ?>
              <br>
          <?php endif ?>  
                <div id="product_gray">
                <span class="pr_text"> <?php echo $row->pro_name?> </span><br />
                <span class="pr_text"> 特價 $ <?php echo $row->pro_group_price ?></span> <s>原價 $ <?php echo $row->pro_original_price?></s><br />
               <button type="button" class="btn btn-danger btn-xs" style="float:right;" onclick="location.href='<?php echo $prod_detail_url.$row->pro_id.".php"?>'">詳細資訊</button>
               <p><?php echo $row->pro_selled_cnt?>份已售出</p>
                <div id="clear"></div>

              </div>
              <!-- <img src="<?php echo site_url() ?>templates/images/lable_new.png" id="lable_new"> -->
            <?php if ($row->pro_promote != ""): ?>
                <img src='<?php echo site_url()."/templates/images/lable_$row->pro_promote.png" ?>' id="lable_new">
            <?php endif ?>      

        </div>
        <!--box-->
<?php
        $i++;
        }
      }
?> 

</div>
 

 