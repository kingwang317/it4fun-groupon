<!--banner-->
<div id="banner_title">
<img src="<?php echo site_url() ?>templates/images/banner_title.jpg">
</div>
<div id="orders">
<ul class="nav nav-tabs" role="tablist" style="margin:20px 0px;">
  <li class="active"><a href="#">訂單查詢</a></li>
  <li><a href="<?php echo site_url()."member/edit" ?>">會員資料編輯</a></li>
  
</ul>
<table width="100" class="table table-striped">

  <tr bgcolor="#696969" >
    <th scope="col">訂單編號</th>
    <th scope="col">下單日期</th>
    <th scope="col">商品名稱</th>
    <th scope="col">總價</th>
    <th scope="col">訂單狀態</th>
    <th scope="col">出貨狀態</th>
    <th scope="col">備註</th>
  </tr>
<?php if (isset($order_result) && sizeof($order_result) > 0 ): ?>
  <?php foreach ($order_result as $key => $value): ?>
  <?php 
         $date = explode(" ", $value->order_time); 
         $date2 = $date[0];  
  ?>
  <tr>
    <td><?php echo $value->order_id ?></td>
    <td><?php echo $date2 ?></td>
    <td><?php echo $value->pro_name ?></td>
    <td><?php echo $value->total_amount ?></td> 
    <td>
      <?php if ($value->order_status == "order_status_0002"): ?>
        已確認
      <?php else: ?>
        未確認
      <?php endif ?> 
    </td>
    <td>
      <?php if ($value->order_ship_status == "ship_status_0002"): ?>
        已出貨
      <? else: ?>
        未出貨
      <?php endif ?>  
    </td>
    <td><?php echo $value->order_note ?></td>
  </tr>
  <?php endforeach ?>
<?php else: ?>
  <tr>
    <td colspan="7">目前無訂單資料</td>
  </tr>
<?php endif ?>
  
 
 
</table>
<center>
<ul class="pagination"> 
    <?php echo $pagination?>
   
</ul>
   </center>
</div>

 