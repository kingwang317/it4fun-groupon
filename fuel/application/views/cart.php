  
<!--banner-->
<div id="banner_title">
<img src="<?php echo site_url() ?>templates/images/banner_title.jpg">
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
  <?php if (isset($pro_cart)): ?>
      <?php $total_price = 0; ?>
      <?php foreach ($pro_cart as $key => $value): ?>
      <tr>
        <?php 
          $num = $cart[$value->pro_id]["num"]; 
          $price = $value->plan_price;
          $total_price += $price * $num;
        ?>
        <td valign="center"><img style="width:128px" src='<?php echo $value->photo->ga_url ?>'></td>
        <td valign="center"><?php echo $value->pro_name ?></td>
        <td valign="center"><?php echo $num ?></td>
        <td valign="center"><?php echo $value->plan_price ?></td>
        <td valign="center"><?php echo $price*$num ?></td>
        <td valign="center"><img class="cancel_img" data-proid='<?php echo $value->pro_id; ?>' src="<?php echo site_url() ?>templates/images/cart_cancel.png"> 取消</td>
        
      </tr>
      <?php endforeach ?>
       <!--最下面的運費-->
      <tr>
        <td valign="center"><img src="<?php echo site_url() ?>templates/images/cart_icon_1.png"></td>
        <td valign="center">運費（滿2000免運）</td>
        <td valign="center">&nbsp;  </td>
          <td valign="center"></td>
        <td valign="center">150</td>
        <td valign="center">&nbsp; </td>
      </tr>
      <!--繼續借用表格的加總-->
      <?php 
          if ($total_price < 2000) {
            $total_price += 150;
          }
      ?>
      <tr>
        <td valign="center"><img src="<?php echo site_url() ?>templates/images/cart_money.png"></td>
        <td valign="center" style="font-size:16px;">消費金額</td>
        <td valign="center"><span style="color:red;font-size:16px;"><?php echo $total_price ?></span></td>
        <td colspan="3" align="right" valign="center" style="line-height:70px;"><img src="<?php echo site_url() ?>templates/images/cart_btn1.png"> <br><img  onclick="$('#divShow').show();" id="login_btn" style="cursor:pointer;" src="<?php echo site_url() ?>templates/images/cart_btn2.png"></td>
      </tr>
  <?php else: ?>
    <tr>
        <td colspan="5" valign="center">
            購物車目前無商品
        </td>
    </tr>
  <?php endif ?>
 
  
</table>

</div>

<script type="text/javascript">
    $(function(){
        $("#divShow").hide();
        
        $('body').click(function(evt) {
            if($(evt.target).parents("#divShow").length==0 && 
                evt.target.id != "login_btn" && evt.target.id != "divShow") {
                $('#divShow').hide();
            }
        });
    });
</script>
<div id="divShow">
<div id="login_bg" style=" display: block;"></div>
<div id="login_box" style=" display: block;">
    <div onclick="$('#divShow').hide();" style="float:right; top:0px; width:40px; height:20px;">關閉 X</div>
    <form >
      
      <h4>歡迎回來！請輸入帳號密碼： </h4>
      <!-- <span  style="color:red">error</span><br> -->
      電子信箱：<input type="text" class="mail"><br>
      輸入密碼：<input type="password" ><br>
        <a >忘記密碼</a>
        <img src="<?php echo site_url() ?>templates/images/login_btn.png"></div>
    </form>
  </div>
</div>

 <!--  <div id="cart_black"></div>
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
  </div> -->

  <script>
    $(document).ready(function($) {


      $('.cancel_img').on("click", function (e) {
        var pro_id = $(this).data('proid');
        var deleteConfirm = confirm("您確定將商品從購物車移除?");
        if (deleteConfirm) {
          var url = '<?php echo site_url()."removeFromCart/"; ?>' + pro_id;
          $.get(url, function(data) {
            /*optional stuff to do after success */
            alert('商品已從購物車移除！');
            location.reload();
          });
        }  
     });

      $(".plan_price").click(function(){
        var price = $(this).attr('price');
        $(".price").find("span").text(price);
      });

      $("#loginButton").click(function(){
        var url = '<?php echo "" ?>';
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