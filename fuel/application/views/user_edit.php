 

<!--banner-->
<div id="banner_title">
<img src="<?php echo site_url() ?>templates/images/banner_title.jpg">
</div>
<div id="orders">
<ul class="nav nav-tabs" role="tablist" style="margin:20px 0px;">
  <li ><a href="<?php echo site_url()."orders" ?>">訂單查詢</a></li>
  <li class="active"><a href="#">會員資料編輯</a></li>
  
</ul>

<div id="user">
<table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th width="146" scope="col"><img src="<?php echo site_url() ?>templates/images/user_1.png"></th>
    <th colspan="2" scope="col"><h3>基本資料</h3></th>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="142" align="right">姓名：</td>
    <td width="326"><input type="text" class="form-control" value="<?php echo isset($member_result->member_name)? $member_result->member_name:''?>" id="member_name" name="member_name" ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">電子郵件：</td>
    <td><input type="text" class="form-control" value="<?php echo isset($member_result->member_account)? $member_result->member_account:''?>" id="member_account" name="member_account" ></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td align="right">設定密碼：</td>
    <td><input type="password" class="form-control" id="member_pass" name="member_pass" ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">確認密碼：</td>
    <td><input type="password" class="form-control" id="member_chk_pass" name="member_chk_pass" ></td>
  </tr>  
 
  <tr>
      <td>&nbsp;</td>
      <td align="right">地址：</td>
      <td>
        <select name="member_city" id="member_city">
        <?php if(isset($member_result->member_city)):?>
        <?php
          if(isset($city_result))
          {
            foreach($city_result as $row)
            {
        ?>
          <option value="<?php echo $row->code_value1?>" <?php if($row->code_value1 == $member_result->member_city):?> SELECTED <?php endif;?>><?php echo $row->code_name?></option>
        <?php  
            }
          }
        ?>
      <?php else:?>
        <?php
          if(isset($city_result))
          {
            foreach($city_result as $row)
            {
        ?>
          <option value="<?php echo $row->code_value1?>"><?php echo $row->code_name?></option>
        <?php  
            }
          }
        ?>
      <?php endif;?>
        </select>
        <input type="text" class="form-control" value="<?php echo isset($member_result->member_account)? $member_result->member_addr:''?>" id="member_addr" name="member_addr" >
      </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">手機：</td>
    <td><input type="text" class="form-control" value="<?php echo isset($member_result->member_mobile)? $member_result->member_mobile:''?>" id="member_mobile" name="member_mobile" onkeypress='validate(event)' ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">統一編號：</td>
    <td><input type="text" class="form-control" value="<?php echo isset($member_result->vat_number)? $member_result->vat_number:''?>" id="vat_number" name="vat_number" ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">發票抬頭：</td>
    <td><input type="text" class="form-control" value="<?php echo isset($member_result->invoice_title)? $member_result->invoice_title:''?>" id="invoice_title" name="invoice_title" ></td>
  </tr> 
  <tr>
    <td colspan="3" align="center">
      <!-- <img id='send_payment' src="<?php echo site_url() ?>templates/images/checkitout.png" style="margin-top:10px;"> -->
      <!-- <button class="btn btn-primary" id="btn_done" >確認修改</button> -->
      <input type="button" class="btn btn-primary" value="儲存修改" id="update" updateuri="<?php echo $update_url?>">
      <input type="reset" class="btn btn-default" value="取消">
    </td>
    </tr>
</table>
</div>

<script>
  $(document).ready(function($) {
    
    $("#update").click(function(){
      var url = $(this).attr("updateuri");
      var postData = {
        'member_name': $("#member_name").val(),
        'member_account': $("#member_account").val(),
        'member_city': $("#member_city").val(),
        'member_addr': $("#member_addr").val(),
        'member_mobile': $("#member_mobile").val(),
        'vat_number': $("#vat_number").val(),
        'invoice_title': $("#invoice_title").val(),
        'member_pass': $("#member_pass").val(),
        'member_chk_pass': $("#member_chk_pass").val()
      };

      if($("#member_pass").val() != "")
      {
        if($("#member_pass").val() != $("#member_chk_pass").val())
        {
          alert("密碼不一致!!")
          return false;
        }
      }

        $.ajax({
          url: url,
          type: 'POST',
          dataType: 'json',
          data: postData,
          success: function(data)
          {
            if(data.status == 1)
            {
              alert("更新完成");
            }
            else
            {
              alert(data.msg);
            }
          }
        });
    });
       

  });

  function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }

  function ValidEmail(emailtoCheck)
  {
    // 規則: 1.只有一個 "@"
    //       2.網址中, 至少要有一個".", 且不能連續出現
    //       3.不能有空白
    var regExp = /^[^@^\s]+@[^\.@^\s]+(\.[^\.@^\s]+)+$/;
    if ( emailtoCheck.match(regExp) )
      return true;
    else
      return false;
  }
</script>

 