<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>崇文冷凍食品股份有限公司-海鮮宅配鮮到你家</title>
<? include "script.inc.php" ?>
<? include "ga.inc.php" ?>
</head>

<body>
<? include "login.inc.php" ?>
<? include "header.inc.php" ?>

<!--banner-->
<div id="banner_title">
<img src="images/banner_title.jpg">
</div>
<div id="cart">
<table width="100" class="table table-striped">
  <tr bgcolor="#696969" >
  <th scope="col">圖片</th>
    <th scope="col">商品名稱</th>
    <th scope="col">數量</th>
    <th scope="col">單價</th>
    <th scope="col">小計</th>
    <th scope="col">取消</th>
  </tr>
  <tr>
    <td valign="center"><img src="images/cart_sample.jpg"></td>
    <td valign="center">曼波魚膠</td>
    <td valign="center">5</td>
      <td valign="center">100</td>
    <td valign="center">500</td>
    <td valign="center"><img src="images/cart_cancel.png"> 取消</td>
    
  </tr>
  <tr>
    <td valign="center"><img src="images/cart_sample.jpg"></td>
    <td valign="center">曼波魚膠</td>
    <td valign="center">5</td>
      <td valign="center">100</td>
    <td valign="center">500</td>
    <td valign="center"><img src="images/cart_cancel.png"> 取消</td>
    
  </tr>
  <!--最下面的運費-->
    <tr>
    <td valign="center"><img src="images/cart_icon_1.png"></td>
    <td valign="center">運費（滿2000免運）</td>
    <td valign="center">&nbsp;  </td>
      <td valign="center"></td>
    <td valign="center">150</td>
    <td valign="center">&nbsp; </td>
  </tr>
  <!--繼續借用表格的加總-->
     <tr>
    <td valign="center"><img src="images/cart_money.png"></td>
    <td valign="center" style="font-size:16px;">消費金額</td>
    <td valign="center"><span style="color:red;font-size:16px;">1,000</span></td>
      <td colspan="3" align="right" valign="center" style="line-height:70px;"><img src="images/cart_btn1.png"> <br><img  onclick="$('#divShow').show();" id="login_btn" style="cursor:pointer;" src="images/cart_btn2.png"></td>
    </tr>
 </td>
  </tr>
</table>

</div>


<? include "footer.inc.php" ?>
</body>
</html>