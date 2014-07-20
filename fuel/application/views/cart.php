  <link href="<?=site_url()?>templates/css/bootstrap.css" rel="stylesheet">
  <script type="text/javascript" src="<?=site_url();?>templates/Scripts/cart.js" ></script> 
        <div id="navigation">
          <div class="bar">
            <a class='logo' href="<?php echo site_url();?>"></a>
            <!--<div class="menu1"><a href="<?php echo $pro_cate_1?>" ></a></div>-->
            <div class="menu2"><a href="<?php echo $pro_cate_2?>" ></a></div>
          </div>
        </div>
  <div id="buystep1">
    <div class="txt"></div>
    <div class="line"><div></div></div>
  </div>        
  <div id="buystep1_info">
    <form action="<?php echo $payment_url?>" method="POST" id="sendForm">
      <div class="content1">
        <div class="photo"><img src="<?php echo $base_url.$pro_cover_photo->ga_url?>" width="300" height="220"></div>
        <div class="contnet">
          <div class="name"><?php echo $pro_detail_results->pro_name?></div>
          <div class="desc"><?php echo $pro_detail_results->pro_summary?></div>
          <div class="title">請選擇方案：</div>
          <ul id="planStyle">
          <?php 
            if(isset($plan_results))
            {
              foreach($plan_results as $row)
              {
          ?>
            <li <?php if($row->is_empty==1) echo "class='Isempty'"?>>
              <input type="radio" name="pro_plan" class="plan_price" price="<?php echo $row->plan_price?>" value="<?php echo $row->plan_id?>" <?php if($row->is_empty==1) echo "DISABLED"?>>
              <?php echo $row->plan_desc?>
              <span>$<?php echo $row->plan_price?></span> 
              <span style="color:red"><?php if($row->is_empty==1) echo "銷售一空"?></span>
            </li>                
          <?php
              }
            }
          ?>
          </ul>
        </div>
      </div>
      <div class="content2">
        <div class="icon"></div>
        <div class="sum">消費金額：</div>
        <div class="price">NT$<span></span>元</div>
        <a class="step1btn1" url="<?php echo $payment_url?>"></a>
        <a class="step1btn2" style="display:none"></a>
      </div>
    </form>
  </div>

  <div id="cart_black"></div>
  <div id="cart_login">
    <form action="<?php echo $login_url?>" method="POST" id="loginForm">
      <div class="close">X</div>
      <h2>歡迎回來！請輸入帳號密碼： </h2>
      <span id="error_msg" style="color:red; height: 15px;"></span>
      <div class="mail">電子信箱：<input type="text" class="mail" name="member_account" id="member_account"></div>
      <div class="paswd">密碼：<input type="password" class="paswd" name="password" id="password">
      <a href="javascript:;" class="forgotBtn">忘記密碼</a></div>
      <a href="javascript:;" class="firstTimeBtn">第一次購買請按這裡</a>
      <input type="button" class="submit" id="loginButton">
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
      $(".plan_price").click(function(){
        var price = $(this).attr('price');
        $(".price").find("span").text(price);
      });

      $("#loginButton").click(function(){
        var url = '<?php echo $login_url?>';
        var member_account = $('#member_account').val();
        var password = $('#password').val();
        var chkMail = ValidEmail(member_account);
        if(member_account == "" || password == "")
        {
          $("#error_msg").html("請輸入帳號密碼");
          return;
        }
        else if(chkMail === false)
        {
          $("#error_msg").html("email格式錯誤");
          return;
        }
        else
        {
          var postData = {'member_account': member_account, 'password': password};
          $("#error_msg").html("登入中...");
          $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: postData,
            success: function(data)
            {
              console.log(data);
              if(data.status == 1)
              {
                sendForm.submit();
              }
              else
              {
                $("#error_msg").html("帳號密碼錯誤");
              }
            }
          });
        }

      });
        
        $('a.step1btn1').on('click',function(){
          if($(".plan_price:checked").size() == 0)
          {
            alert("請選擇方案");
            return false;
          }

          $.ajax({
            url: '/chkLogin',
            dataType: 'json',
            type: 'POST'
          })
          .done(function(data) {
            if(data.status == 1)
            {
              if(data.is_logined)
              {
                sendForm.submit();
              }
              else
              {
                $("a.step1btn2").click();
              }
            }
          })
          .fail(function() {
            console.log("error");
          });
          

          return true;

        });
      
    });
  </script>