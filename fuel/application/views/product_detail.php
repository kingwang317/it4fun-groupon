 
   
  <!--banner-->
<div id="banner_title">
<img src="<?php echo site_url() ?>templates/images/banner_title.jpg">
</div>
<div id="category">
<ul> 
 <?php if (isset($pro_cate_result)): ?>
    <?php foreach ($pro_cate_result as $key => $value): ?>
  
      <?php if ($value->id==$pro_detail_results->pro_cate): ?>
        <li class="set"><?php echo $value->code_name ?></li>
      <?php else: ?>
        <li><a href="<?php echo site_url()."category/".$value->id ?>"><?php echo $value->code_name ?></a></li>
      <?php endif ?>
    <?php endforeach ?>
  <?php endif ?>
</ul>
<div id="clear"></div>
</div>

<div id="product-detail-box">
<div id="left">
<img src="<?php echo $base_url.$pro_photo_data->ga_url?>"><br>
<br>
<div class="fb-like" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
<div id="textbox">
<h2><?php echo $pro_detail_results->pro_name?></h2>
<p>
<?php echo $pro_detail_results->pro_summary?>
</p>
</div>
 
　<?php echo htmlspecialchars_decode($pro_detail_results->pro_desc)?>
 
<div id="getting-started" style="color:#707070;font-size:18px;"></div>
 
    <script type="text/javascript">
        $("#getting-started")
        .countdown('<?php echo str_replace("-","/",$pro_detail_results->pro_off_time)?>', function(event) {
        $(this).html(event.strftime('搶購時間剩餘： %D 天 %H 時 %M 分 %S 秒 '));
      });
     </script>
<div id="product_cart">
<div id="left">
<br><s>原價：<?php echo $pro_detail_results->pro_original_price?></s><br>
<?php 
$price = 0; 
$num = 0;

if (isset($pro_plan_results)) {
  $price = $pro_plan_results->plan_price;
  $num = $pro_plan_results->plan_num;
}

?>
<span style="color:#af201a;font-size:36px;">特價＄<?php echo $price?></span><br>
<span style="color:#707070;font-size:18px;"> <?php echo $pro_selled_cnt?>份已購買 </span>
</div>
<div id="right">
數量： 
<select id="num" style="width:70px;margin:0px 10px;">
  <?php if ($num > 0): ?>
     <?php for ($i=1;$i<=$num;$i++): ?>
      <option value="<?php echo $i ?>"><?php echo $i ?></option>
    <?php endfor ?>
  <?php else: ?>
    <option value="0">缺貨中</option>
  <?php endif ?>
 
</select><img id="img_cart" src="<?php echo site_url() ?>templates/images/cart-btn.jpg">
</div>
</div>
</div>

<?php if (isset($pro_top_result) && sizeof($pro_top_result) > 0 ): ?>
<div id="right">
銷售排行：
<?php foreach ($pro_top_result as $key => $value): ?>
  <a href='<?php echo site_url()."prod/detail/$value->pro_id.php"; ?>'><img style="width:227px" src="<?php echo $base_url.$value->photo->ga_url?>"></a><br> 
<?php endforeach ?>
</div>
<div id="clear"></div>
</div>
<?php endif ?>

<?php if (isset($pro_interest_results) && sizeof($pro_interest_results) > 0 ): ?>
<div id="interest">
您可能也有興趣：<br> 
<?php $i = 0; ?>
<?php foreach ($pro_interest_results as $key => $value): ?>
  <a href='<?php echo site_url()."prod/detail/$value->pro_id.php"; ?>'><img 
    <?php if ($i==0): ?>
    style="margin-left:0px;width:227px"
    <?php else: ?>
    style="width:227px" 
    <?php endif ?>
  src="<?php echo $base_url.$value->photo->ga_url?>"></a>
<?php $i++; ?>
<?php endforeach ?>
<!-- <img  style="margin-left:0px;" src="<?php echo site_url() ?>templates/images/product-small.jpg"> -->
</div>
<?php endif ?>

<script type="text/javascript">
  $(document).ready(function() {
     $('#img_cart').on("click", function (e) {
        var num = $('#num').val();
        if (num > 0) {
            var url = '<?php echo site_url()."addToCart/$pro_detail_results->pro_id/"; ?>' + num;
            $.get(url, function(data) {
              /*optional stuff to do after success */
              alert('商品已加入購物車！');
            });
        }else{
            alert('商品缺貨中！');
        }
     });
  });
</script>

