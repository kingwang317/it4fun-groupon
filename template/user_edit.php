<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>崇文冷凍食品股份有限公司-海鮮宅配鮮到你家</title>
<? include "script.inc.php" ?>
<? include "ga.inc.php" ?>
</head>

<body>
<? include "header.inc.php" ?>

<!--banner-->
<div id="banner_title">
<img src="images/banner_title.jpg">
</div>
<div id="orders">
<ul class="nav nav-tabs" role="tablist" style="margin:20px 0px;">
  <li ><a href="#">訂單查詢</a></li>
  <li class="active"><a href="user_edit.php">會員資料編輯</a></li>
  
</ul>

<div id="user">
<table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th width="146" scope="col"><img src="images/user_1.png"></th>
    <th colspan="2" scope="col"><h3>訂購人資料：</h3></th>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="142" align="right">訂購人姓名：</td>
    <td width="326"><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">電子郵件：</td>
    <td><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">設定密碼：</td>
    <td><input type="password" class="form-control" id="inputPassword" ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">確認密碼：</td>
    <td><input type="password" class="form-control" id="inputPassword" ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">地址：</td>
    <td><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">手機：</td>
    <td><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">統一編號：</td>
    <td><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">發票抬頭：</td>
    <td><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td><img src="images/user_2.png"></td>
    <td colspan="2"><h3>收件人資料：</h3><p>
      <input type="checkbox" name="checkbox" id="checkbox" />
      與訂購人相同</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">訂購人姓名：</td>
    <td><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">地址：</td>
    <td><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">手機：</td>
    <td><input type="text" class="form-control"  ></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><img src="images/checkitout.png" style="margin-top:10px;"></td>
    </tr>
</table>
</div>

</div>

<? include "login.inc.php" ?>
<? include "footer.inc.php" ?>
</body>
</html>