
            <div id="orders" style="line-height:50px;">
                <center>
                    <form id="sendForm" method="POST" >
                    <!-- <h4>歡迎回來，請輸入帳號密碼</h4> -->
                    <?php //echo fuel_block('login_title'); ?>
                        <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">                          
                          <tr>
                            <td>&nbsp;</td>
                            <td align="right">電子郵件：</td>
                            <td><input id="member_account" type="mail" class="form-control"  ></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td align="right">輸入密碼：</td>
                            <td><input id="password" type="password" class="form-control" id="inputPassword" ></td>
                          </tr>
                         </table>
                        <button id="login" type="button" class="btn btn-primary">登入</button>
                        <button id="fpass" type="button" class="btn btn-danger">忘記密碼</button>
                        <span id="error_msg" style="color:red; height: 15px;"></span>
                    </form>
                </center>
            </div>

  <script>

   
    $(document).ready(function($) {

       $('#fpass').click(function(event) {
             location.href = '<?php echo site_url() ?>forget';
        });

      var sendForm = $('#sendForm');

      if('' != '<?php echo $member_id ?>'){
            
          $('#login_btn').click(function(event) {
               location.href = '<?php echo site_url() ?>payment';
          });
      }else{
          $('#login_btn').click(function(event) {
               $('#divShow').show();
          });
      }

      $('#login_img').on("click", function (e) {
            $('#loginForm').submit();
        
      });

 

      $(".plan_price").click(function(){
        var price = $(this).attr('price');
        $(".price").find("span").text(price);
      });

      $("#login").click(function(){ 
        var url = '<?php echo $login_url?>';
        var member_account = $('#member_account').val();
        var password = $('#password').val();
        // var chkMail = ValidEmail(member_account);
        if(member_account == "" || password == "")
        {
          $("#error_msg").html("請輸入帳號密碼");
          return;
        }
        // else if(chkMail === false)
        // {
        //   $("#error_msg").html("email格式錯誤");
        //   return;
        // }
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
               
                // sendForm.submit();
                <?php if ($go_payment): ?>
                   location.href = '<?php echo site_url() ?>payment';
                <?php else: ?>
                   location.href = '<?php echo site_url() ?>orders';
                <?php endif ?>
               
              }
              else
              {
                $("#error_msg").html("帳號密碼錯誤");
              }
            }
          });
        }

      });
        
       
      
    });
  </script>