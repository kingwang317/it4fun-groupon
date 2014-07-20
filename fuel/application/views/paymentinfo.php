
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
            <form>
              <div class="content1">              
                  <div class="photo">
                    <img src="templates/images/photo1.jpg" >
                  </div>
                  <div class="form">
                    <div class="str1">填寫訂購資料： </div>
                    <div class="str2">
                      請填寫您的訂購資料，以利我們作業喔！ <br>
                      請放心，我們絕對不會以任何方式透漏您的任何個人資料，請您安心填寫。
                    </div>
                    <table>
                      <tr>
                        <td width="100" align="right">訂購人姓名：</td>
                        <td><input type="text" value="<?php echo isset($member_result->member_name)? $member_result->member_name:''?>" id="order_name" name="order_name"></td>
                      </tr>
                      <tr>
                        <td align="right"> 電子郵件：</td>
                        <td><input type="text" value="<?php echo isset($member_result->member_account)? $member_result->member_account:''?>" id="order_email" name="order_email"></td>
                      </tr>
                      <?php
                        if(!$is_logined)
                        {
                      ?>
                      <tr>
                        <td align="right">設定密碼：</td>
                        <td><input type="password" id="pwd"></td>
                      </tr>
                      <tr>
                        <td align="right">確認密碼：</td>
                        <td><input type="password" id="chk_pwd"></td>
                      </tr>
                      <?php
                        }
                      ?>
                      <tr>
                        <td align="right">地址：</td>
                        <td>
                          <select name="order_city" id="order_city">
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
                          <input type="text" value="<?php echo isset($member_result->member_account)? $member_result->member_addr:''?>" id="order_addr" name="order_addr" style="width:300px">
                        </td>
                      </tr>
                      <tr>
                        <td align="right">手機：</td>
                        <td><input type="type" value="<?php echo isset($member_result->member_mobile)? $member_result->member_mobile:''?>" id="order_mobile" name="order_mobile" onkeypress='validate(event)'></td>
                      </tr>
                      <tr>
                        <td align="right">統一編號：</td>
                        <td><input type="text" value="<?php echo isset($member_result->vat_number)? $member_result->vat_number:''?>" id="vat_number" name="vat_number"></td>
                      </tr>
                      <tr>
                        <td align="right">發票抬頭：</td>
                        <td><input type="text" value="<?php echo isset($member_result->invoice_title)? $member_result->invoice_title:''?>" id="invoice_title" name="invoice_title"></td>
                      </tr>
                      <tr>
                        <td align="right">電子發票說明：</td>
                        <td>
                          依財稅XX營業字第XXXXXXXXXX號函准使用電子發票
                        </td>
                      </tr>
                    </table>
                  </div>
                
              </div>
              <div class="content2">
                <div class="str1">收件人資料：</div>
                <div class="str2"><input type="checkbox" id="sameorder" value="1">與訂購人相同</div>
                <table>
                  <tr>
                    <td align="right">收件人姓名：</td>
                    <td><input type="text" name="" id="order_addressee_name" name="order_addressee_name"></td>
                  </tr>
                  <tr>
                    <td align="right">地址：</td>
                    <td>
                      <select name="order_addressee_city" id="order_addressee_city">
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
                      </select>
                      <input type="text" id="order_addressee_addr" name="order_addressee_addr" style="width:300px">
                    </td>
                  </tr>
                  <tr>
                    <td align="right">送達時間：</td>
                    <td>
                      <select name="order_ship_time" id="order_ship_time">
                        <?php
                          if(isset($ship_time_result))
                          {
                            foreach($ship_time_result as $row)
                            {
                        ?>
                              <option value="<?php echo $row->code_value1?>"><?php echo $row->code_name?></option>
                        <?php
                            }
                          }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td align="right">手機：</td>
                    <td><input type="text" id="order_addressee_mobile" name="order_addressee_mobile" onkeypress='validate(event)'></td>
                  </tr>
                </table>
              </div>
              <input type="hidden" value="<?php echo $plan_id?>" name="plan_id" id="plan_id">
              <a class="step1btn1" id="send_payment"></a>  
            </form>
            <form id="payment_form" style="display:none" action="" method="POST">
              <input type="hidden" value="" name="MerchantID" id="MerchantID">
              <input type="hidden" value="CREDIT" name="PaymentType" id="PaymentType">
              <input type="hidden" value="" name="XMLData" id="XMLData">
            </form>     
        </div>  
        <br class="clear"> 
<script>
  $(document).ready(function($) {
    $("#sameorder").click(function(){
      if($("#sameorder:checked").val() == 1)
      {
        $("#order_addressee_name").val($("#order_name").val());
        $("#order_addressee_addr").val($("#order_addr").val());
        $("#order_addressee_mobile").val($("#order_mobile").val());
        $("#order_addressee_city").val($("#order_city").val());       
      }
      else
      {
        $("#order_addressee_name").val("");
        $("#order_addressee_addr").val("");
        $("#order_addressee_mobile").val("");
      }
    });

    $("#send_payment").click(function(){
        var url = '<?php echo $get_payment_url?>';
        var pwd = $("#pwd").val();
        var chk_pwd = $("#chk_pwd").val();
        if(typeof pwd != "undefined")
        {
          if(pwd != chk_pwd)
          {
            alert("密碼不一致");
            return false;
          }
          else
          {
            var postData = {"plan_id": $("#plan_id").val(),
                            "order_name": $("#order_name").val(),
                            "order_email": $("#order_email").val(),
                            "pwd": pwd,
                            "chk_pwd": chk_pwd,
                            "order_mobile": $("#order_mobile").val(),
                            "order_city": $("#order_city").val(),
                            "order_addr": $("#order_addr").val(),
                            "vat_number": $("#vat_number").val(),
                            "invoice_title": $("#invoice_title").val(),
                            "order_addressee_name": $("#order_addressee_name").val(),
                            "order_addressee_city": $("#order_addressee_city").val(),
                            "order_addressee_addr": $("#order_addressee_addr").val(),
                            "order_addressee_mobile": $("#order_addressee_mobile").val(),
                            "order_ship_time": $("#order_ship_time").val()
                          };
          }
        }
        else
        {
          var postData = {"plan_id": $("#plan_id").val(),
                          "order_name": $("#order_name").val(),
                          "order_email": $("#order_email").val(),
                          "order_mobile": $("#order_mobile").val(),
                          "order_city": $("#order_city").val(),
                          "order_addr": $("#order_addr").val(),
                          "vat_number": $("#vat_number").val(),
                          "invoice_title": $("#invoice_title").val(),
                          "order_addressee_name": $("#order_addressee_name").val(),
                          "order_addressee_city": $("#order_addressee_city").val(),
                          "order_addressee_addr": $("#order_addressee_addr").val(),
                          "order_addressee_mobile": $("#order_addressee_mobile").val(),
                          "order_ship_time": $("#order_ship_time").val()
                        };          
        }


      if($("#order_addressee_name").val() == "")
      {
        alert("請輸入收件人名稱");

        return false;
      }
      else if($("#order_addressee_addr").val() == "")
      {
        alert("請輸入收件人地址");

        return false;
      }
      else if($("#order_addressee_mobile").val() == "")
      {
        alert("請輸入收件人電話");

        return false;
      }
      else if(ValidEmail($("#order_email").val()) == false)
      {
        alert("email 格式錯誤");

        return false;
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
              $("#MerchantID").val(data.merchant_id);
              $("#XMLData").val(data.encode_data);
              $("#payment_form").attr('action', data.gateway);
              $("#payment_form").submit();
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