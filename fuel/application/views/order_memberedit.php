        <link href="<?=site_url()?>templates/css/bootstrap.css" rel="stylesheet">
        <script type="text/javascript" src="<?=site_url()?>templates/Scripts/order_memberedit.js"></script>
        <script type="text/javascript" src="<?=site_url();?>templates/Scripts/cart.js" ></script> 
        <div id="navigation">
          <div class="bar">
            <a class='logo' href="<?php echo site_url();?>"></a>
           <!--<div class="menu1"><a href="<?php echo $pro_cate_1?>" ></a></div>-->
            <div class="menu2"><a href="<?php echo $pro_cate_2?>" ></a></div>
          </div>
        </div>
              
        <div id="order_memberedit">          
              <div class="abgne_tab">
                <ul class="tabs">
                  <li><a href="#tab1">訂單查詢</a></li>
                  <li><a href="#tab2">會員資料編輯</a></li>
                </ul>

                <div class="tab_container">
                  <div id="tab1" class="tab_content">
                    <table width="945">
                      <tr class="titlebar">
                        <td width="130" align="center">定單編號 </td>
                        <td width="80" align="center">下單日期</td>
                        <td width="175" align="center">商品名稱</td>
                        <td width="85" align="center">方案</td>
                        <td width="100" align="center">總價</td>
                        <td width="110" align="center">訂單狀態</td>
                        <td width="90" align="center">出貨狀態</td>
                        <td width="65" align="center">發票</td>
                        <td align="center">備註</td>
                      </tr>
                  <?php
                    if(isset($order_result))
                    {
                      foreach($order_result as $row)
                      {
                  ?>
                      <tr>
                        <td width="110" align="center"><?php echo $row->order_id?></td>
                        <td width="80" align="center"><?php echo substr($row->order_time, 0, 10)?></td>
                        <td width="175" align="center"><?php echo $row->pro_name?></td>
                        <td width="85" align="center"><?php echo $row->plan_desc?></td>
                        <td width="100" align="center"><?php echo $row->plan_price?></td>
                        <td width="110" align="center">
                          <?php
                            if($row->order_status == "order_status_0002")
                            {
                              echo "已確認";
                            }
                            else
                            {
                              echo "未確認";
                            }
                          ?>
                        </td>
                        <td width="90" align="center">
                          <?php
                            if($row->order_ship_status == "ship_status_0002")
                            {
                              echo "已出貨";
                            }
                            else
                            {
                              echo "未出貨";
                            }
                          ?>
                        </td>
                        <td width="65" align="center">
                          <?php
                            if($row->order_inv_status == "inv_status_0002")
                            {
                              echo "已開立";
                            }
                            else
                            {
                              echo "未開立";
                            }
                          ?>                  
                        </td>
                        <td align="center"></td>
                      </tr>
                  <?php
                      }
                    }
                    else
                    {
                  ?>
                    <tr>
                      <td colspan="9" style="text-align:center"><b>沒有訂單</b></td>
                    </tr>
                  <?php
                    }
                  ?>

                    </table>
                    <div style="text-align:center">
                      <ul class="pagination">
                      <?php echo $pagination?>
                      </ul>
                    </div>
                    <!--<select class="pageNum">
                      <option>第一頁</option>
                      <option>第二頁</option>
                      <option>第三頁</option>
                      <option>第四頁</option>
                    </select>-->
                  </div>
                  <div id="tab2" class="tab_content">
                    
                    <div class="memberedit">
                      <p class="note">
                        請依照需求修改您的會員資料<br/> 
                        請放心，我們絕對不會以任何方式透漏您的任何個人資料，請您安心填寫。
                      </p>
                      <form>
                      <?php
                        if(isset($member_result))
                        {
                      ?>
                      <table class="memberedit">
                        <tr>
                          <td width="80" align="right">訂購人姓名：</td>
                          <td><input type="text" class="name" name="member_name" value="<?php echo $member_result->member_name?>" id="member_name"></td>
                        </tr>
                        <tr>
                          <td align="right">電子郵件：</td>
                          <td><input type="text" class="mail" name="member_account" value="<?php echo $member_result->member_account?>" id="member_account"></td>
                        </tr>
                        <tr>
                          <td align="right">地址：</td>
                          <td>
                            <select name="member_city" id="member_city">
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
                            </select>
                            <input type="text" class="address" name="member_addr" value="<?php echo $member_result->member_addr?>" id="member_addr">
                          </td>
                        </tr>
                        <tr>
                          <td align="right">手機：</td>
                          <td><input type="text" class="phone" name="member_mobile" value="<?php echo $member_result->member_mobile?>" id="member_mobile"></td>
                        </tr>
                        <tr>
                          <td align="right">統一編號：</td>
                          <td><input type="text" class="lid" name="vat_number" value="<?php echo $member_result->vat_number?>" id="vat_number"></td>
                        </tr>
                        <tr>
                          <td align="right">發票抬頭：</td>
                          <td><input type="text" class="title" name="invoice_title" value="<?php echo $member_result->invoice_title?>" id="invoice_title"></td>
                        </tr>
                        <tr>
                          <td colspan="2">修改密碼</td>
                        </tr>
                        <tr>
                          <td align="right">新密碼：</td>
                          <td><input type="password" class="paswd" id="member_pass">不需修改密碼請留空</td>
                        </tr>
                        <tr>
                          <td align="right">確認密碼：</td>
                          <td><input type="password" class="paswd" id="member_chk_pass"></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td align="left">
                            <input type="button" class="submit" value="儲存修改" id="update" updateuri="<?php echo $update_url?>">
                            <input type="reset" class="reset" value="取消">
                          </td>
                        </tr>
                      </table>
                    <?php
                      }
                    ?>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
        </div>        
        <br class="clear"/>
        <br/>

  <div id="cart_black"></div>
  <div id="cart_login">
    <form action="<?php echo $login_url?>" method="POST" id="loginForm">
      <div class="close">X</div>
      <h2>歡迎回來！請輸入帳號密碼： </h2>
      <span id="error_msg" style="color:red"></span>
      <div class="mail">電子信箱：<input type="text" class="mail" name="member_account" id="member_account"></div>
      <div class="paswd">密碼：<input type="password" class="paswd" name="password" id="password">
        <a href="javascript:;" class="forgotBtn">忘記密碼</a></div>
      <input type="button" class="submit orderMemberedit" id="loginButton">
    </form>
  </div>
  <div id="cart_forgot_pwd">
      <div class="close">X</div>
      <h2>忘記密碼： </h2>
      <span id="pwd_error_msg" style="color:red"></span>
      <div class="mail">電子信箱：<input type="text" class="mail" name="cemail" id="cemail"></div>
      <button class="btn btn-warning" id="pwdButton" uri="<?php echo $forgot_url?>" style="margin-left:150px">送出</button>
  </div>
<script>
  $(document).ready(function($) {
    var is_logined = <?php echo $is_logined?>;

    if(is_logined != 1)
    {
      var win_h=$(window).height();
      var win_w=$(window).width();
      $('div#cart_login').css({
        'top':(win_h-$('div#cart_login').height())/2,
        'left':(win_w-$('div#cart_login').width())/2,
      });
      $('div#cart_black').fadeIn();
      $('div#cart_login').fadeIn(); 
    }

      $("#loginButton").click(function(){
        var url = '<?php echo $login_url?>';
        var postData = {'member_account': $('#member_account').val(), 'password': $('#password').val()};

        $.ajax({
          url: url,
          type: 'POST',
          dataType: 'json',
          data: postData,
          success: function(data)
          {
            if(data.status == 1)
            {
              location.reload();
            }
            else
            {
              $("#error_msg").text("帳號密碼錯誤");
            }
          }
        });      

      });
  });
</script>