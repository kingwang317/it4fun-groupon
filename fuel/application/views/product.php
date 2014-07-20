<script type="text/javascript" src="<?=site_url()?>templates/Scripts/home.js" ></script>
<div id="navigation">
  <div class="bar">
    <a class='logo' href="<?php echo site_url()?>"></a>
    <!--<div class="menu1"><a href="<?php echo $pro_cate_1?>" ></a></div>-->
    <div class="menu2"><a href="<?php echo $pro_cate_2?>" <?php if($code_key=='pro_cate_0002') echo 'class="active"' ?>></a></div>
  </div>
</div>

<div id="banner">
  <div id="bannerbox">
    <div class="bannerphotobox">
      <ul>
<?php
      if(isset($ad_results))
      {
        foreach($ad_results as $ad_row)
        {

?>
        <li>
          <img src="<?php echo $base_url.$ad_row->ga_url?>" width="1000" height="297">
          <div class="infobox" startDate="<?php echo $system_time?>" endDate="<?php echo str_replace("-","/",$ad_row->pro_off_time)?>" >
            <p class="name"><?php echo $ad_row->pro_name?></p>
            <p class="desc"><?php echo mb_substr($ad_row->pro_summary,0,70,"utf-8");?></p>
            <p class="time">限時販售
              <span class="day"></span>天
              <span class="hour"></span>時
              <span class="min"></span>分
              <span class="sec"></span>秒
              <span class="msec"></span>
            </p>
            <a href="<?php echo $prod_detail_url.$ad_row->pro_id?>" target="_blank"></a>
          </div>
        </li>
<?php
        }
      }
?>
      </ul>
    </div>
    <div class="bannerbtnbox">
      <ul></ul>
    </div>
  </div>
</div>

<div id="bannerline"></div>

<div id="products">
<?php
  if(!empty($pro_results))
  {

    foreach($pro_results as $row)
    {
?>
      <div class="prod" startDate="<?php echo $system_time?>" endDate="<?php echo str_replace("-","/",$row->pro_off_time)?>" link="<?php echo $prod_detail_url.$row->pro_id?>" title="<?php echo $row->pro_name?>">
        <div class="img"><img src="<?php echo $base_url.$row->photo->ga_url?>" title="<?php echo $row->pro_name?>" width="240" height="194"></div>
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

<br class="clear">

<div id="info1">


</div>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1&appId=539158312835821";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="fbblock">
  <div class="fb-like-box" data-href="https://www.facebook.com/7even.tw" data-width="980" data-height="285" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
</div>

